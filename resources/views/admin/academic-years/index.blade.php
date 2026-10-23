@extends('layouts.app')

@section('title', 'Tahun Ajaran')

@section('content')
    <div class="space-y-5">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-lg font-bold text-ink">Tahun Ajaran</h2>
                <p class="mt-0.5 text-xs text-muted-soft">Atur periode tahun ajaran dan tentukan yang aktif.</p>
            </div>
            <a href="{{ route('admin.academic-years.create') }}" class="rounded-xl bg-brand px-4 py-2.5 text-xs font-bold text-white shadow-sm transition hover:bg-brand-dark">Tambah Tahun Ajaran</a>
        </div>

        <div class="overflow-hidden rounded-2xl border border-line bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table class="min-w-full text-left text-xs">
                    <thead class="bg-cream-soft text-[10px] uppercase tracking-wide text-muted-soft">
                        <tr>
                            <th class="px-5 py-3 font-bold">Tahun Ajaran</th>
                            <th class="px-5 py-3 font-bold">Periode</th>
                            <th class="px-5 py-3 font-bold">Kelas</th>
                            <th class="px-5 py-3 font-bold">Tagihan</th>
                            <th class="px-5 py-3 font-bold">Status</th>
                            <th class="px-5 py-3 text-right font-bold">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-line-soft">
                        @forelse ($academicYears as $year)
                            <tr class="hover:bg-cream-soft">
                                <td class="px-5 py-3 font-semibold text-ink">{{ $year->name }}</td>
                                <td class="px-5 py-3 text-muted">
                                    {{ $year->start_date?->locale('id')->translatedFormat('d M Y') }} &ndash;
                                    {{ $year->end_date?->locale('id')->translatedFormat('d M Y') }}
                                </td>
                                <td class="px-5 py-3 text-muted">{{ $year->classes_count }}</td>
                                <td class="px-5 py-3 text-muted">{{ $year->bills_count }}</td>
                                <td class="px-5 py-3">
                                    @if ($year->is_active)
                                        <span class="inline-flex rounded-full border border-emerald-200 bg-emerald-50 px-2 py-0.5 text-[10px] font-bold text-emerald-700">Aktif</span>
                                    @else
                                        <span class="inline-flex rounded-full border border-line bg-cream px-2 py-0.5 text-[10px] font-bold text-muted">Tidak aktif</span>
                                    @endif
                                </td>
                                <td class="px-5 py-3">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('admin.academic-years.edit', $year) }}" class="rounded-lg border border-line px-2.5 py-1.5 text-[11px] font-bold text-muted-strong transition hover:bg-cream-soft">Edit</a>
                                        <form method="POST" action="{{ route('admin.academic-years.destroy', $year) }}" onsubmit="return confirm('Hapus tahun ajaran ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="rounded-lg border border-rose-200 px-2.5 py-1.5 text-[11px] font-bold text-rose-600 transition hover:bg-rose-50">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-5 py-10 text-center text-muted-soft">Belum ada tahun ajaran.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
