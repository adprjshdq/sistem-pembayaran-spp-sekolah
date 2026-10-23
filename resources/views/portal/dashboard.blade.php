@extends('layouts.app')

@section('title', 'Dashboard Siswa')

@section('content')
    <div class="space-y-6">
        <section class="overflow-hidden rounded-3xl bg-brand p-6 text-white shadow-sm sm:p-8">
            <div class="flex flex-col gap-5 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-gold">Portal Siswa</p>
                    <h2 class="mt-1 text-2xl font-extrabold tracking-tight">{{ $student->name }}</h2>
                    <p class="mt-1 text-sm text-cream">{{ $student->nis }} &middot; {{ $student->schoolClass?->name ?? '-' }}</p>
                    <p class="text-xs text-gold">{{ $student->schoolClass?->academicYear?->name }}</p>
                </div>
                <div class="rounded-2xl border border-white/20 bg-white/10 px-5 py-4 text-right">
                    <p class="text-[10px] uppercase tracking-wide text-gold">Total Tunggakan</p>
                    <p class="text-2xl font-extrabold">{{ rupiah($outstanding) }}</p>
                </div>
            </div>
        </section>

        <section class="grid gap-4 sm:grid-cols-3">
            <div class="rounded-2xl border border-rose-200 bg-rose-50 p-5 shadow-sm">
                <p class="text-[10px] font-bold uppercase tracking-wide text-rose-700">Tagihan Belum Lunas</p>
                <p class="mt-2 text-3xl font-extrabold text-rose-800">{{ $unpaidCount }}</p>
            </div>
            <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-5 shadow-sm">
                <p class="text-[10px] font-bold uppercase tracking-wide text-emerald-700">Dibayar Tahun Ini</p>
                <p class="mt-2 text-2xl font-extrabold text-emerald-800">{{ rupiah($paidThisYear) }}</p>
            </div>
            <div class="rounded-2xl border border-line bg-white p-5 shadow-sm">
                <p class="text-[10px] font-bold uppercase tracking-wide text-muted-faint">Kelas</p>
                <p class="mt-2 text-lg font-extrabold text-ink">{{ $student->schoolClass?->name ?? '-' }}</p>
                <p class="mt-1 text-xs text-muted-soft">Wali: {{ $student->schoolClass?->homeroom_teacher ?? '-' }}</p>
            </div>
        </section>

        <section class="rounded-2xl border border-line bg-white shadow-sm">
            <div class="flex items-center justify-between border-b border-line-soft px-5 py-4">
                <div>
                    <h3 class="text-sm font-bold text-ink">Tagihan Belum Lunas</h3>
                    <p class="mt-0.5 text-xs text-muted-soft">Centang tagihan yang ingin dibayar, lalu klik Bayar Sekarang.</p>
                </div>
                <a href="{{ route('portal.bills.index') }}" class="text-xs font-bold text-brand hover:text-brand-dark">Semua tagihan</a>
            </div>

            @if ($bills->isEmpty())
                <div class="px-5 py-10 text-center text-sm text-emerald-700">Semua tagihan sudah lunas. Terima kasih!</div>
            @else
                <form method="POST" action="{{ route('portal.payments.pay') }}" class="p-5">
                    @csrf

                    <div class="space-y-2">
                        @foreach ($bills as $bill)
                            <label class="flex cursor-pointer items-center justify-between gap-3 rounded-xl border border-line px-4 py-3 transition hover:border-brand/50 hover:bg-cream-soft">
                                <div class="flex items-center gap-3">
                                    <input type="checkbox" name="bill_ids[]" value="{{ $bill->id }}" data-amount="{{ $bill->remainingAmount() }}"
                                           class="bill-checkbox h-4 w-4 rounded border-line-strong text-brand focus:ring-brand/30">
                                    <div>
                                        <p class="text-xs font-bold text-ink">{{ $bill->periodLabel() }}</p>
                                        <p class="text-[10px] text-muted-soft">Jatuh tempo {{ $bill->due_date?->format('d/m/Y') }} &middot; {{ $bill->status->label() }}</p>
                                    </div>
                                </div>
                                <p class="text-xs font-bold text-ink">{{ rupiah($bill->remainingAmount()) }}</p>
                            </label>
                        @endforeach
                    </div>

                    <div class="mt-4 flex flex-col gap-3 rounded-xl bg-cream-soft px-4 py-3 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <p class="text-[10px] uppercase tracking-wide text-muted-faint">Total Dipilih</p>
                            <p id="portal-total" class="text-lg font-extrabold text-ink">Rp 0</p>
                        </div>
                        <button type="submit" class="rounded-xl bg-brand px-5 py-3 text-xs font-bold text-white shadow-sm transition hover:bg-brand-dark">
                            Bayar Sekarang
                        </button>
                    </div>

                    @if ($student->payments()->where('status', 'pending')->exists())
                        <p class="mt-3 text-[11px] text-amber-600">Anda masih memiliki pembayaran berstatus menunggu. Selesaikan pembayaran tersebut terlebih dahulu.</p>
                    @endif
                </form>
            @endif
        </section>

        <section class="rounded-2xl border border-line bg-white shadow-sm">
            <div class="flex items-center justify-between border-b border-line-soft px-5 py-4">
                <h3 class="text-sm font-bold text-ink">Pembayaran Terakhir</h3>
                <a href="{{ route('portal.payments.index') }}" class="text-xs font-bold text-brand hover:text-brand-dark">Lihat semua</a>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full text-left text-xs">
                    <thead class="bg-cream-soft text-[10px] uppercase tracking-wide text-muted-soft">
                        <tr>
                            <th class="px-5 py-3 font-bold">Invoice</th>
                            <th class="px-5 py-3 font-bold">Tanggal</th>
                            <th class="px-5 py-3 font-bold">Metode</th>
                            <th class="px-5 py-3 font-bold">Jumlah</th>
                            <th class="px-5 py-3 font-bold">Status</th>
                            <th class="px-5 py-3 text-right font-bold">Kwitansi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-line-soft">
                        @forelse ($payments as $payment)
                            <tr class="hover:bg-cream-soft">
                                <td class="px-5 py-3 font-mono font-semibold text-brand">{{ $payment->invoice_number }}</td>
                                <td class="px-5 py-3 text-muted">{{ ($payment->paid_at ?? $payment->created_at)->locale('id')->translatedFormat('d M Y') }}</td>
                                <td class="px-5 py-3 text-muted">{{ $payment->channel ?: ucfirst($payment->method) }}</td>
                                <td class="px-5 py-3 font-bold text-ink">{{ rupiah($payment->amount) }}</td>
                                <td class="px-5 py-3">
                                    <span class="inline-flex rounded-full border px-2 py-0.5 text-[10px] font-bold {{ $payment->status->badgeClasses() }}">{{ $payment->status->label() }}</span>
                                </td>
                                <td class="px-5 py-3 text-right">
                                    <a href="{{ route('portal.payments.receipt', $payment) }}" target="_blank" class="text-[11px] font-bold text-brand hover:text-brand-dark">Cetak</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-5 py-8 text-center text-muted-soft">Belum ada pembayaran.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </div>
@endsection

@push('scripts')
    <script>
        function updatePortalTotal() {
            const total = Array.from(document.querySelectorAll('.bill-checkbox:checked'))
                .reduce((sum, checkbox) => sum + Number(checkbox.dataset.amount || 0), 0);

            const target = document.getElementById('portal-total');
            if (target) {
                target.textContent = 'Rp ' + total.toLocaleString('id-ID');
            }
        }

        document.querySelectorAll('.bill-checkbox').forEach((checkbox) => {
            checkbox.addEventListener('change', updatePortalTotal);
        });
    </script>
@endpush
