@php
    $fee = $fee ?? null;
@endphp

<div class="grid gap-4 sm:grid-cols-2">
    <div>
        <label for="academic_year_id" class="mb-1.5 block text-xs font-bold text-muted-strong">Tahun Ajaran</label>
        <select id="academic_year_id" name="academic_year_id" required
                class="w-full rounded-xl border border-line-strong bg-white px-4 py-2.5 text-sm outline-none focus:border-brand focus:ring-2 focus:ring-brand/15">
            <option value="">Pilih tahun ajaran</option>
            @foreach ($academicYears as $year)
                <option value="{{ $year->id }}" @selected((int) old('academic_year_id', $fee?->academic_year_id) === $year->id)>{{ $year->name }}</option>
            @endforeach
        </select>
    </div>

    <div>
        <label for="level" class="mb-1.5 block text-xs font-bold text-muted-strong">Tingkat</label>
        <select id="level" name="level" required
                class="w-full rounded-xl border border-line-strong bg-white px-4 py-2.5 text-sm outline-none focus:border-brand focus:ring-2 focus:ring-brand/15">
            @foreach (['X', 'XI', 'XII'] as $level)
                <option value="{{ $level }}" @selected(old('level', $fee?->level) === $level)>{{ $level }}</option>
            @endforeach
        </select>
    </div>

    <div class="sm:col-span-2">
        <label for="amount" class="mb-1.5 block text-xs font-bold text-muted-strong">Nominal SPP per Bulan (Rp)</label>
        <input id="amount" name="amount" type="number" min="0" step="1000" value="{{ old('amount', $fee?->amount) }}" required placeholder="350000"
               class="w-full rounded-xl border border-line-strong bg-white px-4 py-2.5 text-sm outline-none focus:border-brand focus:ring-2 focus:ring-brand/15">
    </div>
</div>
