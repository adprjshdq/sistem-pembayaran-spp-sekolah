<?php

namespace App\Http\Controllers\Portal;

use App\Enums\PaymentStatus;
use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class BillController extends Controller
{
    public function index(Request $request): View
    {
        $student = $this->student($request);

        $bills = $student->bills()
            ->with('academicYear')
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->string('status')->toString()))
            ->when($request->filled('period_year'), fn ($query) => $query->where('period_year', $request->integer('period_year')))
            ->orderByDesc('period_year')
            ->orderByDesc('period_month')
            ->paginate(12)
            ->withQueryString();

        $outstanding = (int) $student->bills()
            ->whereIn('status', ['unpaid', 'partial'])
            ->sum(DB::raw('amount - paid_amount'));

        return view('portal.bills.index', [
            'student' => $student->load('schoolClass'),
            'bills' => $bills,
            'outstanding' => $outstanding,
            'years' => $student->bills()->distinct()->orderByDesc('period_year')->pluck('period_year'),
            'hasPendingPayment' => $student->payments()->where('status', PaymentStatus::Pending)->exists(),
        ]);
    }

    private function student(Request $request): Student
    {
        $student = $request->user()->student()->with('schoolClass.academicYear')->first();

        abort_unless($student, 403, 'Akun Anda belum terhubung dengan data siswa. Hubungi administrator.');

        return $student;
    }
}
