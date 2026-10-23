<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\SchoolClass;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class SchoolClassController extends Controller
{
    public function index(Request $request): View
    {
        $classes = SchoolClass::query()
            ->with('academicYear')
            ->withCount('students')
            ->when($request->filled('academic_year_id'), fn ($query) => $query->where('academic_year_id', $request->integer('academic_year_id')))
            ->orderBy('name')
            ->get();

        return view('admin.classes.index', [
            'classes' => $classes,
            'academicYears' => AcademicYear::query()->latest('start_date')->get(),
        ]);
    }

    public function create(): View
    {
        return view('admin.classes.create', [
            'academicYears' => AcademicYear::query()->latest('start_date')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        SchoolClass::create($this->validatedData($request));

        return redirect()->route('admin.classes.index')
            ->with('success', 'Kelas berhasil ditambahkan.');
    }

    public function edit(SchoolClass $class): View
    {
        return view('admin.classes.edit', [
            'class' => $class,
            'academicYears' => AcademicYear::query()->latest('start_date')->get(),
        ]);
    }

    public function update(Request $request, SchoolClass $class): RedirectResponse
    {
        $class->update($this->validatedData($request, $class));

        return redirect()->route('admin.classes.index')
            ->with('success', 'Kelas berhasil diperbarui.');
    }

    public function destroy(SchoolClass $class): RedirectResponse
    {
        if ($class->students()->exists()) {
            return back()->with('error', 'Kelas yang masih memiliki siswa tidak dapat dihapus.');
        }

        $class->delete();

        return redirect()->route('admin.classes.index')
            ->with('success', 'Kelas berhasil dihapus.');
    }

    /**
     * @return array<string, mixed>
     */
    private function validatedData(Request $request, ?SchoolClass $class = null): array
    {
        return $request->validate([
            'academic_year_id' => ['required', 'exists:academic_years,id'],
            'name' => [
                'required',
                'string',
                'max:60',
                Rule::unique('school_classes', 'name')
                    ->where('academic_year_id', $request->integer('academic_year_id'))
                    ->ignore($class?->id),
            ],
            'level' => ['required', Rule::in(['X', 'XI', 'XII'])],
            'major' => ['nullable', 'string', 'max:60'],
            'homeroom_teacher' => ['nullable', 'string', 'max:120'],
        ]);
    }
}
