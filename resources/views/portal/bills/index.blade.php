@extends('layouts.app')

@section('title', 'Tagihan Saya')

@section('content')
    <div class="space-y-5">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-lg font-bold text-ink">Tagihan Saya</h2>
                <p class="mt-0.5 text-xs text-muted-soft">{{ $student->name }} &middot; {{ $student->schoolClass?->name }}</p>
            </div>
            <div class="rounded-xl border border-rose-200 bg-rose-50 px-4 py-2.5 text-right">
                <p class="text-[10px] font-bold uppercase tracking-wide text-rose-700">Total Tunggakan</p>
                <p class="text-lg font-extrabold text-rose-800">{{ rupiah($outstanding) }}</p>
            </div>
        </div>

        <form method="GET" class="flex flex-wrap gap-2 rounded-2xl border border-line bg-white p-4 shadow-sm">
            <select name="status" class="rounded-xl border border-line-strong px-4 py-2.5 text-sm outline-none focus:border-brand focus:ring-2 focus:ring-brand/15">
                <option value="">Semua status</option>
                <option value="unpaid" @selected(request('status') === 'unpaid')>Belum dibayar</option>
                <option value="partial" @selected(request('status') === 'partial')>Sebagian</option>
                <option value="paid" @selected(request('status') === 'paid')>Lunas</option>
            </select>
            <select name="period_year" class="rounded-xl border border-line-strong px-4 py-2.5 text-sm outline-none focus:border-brand focus:ring-2 focus:ring-brand/15">
                <option value="">Semua tahun</option>
                @foreach ($years as $year)
                    <option value="{{ $year }}" @selected((int) request('period_year') === $year)>{{ $year }}</option>
                @endforeach
            </select>
            <button type="submit" class="rounded-xl bg-brand px-4 py-2.5 text-xs font-bold text-white transition hover:bg-brand-dark">Filter</button>
            <a href="{{ route('portal.dashboard') }}" class="rounded-xl border border-brand/30 px-4 py-2.5 text-xs font-bold text-brand transition hover:bg-brand-soft">Bayar Tagihan</a>
        </form>

        <div class="overflow-hidden rounded-2xl border border-line bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table class="min-w-full text-left text-xs">
                    <thead class="bg-cream-soft text-[10px] uppercase tracking-wide text-muted-soft">
                        <tr>
                            <th class="px-5 py-3 font-bold">Periode</th>
                            <th class="px-5 py-3 font-bold">Tahun Ajaran</th>
                            <th class="px-5 py-3 font-bold">Jatuh Tempo</th>
                            <th class="px-5 py-3 text-right font-bold">Tagihan</th>
                            <th class="px-5 py-3 text-right font-bold">Dibayar</th>
                            <th class="px-5 py-3 text-right font-bold">Sisa</th>
                            <th class="px-5 py-3 font-bold">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-line-soft">
                        @forelse ($bills as $bill)
                            <tr class="hover:bg-cream-soft">
                                <td class="px-5 py-3 font-semibold text-ink">{{ $bill->periodLabel() }}</td>
                                <td class="px-5 py-3 text-muted">{{ $bill->academicYear?->name }}</td>
                                <td class="px-5 py-3 text-muted">{{ $bill->due_date?->format('d/m/Y') }}</td>
                                <td class="px-5 py-3 text-right font-semibold text-ink">{{ rupiah($bill->amount) }}</td>
                                <td class="px-5 py-3 text-right text-emerald-700">{{ rupiah($bill->paid_amount) }}</td>
                                <td class="px-5 py-3 text-right {{ $bill->remainingAmount() > 0 ? 'font-semibold text-rose-600' : 'text-muted-faint' }}">{{ rupiah($bill->remainingAmount()) }}</td>
                                <td class="px-5 py-3">
                                    <span class="inline-flex rounded-full border px-2 py-0.5 text-[10px] font-bold {{ $bill->status->badgeClasses() }}">{{ $bill->status->label() }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-5 py-10 text-center text-muted-soft">Tidak ada tagihan untuk filter yang dipilih.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($bills->hasPages())
                <div class="border-t border-line-soft px-5 py-4">{{ $bills->links() }}</div>
            @endif
        </div>
    </div>
@endsection
