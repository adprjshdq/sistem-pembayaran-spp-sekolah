<?php

namespace App\Http\Controllers\Admin;

use App\Enums\PaymentStatus;
use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\Bill;
use App\Models\Payment;
use App\Models\SchoolClass;
use App\Models\Student;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $today = Carbon::now();
        $activeYear = AcademicYear::active();

        $totalStudents = Student::query()->active()->count();

        $billedThisMonth = (int) Bill::query()
            ->where('period_month', $today->month)
            ->where('period_year', $today->year)
            ->sum('amount');

        $collectedThisMonth = (int) Payment::query()
            ->where('status', PaymentStatus::Success)
            ->whereBetween('paid_at', [$today->copy()->startOfMonth(), $today->copy()->endOfMonth()])
            ->sum('amount');

        $outstanding = (int) Bill::query()
            ->whereIn('status', ['unpaid', 'partial'])
            ->sum(DB::raw('amount - paid_amount'));

        $recentPayments = Payment::query()
            ->with(['student.schoolClass'])
            ->latest()
            ->limit(6)
            ->get();

        $collections = Payment::query()
            ->where('status', PaymentStatus::Success)
            ->where('paid_at', '>=', $today->copy()->subMonths(5)->startOfMonth())
            ->get(['amount', 'paid_at']);

        $chart = collect(range(5, 0))->map(function (int $monthsAgo) use ($today, $collections) {
            $month = $today->copy()->subMonths($monthsAgo);

            return [
                'label' => $month->locale('id')->translatedFormat('M y'),
                'amount' => (int) $collections
                    ->filter(fn (Payment $payment) => $payment->paid_at->isSameMonth($month))
                    ->sum('amount'),
            ];
        });

        $maxChart = max(1, $chart->max('amount'));

        $classes = SchoolClass::query()
            ->when($activeYear, fn ($query) => $query->where('academic_year_id', $activeYear->id))
            ->withCount('students')
            ->orderBy('name')
            ->limit(6)
            ->get()
            ->map(function (SchoolClass $class) {
                $totals = Bill::query()
                    ->whereHas('student', fn ($query) => $query->where('class_id', $class->id))
                    ->selectRaw('SUM(amount) as total, SUM(paid_amount) as paid')
                    ->first();

                $total = (int) ($totals->total ?? 0);
                $paid = (int) ($totals->paid ?? 0);

                return [
                    'class' => $class,
                    'percentage' => $total > 0 ? (int) round($paid / $total * 100) : 0,
                    'outstanding' => max(0, $total - $paid),
                ];
            });

        return view('admin.dashboard', compact(
            'today',
            'activeYear',
            'totalStudents',
            'billedThisMonth',
            'collectedThisMonth',
            'outstanding',
            'recentPayments',
            'chart',
            'maxChart',
            'classes',
        ));
    }
}
