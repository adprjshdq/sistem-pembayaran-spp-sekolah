<?php

namespace App\Http\Controllers\Portal;

use App\Enums\PaymentStatus;
use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Student;
use App\Services\MidtransService;
use App\Services\PaymentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PaymentController extends Controller
{
    public function __construct(
        private readonly PaymentService $payments,
        private readonly MidtransService $midtrans,
    ) {}

    public function index(Request $request): View
    {
        $student = $this->student($request);

        $payments = $student->payments()
            ->with('bills')
            ->latest()
            ->paginate(10);

        return view('portal.payments.index', compact('student', 'payments'));
    }

    public function pay(Request $request): RedirectResponse
    {
        $student = $this->student($request);

        $data = $request->validate([
            'bill_ids' => ['required', 'array', 'min:1'],
            'bill_ids.*' => ['integer', 'exists:bills,id'],
            'channel' => ['nullable', 'string', 'max:60'],
        ]);

        $result = $this->payments->createOnlinePayment(
            $student,
            $data['bill_ids'],
            $data['channel'] ?? 'Midtrans',
        );

        if (! empty($result['snap']['redirect_url'])) {
            return redirect()->away($result['snap']['redirect_url']);
        }

        return redirect()->route('portal.payments.show', $result['payment'])
            ->with('success', 'Pembayaran berhasil diproses (mode simulasi karena Midtrans belum dikonfigurasi).');
    }

    public function show(Request $request, Payment $payment): View
    {
        $student = $this->student($request);
        abort_unless($payment->student_id === $student->id, 404);

        $payment->load(['student.schoolClass', 'bills', 'recordedBy']);

        return view('portal.payments.show', compact('student', 'payment'));
    }

    public function finish(Request $request, Payment $payment): RedirectResponse
    {
        $student = $this->student($request);
        abort_unless($payment->student_id === $student->id, 404);

        $statusBefore = $payment->status;
        $payment = $this->payments->refreshStatus($payment);

        $message = $payment->status === PaymentStatus::Success
            ? 'Pembayaran berhasil. Terima kasih!'
            : 'Status pembayaran masih menunggu konfirmasi payment gateway.';

        if ($statusBefore === PaymentStatus::Success) {
            $message = 'Pembayaran sudah tercatat lunas.';
        }

        return redirect()->route('portal.payments.show', $payment)->with('success', $message);
    }

    public function receipt(Request $request, Payment $payment): View
    {
        $student = $this->student($request);
        abort_unless($payment->student_id === $student->id, 404);

        $payment->load(['student.schoolClass', 'bills', 'recordedBy']);

        return view('partials.receipt', [
            'payment' => $payment,
            'backUrl' => route('portal.payments.show', $payment),
        ]);
    }

    private function student(Request $request): Student
    {
        $student = $request->user()->student()->with('schoolClass.academicYear')->first();

        abort_unless($student, 403, 'Akun Anda belum terhubung dengan data siswa. Hubungi administrator.');

        return $student;
    }
}
