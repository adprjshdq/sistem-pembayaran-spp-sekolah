@extends('layouts.app')

@section('title', 'Laporan SPP')

@section('content')
    <div class="space-y-5">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-lg font-bold text-ink">Rekapitulasi &amp; Laporan SPP</h2>
                <p class="mt-0.5 text-xs text-muted-soft">Filter data untuk melihat rekap penerimaan dan tunggakan.</p>
            </div>
            <div class="no-print flex gap-2">
                <a href="{{ route('admin.reports.export', request()->query()) }}" class="rounded-xl bg-emerald-600 px-4 py-2.5 text-xs font-bold text-white transition hover:bg-emerald-700">Export Excel (CSV)</a>
                <button type="button" onclick="window.print()" class="rounded-xl bg-brand px-4 py-2.5 text-xs font-bold text-white transition hover:bg-brand-dark">Cetak</button>
            </div>
        </div>

        <form method="GET" class="no-print grid gap-3 rounded-2xl border border-line bg-white p-4 shadow-sm sm:grid-cols-7">
            <select name="academic_year_id" class="rounded-xl border border-line-strong px-4 py-2.5 text-sm outline-none focus:border-brand focus:ring-2 focus:ring-brand/15">
                <option value="">Semua tahun ajaran</option>
                @foreach ($academicYears as $year)
                    <option value="{{ $year->id }}" @selected((int) request('academic_year_id') === $year->id)>{{ $year->name }}</option>
                @endforeach
            </select>
            <select name="class_id" class="rounded-xl border border-line-strong px-4 py-2.5 text-sm outline-none focus:border-brand focus:ring-2 focus:ring-brand/15">
                <option value="">Semua kelas</option>
                @foreach ($classes as $class)
                    <option value="{{ $class->id }}" @selected((int) request('class_id') === $class->id)>{{ $class->name }}</option>
                @endforeach
            </select>
            <select name="period_month" class="rounded-xl border border-line-strong px-4 py-2.5 text-sm outline-none focus:border-brand focus:ring-2 focus:ring-brand/15">
                <option value="">Semua bulan</option>
                @foreach ($months as $value => $label)
                    <option value="{{ $value }}" @selected((int) request('period_month') === $value)>{{ $label }}</option>
                @endforeach
            </select>
            <select name="period_year" class="rounded-xl border border-line-strong px-4 py-2.5 text-sm outline-none focus:border-brand focus:ring-2 focus:ring-brand/15">
                <option value="">Semua tahun</option>
                @foreach ($years as $year)
                    <option value="{{ $year }}" @selected((int) request('period_year') === $year)>{{ $year }}</option>
                @endforeach
            </select>
            <select name="status" class="rounded-xl border border-line-strong px-4 py-2.5 text-sm outline-none focus:border-brand focus:ring-2 focus:ring-brand/15">
                <option value="">Semua status</option>
                <option value="unpaid" @selected(request('status') === 'unpaid')>Belum dibayar</option>
                <option value="partial" @selected(request('status') === 'partial')>Sebagian</option>
                <option value="paid" @selected(request('status') === 'paid')>Lunas</option>
            </select>
            <input type="search" name="search" value="{{ request('search') }}" placeholder="Cari nama/NIS"
                   class="rounded-xl border border-line-strong px-4 py-2.5 text-sm outline-none focus:border-brand focus:ring-2 focus:ring-brand/15">
            <button type="submit" class="rounded-xl bg-brand px-4 py-2.5 text-xs font-bold text-white transition hover:bg-brand-dark">Terapkan</button>
        </form>

        <div class="grid gap-4 sm:grid-cols-4">
            <div class="rounded-2xl border border-line bg-white p-5 shadow-sm">
                <p class="text-[10px] font-bold uppercase tracking-wide text-muted-faint">Jumlah Tagihan</p>
                <p class="mt-2 text-2xl font-extrabold text-ink">{{ number_format($summary['total_bills']) }}</p>
            </div>
            <div class="rounded-2xl border border-line bg-white p-5 shadow-sm">
                <p class="text-[10px] font-bold uppercase tracking-wide text-muted-faint">Total Tagihan</p>
                <p class="mt-2 text-xl font-extrabold text-ink">{{ rupiah($summary['total_amount']) }}</p>
            </div>
            <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-5 shadow-sm">
                <p class="text-[10px] font-bold uppercase tracking-wide text-emerald-700">Total Diterima</p>
                <p class="mt-2 text-xl font-extrabold text-emerald-800">{{ rupiah($summary['total_paid']) }}</p>
            </div>
            <div class="rounded-2xl border border-rose-200 bg-rose-50 p-5 shadow-sm">
                <p class="text-[10px] font-bold uppercase tracking-wide text-rose-700">Sisa Tunggakan</p>
                <p class="mt-2 text-xl font-extrabold text-rose-800">{{ rupiah($summary['total_outstanding']) }}</p>
            </div>
        </div>

        <div class="overflow-hidden rounded-2xl border border-line bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table class="min-w-full text-left text-xs">
                    <thead class="bg-cream-soft text-[10px] uppercase tracking-wide text-muted-soft">
                        <tr>
                            <th class="px-4 py-3 font-bold">NIS</th>
                            <th class="px-4 py-3 font-bold">Nama</th>
                            <th class="px-4 py-3 font-bold">Kelas</th>
                            <th class="px-4 py-3 font-bold">Periode</th>
                            <th class="px-4 py-3 font-bold">Jatuh Tempo</th>
                            <th class="px-4 py-3 text-right font-bold">Tagihan</th>
                            <th class="px-4 py-3 text-right font-bold">Dibayar</th>
                            <th class="px-4 py-3 text-right font-bold">Sisa</th>
                            <th class="px-4 py-3 font-bold">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-line-soft">
                        @forelse ($bills as $bill)
                            <tr class="hover:bg-cream-soft">
                                <td class="px-4 py-2.5 font-mono text-muted">{{ $bill->student?->nis }}</td>
                                <td class="px-4 py-2.5 font-semibold text-ink">{{ $bill->student?->name }}</td>
                                <td class="px-4 py-2.5 text-muted">{{ $bill->student?->schoolClass?->name ?? '-' }}</td>
                                <td class="px-4 py-2.5 text-muted">{{ $bill->periodLabel() }}</td>
                                <td class="px-4 py-2.5 text-muted">{{ $bill->due_date?->format('d/m/Y') }}</td>
                                <td class="px-4 py-2.5 text-right font-semibold text-ink">{{ rupiah($bill->amount) }}</td>
                                <td class="px-4 py-2.5 text-right text-emerald-700">{{ rupiah($bill->paid_amount) }}</td>
                                <td class="px-4 py-2.5 text-right text-rose-600">{{ rupiah($bill->remainingAmount()) }}</td>
                                <td class="px-4 py-2.5">
                                    <span class="inline-flex rounded-full border px-2 py-0.5 text-[10px] font-bold {{ $bill->status->badgeClasses() }}">{{ $bill->status->label() }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="px-4 py-10 text-center text-muted-soft">Tidak ada data untuk filter yang dipilih.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($bills->hasPages())
                <div class="no-print border-t border-line-soft px-5 py-4">{{ $bills->links() }}</div>
            @endif
        </div>
    </div>
@endsection
