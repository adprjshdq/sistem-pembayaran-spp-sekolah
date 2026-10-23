<?php

namespace App\Http\Controllers\Admin;

use App\Enums\BillStatus;
use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\Bill;
use App\Models\Fee;
use App\Models\SchoolClass;
use App\Models\Student;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class BillController extends Controller
{
    public function index(Request $request): View
    {
        $bills = Bill::query()
            ->with(['student.schoolClass', 'academicYear'])
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->string('search')->toString();
                $query->whereHas('student', fn ($query) => $query
                    ->where('name', 'like', "%{$search}%")
                    ->orWhere('nis', 'like', "%{$search}%"));
            })
            ->when($request->filled('class_id'), fn ($query) => $query->whereHas('student', fn ($query) => $query->where('class_id', $request->integer('class_id'))))
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->string('status')->toString()))
            ->when($request->filled('period_month'), fn ($query) => $query->where('period_month', $request->integer('period_month')))
            ->when($request->filled('period_year'), fn ($query) => $query->where('period_year', $request->integer('period_year')))
            ->orderByDesc('period_year')
            ->orderByDesc('period_month')
            ->orderBy('student_id')
            ->paginate(15)
            ->withQueryString();

        return view('admin.bills.index', [
            'bills' => $bills,
            'classes' => SchoolClass::query()->orderBy('name')->get(),
            'months' => Bill::monthOptions(),
            'years' => Bill::query()->distinct()->orderByDesc('period_year')->pluck('period_year'),
        ]);
    }

    public function create(): View
    {
        return view('admin.bills.create', [
            'students' => Student::query()->active()->with('schoolClass')->orderBy('name')->get(),
            'academicYears' => AcademicYear::query()->latest('start_date')->get(),
            'months' => Bill::monthOptions(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'student_id' => ['required', 'exists:students,id'],
            'academic_year_id' => ['required', 'exists:academic_years,id'],
            'period_month' => ['required', 'integer', 'between:1,12'],
            'period_year' => ['required', 'integer', 'between:2000,2100'],
            'amount' => ['required', 'numeric', 'min:0'],
            'due_date' => ['required', 'date'],
            'notes' => ['nullable', 'string', 'max:255'],
        ]);

        $exists = Bill::query()
            ->where('student_id', $data['student_id'])
            ->where('period_month', $data['period_month'])
            ->where('period_year', $data['period_year'])
            ->exists();

        if ($exists) {
            throw ValidationException::withMessages([
                'period_month' => 'Tagihan untuk siswa dan periode tersebut sudah ada.',
            ]);
        }

        $data['amount'] = (int) $data['amount'];
        Bill::create($data);

        return redirect()->route('admin.bills.index')
            ->with('success', 'Tagihan berhasil ditambahkan.');
    }

    public function generateForm(): View
    {
        return view('admin.bills.generate', [
            'academicYears' => AcademicYear::query()->latest('start_date')->get(),
            'classes' => SchoolClass::query()->with('academicYear')->orderBy('name')->get(),
            'fees' => Fee::query()->with('academicYear')->get(),
            'months' => Bill::monthOptions(),
        ]);
    }

    public function generate(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'academic_year_id' => ['required', 'exists:academic_years,id'],
            'month_from' => ['required', 'integer', 'between:1,12'],
            'month_to' => ['required', 'integer', 'between:1,12', 'gte:month_from'],
            'period_year' => ['required', 'integer', 'between:2000,2100'],
            'due_date' => ['required', 'date'],
            'class_id' => ['nullable', 'exists:school_classes,id'],
        ]);

        $fees = Fee::query()
            ->where('academic_year_id', $data['academic_year_id'])
            ->get()
            ->keyBy('level');

        if ($fees->isEmpty()) {
            throw ValidationException::withMessages([
                'academic_year_id' => 'Belum ada tarif SPP untuk tahun ajaran ini. Tambahkan tarif terlebih dahulu.',
            ]);
        }

        $students = Student::query()
            ->active()
            ->with('schoolClass')
            ->whereHas('schoolClass', function ($query) use ($data) {
                $query->where('academic_year_id', $data['academic_year_id']);

                if (! empty($data['class_id'])) {
                    $query->whereKey($data['class_id']);
                }
            })
            ->get();

        if ($students->isEmpty()) {
            throw ValidationException::withMessages([
                'class_id' => 'Tidak ada siswa aktif pada tahun ajaran/kelas yang dipilih.',
            ]);
        }

        $created = 0;
        $skipped = 0;
        $withoutFee = 0;

        DB::transaction(function () use ($students, $fees, $data, &$created, &$skipped, &$withoutFee) {
            foreach ($students as $student) {
                $level = $student->schoolClass?->level;
                $fee = $level ? $fees->get($level) : null;

                if (! $fee) {
                    $withoutFee++;

                    continue;
                }

                for ($month = $data['month_from']; $month <= $data['month_to']; $month++) {
                    $bill = Bill::query()->firstOrCreate(
                        [
                            'student_id' => $student->id,
                            'period_month' => $month,
                            'period_year' => $data['period_year'],
                        ],
                        [
                            'academic_year_id' => $data['academic_year_id'],
                            'amount' => $fee->amount,
                            'paid_amount' => 0,
                            'due_date' => $data['due_date'],
                            'status' => BillStatus::Unpaid,
                        ],
                    );

                    $bill->wasRecentlyCreated ? $created++ : $skipped++;
                }
            }
        });

        $message = "{$created} tagihan berhasil dibuat, {$skipped} dilewati karena sudah ada.";

        if ($withoutFee > 0) {
            $message .= " {$withoutFee} siswa tidak memiliki tarif sesuai tingkatnya.";
        }

        return redirect()->route('admin.bills.index')->with('success', $message);
    }

    public function destroy(Bill $bill): RedirectResponse
    {
        if ($bill->paid_amount > 0 || $bill->items()->exists()) {
            return back()->with('error', 'Tagihan yang sudah memiliki pembayaran tidak dapat dihapus.');
        }

        $bill->delete();

        return redirect()->route('admin.bills.index')
            ->with('success', 'Tagihan berhasil dihapus.');
    }
}
