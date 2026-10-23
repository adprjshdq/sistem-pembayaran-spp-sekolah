@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="space-y-6">
        <section class="overflow-hidden rounded-3xl bg-brand p-6 text-white shadow-sm sm:p-8">
            <div class="flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-gold">Ringkasan Keuangan</p>
                    <h2 class="mt-1 text-2xl font-extrabold tracking-tight sm:text-3xl">Pusat Pembayaran SPP</h2>
                    <p class="mt-2 max-w-xl text-sm text-cream">
                        Tahun ajaran aktif: <span class="font-bold text-white">{{ $activeYear?->name ?? 'Belum ditentukan' }}</span>.
                        Pantau penerimaan dan tunggakan SPP secara real-time.
                    </p>
                </div>
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('admin.payments.create') }}" class="rounded-xl bg-white px-4 py-2.5 text-xs font-bold text-brand-dark shadow-sm transition hover:bg-brand-soft">
                        Catat Pembayaran
                    </a>
                    <a href="{{ route('admin.bills.generate') }}" class="rounded-xl border border-white/25 bg-white/10 px-4 py-2.5 text-xs font-bold text-white transition hover:bg-white/20">
                        Generate Tagihan
                    </a>
                </div>
            </div>
        </section>

        <section class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            <div class="rounded-2xl border border-line bg-white p-5 shadow-sm">
                <p class="text-[11px] font-bold uppercase tracking-wide text-muted-faint">Siswa Aktif</p>
                <p class="mt-2 text-3xl font-extrabold text-ink">{{ number_format($totalStudents) }}</p>
                <p class="mt-1 text-xs text-muted-soft">Terdaftar di semua kelas</p>
            </div>
            <div class="rounded-2xl border border-line bg-white p-5 shadow-sm">
                <p class="text-[11px] font-bold uppercase tracking-wide text-muted-faint">Tagihan Bulan Ini</p>
                <p class="mt-2 text-2xl font-extrabold text-ink">{{ rupiah($billedThisMonth) }}</p>
                <p class="mt-1 text-xs text-muted-soft">{{ $today->locale('id')->translatedFormat('F Y') }}</p>
            </div>
            <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-5 shadow-sm">
                <p class="text-[11px] font-bold uppercase tracking-wide text-emerald-700">Diterima Bulan Ini</p>
                <p class="mt-2 text-2xl font-extrabold text-emerald-800">{{ rupiah($collectedThisMonth) }}</p>
                <p class="mt-1 text-xs text-emerald-700">Pembayaran berhasil</p>
            </div>
            <div class="rounded-2xl border border-rose-200 bg-rose-50 p-5 shadow-sm">
                <p class="text-[11px] font-bold uppercase tracking-wide text-rose-700">Total Tunggakan</p>
                <p class="mt-2 text-2xl font-extrabold text-rose-800">{{ rupiah($outstanding) }}</p>
                <p class="mt-1 text-xs text-rose-700">Seluruh tagihan belum lunas</p>
            </div>
        </section>

        <section class="grid gap-6 xl:grid-cols-5">
            <div class="rounded-2xl border border-line bg-white p-5 shadow-sm xl:col-span-3">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-bold text-ink">Penerimaan SPP 6 Bulan Terakhir</h3>
                        <p class="mt-0.5 text-xs text-muted-soft">Total pembayaran berstatus berhasil.</p>
                    </div>
                </div>

                <div class="mt-6 flex h-48 items-end gap-3">
                    @foreach ($chart as $item)
                        @php
                            $height = max(3, (int) round($item['amount'] / $maxChart * 100));
                        @endphp
                        <div class="flex flex-1 flex-col items-center justify-end gap-2">
                            <span class="text-[10px] font-semibold text-muted-soft">{{ number_format($item['amount'] / 1000000, 1, ',', '.') }} jt</span>
                            <div class="w-full rounded-t-lg bg-brand" style="height: {{ $height }}%"></div>
                            <span class="text-[10px] font-medium text-muted-soft">{{ $item['label'] }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="rounded-2xl border border-line bg-white p-5 shadow-sm xl:col-span-2">
                <h3 class="text-sm font-bold text-ink">Kelunasan per Kelas</h3>
                <p class="mt-0.5 text-xs text-muted-soft">Persentase tagihan yang sudah dibayar.</p>

                <div class="mt-5 space-y-4">
                    @forelse ($classes as $item)
                        <div>
                            <div class="flex items-center justify-between text-xs">
                                <span class="font-semibold text-muted-strong">{{ $item['class']->name }}</span>
                                <span class="font-bold text-ink">{{ $item['percentage'] }}%</span>
                            </div>
                            <div class="mt-1.5 h-2 overflow-hidden rounded-full bg-cream">
                                <div class="h-full rounded-full bg-gold" style="width: {{ $item['percentage'] }}%"></div>
                            </div>
                            <p class="mt-1 text-[10px] text-muted-faint">Sisa tunggakan {{ rupiah($item['outstanding']) }}</p>
                        </div>
                    @empty
                        <p class="text-xs text-muted-soft">Belum ada kelas pada tahun ajaran aktif.</p>
                    @endforelse
                </div>
            </div>
        </section>

        <section class="rounded-2xl border border-line bg-white shadow-sm">
            <div class="flex items-center justify-between border-b border-line-soft px-5 py-4">
                <div>
                    <h3 class="text-sm font-bold text-ink">Transaksi Terbaru</h3>
                    <p class="mt-0.5 text-xs text-muted-soft">6 pembayaran terakhir yang tercatat.</p>
                </div>
                <a href="{{ route('admin.payments.index') }}" class="text-xs font-bold text-brand hover:text-brand-dark">Lihat semua</a>
            </div>
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
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-line-soft">
                        @forelse ($recentPayments as $payment)
                            <tr class="hover:bg-cream-soft">
                                <td class="px-5 py-3 font-mono font-semibold text-brand">
                                    <a href="{{ route('admin.payments.show', $payment) }}" class="hover:underline">{{ $payment->invoice_number }}</a>
                                </td>
                                <td class="px-5 py-3">
                                    <p class="font-semibold text-ink">{{ $payment->student?->name }}</p>
                                    <p class="text-[10px] text-muted-faint">{{ $payment->student?->schoolClass?->name }}</p>
                                </td>
                                <td class="px-5 py-3 text-muted">{{ $payment->channel ?: $payment->method }}</td>
                                <td class="px-5 py-3 font-bold text-ink">{{ rupiah($payment->amount) }}</td>
                                <td class="px-5 py-3">
                                    <span class="inline-flex rounded-full border px-2 py-0.5 text-[10px] font-bold {{ $payment->status->badgeClasses() }}">
                                        {{ $payment->status->label() }}
                                    </span>
                                </td>
                                <td class="px-5 py-3 text-muted-soft">{{ $payment->paid_at?->locale('id')->translatedFormat('d M Y H:i') ?? $payment->created_at->translatedFormat('d M Y H:i') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-5 py-8 text-center text-muted-soft">Belum ada transaksi pembayaran.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </div>
@endsection
