@extends('layouts.app')

@section('title', 'Riwayat Pembayaran')

@section('content')
    <div class="space-y-5">
        <div>
            <h2 class="text-lg font-bold text-ink">Riwayat Pembayaran</h2>
            <p class="mt-0.5 text-xs text-muted-soft">Seluruh transaksi pembayaran SPP Anda.</p>
        </div>

        <div class="overflow-hidden rounded-2xl border border-line bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table class="min-w-full text-left text-xs">
                    <thead class="bg-cream-soft text-[10px] uppercase tracking-wide text-muted-soft">
                        <tr>
                            <th class="px-5 py-3 font-bold">Invoice</th>
                            <th class="px-5 py-3 font-bold">Tanggal</th>
                            <th class="px-5 py-3 font-bold">Tagihan</th>
                            <th class="px-5 py-3 font-bold">Metode</th>
                            <th class="px-5 py-3 text-right font-bold">Jumlah</th>
                            <th class="px-5 py-3 font-bold">Status</th>
                            <th class="px-5 py-3 text-right font-bold">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-line-soft">
                        @forelse ($payments as $payment)
                            <tr class="hover:bg-cream-soft">
                                <td class="px-5 py-3 font-mono font-semibold text-brand">{{ $payment->invoice_number }}</td>
                                <td class="px-5 py-3 text-muted">{{ ($payment->paid_at ?? $payment->created_at)->locale('id')->translatedFormat('d M Y H:i') }}</td>
                                <td class="px-5 py-3 text-muted">
                                    {{ $payment->bills->map(fn ($bill) => $bill->periodLabel())->join(', ') }}
                                </td>
                                <td class="px-5 py-3 text-muted">{{ $payment->channel ?: ucfirst($payment->method) }}</td>
                                <td class="px-5 py-3 text-right font-bold text-ink">{{ rupiah($payment->amount) }}</td>
                                <td class="px-5 py-3">
                                    <span class="inline-flex rounded-full border px-2 py-0.5 text-[10px] font-bold {{ $payment->status->badgeClasses() }}">{{ $payment->status->label() }}</span>
                                </td>
                                <td class="px-5 py-3">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('portal.payments.show', $payment) }}" class="rounded-lg border border-line px-2.5 py-1.5 text-[11px] font-bold text-muted-strong transition hover:bg-cream-soft">Detail</a>
                                        @if ($payment->status->value === 'success')
                                            <a href="{{ route('portal.payments.receipt', $payment) }}" target="_blank" class="rounded-lg border border-brand/30 px-2.5 py-1.5 text-[11px] font-bold text-brand transition hover:bg-brand-soft">Kwitansi</a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-5 py-10 text-center text-muted-soft">Belum ada riwayat pembayaran.</td>
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
