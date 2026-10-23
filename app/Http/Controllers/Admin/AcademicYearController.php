<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class AcademicYearController extends Controller
{
    public function index(): View
    {
        return view('admin.academic-years.index', [
            'academicYears' => AcademicYear::query()
                ->withCount(['classes', 'bills'])
                ->latest('start_date')
                ->get(),
        ]);
    }

    public function create(): View
    {
        return view('admin.academic-years.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatedData($request);

        DB::transaction(function () use ($data) {
            if ($data['is_active'] ?? false) {
                AcademicYear::query()->update(['is_active' => false]);
            }

            AcademicYear::create($data);
        });

        return redirect()->route('admin.academic-years.index')
            ->with('success', 'Tahun ajaran berhasil ditambahkan.');
    }

    public function edit(AcademicYear $academicYear): View
    {
        return view('admin.academic-years.edit', [
            'academicYear' => $academicYear,
        ]);
    }

    public function update(Request $request, AcademicYear $academicYear): RedirectResponse
    {
        $data = $this->validatedData($request, $academicYear);

        DB::transaction(function () use ($data, $academicYear) {
            if ($data['is_active'] ?? false) {
                AcademicYear::query()->whereKeyNot($academicYear->id)->update(['is_active' => false]);
            }

            $academicYear->update($data);
        });

        return redirect()->route('admin.academic-years.index')
            ->with('success', 'Tahun ajaran berhasil diperbarui.');
    }

    public function destroy(AcademicYear $academicYear): RedirectResponse
    {
        if ($academicYear->classes()->exists() || $academicYear->bills()->exists()) {
            return back()->with('error', 'Tahun ajaran yang memiliki kelas atau tagihan tidak dapat dihapus.');
        }

        $academicYear->delete();

        return redirect()->route('admin.academic-years.index')
            ->with('success', 'Tahun ajaran berhasil dihapus.');
    }

    /**
     * @return array<string, mixed>
     */
    private function validatedData(Request $request, ?AcademicYear $academicYear = null): array
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:20', 'unique:academic_years,name'.($academicYear ? ','.$academicYear->id : '')],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after:start_date'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        $data['is_active'] = $request->boolean('is_active');

        return $data;
    }
}
