@extends('layouts.app')

@section('title', 'Detail Pembayaran')

@section('content')
    <div class="mx-auto max-w-3xl space-y-5">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <a href="{{ route('portal.payments.index') }}" class="text-xs font-semibold text-brand hover:text-brand-dark">&larr; Kembali ke riwayat</a>
                <h2 class="mt-2 text-lg font-bold text-ink">Detail Pembayaran</h2>
                <p class="mt-0.5 font-mono text-xs text-muted-soft">{{ $payment->invoice_number }}</p>
            </div>
            <div class="flex gap-2">
                @if ($payment->status->value === 'success')
                    <a href="{{ route('portal.payments.receipt', $payment) }}" target="_blank" class="rounded-xl bg-brand px-4 py-2.5 text-xs font-bold text-white transition hover:bg-brand-dark">Cetak Kwitansi</a>
                @elseif ($payment->status->value === 'pending')
                    <a href="{{ route('portal.payments.finish', $payment) }}" class="rounded-xl bg-brand px-4 py-2.5 text-xs font-bold text-white transition hover:bg-brand-dark">Cek Status Pembayaran</a>
                @endif
            </div>
        </div>

        <div class="overflow-hidden rounded-2xl border border-line bg-white shadow-sm">
            <div class="flex flex-col gap-4 border-b border-line-soft bg-cream-soft px-5 py-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <p class="text-[10px] font-bold uppercase tracking-wide text-muted-faint">Status</p>
                    <span class="mt-1 inline-flex rounded-full border px-2.5 py-0.5 text-[11px] font-bold {{ $payment->status->badgeClasses() }}">{{ $payment->status->label() }}</span>
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
                </div>
                <div>
                    <p class="text-[10px] font-bold uppercase tracking-wide text-muted-faint">Waktu</p>
                    <p class="mt-1 text-sm text-muted-strong">{{ ($payment->paid_at ?? $payment->created_at)->locale('id')->translatedFormat('d F Y H:i') }}</p>
                </div>
            </div>

            <div class="border-t border-line-soft px-5 py-4">
                <p class="text-xs font-bold text-ink">Rincian Tagihan</p>
                <div class="mt-3 space-y-2">
                    @foreach ($payment->bills as $bill)
                        <div class="flex items-center justify-between rounded-xl border border-line-soft px-4 py-2.5 text-xs">
                            <div>
                                <p class="font-semibold text-ink">{{ $bill->periodLabel() }}</p>
                                <p class="text-[10px] text-muted-faint">Jatuh tempo {{ $bill->due_date?->format('d/m/Y') }}</p>
                            </div>
                            <p class="font-bold text-ink">{{ rupiah($bill->pivot->amount) }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        @if ($payment->status->value === 'pending')
            <div class="rounded-2xl border border-amber-200 bg-amber-50 px-5 py-4 text-xs text-amber-800">
                Pembayaran sedang menunggu konfirmasi. Jika Anda sudah menyelesaikan pembayaran, klik <span class="font-bold">Cek Status Pembayaran</span>.
            </div>
        @endif
    </div>
@endsection
