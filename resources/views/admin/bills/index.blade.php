@extends('layouts.app')

@section('title', 'Tagihan SPP')

@section('content')
    <div class="space-y-5">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-lg font-bold text-ink">Tagihan SPP</h2>
                <p class="mt-0.5 text-xs text-muted-soft">Daftar tagihan siswa beserta status pembayarannya.</p>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('admin.bills.generate') }}" class="rounded-xl bg-brand px-4 py-2.5 text-xs font-bold text-white shadow-sm transition hover:bg-brand-dark">Generate Massal</a>
                <a href="{{ route('admin.bills.create') }}" class="rounded-xl border border-line-strong bg-white px-4 py-2.5 text-xs font-bold text-muted-strong transition hover:bg-cream-soft">Tambah Tagihan</a>
            </div>
        </div>

        <form method="GET" class="grid gap-3 rounded-2xl border border-line bg-white p-4 shadow-sm sm:grid-cols-6">
            <div class="sm:col-span-2">
                <input type="search" name="search" value="{{ request('search') }}" placeholder="Cari nama atau NIS..."
                       class="w-full rounded-xl border border-line-strong px-4 py-2.5 text-sm outline-none focus:border-brand focus:ring-2 focus:ring-brand/15">
            </div>
            <select name="class_id" class="rounded-xl border border-line-strong px-4 py-2.5 text-sm outline-none focus:border-brand focus:ring-2 focus:ring-brand/15">
                <option value="">Semua kelas</option>
                @foreach ($classes as $class)
                    <option value="{{ $class->id }}" @selected((int) request('class_id') === $class->id)>{{ $class->name }}</option>
                @endforeach
            </select>
            <select name="period_month" class="rounded-xl border border-line-strong px-4 py-2.5 text-sm outline-none focus:border-brand focus:ring-2 focus:ring-brand/15">
                <option value="">Semua bulan</option>
                @foreach ($months as $value => $label)
                    <option value="{{ $value }}" @selected((int) request('period_month') === $value)>{{ $label }}</option>
                @endforeach
            </select>
            <select name="status" class="rounded-xl border border-line-strong px-4 py-2.5 text-sm outline-none focus:border-brand focus:ring-2 focus:ring-brand/15">
                <option value="">Semua status</option>
                <option value="unpaid" @selected(request('status') === 'unpaid')>Belum dibayar</option>
                <option value="partial" @selected(request('status') === 'partial')>Sebagian</option>
                <option value="paid" @selected(request('status') === 'paid')>Lunas</option>
            </select>
            <div class="flex gap-2">
                <select name="period_year" class="w-full rounded-xl border border-line-strong px-4 py-2.5 text-sm outline-none focus:border-brand focus:ring-2 focus:ring-brand/15">
                    <option value="">Tahun</option>
                    @foreach ($years as $year)
                        <option value="{{ $year }}" @selected((int) request('period_year') === $year)>{{ $year }}</option>
                    @endforeach
                </select>
                <button type="submit" class="rounded-xl bg-brand px-4 py-2.5 text-xs font-bold text-white transition hover:bg-brand-dark">Filter</button>
            </div>
        </form>

        <div class="overflow-hidden rounded-2xl border border-line bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table class="min-w-full text-left text-xs">
                    <thead class="bg-cream-soft text-[10px] uppercase tracking-wide text-muted-soft">
                        <tr>
                            <th class="px-5 py-3 font-bold">Siswa</th>
                            <th class="px-5 py-3 font-bold">Kelas</th>
                            <th class="px-5 py-3 font-bold">Periode</th>
                            <th class="px-5 py-3 font-bold">Jatuh Tempo</th>
                            <th class="px-5 py-3 font-bold">Tagihan</th>
                            <th class="px-5 py-3 font-bold">Dibayar</th>
                            <th class="px-5 py-3 font-bold">Sisa</th>
                            <th class="px-5 py-3 font-bold">Status</th>
                            <th class="px-5 py-3 text-right font-bold">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-line-soft">
                        @forelse ($bills as $bill)
                            <tr class="hover:bg-cream-soft">
                                <td class="px-5 py-3">
                                    <p class="font-semibold text-ink">{{ $bill->student?->name }}</p>
                                    <p class="font-mono text-[10px] text-muted-faint">{{ $bill->student?->nis }}</p>
                                </td>
                                <td class="px-5 py-3 text-muted">{{ $bill->student?->schoolClass?->name ?? '-' }}</td>
                                <td class="px-5 py-3 font-semibold text-muted-strong">{{ $bill->periodLabel() }}</td>
                                <td class="px-5 py-3 text-muted">{{ $bill->due_date?->format('d/m/Y') }}</td>
                                <td class="px-5 py-3 font-bold text-ink">{{ rupiah($bill->amount) }}</td>
                                <td class="px-5 py-3 text-emerald-700">{{ rupiah($bill->paid_amount) }}</td>
                                <td class="px-5 py-3 {{ $bill->remainingAmount() > 0 ? 'text-rose-600 font-semibold' : 'text-muted-faint' }}">{{ rupiah($bill->remainingAmount()) }}</td>
                                <td class="px-5 py-3">
                                    <span class="inline-flex rounded-full border px-2 py-0.5 text-[10px] font-bold {{ $bill->status->badgeClasses() }}">
                                        {{ $bill->status->label() }}
                                    </span>
                                </td>
                                <td class="px-5 py-3">
                                    <div class="flex items-center justify-end gap-2">
                                        @if ($bill->isOutstanding())
                                            <a href="{{ route('admin.payments.create', ['student_id' => $bill->student_id]) }}" class="rounded-lg border border-brand/30 px-2.5 py-1.5 text-[11px] font-bold text-brand transition hover:bg-brand-soft">Bayar</a>
                                        @endif
                                        <form method="POST" action="{{ route('admin.bills.destroy', $bill) }}" onsubmit="return confirm('Hapus tagihan ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="rounded-lg border border-rose-200 px-2.5 py-1.5 text-[11px] font-bold text-rose-600 transition hover:bg-rose-50">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="px-5 py-10 text-center text-muted-soft">Belum ada tagihan yang cocok.</td>
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
