@extends('layouts.app')

@section('title', 'Data Siswa')

@section('content')
    <div class="space-y-5">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-lg font-bold text-ink">Data Siswa</h2>
                <p class="mt-0.5 text-xs text-muted-soft">Kelola data siswa dan akun portal pembayaran.</p>
            </div>
            <a href="{{ route('admin.students.create') }}" class="inline-flex items-center justify-center rounded-xl bg-brand px-4 py-2.5 text-xs font-bold text-white shadow-sm transition hover:bg-brand-dark">
                Tambah Siswa
            </a>
        </div>

        <form method="GET" class="grid gap-3 rounded-2xl border border-line bg-white p-4 shadow-sm sm:grid-cols-4">
            <div class="sm:col-span-2">
                <input type="search" name="search" value="{{ request('search') }}" placeholder="Cari nama atau NIS..."
                       class="w-full rounded-xl border border-line-strong px-4 py-2.5 text-sm outline-none focus:border-brand focus:ring-2 focus:ring-brand/15">
            </div>
            <select name="class_id" class="rounded-xl border border-line-strong px-4 py-2.5 text-sm outline-none focus:border-brand focus:ring-2 focus:ring-brand/15">
                <option value="">Semua kelas</option>
                @foreach ($classes as $class)
                    <option value="{{ $class->id }}" @selected((int) request('class_id') === $class->id)>{{ $class->name }}</option>
                @endforeach
            </select>
            <div class="flex gap-2">
                <select name="status" class="w-full rounded-xl border border-line-strong px-4 py-2.5 text-sm outline-none focus:border-brand focus:ring-2 focus:ring-brand/15">
                    <option value="">Semua status</option>
                    <option value="active" @selected(request('status') === 'active')>Aktif</option>
                    <option value="inactive" @selected(request('status') === 'inactive')>Nonaktif</option>
                    <option value="graduated" @selected(request('status') === 'graduated')>Lulus</option>
                </select>
                <button type="submit" class="rounded-xl bg-brand px-4 py-2.5 text-xs font-bold text-white transition hover:bg-brand-dark">Cari</button>
            </div>
        </form>

        <div class="overflow-hidden rounded-2xl border border-line bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table class="min-w-full text-left text-xs">
                    <thead class="bg-cream-soft text-[10px] uppercase tracking-wide text-muted-soft">
                        <tr>
                            <th class="px-5 py-3 font-bold">NIS</th>
                            <th class="px-5 py-3 font-bold">Nama</th>
                            <th class="px-5 py-3 font-bold">Kelas</th>
                            <th class="px-5 py-3 font-bold">Kontak</th>
                            <th class="px-5 py-3 font-bold">Akun Portal</th>
                            <th class="px-5 py-3 font-bold">Status</th>
                            <th class="px-5 py-3 text-right font-bold">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-line-soft">
                        @forelse ($students as $student)
                            <tr class="hover:bg-cream-soft">
                                <td class="px-5 py-3 font-mono font-semibold text-muted-strong">{{ $student->nis }}</td>
                                <td class="px-5 py-3">
                                    <p class="font-semibold text-ink">{{ $student->name }}</p>
                                    <p class="text-[10px] text-muted-faint">{{ $student->parent_name ?? '-' }}</p>
                                </td>
                                <td class="px-5 py-3 text-muted">{{ $student->schoolClass?->name ?? '-' }}</td>
                                <td class="px-5 py-3 text-muted">{{ $student->phone ?? $student->parent_phone ?? '-' }}</td>
                                <td class="px-5 py-3 text-muted">{{ $student->user?->email ?? 'Belum ada' }}</td>
                                <td class="px-5 py-3">
                                    @php
                                        $statusClass = match ($student->status) {
                                            'active' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                            'graduated' => 'bg-sky-50 text-sky-700 border-sky-200',
                                            default => 'bg-cream text-muted border-line',
                                        };
                                        $statusLabel = match ($student->status) {
                                            'active' => 'Aktif',
                                            'graduated' => 'Lulus',
                                            default => 'Nonaktif',
                                        };
                                    @endphp
                                    <span class="inline-flex rounded-full border px-2 py-0.5 text-[10px] font-bold {{ $statusClass }}">{{ $statusLabel }}</span>
                                </td>
                                <td class="px-5 py-3">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('admin.students.edit', $student) }}" class="rounded-lg border border-line px-2.5 py-1.5 text-[11px] font-bold text-muted-strong transition hover:bg-cream-soft">Edit</a>
                                        <form method="POST" action="{{ route('admin.students.destroy', $student) }}" onsubmit="return confirm('Hapus data siswa ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="rounded-lg border border-rose-200 px-2.5 py-1.5 text-[11px] font-bold text-rose-600 transition hover:bg-rose-50">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-5 py-10 text-center text-muted-soft">Belum ada data siswa yang cocok.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($students->hasPages())
                <div class="border-t border-line-soft px-5 py-4">{{ $students->links() }}</div>
            @endif
        </div>
    </div>
@endsection
