@extends('layouts.app')

@section('title', 'Catat Pembayaran')

@section('content')
    <div class="mx-auto max-w-4xl space-y-5">
        <div>
            <a href="{{ route('admin.payments.index') }}" class="text-xs font-semibold text-brand hover:text-brand-dark">&larr; Kembali ke pembayaran</a>
            <h2 class="mt-2 text-lg font-bold text-ink">Catat Pembayaran</h2>
            <p class="mt-0.5 text-xs text-muted-soft">Pilih siswa, centang tagihan, lalu simpan pembayaran.</p>
        </div>

        <form method="GET" action="{{ route('admin.payments.create') }}" class="rounded-2xl border border-line bg-white p-5 shadow-sm">
            <label for="student_id" class="mb-1.5 block text-xs font-bold text-muted-strong">Pilih Siswa</label>
            <select id="student_id" name="student_id" onchange="this.form.submit()"
                    class="w-full rounded-xl border border-line-strong bg-white px-4 py-2.5 text-sm outline-none focus:border-brand focus:ring-2 focus:ring-brand/15">
                <option value="">-- Pilih siswa dengan tagihan belum lunas --</option>
                @foreach ($students as $student)
                    <option value="{{ $student->id }}" @selected($selectedStudent?->id === $student->id)>
                        {{ $student->nis }} &middot; {{ $student->name }} ({{ $student->schoolClass?->name }})
                    </option>
                @endforeach
            </select>
        </form>

        @if ($selectedStudent)
            @if ($selectedStudent->bills->isEmpty())
                <div class="rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-6 text-center text-sm text-emerald-800">
                    Tidak ada tagihan yang belum lunas untuk siswa ini.
                </div>
            @else
                <form method="POST" action="{{ route('admin.payments.store') }}" class="rounded-2xl border border-line bg-white p-5 shadow-sm sm:p-6">
                    @csrf
                    <input type="hidden" name="student_id" value="{{ $selectedStudent->id }}">

                    <div class="flex flex-col gap-1 rounded-xl bg-cream-soft px-4 py-3 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <p class="text-sm font-bold text-ink">{{ $selectedStudent->name }}</p>
                            <p class="text-[11px] text-muted-soft">{{ $selectedStudent->nis }} &middot; {{ $selectedStudent->schoolClass?->name }}</p>
                        </div>
                        <button type="button" onclick="toggleAllBills()" class="self-start text-[11px] font-bold text-brand hover:text-brand-dark">Pilih semua</button>
                    </div>

                    <div class="mt-4 space-y-2">
                        @foreach ($selectedStudent->bills as $bill)
                            <label class="flex cursor-pointer items-center justify-between gap-3 rounded-xl border border-line px-4 py-3 transition hover:border-brand/50 hover:bg-cream-soft">
                                <div class="flex items-center gap-3">
                                    <input type="checkbox" name="bill_ids[]" value="{{ $bill->id }}" data-amount="{{ $bill->remainingAmount() }}"
                                           class="bill-checkbox h-4 w-4 rounded border-line-strong text-brand focus:ring-brand/30">
                                    <div>
                                        <p class="text-xs font-bold text-ink">{{ $bill->periodLabel() }}</p>
                                        <p class="text-[10px] text-muted-soft">Jatuh tempo {{ $bill->due_date?->format('d/m/Y') }} &middot; {{ $bill->status->label() }}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-xs font-bold text-ink">{{ rupiah($bill->remainingAmount()) }}</p>
                                    @if ($bill->paid_amount > 0)
                                        <p class="text-[10px] text-amber-600">Sudah dibayar {{ rupiah($bill->paid_amount) }}</p>
                                    @endif
                                </div>
                            </label>
                        @endforeach
                    </div>

                    <div class="mt-5 grid gap-4 sm:grid-cols-3">
                        <div>
                            <label for="method" class="mb-1.5 block text-xs font-bold text-muted-strong">Metode Pembayaran</label>
                            <select id="method" name="method" required
                                    class="w-full rounded-xl border border-line-strong bg-white px-4 py-2.5 text-sm outline-none focus:border-brand focus:ring-2 focus:ring-brand/15">
                                <option value="cash">Tunai</option>
                                <option value="transfer">Transfer Bank</option>
                                <option value="midtrans">Midtrans</option>
                            </select>
                        </div>
                        <div>
                            <label for="channel" class="mb-1.5 block text-xs font-bold text-muted-strong">Keterangan Channel</label>
                            <input id="channel" name="channel" type="text" placeholder="Contoh: BCA VA / Tunai"
                                   class="w-full rounded-xl border border-line-strong bg-white px-4 py-2.5 text-sm outline-none focus:border-brand focus:ring-2 focus:ring-brand/15">
                        </div>
                        <div class="flex items-end">
                            <div class="w-full rounded-xl bg-brand px-4 py-2.5 text-white">
                                <p class="text-[10px] uppercase tracking-wide text-muted-faint">Total Dipilih</p>
                                <p id="payment-total" class="text-lg font-extrabold">Rp 0</p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4">
                        <label for="notes" class="mb-1.5 block text-xs font-bold text-muted-strong">Catatan</label>
                        <input id="notes" name="notes" type="text" placeholder="Opsional"
                               class="w-full rounded-xl border border-line-strong bg-white px-4 py-2.5 text-sm outline-none focus:border-brand focus:ring-2 focus:ring-brand/15">
                    </div>

                    <div class="mt-6 flex items-center justify-end gap-2">
                        <a href="{{ route('admin.payments.index') }}" class="rounded-xl border border-line px-4 py-2.5 text-xs font-bold text-muted transition hover:bg-cream-soft">Batal</a>
                        <button type="submit" class="rounded-xl bg-brand px-4 py-2.5 text-xs font-bold text-white shadow-sm transition hover:bg-brand-dark">Simpan Pembayaran</button>
                    </div>
                </form>
            @endif
        @else
            <div class="rounded-2xl border border-line bg-white px-5 py-10 text-center text-sm text-muted-soft">
                Pilih siswa terlebih dahulu untuk melihat tagihan yang belum lunas.
            </div>
        @endif
    </div>
@endsection

@push('scripts')
    <script>
        function updateTotal() {
            const total = Array.from(document.querySelectorAll('.bill-checkbox:checked'))
                .reduce((sum, checkbox) => sum + Number(checkbox.dataset.amount || 0), 0);

            document.getElementById('payment-total').textContent = 'Rp ' + total.toLocaleString('id-ID');
        }

        function toggleAllBills() {
            const checkboxes = document.querySelectorAll('.bill-checkbox');
            const allChecked = Array.from(checkboxes).every((checkbox) => checkbox.checked);

            checkboxes.forEach((checkbox) => { checkbox.checked = ! allChecked; });
            updateTotal();
        }

        document.querySelectorAll('.bill-checkbox').forEach((checkbox) => {
            checkbox.addEventListener('change', updateTotal);
        });
    </script>
@endpush
