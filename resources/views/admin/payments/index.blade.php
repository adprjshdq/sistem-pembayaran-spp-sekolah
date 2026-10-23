@extends('layouts.app')

@section('title', 'Pembayaran')

@section('content')
    <div class="space-y-5">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-lg font-bold text-ink">Pembayaran</h2>
                <p class="mt-0.5 text-xs text-muted-soft">Riwayat transaksi pembayaran SPP.</p>
            </div>
            <a href="{{ route('admin.payments.create') }}" class="rounded-xl bg-brand px-4 py-2.5 text-xs font-bold text-white shadow-sm transition hover:bg-brand-dark">Catat Pembayaran</a>
        </div>

        <form method="GET" class="grid gap-3 rounded-2xl border border-line bg-white p-4 shadow-sm sm:grid-cols-5">
            <div class="sm:col-span-2">
                <input type="search" name="search" value="{{ request('search') }}" placeholder="Cari invoice, nama, atau NIS..."
                       class="w-full rounded-xl border border-line-strong px-4 py-2.5 text-sm outline-none focus:border-brand focus:ring-2 focus:ring-brand/15">
            </div>
            <select name="status" class="rounded-xl border border-line-strong px-4 py-2.5 text-sm outline-none focus:border-brand focus:ring-2 focus:ring-brand/15">
                <option value="">Semua status</option>
                <option value="pending" @selected(request('status') === 'pending')>Menunggu</option>
                <option value="success" @selected(request('status') === 'success')>Berhasil</option>
                <option value="failed" @selected(request('status') === 'failed')>Gagal</option>
            </select>
            <select name="method" class="rounded-xl border border-line-strong px-4 py-2.5 text-sm outline-none focus:border-brand focus:ring-2 focus:ring-brand/15">
                <option value="">Semua metode</option>
                <option value="cash" @selected(request('method') === 'cash')>Tunai</option>
                <option value="transfer" @selected(request('method') === 'transfer')>Transfer</option>
                <option value="midtrans" @selected(request('method') === 'midtrans')>Midtrans</option>
            </select>
            <button type="submit" class="rounded-xl bg-brand px-4 py-2.5 text-xs font-bold text-white transition hover:bg-brand-dark">Filter</button>
        </form>

        <div class="overflow-hidden rounded-2xl border border-line bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table class="min-w-full text-left text-xs">
                    <thead class="bg-cream-soft text-[10px] uppercase tracking-wide text-muted-soft">
                        <tr>
                            <th class="px-5 py-3 font-bold">Invoice</th>
                            <th class="px-5 py-3 font-bold">Siswa</th>
                            <th class="px-5 py-3 font-bold">Metode</th>
                            <th class="px-5 py-3 font-bold">Jumlah</th>
                            <th class="px-5 py-3 font-bold">Status</th>
                            <th class="px-5 py-3 font-bold">Waktu</th>
                            <th class="px-5 py-3 text-right font-bold">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-line-soft">
                        @forelse ($payments as $payment)
                            <tr class="hover:bg-cream-soft">
                                <td class="px-5 py-3 font-mono font-semibold text-brand">{{ $payment->invoice_number }}</td>
                                <td class="px-5 py-3">
                                    <p class="font-semibold text-ink">{{ $payment->student?->name }}</p>
                                    <p class="text-[10px] text-muted-faint">{{ $payment->student?->nis }} &middot; {{ $payment->student?->schoolClass?->name }}</p>
                                </td>
                                <td class="px-5 py-3">
                                    <p class="text-muted-strong">{{ $payment->channel ?: ucfirst($payment->method) }}</p>
                                    <p class="text-[10px] text-muted-faint">{{ ucfirst($payment->method) }}</p>
                                </td>
                                <td class="px-5 py-3 font-bold text-ink">{{ rupiah($payment->amount) }}</td>
                                <td class="px-5 py-3">
                                    <span class="inline-flex rounded-full border px-2 py-0.5 text-[10px] font-bold {{ $payment->status->badgeClasses() }}">{{ $payment->status->label() }}</span>
                                </td>
                                <td class="px-5 py-3 text-muted-soft">{{ ($payment->paid_at ?? $payment->created_at)->locale('id')->translatedFormat('d M Y H:i') }}</td>
                                <td class="px-5 py-3 text-right">
                                    <a href="{{ route('admin.payments.show', $payment) }}" class="rounded-lg border border-line px-2.5 py-1.5 text-[11px] font-bold text-muted-strong transition hover:bg-cream-soft">Detail</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-5 py-10 text-center text-muted-soft">Belum ada pembayaran.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($payments->hasPages())
                <div class="border-t border-line-soft px-5 py-4">{{ $payments->links() }}</div>
            @endif
        </div>
    </div>
@endsection
