<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Student;
use App\Services\PaymentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class PaymentController extends Controller
{
    public function __construct(private readonly PaymentService $payments) {}

    public function index(Request $request): View
    {
        $payments = Payment::query()
            ->with(['student.schoolClass', 'recordedBy'])
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->string('search')->toString();
                $query->where(fn ($query) => $query
                    ->where('invoice_number', 'like', "%{$search}%")
                    ->orWhereHas('student', fn ($query) => $query
                        ->where('name', 'like', "%{$search}%")
                        ->orWhere('nis', 'like', "%{$search}%")));
            })
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->string('status')->toString()))
            ->when($request->filled('method'), fn ($query) => $query->where('method', $request->string('method')->toString()))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.payments.index', compact('payments'));
    }

    public function create(Request $request): View
    {
        $students = Student::query()
            ->active()
            ->with('schoolClass')
            ->whereHas('bills', fn ($query) => $query->whereIn('status', ['unpaid', 'partial']))
            ->orderBy('name')
            ->get();

        $selectedStudent = null;

        if ($request->filled('student_id')) {
            $selectedStudent = Student::query()
                ->with(['schoolClass', 'bills' => fn ($query) => $query
                    ->whereIn('status', ['unpaid', 'partial'])
                    ->orderBy('period_year')
                    ->orderBy('period_month')])
                ->find($request->integer('student_id'));
        }

        return view('admin.payments.create', compact('students', 'selectedStudent'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'student_id' => ['required', 'exists:students,id'],
            'bill_ids' => ['required', 'array', 'min:1'],
            'bill_ids.*' => ['integer', 'exists:bills,id'],
            'method' => ['required', Rule::in(['cash', 'transfer', 'midtrans'])],
            'channel' => ['nullable', 'string', 'max:60'],
            'notes' => ['nullable', 'string', 'max:500'],
        ]);

        $student = Student::query()->findOrFail($data['student_id']);
        $channel = ($data['channel'] ?? null) ?: match ($data['method']) {
            'cash' => 'Tunai',
            'transfer' => 'Transfer Bank',
            default => 'Midtrans',
        };

        if ($data['method'] === 'midtrans') {
            $result = $this->payments->createOnlinePayment($student, $data['bill_ids'], $channel, $data['notes'] ?? null);
            $payment = $result['payment'];

            if (! empty($result['snap']['redirect_url'])) {
                return redirect()->away($result['snap']['redirect_url']);
            }

            return redirect()->route('admin.payments.show', $payment)
                ->with('success', 'Pembayaran Midtrans mode simulasi berhasil dicatat.');
        }

        $payment = $this->payments->createPayment(
            $student,
            $data['bill_ids'],
            $data['method'],
            $channel,
            $request->user(),
            $data['notes'] ?? null,
            settleImmediately: true,
        );

        return redirect()->route('admin.payments.show', $payment)
            ->with('success', 'Pembayaran berhasil dicatat.');
    }

    public function show(Payment $payment): View
    {
        $payment->load(['student.schoolClass', 'bills', 'recordedBy', 'items']);

        return view('admin.payments.show', compact('payment'));
    }

    public function receipt(Payment $payment): View
    {
        $payment->load(['student.schoolClass', 'bills', 'recordedBy']);

        return view('partials.receipt', [
            'payment' => $payment,
            'backUrl' => route('admin.payments.show', $payment),
        ]);
    }
}
