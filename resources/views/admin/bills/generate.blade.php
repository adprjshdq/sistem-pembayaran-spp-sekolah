@extends('layouts.app')

@section('title', 'Generate Tagihan Massal')

@section('content')
    <div class="grid gap-6 xl:grid-cols-3">
        <div class="xl:col-span-2">
            <div class="mb-5">
                <a href="{{ route('admin.bills.index') }}" class="text-xs font-semibold text-brand hover:text-brand-dark">&larr; Kembali ke tagihan</a>
                <h2 class="mt-2 text-lg font-bold text-ink">Generate Tagihan Massal</h2>
                <p class="mt-0.5 text-xs text-muted-soft">Buat tagihan SPP untuk seluruh siswa aktif berdasarkan tarif tingkat.</p>
            </div>

            <form method="POST" action="{{ route('admin.bills.generate.store') }}" class="rounded-2xl border border-line bg-white p-5 shadow-sm sm:p-6">
                @csrf

                <div class="grid gap-4 sm:grid-cols-2">
                    <div class="sm:col-span-2">
                        <label for="academic_year_id" class="mb-1.5 block text-xs font-bold text-muted-strong">Tahun Ajaran</label>
                        <select id="academic_year_id" name="academic_year_id" required
                                class="w-full rounded-xl border border-line-strong bg-white px-4 py-2.5 text-sm outline-none focus:border-brand focus:ring-2 focus:ring-brand/15">
                            <option value="">Pilih tahun ajaran</option>
                            @foreach ($academicYears as $year)
                                <option value="{{ $year->id }}" @selected((int) old('academic_year_id', $year->is_active ? $year->id : null) === $year->id)>{{ $year->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="sm:col-span-2">
                        <label for="class_id" class="mb-1.5 block text-xs font-bold text-muted-strong">Kelas (opsional)</label>
                        <select id="class_id" name="class_id"
                                class="w-full rounded-xl border border-line-strong bg-white px-4 py-2.5 text-sm outline-none focus:border-brand focus:ring-2 focus:ring-brand/15">
                            <option value="">Semua kelas pada tahun ajaran</option>
                            @foreach ($classes as $class)
                                <option value="{{ $class->id }}" @selected((int) old('class_id') === $class->id)>{{ $class->name }} &middot; {{ $class->academicYear?->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="month_from" class="mb-1.5 block text-xs font-bold text-muted-strong">Dari Bulan</label>
                        <select id="month_from" name="month_from" required
                                class="w-full rounded-xl border border-line-strong bg-white px-4 py-2.5 text-sm outline-none focus:border-brand focus:ring-2 focus:ring-brand/15">
                            @foreach ($months as $value => $label)
                                <option value="{{ $value }}" @selected((int) old('month_from', now()->month) === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="month_to" class="mb-1.5 block text-xs font-bold text-muted-strong">Sampai Bulan</label>
                        <select id="month_to" name="month_to" required
                                class="w-full rounded-xl border border-line-strong bg-white px-4 py-2.5 text-sm outline-none focus:border-brand focus:ring-2 focus:ring-brand/15">
                            @foreach ($months as $value => $label)
                                <option value="{{ $value }}" @selected((int) old('month_to', now()->month) === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="period_year" class="mb-1.5 block text-xs font-bold text-muted-strong">Tahun Tagihan</label>
                        <input id="period_year" name="period_year" type="number" min="2000" max="2100" value="{{ old('period_year', now()->year) }}" required
                               class="w-full rounded-xl border border-line-strong bg-white px-4 py-2.5 text-sm outline-none focus:border-brand focus:ring-2 focus:ring-brand/15">
                    </div>

                    <div>
                        <label for="due_date" class="mb-1.5 block text-xs font-bold text-muted-strong">Jatuh Tempo</label>
                        <input id="due_date" name="due_date" type="date" value="{{ old('due_date', now()->addMonthNoOverflow()->startOfMonth()->addDays(9)->format('Y-m-d')) }}" required
                               class="w-full rounded-xl border border-line-strong bg-white px-4 py-2.5 text-sm outline-none focus:border-brand focus:ring-2 focus:ring-brand/15">
                    </div>
                </div>

                <div class="mt-5 rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 text-[11px] text-amber-800">
                    Tagihan yang sudah ada untuk siswa dan periode yang sama akan dilewati, sehingga proses ini aman dijalankan berulang.
                </div>

                <div class="mt-6 flex items-center justify-end gap-2">
                    <a href="{{ route('admin.bills.index') }}" class="rounded-xl border border-line px-4 py-2.5 text-xs font-bold text-muted transition hover:bg-cream-soft">Batal</a>
                    <button type="submit" class="rounded-xl bg-brand px-4 py-2.5 text-xs font-bold text-white shadow-sm transition hover:bg-brand-dark">Generate Tagihan</button>
                </div>
            </form>
        </div>

        <div class="space-y-4">
            <div class="rounded-2xl border border-line bg-white p-5 shadow-sm">
                <h3 class="text-sm font-bold text-ink">Tarif Tersedia</h3>
                <p class="mt-0.5 text-xs text-muted-soft">Nominal per tingkat yang digunakan saat generate.</p>

                <div class="mt-4 space-y-2">
                    @forelse ($fees->groupBy('academic_year_id') as $yearFees)
                        <div class="rounded-xl border border-line-soft bg-cream-soft p-3">
                            <p class="text-xs font-bold text-ink">{{ $yearFees->first()->academicYear?->name }}</p>
                            <div class="mt-1.5 space-y-1">
                                @foreach ($yearFees->sortBy('level') as $fee)
                                    <div class="flex items-center justify-between text-[11px]">
                                        <span class="text-muted-soft">Kelas {{ $fee->level }}</span>
                                        <span class="font-bold text-ink">{{ rupiah($fee->amount) }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @empty
                        <p class="text-xs text-rose-600">Belum ada tarif. Tambahkan tarif terlebih dahulu.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection
