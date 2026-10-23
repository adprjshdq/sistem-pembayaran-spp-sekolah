@php
    $class = $class ?? null;
@endphp

<div class="grid gap-4 sm:grid-cols-2">
    <div>
        <label for="academic_year_id" class="mb-1.5 block text-xs font-bold text-muted-strong">Tahun Ajaran</label>
        <select id="academic_year_id" name="academic_year_id" required
                class="w-full rounded-xl border border-line-strong bg-white px-4 py-2.5 text-sm outline-none focus:border-brand focus:ring-2 focus:ring-brand/15">
            <option value="">Pilih tahun ajaran</option>
            @foreach ($academicYears as $year)
                <option value="{{ $year->id }}" @selected((int) old('academic_year_id', $class?->academic_year_id) === $year->id)>{{ $year->name }}</option>
            @endforeach
        </select>
    </div>

    <div>
        <label for="name" class="mb-1.5 block text-xs font-bold text-muted-strong">Nama Kelas</label>
        <input id="name" name="name" type="text" value="{{ old('name', $class?->name) }}" required placeholder="XII RPL 1"
               class="w-full rounded-xl border border-line-strong bg-white px-4 py-2.5 text-sm outline-none focus:border-brand focus:ring-2 focus:ring-brand/15">
    </div>

    <div>
        <label for="level" class="mb-1.5 block text-xs font-bold text-muted-strong">Tingkat</label>
        <select id="level" name="level" required
                class="w-full rounded-xl border border-line-strong bg-white px-4 py-2.5 text-sm outline-none focus:border-brand focus:ring-2 focus:ring-brand/15">
            @foreach (['X', 'XI', 'XII'] as $level)
                <option value="{{ $level }}" @selected(old('level', $class?->level) === $level)>{{ $level }}</option>
            @endforeach
        </select>
    </div>

    <div>
        <label for="major" class="mb-1.5 block text-xs font-bold text-muted-strong">Jurusan</label>
        <input id="major" name="major" type="text" value="{{ old('major', $class?->major) }}" placeholder="Rekayasa Perangkat Lunak"
               class="w-full rounded-xl border border-line-strong bg-white px-4 py-2.5 text-sm outline-none focus:border-brand focus:ring-2 focus:ring-brand/15">
    </div>

    <div class="sm:col-span-2">
        <label for="homeroom_teacher" class="mb-1.5 block text-xs font-bold text-muted-strong">Wali Kelas</label>
        <input id="homeroom_teacher" name="homeroom_teacher" type="text" value="{{ old('homeroom_teacher', $class?->homeroom_teacher) }}"
               class="w-full rounded-xl border border-line-strong bg-white px-4 py-2.5 text-sm outline-none focus:border-brand focus:ring-2 focus:ring-brand/15">
    </div>
</div>
