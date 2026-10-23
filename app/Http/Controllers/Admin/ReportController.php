<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\Bill;
use App\Models\SchoolClass;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportController extends Controller
{
    public function index(Request $request): View
    {
        $query = $this->filteredQuery($request);

        $summary = [
            'total_bills' => (clone $query)->count(),
            'total_amount' => (int) (clone $query)->sum('amount'),
            'total_paid' => (int) (clone $query)->sum('paid_amount'),
        ];
        $summary['total_outstanding'] = max(0, $summary['total_amount'] - $summary['total_paid']);

        $bills = $query
            ->with(['student.schoolClass', 'academicYear'])
            ->orderByDesc('period_year')
            ->orderByDesc('period_month')
            ->orderBy('student_id')
            ->paginate(25)
            ->withQueryString();

        return view('admin.reports.index', [
            'bills' => $bills,
            'summary' => $summary,
            'academicYears' => AcademicYear::query()->latest('start_date')->get(),
            'classes' => SchoolClass::query()->orderBy('name')->get(),
            'months' => Bill::monthOptions(),
            'years' => Bill::query()->distinct()->orderByDesc('period_year')->pluck('period_year'),
        ]);
    }

    public function export(Request $request): StreamedResponse
    {
        $bills = $this->filteredQuery($request)
            ->with(['student.schoolClass'])
            ->orderByDesc('period_year')
            ->orderByDesc('period_month')
            ->orderBy('student_id')
            ->cursor();

        $filename = 'laporan-spp-'.now()->format('Ymd-His').'.csv';

        return response()->streamDownload(function () use ($bills) {
            $output = fopen('php://output', 'w');

            fwrite($output, "\xEF\xBB\xBF");
            fputcsv($output, [
                'NIS',
                'Nama Siswa',
                'Kelas',
                'Tahun Ajaran',
                'Periode',
                'Jatuh Tempo',
                'Tagihan',
                'Dibayar',
                'Sisa',
                'Status',
            ]);

            foreach ($bills as $bill) {
                fputcsv($output, [
                    $bill->student?->nis,
                    $bill->student?->name,
                    $bill->student?->schoolClass?->name,
                    $bill->academicYear?->name,
                    $bill->periodLabel(),
                    $bill->due_date?->format('d/m/Y'),
                    $bill->amount,
                    $bill->paid_amount,
                    $bill->remainingAmount(),
                    $bill->status->label(),
                ]);
            }

            fclose($output);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    private function filteredQuery(Request $request): Builder
    {
        $activeYear = AcademicYear::active();

        return Bill::query()
            ->when($request->filled('academic_year_id'), fn (Builder $query) => $query->where('academic_year_id', $request->integer('academic_year_id')))
            ->when(! $request->filled('academic_year_id') && $activeYear, fn (Builder $query) => $query->where('academic_year_id', $activeYear->id))
            ->when($request->filled('period_month'), fn (Builder $query) => $query->where('period_month', $request->integer('period_month')))
            ->when($request->filled('period_year'), fn (Builder $query) => $query->where('period_year', $request->integer('period_year')))
            ->when($request->filled('class_id'), fn (Builder $query) => $query->whereHas('student', fn (Builder $query) => $query->where('class_id', $request->integer('class_id'))))
            ->when($request->filled('status'), fn (Builder $query) => $query->where('status', $request->string('status')->toString()))
            ->when($request->filled('search'), function (Builder $query) use ($request) {
                $search = $request->string('search')->toString();
                $query->whereHas('student', fn (Builder $query) => $query
                    ->where('name', 'like', "%{$search}%")
                    ->orWhere('nis', 'like', "%{$search}%"));
            });
    }
}
