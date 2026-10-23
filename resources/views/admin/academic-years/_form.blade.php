@php
    $academicYear = $academicYear ?? null;
@endphp

<div class="grid gap-4 sm:grid-cols-2">
    <div>
        <label for="name" class="mb-1.5 block text-xs font-bold text-muted-strong">Nama Tahun Ajaran</label>
        <input id="name" name="name" type="text" value="{{ old('name', $academicYear?->name) }}" required placeholder="2026/2027"
               class="w-full rounded-xl border border-line-strong bg-white px-4 py-2.5 text-sm outline-none focus:border-brand focus:ring-2 focus:ring-brand/15">
    </div>

    <div class="flex items-end">
        <label class="flex items-center gap-2 rounded-xl border border-line bg-cream-soft px-4 py-2.5 text-xs font-semibold text-muted-strong">
            <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $academicYear?->is_active))
                   class="h-4 w-4 rounded border-line-strong text-brand focus:ring-brand/30">
            Jadikan tahun ajaran aktif
        </label>
    </div>

    <div>
        <label for="start_date" class="mb-1.5 block text-xs font-bold text-muted-strong">Tanggal Mulai</label>
        <input id="start_date" name="start_date" type="date" value="{{ old('start_date', $academicYear?->start_date?->format('Y-m-d')) }}" required
               class="w-full rounded-xl border border-line-strong bg-white px-4 py-2.5 text-sm outline-none focus:border-brand focus:ring-2 focus:ring-brand/15">
    </div>

    <div>
        <label for="end_date" class="mb-1.5 block text-xs font-bold text-muted-strong">Tanggal Selesai</label>
        <input id="end_date" name="end_date" type="date" value="{{ old('end_date', $academicYear?->end_date?->format('Y-m-d')) }}" required
               class="w-full rounded-xl border border-line-strong bg-white px-4 py-2.5 text-sm outline-none focus:border-brand focus:ring-2 focus:ring-brand/15">
    </div>
</div>
