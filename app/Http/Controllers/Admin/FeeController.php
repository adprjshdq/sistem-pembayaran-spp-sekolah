<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\Fee;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class FeeController extends Controller
{
    public function index(Request $request): View
    {
        $fees = Fee::query()
            ->with('academicYear')
            ->when($request->filled('academic_year_id'), fn ($query) => $query->where('academic_year_id', $request->integer('academic_year_id')))
            ->orderByDesc('academic_year_id')
            ->orderBy('level')
            ->get();

        return view('admin.fees.index', [
            'fees' => $fees,
            'academicYears' => AcademicYear::query()->latest('start_date')->get(),
        ]);
    }

    public function create(): View
    {
        return view('admin.fees.create', [
            'academicYears' => AcademicYear::query()->latest('start_date')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        Fee::create($this->validatedData($request));

        return redirect()->route('admin.fees.index')
            ->with('success', 'Tarif SPP berhasil ditambahkan.');
    }

    public function edit(Fee $fee): View
    {
        return view('admin.fees.edit', [
            'fee' => $fee,
            'academicYears' => AcademicYear::query()->latest('start_date')->get(),
        ]);
    }

    public function update(Request $request, Fee $fee): RedirectResponse
    {
        $fee->update($this->validatedData($request, $fee));

        return redirect()->route('admin.fees.index')
            ->with('success', 'Tarif SPP berhasil diperbarui.');
    }

    public function destroy(Fee $fee): RedirectResponse
    {
        $fee->delete();

        return redirect()->route('admin.fees.index')
            ->with('success', 'Tarif SPP berhasil dihapus.');
    }

    /**
     * @return array<string, mixed>
     */
    private function validatedData(Request $request, ?Fee $fee = null): array
    {
        $data = $request->validate([
            'academic_year_id' => ['required', 'exists:academic_years,id'],
            'level' => [
                'required',
                Rule::in(['X', 'XI', 'XII']),
                Rule::unique('fees', 'level')
                    ->where('academic_year_id', $request->integer('academic_year_id'))
                    ->ignore($fee?->id),
            ],
            'amount' => ['required', 'numeric', 'min:0'],
        ]);

        $data['amount'] = (int) $data['amount'];

        return $data;
    }
}
