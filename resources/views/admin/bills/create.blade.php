@extends('layouts.app')

@section('title', 'Tambah Tagihan')

@section('content')
    <div class="mx-auto max-w-2xl">
        <div class="mb-5">
            <a href="{{ route('admin.bills.index') }}" class="text-xs font-semibold text-brand hover:text-brand-dark">&larr; Kembali ke tagihan</a>
            <h2 class="mt-2 text-lg font-bold text-ink">Tambah Tagihan</h2>
            <p class="mt-0.5 text-xs text-muted-soft">Buat tagihan untuk satu siswa dan satu periode.</p>
        </div>

        <form method="POST" action="{{ route('admin.bills.store') }}" class="rounded-2xl border border-line bg-white p-5 shadow-sm sm:p-6">
            @csrf

            <div class="grid gap-4 sm:grid-cols-2">
                <div class="sm:col-span-2">
                    <label for="student_id" class="mb-1.5 block text-xs font-bold text-muted-strong">Siswa</label>
                    <select id="student_id" name="student_id" required
                            class="w-full rounded-xl border border-line-strong bg-white px-4 py-2.5 text-sm outline-none focus:border-brand focus:ring-2 focus:ring-brand/15">
                        <option value="">Pilih siswa</option>
                        @foreach ($students as $student)
                            <option value="{{ $student->id }}" @selected((int) old('student_id') === $student->id)>{{ $student->nis }} &middot; {{ $student->name }} ({{ $student->schoolClass?->name }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="sm:col-span-2">
                    <label for="academic_year_id" class="mb-1.5 block text-xs font-bold text-muted-strong">Tahun Ajaran</label>
                    <select id="academic_year_id" name="academic_year_id" required
                            class="w-full rounded-xl border border-line-strong bg-white px-4 py-2.5 text-sm outline-none focus:border-brand focus:ring-2 focus:ring-brand/15">
                        <option value="">Pilih tahun ajaran</option>
                        @foreach ($academicYears as $year)
                            <option value="{{ $year->id }}" @selected((int) old('academic_year_id') === $year->id)>{{ $year->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="period_month" class="mb-1.5 block text-xs font-bold text-muted-strong">Bulan</label>
                    <select id="period_month" name="period_month" required
                            class="w-full rounded-xl border border-line-strong bg-white px-4 py-2.5 text-sm outline-none focus:border-brand focus:ring-2 focus:ring-brand/15">
                        @foreach ($months as $value => $label)
                            <option value="{{ $value }}" @selected((int) old('period_month', now()->month) === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="period_year" class="mb-1.5 block text-xs font-bold text-muted-strong">Tahun</label>
                    <input id="period_year" name="period_year" type="number" min="2000" max="2100" value="{{ old('period_year', now()->year) }}" required
                           class="w-full rounded-xl border border-line-strong bg-white px-4 py-2.5 text-sm outline-none focus:border-brand focus:ring-2 focus:ring-brand/15">
                </div>

                <div>
                    <label for="amount" class="mb-1.5 block text-xs font-bold text-muted-strong">Nominal (Rp)</label>
                    <input id="amount" name="amount" type="number" min="0" step="1000" value="{{ old('amount', 350000) }}" required
                           class="w-full rounded-xl border border-line-strong bg-white px-4 py-2.5 text-sm outline-none focus:border-brand focus:ring-2 focus:ring-brand/15">
                </div>

                <div>
                    <label for="due_date" class="mb-1.5 block text-xs font-bold text-muted-strong">Jatuh Tempo</label>
                    <input id="due_date" name="due_date" type="date" value="{{ old('due_date', now()->addMonthNoOverflow()->startOfMonth()->addDays(9)->format('Y-m-d')) }}" required
                           class="w-full rounded-xl border border-line-strong bg-white px-4 py-2.5 text-sm outline-none focus:border-brand focus:ring-2 focus:ring-brand/15">
                </div>

                <div class="sm:col-span-2">
                    <label for="notes" class="mb-1.5 block text-xs font-bold text-muted-strong">Catatan</label>
                    <input id="notes" name="notes" type="text" value="{{ old('notes') }}" placeholder="Opsional"
                           class="w-full rounded-xl border border-line-strong bg-white px-4 py-2.5 text-sm outline-none focus:border-brand focus:ring-2 focus:ring-brand/15">
                </div>
            </div>

            <div class="mt-6 flex items-center justify-end gap-2">
                <a href="{{ route('admin.bills.index') }}" class="rounded-xl border border-line px-4 py-2.5 text-xs font-bold text-muted transition hover:bg-cream-soft">Batal</a>
                <button type="submit" class="rounded-xl bg-brand px-4 py-2.5 text-xs font-bold text-white shadow-sm transition hover:bg-brand-dark">Simpan Tagihan</button>
            </div>
        </form>
    </div>
@endsection
