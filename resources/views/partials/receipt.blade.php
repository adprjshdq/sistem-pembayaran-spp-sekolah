<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Kwitansi {{ $payment->invoice_number }}</title>
    @vite(['resources/css/app.css'])
    <style>
        @media print {
            .no-print { display: none !important; }
            body { background: #fff !important; }
            .sheet { box-shadow: none !important; border: 0 !important; margin: 0 !important; }
        }
    </style>
</head>
<body class="min-h-screen bg-cream py-8 text-ink antialiased">
    <div class="no-print mx-auto mb-4 flex max-w-3xl items-center justify-between px-4">
        <a href="{{ $backUrl }}" class="text-xs font-semibold text-brand hover:text-brand-dark">&larr; Kembali</a>
        <button type="button" onclick="window.print()" class="rounded-xl bg-brand px-4 py-2.5 text-xs font-bold text-white transition hover:bg-brand-dark print:hidden">Cetak Kwitansi</button>
    </div>

    <div class="sheet mx-auto max-w-3xl rounded-2xl border border-line bg-white p-8 shadow-sm">
        <div class="flex flex-col gap-4 border-b-2 border-brand pb-5 sm:flex-row sm:items-start sm:justify-between">
            <div>
                <h1 class="text-lg font-extrabold uppercase tracking-tight text-ink">{{ school_setting('school_name', config('app.name')) }}</h1>
                <p class="mt-1 text-[11px] text-muted-soft">{{ school_setting('school_address', '-') }}</p>
                <p class="text-[11px] text-muted-soft">
                    {{ school_setting('school_phone', '-') }}
                    @if (school_setting('school_email'))
                        &middot; {{ school_setting('school_email') }}
                    @endif
                </p>
            </div>
            <div class="text-left sm:text-right">
                @php
                    $stampClasses = match ($payment->status->value) {
                        'success' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                        'failed' => 'bg-rose-50 text-rose-700 border-rose-200',
                        default => 'bg-amber-50 text-amber-700 border-amber-200',
                    };
                @endphp
                <span class="inline-flex rounded-full border px-3 py-1 text-[11px] font-bold {{ $stampClasses }}">
                    {{ $payment->status->value === 'success' ? 'LUNAS / TERVERIFIKASI' : strtoupper($payment->status->label()) }}
                </span>
                <p class="mt-2 text-[11px] text-muted-soft">No. Invoice</p>
                <p class="font-mono text-xs font-bold text-ink">{{ $payment->invoice_number }}</p>
            </div>
        </div>

        <h2 class="mt-6 text-sm font-extrabold uppercase tracking-wide text-ink">Bukti Pembayaran SPP Sekolah</h2>

        <table class="mt-4 w-full text-xs">
            <tbody class="divide-y divide-line-soft">
                <tr>
                    <td class="w-40 py-2.5 text-muted-soft">Nama Siswa</td>
                    <td class="py-2.5 font-bold text-ink">{{ $payment->student?->name }}</td>
                </tr>
                <tr>
                    <td class="py-2.5 text-muted-soft">NIS</td>
                    <td class="py-2.5 text-muted-strong">{{ $payment->student?->nis }}</td>
                </tr>
                <tr>
                    <td class="py-2.5 text-muted-soft">Kelas / Jurusan</td>
                    <td class="py-2.5 text-muted-strong">{{ $payment->student?->schoolClass?->name ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="py-2.5 text-muted-soft">Metode Pembayaran</td>
                    <td class="py-2.5 text-muted-strong">{{ $payment->channel ?: ucfirst($payment->method) }}</td>
                </tr>
                <tr>
                    <td class="py-2.5 text-muted-soft">Waktu Pembayaran</td>
                    <td class="py-2.5 text-muted-strong">{{ ($payment->paid_at ?? $payment->created_at)->locale('id')->translatedFormat('d F Y H:i') }}</td>
                </tr>
                <tr>
                    <td class="py-2.5 text-muted-soft">Dicatat Oleh</td>
                    <td class="py-2.5 text-muted-strong">{{ $payment->recordedBy?->name ?? 'Sistem / Portal Siswa' }}</td>
                </tr>
            </tbody>
        </table>

        <div class="mt-6 overflow-hidden rounded-xl border border-line">
            <table class="w-full text-xs">
                <thead class="bg-cream-soft text-left text-[10px] uppercase tracking-wide text-muted-soft">
                    <tr>
                        <th class="px-4 py-2.5 font-bold">Periode Tagihan</th>
                        <th class="px-4 py-2.5 font-bold">Tahun Ajaran</th>
                        <th class="px-4 py-2.5 text-right font-bold">Nominal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-line-soft">
                    @foreach ($payment->bills as $bill)
                        <tr>
                            <td class="px-4 py-2.5 font-semibold text-ink">{{ $bill->periodLabel() }}</td>
                            <td class="px-4 py-2.5 text-muted">{{ $bill->academicYear?->name }}</td>
                            <td class="px-4 py-2.5 text-right font-semibold text-ink">{{ rupiah($bill->pivot->amount) }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot class="bg-brand text-white">
                    <tr>
                        <td colspan="2" class="px-4 py-3 text-right text-[11px] font-bold uppercase tracking-wide">Total Dibayar</td>
                        <td class="px-4 py-3 text-right text-base font-extrabold">{{ rupiah($payment->amount) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>

        @if (school_setting('receipt_note'))
            <p class="mt-4 text-[11px] italic text-muted-soft">{{ school_setting('receipt_note') }}</p>
        @endif

        <div class="mt-10 flex justify-end">
            <div class="text-center text-xs">
                <p class="text-muted-soft">{{ now()->locale('id')->translatedFormat('d F Y') }}</p>
                <p class="mt-1 font-semibold text-muted-strong">Bendahara Sekolah</p>
                <div class="mt-14">
                    <p class="font-bold text-ink underline">{{ school_setting('treasurer_name', '-') }}</p>
                    <p class="text-[10px] text-muted-soft">NIP. {{ school_setting('treasurer_nip', '-') }}</p>
                </div>
            </div>
        </div>

        <p class="mt-8 border-t border-line-soft pt-3 text-center text-[10px] text-muted-faint">
            Kwitansi ini diterbitkan secara digital oleh sistem {{ config('app.name') }}.
        </p>
    </div>
</body>
</html>
