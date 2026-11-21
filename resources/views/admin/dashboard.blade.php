@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
@php
    // Perhitungan metrik tanpa mengubah data bawaan controller
    $collectionRate = $billedThisMonth > 0 ? min(100, round(($collectedThisMonth / $billedThisMonth) * 100, 1)) : 0;
    $remainingBilled = max(0, $billedThisMonth - $collectedThisMonth);
    $totalFinances = $collectedThisMonth + $remainingBilled + $outstanding;

    if ($totalFinances > 0) {
        $pctCollected = round(($collectedThisMonth / $totalFinances) * 100, 1);
        $pctRemaining = round(($remainingBilled / $totalFinances) * 100, 1);
        $pctOutstanding = max(0, 100 - $pctCollected - $pctRemaining);
    } else {
        $pctCollected = 100;
        $pctRemaining = 0;
        $pctOutstanding = 0;
    }

    // Urutkan kelas berdasarkan persentase kepatuhan pembayaran tertinggi
    $sortedClasses = collect($classes)->sortByDesc('percentage')->values();
@endphp

<div class="space-y-6">
    {{-- Header Banner / Overview Bar --}}
    <div class="flex flex-col gap-4 rounded-3xl bg-white p-5 shadow-sm border border-line lg:flex-row lg:items-center lg:justify-between">
        <div>
            <div class="flex items-center gap-2">
                <span class="inline-flex items-center rounded-lg bg-brand-soft px-2.5 py-0.5 text-[11px] font-extrabold uppercase tracking-wider text-brand">
                    Overview
                </span>
                <span class="text-xs font-semibold text-muted-soft">Performance & Finansial at a Glance</span>
            </div>
            <h1 class="mt-1 text-2xl font-black tracking-tight text-ink sm:text-3xl">Pusat Pembayaran SPP</h1>
            <p class="mt-0.5 text-xs text-muted-soft">
                Pantau real-time kepatuhan pembayaran, statistik tagihan, dan histori penerimaan siswa.
            </p>
        </div>

        {{-- Filter chips & quick actions --}}
        <div class="flex flex-wrap items-center gap-2">
            <div class="flex items-center gap-1.5 rounded-xl border border-line bg-cream-soft px-3 py-2 text-xs font-semibold text-ink shadow-xs">
                <svg class="h-4 w-4 text-brand" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
                </svg>
                <span>T.A: {{ $activeYear?->name ?? 'Belum Ditentukan' }}</span>
            </div>

            <div class="flex items-center gap-1.5 rounded-xl border border-line bg-cream-soft px-3 py-2 text-xs font-semibold text-ink shadow-xs">
                <svg class="h-4 w-4 text-brand" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
                <span>{{ $today->locale('id')->translatedFormat('F Y') }}</span>
            </div>

            <a href="{{ route('admin.payments.create') }}" class="inline-flex items-center gap-1.5 rounded-xl bg-brand px-4 py-2 text-xs font-bold text-white shadow-sm transition hover:bg-brand-dark">
                <svg class="h-4 w-4 text-gold" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                <span>Catat Pembayaran</span>
            </a>

            <a href="{{ route('admin.bills.generate') }}" class="inline-flex items-center gap-1.5 rounded-xl border border-brand/20 bg-brand-soft px-3.5 py-2 text-xs font-bold text-brand transition hover:bg-brand hover:text-white">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                </svg>
                <span>Generate</span>
            </a>

            <button type="button" onclick="window.print()" class="no-print inline-flex items-center justify-center rounded-xl border border-line bg-white p-2 text-muted transition hover:bg-cream-soft hover:text-ink shadow-xs" title="Cetak Ringkasan">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24-1.01.5-2.029 1.53-2.029h7.5c1.03 0 1.77 1.019 1.53 2.029l-.75 3.171a2.25 2.25 0 0 1-2.19 1.749H9.66a2.25 2.25 0 0 1-2.19-1.749l-.75-3.171Z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18.75h12m-12-6V6.75A2.25 2.25 0 0 1 8.25 4.5h7.5A2.25 2.25 0 0 1 18 6.75v6" />
                </svg>
            </button>
        </div>
    </div>

    {{-- Top 5 KPI Stat Cards (Persis seperti layout 5 kartu atas pada referensi gambar) --}}
    <section class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-5">
        {{-- Card 1: Total Students --}}
        <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-brand to-brand-dark p-5 text-white shadow-md">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-[10px] font-bold uppercase tracking-wider text-cream/70">Total Siswa</p>
                    <p class="mt-2 text-2xl font-black tracking-tight">{{ number_format($totalStudents) }}</p>
                </div>
                <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-white/15 backdrop-blur-xs text-white shadow-xs">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
                    </svg>
                </div>
            </div>
            <div class="mt-3 flex items-center gap-1.5 text-[11px] text-cream/80">
                <span class="inline-flex items-center text-emerald-300 font-bold">● Aktif</span>
                <span>Terdaftar di semua kelas</span>
            </div>
        </div>

        {{-- Card 2: Tagihan Bulan Ini --}}
        <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-emerald-600 to-teal-700 p-5 text-white shadow-md">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-[10px] font-bold uppercase tracking-wider text-emerald-100/70">Tagihan Bulan Ini</p>
                    <p class="mt-2 text-2xl font-black tracking-tight">{{ rupiah($billedThisMonth) }}</p>
                </div>
                <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-white/15 backdrop-blur-xs text-white shadow-xs">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                    </svg>
                </div>
            </div>
            <div class="mt-3 flex items-center gap-1 text-[11px] text-emerald-100/90 font-medium">
                <span>{{ $today->locale('id')->translatedFormat('F Y') }}</span>
            </div>
        </div>

        {{-- Card 3: Diterima Bulan Ini --}}
        <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-teal-700 to-brand-deep p-5 text-white shadow-md">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-[10px] font-bold uppercase tracking-wider text-teal-100/70">Diterima Bulan Ini</p>
                    <p class="mt-2 text-2xl font-black tracking-tight">{{ rupiah($collectedThisMonth) }}</p>
                </div>
                <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-white/15 backdrop-blur-xs text-white shadow-xs">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 0 1-1.043 3.296 3.745 3.745 0 0 1-3.296 1.043A3.745 3.745 0 0 1 12 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 0 1-3.296-1.043 3.745 3.745 0 0 1-1.043-3.296A3.745 3.745 0 0 1 3 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 0 1 1.043-3.296 3.746 3.746 0 0 1 3.296-1.043A3.746 3.746 0 0 1 12 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 0 1 3.296 1.043 3.746 3.746 0 0 1 1.043 3.296A3.745 3.745 0 0 1 21 12Z" />
                    </svg>
                </div>
            </div>
            <div class="mt-3 flex items-center gap-1.5 text-[11px] text-teal-100 font-semibold">
                <span class="inline-flex rounded bg-emerald-400/20 px-1 py-0.2 text-[10px] text-emerald-200">✓ Berhasil</span>
                <span>{{ $collectionRate }}% tertagih</span>
            </div>
        </div>

        {{-- Card 4: Total Tunggakan --}}
        <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-amber-600 to-amber-700 p-5 text-white shadow-md">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-[10px] font-bold uppercase tracking-wider text-amber-100/70">Total Tunggakan</p>
                    <p class="mt-2 text-2xl font-black tracking-tight">{{ rupiah($outstanding) }}</p>
                </div>
                <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-white/15 backdrop-blur-xs text-white shadow-xs">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
                    </svg>
                </div>
            </div>
            <div class="mt-3 flex items-center gap-1.5 text-[11px] text-amber-100/90 font-medium">
                <span>Seluruh tagihan belum lunas</span>
            </div>
        </div>

        {{-- Card 5: Efektivitas / Kelunasan --}}
        <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-brand-light to-brand-dark p-5 text-white shadow-md">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-[10px] font-bold uppercase tracking-wider text-cream/70">Tingkat Kelunasan</p>
                    <p class="mt-2 text-2xl font-black tracking-tight">{{ $collectionRate }}%</p>
                </div>
                <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-white/15 backdrop-blur-xs text-white shadow-xs">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3v11.25A2.25 2.25 0 0 0 6 16.5h2.25M3.75 3h-1.5m1.5 0h16.5m0 0h1.5m-1.5 0v11.25A2.25 2.25 0 0 1 18 16.5h-2.25m-7.5 0h7.5m-7.5 0-1 3m8.5-3 1 3m0 0 .5 1.5m-.5-1.5h-9.5m0 0-.5 1.5M9 11.25v1.5M12 9v3.75m3-6v6" />
                    </svg>
                </div>
            </div>
            <div class="mt-3 flex items-center gap-1.5 text-[11px] text-emerald-200 font-semibold">
                <span>Rasio pembayaran aktif</span>
            </div>
        </div>
    </section>

    {{-- Middle Section: 3-column / Grid visual mirip referensi gambar --}}
    <section class="grid grid-cols-1 gap-6 xl:grid-cols-12">
        {{-- Card 1: RESULT SUMMARY / Distribusi Status Pembayaran (4 cols) --}}
        <div class="rounded-3xl border border-line bg-white p-6 shadow-sm xl:col-span-4 flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-xs font-bold uppercase tracking-wider text-brand">Result Summary</h2>
                        <h3 class="mt-0.5 text-base font-extrabold text-ink">Ringkasan SPP</h3>
                    </div>
                    <span class="rounded-full bg-cream-soft border border-line px-2.5 py-1 text-[10px] font-semibold text-muted-soft">
                        Periode Berjalan
                    </span>
                </div>
                <p class="mt-1 text-xs text-muted-soft">Proporsi penerimaan, sisa tagihan, dan tunggakan.</p>

                {{-- Donut Chart Visual --}}
                <div class="mt-6 flex flex-col items-center sm:flex-row sm:items-center sm:justify-around gap-6">
                    <div class="relative flex h-36 w-36 items-center justify-center rounded-full shadow-inner"
                         style="background: conic-gradient(#10b981 0% {{ $pctCollected }}%, #f59e0b {{ $pctCollected }}% {{ $pctCollected + $pctRemaining }}%, #e11d48 {{ $pctCollected + $pctRemaining }}% 100%);">
                        <div class="flex h-24 w-24 flex-col items-center justify-center rounded-full bg-white shadow-xs">
                            <span class="text-xl font-black text-ink">{{ $collectionRate }}%</span>
                            <span class="text-[9px] font-extrabold uppercase tracking-widest text-brand">Terkumpul</span>
                        </div>
                    </div>

                    {{-- Legend --}}
                    <div class="space-y-3 text-xs w-full sm:w-auto">
                        <div class="flex items-center justify-between sm:justify-start gap-3">
                            <span class="flex items-center gap-2">
                                <span class="h-3 w-3 rounded-full bg-emerald-500 shrink-0"></span>
                                <span class="font-medium text-muted">Diterima</span>
                            </span>
                            <span class="font-bold text-ink">{{ rupiah($collectedThisMonth) }}</span>
                        </div>
                        <div class="flex items-center justify-between sm:justify-start gap-3">
                            <span class="flex items-center gap-2">
                                <span class="h-3 w-3 rounded-full bg-amber-500 shrink-0"></span>
                                <span class="font-medium text-muted">Tagihan Aktif</span>
                            </span>
                            <span class="font-bold text-ink">{{ rupiah($remainingBilled) }}</span>
                        </div>
                        <div class="flex items-center justify-between sm:justify-start gap-3">
                            <span class="flex items-center gap-2">
                                <span class="h-3 w-3 rounded-full bg-rose-600 shrink-0"></span>
                                <span class="font-medium text-muted">Tunggakan</span>
                            </span>
                            <span class="font-bold text-ink">{{ rupiah($outstanding) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-6 rounded-2xl bg-cream-soft p-3.5 border border-line-soft">
                <div class="flex items-center justify-between text-xs">
                    <span class="font-semibold text-muted">Total Portofolio Siswa:</span>
                    <span class="font-black text-ink">{{ number_format($totalStudents) }} Siswa Aktif</span>
                </div>
            </div>
        </div>

        {{-- Card 2: AVERAGE SCORE BY SUBJECT / Penerimaan SPP 6 Bulan Terakhir (5 cols) --}}
        <div class="rounded-3xl border border-line bg-white p-6 shadow-sm xl:col-span-5 flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-xs font-bold uppercase tracking-wider text-brand">Histori Transaksi</h2>
                        <h3 class="mt-0.5 text-base font-extrabold text-ink">Penerimaan SPP 6 Bulan</h3>
                    </div>
                    <span class="rounded-full bg-brand-soft px-2.5 py-1 text-[10px] font-bold text-brand">
                        Status Berhasil
                    </span>
                </div>
                <p class="mt-1 text-xs text-muted-soft">Tren pembayaran SPP dari bulan ke bulan.</p>

                {{-- Bar Chart --}}
                <div class="mt-6 flex h-48 items-end gap-3 pt-6 border-b border-line-soft pb-2">
                    @foreach ($chart as $item)
                        @php
                            $height = max(6, (int) round($item['amount'] / $maxChart * 100));
                        @endphp
                        <div class="group relative flex flex-1 flex-col items-center justify-end h-full">
                            {{-- Value Badge / Tooltip --}}
                            <div class="mb-1.5 opacity-80 group-hover:opacity-100 transition-opacity">
                                <span class="inline-block rounded-md bg-cream-soft px-1.5 py-0.5 text-[9px] font-bold text-brand border border-line-soft shadow-2xs whitespace-nowrap">
                                    {{ number_format($item['amount'] / 1000000, 1, ',', '.') }} jt
                                </span>
                            </div>

                            {{-- The Bar --}}
                            <div class="w-full max-w-[42px] rounded-t-xl bg-gradient-to-t from-brand to-emerald-500 shadow-xs transition-all duration-300 group-hover:brightness-110"
                                 style="height: {{ $height }}%">
                            </div>

                            {{-- Month Label --}}
                            <span class="mt-2 text-[10px] font-semibold text-muted-soft group-hover:text-ink transition-colors">
                                {{ $item['label'] }}
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="mt-4 flex items-center justify-between text-xs text-muted-soft">
                <span>Skala Relatif: Rp 0 s/d {{ rupiah($maxChart) }}</span>
                <span class="font-bold text-brand">Rata-rata: {{ rupiah(round($chart->avg('amount'))) }}</span>
            </div>
        </div>

        {{-- Card 3: TOP PERFORMING STUDENTS / Peringkat Kepatuhan Kelas (3 cols) --}}
        <div class="rounded-3xl border border-line bg-white p-6 shadow-sm xl:col-span-3 flex flex-col justify-between">
            <div>
                <div class="flex items-center gap-2">
                    <div class="flex h-7 w-7 items-center justify-center rounded-lg bg-gold/20 text-gold-dark">
                        <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2l2.4 7.2h7.6l-6 4.8 2.4 7.2-6-4.8-6 4.8 2.4-7.2-6-4.8h7.6z"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xs font-bold uppercase tracking-wider text-brand">Top Classes</h2>
                        <h3 class="text-sm font-extrabold text-ink">Kepatuhan Tertinggi</h3>
                    </div>
                </div>
                <p class="mt-1 text-xs text-muted-soft">Kelas dengan rasio kelunasan SPP terbaik.</p>

                <div class="mt-4 space-y-3">
                    @forelse ($sortedClasses->take(5) as $index => $item)
                        @php
                            $badgeColor = match ($index) {
                                0 => 'bg-amber-400 text-amber-950 shadow-xs font-black',
                                1 => 'bg-slate-300 text-slate-800 font-bold',
                                2 => 'bg-amber-700/30 text-amber-900 font-bold',
                                default => 'bg-cream text-muted-strong font-semibold',
                            };
                        @endphp
                        <div class="flex items-center justify-between rounded-xl p-2 transition hover:bg-cream-soft">
                            <div class="flex items-center gap-2.5 min-w-0">
                                <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full text-[11px] {{ $badgeColor }}">
                                    {{ $index + 1 }}
                                </span>
                                <div class="min-w-0">
                                    <p class="truncate text-xs font-bold text-ink">{{ $item['class']->name }}</p>
                                    <p class="text-[10px] text-muted-soft">{{ $item['class']->students_count ?? 0 }} siswa</p>
                                </div>
                            </div>
                            <span class="rounded-lg bg-emerald-50 px-2 py-0.5 text-xs font-extrabold text-emerald-700 border border-emerald-200">
                                {{ $item['percentage'] }}%
                            </span>
                        </div>
                    @empty
                        <p class="py-4 text-center text-xs text-muted-soft">Belum ada data kelas aktif.</p>
                    @endforelse
                </div>
            </div>

            {{-- Motivation / School Advice Banner --}}
            <div class="mt-5 rounded-2xl bg-gradient-to-br from-brand-soft to-emerald-50 p-3.5 border border-brand/10">
                <div class="flex items-center gap-2 text-xs font-bold text-brand">
                    <span>✨</span>
                    <span>Pengelolaan Akuntabel</span>
                </div>
                <p class="mt-1 text-[11px] text-muted-strong leading-relaxed">
                    Arus kas SPP yang tertib menunjang mutu fasilitas dan proses belajar mengajar sekolah.
                </p>
            </div>
        </div>
    </section>

    {{-- Bottom Section: Class Performance Overview & Transaksi Terbaru --}}
    <section class="grid grid-cols-1 gap-6 xl:grid-cols-12">
        {{-- CLASS PERFORMANCE OVERVIEW Table (7 cols) --}}
        <div class="rounded-3xl border border-line bg-white shadow-sm overflow-hidden xl:col-span-7">
            <div class="flex items-center justify-between border-b border-line px-6 py-4 bg-cream-soft">
                <div>
                    <h2 class="text-xs font-bold uppercase tracking-wider text-brand">Class Performance Overview</h2>
                    <h3 class="text-sm font-extrabold text-ink">Monitoring Kelunasan per Kelas</h3>
                </div>
                <a href="{{ route('admin.classes.index') }}" class="text-xs font-bold text-brand hover:underline">
                    Kelola Kelas &rarr;
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full text-left text-xs">
                    <thead>
                        <tr class="bg-[#0b2c27] text-white text-[10px] uppercase tracking-wider">
                            <th class="px-5 py-3.5 font-bold">Kelas</th>
                            <th class="px-4 py-3.5 font-bold text-center">Siswa</th>
                            <th class="px-4 py-3.5 font-bold">Kelunasan</th>
                            <th class="px-4 py-3.5 font-bold">Sisa Tunggakan</th>
                            <th class="px-4 py-3.5 font-bold text-center">Trend</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-line-soft">
                        @forelse ($classes as $item)
                            <tr class="hover:bg-cream-soft/60 transition-colors">
                                <td class="px-5 py-3.5 font-bold text-ink">
                                    {{ $item['class']->name }}
                                </td>
                                <td class="px-4 py-3.5 text-center font-medium text-muted">
                                    {{ $item['class']->students_count ?? '-' }}
                                </td>
                                <td class="px-4 py-3.5">
                                    <div class="flex items-center gap-2">
                                        <div class="h-2 w-20 overflow-hidden rounded-full bg-line-soft">
                                            <div class="h-full rounded-full {{ $item['percentage'] >= 80 ? 'bg-emerald-500' : ($item['percentage'] >= 50 ? 'bg-amber-500' : 'bg-rose-500') }}"
                                                 style="width: {{ $item['percentage'] }}%"></div>
                                        </div>
                                        <span class="text-[11px] font-extrabold text-ink">{{ $item['percentage'] }}%</span>
                                    </div>
                                </td>
                                <td class="px-4 py-3.5 font-semibold {{ $item['outstanding'] > 0 ? 'text-rose-600' : 'text-emerald-600' }}">
                                    {{ rupiah($item['outstanding']) }}
                                </td>
                                <td class="px-4 py-3.5 text-center">
                                    @if ($item['percentage'] >= 75)
                                        <svg class="h-4 w-14 inline-block text-emerald-500" viewBox="0 0 60 20" fill="none" stroke="currentColor" stroke-width="2.5">
                                            <path d="M2 16 L16 11 L28 13 L44 6 L58 3" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                    @elseif ($item['percentage'] >= 50)
                                        <svg class="h-4 w-14 inline-block text-amber-500" viewBox="0 0 60 20" fill="none" stroke="currentColor" stroke-width="2.5">
                                            <path d="M2 13 L16 8 L28 14 L44 9 L58 7" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                    @else
                                        <svg class="h-4 w-14 inline-block text-rose-500" viewBox="0 0 60 20" fill="none" stroke="currentColor" stroke-width="2.5">
                                            <path d="M2 7 L16 14 L28 9 L44 16 L58 12" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-5 py-6 text-center text-muted-soft">Belum ada data kelas aktif.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- TRANSAKSI TERBARU (5 cols) --}}
        <div class="rounded-3xl border border-line bg-white shadow-sm overflow-hidden xl:col-span-5 flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between border-b border-line px-5 py-4 bg-cream-soft">
                    <div>
                        <h2 class="text-xs font-bold uppercase tracking-wider text-brand">Aktivitas Terkini</h2>
                        <h3 class="text-sm font-extrabold text-ink">Transaksi Terbaru</h3>
                    </div>
                    <a href="{{ route('admin.payments.index') }}" class="text-xs font-bold text-brand hover:underline">
                        Lihat Semua &rarr;
                    </a>
                </div>

                <div class="divide-y divide-line-soft">
                    @forelse ($recentPayments as $payment)
                        <div class="flex items-center justify-between px-5 py-3 hover:bg-cream-soft/60 transition-colors">
                            <div class="flex items-center gap-3 min-w-0">
                                <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-brand-soft text-brand font-bold text-xs">
                                    {{ strtoupper(substr($payment->student?->name ?? 'S', 0, 2)) }}
                                </div>
                                <div class="min-w-0">
                                    <a href="{{ route('admin.payments.show', $payment) }}" class="truncate text-xs font-bold text-ink hover:text-brand hover:underline block">
                                        {{ $payment->student?->name ?? 'Siswa' }}
                                    </a>
                                    <p class="text-[10px] text-muted-soft">
                                        {{ $payment->student?->schoolClass?->name ?? '-' }} &middot;
                                        <span class="font-mono text-muted">{{ $payment->invoice_number }}</span>
                                    </p>
                                </div>
                            </div>

                            <div class="text-right shrink-0">
                                <p class="text-xs font-black text-ink">{{ rupiah($payment->amount) }}</p>
                                <div class="mt-0.5 flex items-center justify-end gap-1">
                                    <span class="inline-flex rounded-full border px-2 py-0.2 text-[9px] font-bold {{ $payment->status->badgeClasses() }}">
                                        {{ $payment->status->label() }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="py-8 text-center text-xs text-muted-soft">
                            Belum ada transaksi pembayaran.
                        </div>
                    @endforelse
                </div>
            </div>

            <div class="border-t border-line-soft bg-cream-soft/40 px-5 py-3 text-right">
                <span class="text-[10px] text-muted-soft">
                    Menampilkan {{ $recentPayments->count() }} transaksi terakhir yang tercatat
                </span>
            </div>
        </div>
    </section>
</div>
@endsection
