<?php

namespace App\Http\Controllers\Portal;

use App\Enums\PaymentStatus;
use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(Request $request): View
    {
        $student = $this->student($request);
        $activeYear = AcademicYear::active();

        $outstanding = (int) $student->bills()
            ->whereIn('status', ['unpaid', 'partial'])
            ->sum(DB::raw('amount - paid_amount'));

        $paidThisYear = (int) $student->payments()
            ->where('status', PaymentStatus::Success)
            ->whereYear('paid_at', now()->year)
            ->sum('amount');

        $unpaidCount = $student->bills()
            ->whereIn('status', ['unpaid', 'partial'])
            ->count();

        $bills = $student->bills()
            ->with('academicYear')
            ->whereIn('status', ['unpaid', 'partial'])
            ->orderBy('period_year')
            ->orderBy('period_month')
            ->limit(6)
            ->get();

        $payments = $student->payments()
            ->with('bills')
            ->where('status', PaymentStatus::Success)
            ->latest('paid_at')
            ->limit(5)
            ->get();

        return view('portal.dashboard', compact(
            'student',
            'activeYear',
            'outstanding',
            'paidThisYear',
            'unpaidCount',
            'bills',
            'payments',
        ));
    }

    private function student(Request $request): Student
    {
        $student = $request->user()->student()->with('schoolClass.academicYear')->first();

        abort_unless($student, 403, 'Akun Anda belum terhubung dengan data siswa. Hubungi administrator.');

        return $student;
    }
}
