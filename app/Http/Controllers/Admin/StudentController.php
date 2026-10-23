<?php

namespace App\Http\Controllers\Admin;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class StudentController extends Controller
{
    public function index(Request $request): View
    {
        $students = Student::query()
            ->with('schoolClass')
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->string('search')->toString();
                $query->where(fn ($query) => $query
                    ->where('name', 'like', "%{$search}%")
                    ->orWhere('nis', 'like', "%{$search}%"));
            })
            ->when($request->filled('class_id'), fn ($query) => $query->where('class_id', $request->integer('class_id')))
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->string('status')->toString()))
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        return view('admin.students.index', [
            'students' => $students,
            'classes' => SchoolClass::query()->with('academicYear')->orderBy('name')->get(),
        ]);
    }

    public function create(): View
    {
        return view('admin.students.create', [
            'classes' => $this->classes(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatedData($request);

        DB::transaction(function () use ($data, $request) {
            $user = $this->syncUserAccount($request, $data['name']);

            Student::create([
                'user_id' => $user?->id,
                'class_id' => $data['class_id'],
                'nis' => $data['nis'],
                'name' => $data['name'],
                'gender' => $data['gender'],
                'phone' => $data['phone'] ?? null,
                'parent_name' => $data['parent_name'] ?? null,
                'parent_phone' => $data['parent_phone'] ?? null,
                'address' => $data['address'] ?? null,
                'status' => $data['status'],
            ]);
        });

        return redirect()->route('admin.students.index')
            ->with('success', 'Data siswa berhasil ditambahkan.');
    }

    public function edit(Student $student): View
    {
        return view('admin.students.edit', [
            'student' => $student->load('user'),
            'classes' => $this->classes(),
        ]);
    }

    public function update(Request $request, Student $student): RedirectResponse
    {
        $data = $this->validatedData($request, $student);

        DB::transaction(function () use ($data, $request, $student) {
            $user = $this->syncUserAccount($request, $data['name'], $student->user);

            $student->update([
                'user_id' => $user?->id ?? $student->user_id,
                'class_id' => $data['class_id'],
                'nis' => $data['nis'],
                'name' => $data['name'],
                'gender' => $data['gender'],
                'phone' => $data['phone'] ?? null,
                'parent_name' => $data['parent_name'] ?? null,
                'parent_phone' => $data['parent_phone'] ?? null,
                'address' => $data['address'] ?? null,
                'status' => $data['status'],
            ]);
        });

        return redirect()->route('admin.students.index')
            ->with('success', 'Data siswa berhasil diperbarui.');
    }

    public function destroy(Student $student): RedirectResponse
    {
        if ($student->bills()->where('paid_amount', '>', 0)->exists()) {
            return back()->with('error', 'Siswa yang sudah memiliki pembayaran tidak dapat dihapus.');
        }

        DB::transaction(function () use ($student) {
            $user = $student->user;
            $student->delete();

            if ($user && $user->isStudent()) {
                $user->delete();
            }
        });

        return redirect()->route('admin.students.index')
            ->with('success', 'Data siswa berhasil dihapus.');
    }

    /**
     * @return array<string, mixed>
     */
    private function validatedData(Request $request, ?Student $student = null): array
    {
        return $request->validate([
            'nis' => ['required', 'string', 'max:30', Rule::unique('students', 'nis')->ignore($student?->id)],
            'name' => ['required', 'string', 'max:120'],
            'class_id' => ['required', 'exists:school_classes,id'],
            'gender' => ['required', Rule::in(['male', 'female'])],
            'phone' => ['nullable', 'string', 'max:30'],
            'parent_name' => ['nullable', 'string', 'max:120'],
            'parent_phone' => ['nullable', 'string', 'max:30'],
            'address' => ['nullable', 'string', 'max:500'],
            'status' => ['required', Rule::in(['active', 'inactive', 'graduated'])],
            'email' => ['nullable', 'email', 'max:120', Rule::unique('users', 'email')->ignore($student?->user_id)],
            'password' => ['nullable', 'string', 'min:6'],
        ]);
    }

    private function syncUserAccount(Request $request, string $name, ?User $user = null): ?User
    {
        if (! $request->filled('email')) {
            return $user;
        }

        $attributes = [
            'name' => $name,
            'email' => $request->string('email')->toString(),
            'role' => UserRole::Student,
            'is_active' => true,
        ];

        if ($request->filled('password')) {
            $attributes['password'] = $request->string('password')->toString();
        }

        if ($user) {
            $user->update($attributes);

            return $user;
        }

        $attributes['password'] = $request->string('password')->toString() ?: 'password';

        return User::create($attributes);
    }

    private function classes()
    {
        $activeYear = AcademicYear::active();

        return SchoolClass::query()
            ->when($activeYear, fn ($query) => $query->where('academic_year_id', $activeYear->id))
            ->with('academicYear')
            ->orderBy('name')
            ->get();
    }
}
