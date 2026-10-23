@extends('layouts.app')

@section('title', 'Detail Pembayaran')

@section('content')
    <div class="mx-auto max-w-3xl space-y-5">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <a href="{{ route('admin.payments.index') }}" class="text-xs font-semibold text-brand hover:text-brand-dark">&larr; Kembali ke pembayaran</a>
                <h2 class="mt-2 text-lg font-bold text-ink">Detail Pembayaran</h2>
                <p class="mt-0.5 font-mono text-xs text-muted-soft">{{ $payment->invoice_number }}</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('admin.payments.receipt', $payment) }}" target="_blank" class="rounded-xl bg-brand px-4 py-2.5 text-xs font-bold text-white transition hover:bg-brand-dark">Cetak Kwitansi</a>
            </div>
        </div>

        <div class="overflow-hidden rounded-2xl border border-line bg-white shadow-sm">
            <div class="flex flex-col gap-4 border-b border-line-soft bg-cream-soft px-5 py-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <p class="text-[10px] font-bold uppercase tracking-wide text-muted-faint">Status Pembayaran</p>
                    <span class="mt-1 inline-flex rounded-full border px-2.5 py-0.5 text-[11px] font-bold {{ $payment->status->badgeClasses() }}">
                        {{ $payment->status->label() }}
                    </span>
                </div>
                <div class="sm:text-right">
                    <p class="text-[10px] font-bold uppercase tracking-wide text-muted-faint">Total</p>
                    <p class="text-2xl font-extrabold text-ink">{{ rupiah($payment->amount) }}</p>
                </div>
            </div>

            <div class="grid gap-4 px-5 py-5 sm:grid-cols-2">
                <div>
                    <p class="text-[10px] font-bold uppercase tracking-wide text-muted-faint">Siswa</p>
                    <p class="mt-1 text-sm font-bold text-ink">{{ $payment->student?->name }}</p>
                    <p class="text-[11px] text-muted-soft">{{ $payment->student?->nis }} &middot; {{ $payment->student?->schoolClass?->name }}</p>
                </div>
                <div>
                    <p class="text-[10px] font-bold uppercase tracking-wide text-muted-faint">Metode</p>
                    <p class="mt-1 text-sm font-semibold text-ink">{{ $payment->channel ?: ucfirst($payment->method) }}</p>
                    <p class="text-[11px] text-muted-soft">{{ ucfirst($payment->method) }}</p>
                </div>
                <div>
                    <p class="text-[10px] font-bold uppercase tracking-wide text-muted-faint">Waktu Pembayaran</p>
                    <p class="mt-1 text-sm text-muted-strong">{{ ($payment->paid_at ?? $payment->created_at)->locale('id')->translatedFormat('d F Y H:i') }}</p>
                </div>
                <div>
                    <p class="text-[10px] font-bold uppercase tracking-wide text-muted-faint">Dicatat Oleh</p>
                    <p class="mt-1 text-sm text-muted-strong">{{ $payment->recordedBy?->name ?? 'Sistem / Portal Siswa' }}</p>
                </div>
                @if ($payment->notes)
                    <div class="sm:col-span-2">
                        <p class="text-[10px] font-bold uppercase tracking-wide text-muted-faint">Catatan</p>
                        <p class="mt-1 text-sm text-muted-strong">{{ $payment->notes }}</p>
                    </div>
                @endif
            </div>

            <div class="border-t border-line-soft px-5 py-4">
                <p class="text-xs font-bold text-ink">Rincian Tagihan</p>
                <div class="mt-3 overflow-x-auto">
                    <table class="min-w-full text-left text-xs">
                        <thead class="text-[10px] uppercase tracking-wide text-muted-faint">
                            <tr>
                                <th class="py-2 font-bold">Periode</th>
                                <th class="py-2 font-bold">Jatuh Tempo</th>
                                <th class="py-2 text-right font-bold">Nominal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-line-soft">
                            @foreach ($payment->bills as $bill)
                                <tr>
                                    <td class="py-2.5 font-semibold text-ink">{{ $bill->periodLabel() }}</td>
                                    <td class="py-2.5 text-muted-soft">{{ $bill->due_date?->format('d/m/Y') }}</td>
                                    <td class="py-2.5 text-right font-bold text-ink">{{ rupiah($bill->pivot->amount) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
