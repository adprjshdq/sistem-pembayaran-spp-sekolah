@extends('layouts.app')

@section('title', 'Kelas')

@section('content')
    <div class="space-y-5">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-lg font-bold text-ink">Kelas</h2>
                <p class="mt-0.5 text-xs text-muted-soft">Daftar kelas per tahun ajaran.</p>
            </div>
            <a href="{{ route('admin.classes.create') }}" class="rounded-xl bg-brand px-4 py-2.5 text-xs font-bold text-white shadow-sm transition hover:bg-brand-dark">Tambah Kelas</a>
        </div>

        <form method="GET" class="flex gap-2 rounded-2xl border border-line bg-white p-4 shadow-sm">
            <select name="academic_year_id" class="w-full max-w-xs rounded-xl border border-line-strong px-4 py-2.5 text-sm outline-none focus:border-brand focus:ring-2 focus:ring-brand/15">
                <option value="">Semua tahun ajaran</option>
                @foreach ($academicYears as $year)
                    <option value="{{ $year->id }}" @selected((int) request('academic_year_id') === $year->id)>{{ $year->name }}</option>
                @endforeach
            </select>
            <button type="submit" class="rounded-xl bg-brand px-4 py-2.5 text-xs font-bold text-white transition hover:bg-brand-dark">Filter</button>
        </form>

        <div class="overflow-hidden rounded-2xl border border-line bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table class="min-w-full text-left text-xs">
                    <thead class="bg-cream-soft text-[10px] uppercase tracking-wide text-muted-soft">
                        <tr>
                            <th class="px-5 py-3 font-bold">Nama Kelas</th>
                            <th class="px-5 py-3 font-bold">Tingkat</th>
                            <th class="px-5 py-3 font-bold">Jurusan</th>
                            <th class="px-5 py-3 font-bold">Tahun Ajaran</th>
                            <th class="px-5 py-3 font-bold">Wali Kelas</th>
                            <th class="px-5 py-3 font-bold">Siswa</th>
                            <th class="px-5 py-3 text-right font-bold">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-line-soft">
                        @forelse ($classes as $class)
                            <tr class="hover:bg-cream-soft">
                                <td class="px-5 py-3 font-semibold text-ink">{{ $class->name }}</td>
                                <td class="px-5 py-3 text-muted">{{ $class->level }}</td>
                                <td class="px-5 py-3 text-muted">{{ $class->major ?? '-' }}</td>
                                <td class="px-5 py-3 text-muted">{{ $class->academicYear?->name }}</td>
                                <td class="px-5 py-3 text-muted">{{ $class->homeroom_teacher ?? '-' }}</td>
                                <td class="px-5 py-3 font-bold text-ink">{{ $class->students_count }}</td>
                                <td class="px-5 py-3">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('admin.classes.edit', $class) }}" class="rounded-lg border border-line px-2.5 py-1.5 text-[11px] font-bold text-muted-strong transition hover:bg-cream-soft">Edit</a>
                                        <form method="POST" action="{{ route('admin.classes.destroy', $class) }}" onsubmit="return confirm('Hapus kelas ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="rounded-lg border border-rose-200 px-2.5 py-1.5 text-[11px] font-bold text-rose-600 transition hover:bg-rose-50">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-5 py-10 text-center text-muted-soft">Belum ada kelas.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
