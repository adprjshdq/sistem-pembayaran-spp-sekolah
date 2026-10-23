@php
    $student = $student ?? null;
    $user = $student?->user;
@endphp

<div class="grid gap-4 sm:grid-cols-2">
    <div>
        <label for="nis" class="mb-1.5 block text-xs font-bold text-muted-strong">NIS</label>
        <input id="nis" name="nis" type="text" value="{{ old('nis', $student?->nis) }}" required
               class="w-full rounded-xl border border-line-strong bg-white px-4 py-2.5 text-sm outline-none transition focus:border-brand focus:ring-2 focus:ring-brand/15">
    </div>

    <div>
        <label for="name" class="mb-1.5 block text-xs font-bold text-muted-strong">Nama Lengkap</label>
        <input id="name" name="name" type="text" value="{{ old('name', $student?->name) }}" required
               class="w-full rounded-xl border border-line-strong bg-white px-4 py-2.5 text-sm outline-none transition focus:border-brand focus:ring-2 focus:ring-brand/15">
    </div>

    <div>
        <label for="class_id" class="mb-1.5 block text-xs font-bold text-muted-strong">Kelas</label>
        <select id="class_id" name="class_id" required
                class="w-full rounded-xl border border-line-strong bg-white px-4 py-2.5 text-sm outline-none transition focus:border-brand focus:ring-2 focus:ring-brand/15">
            <option value="">Pilih kelas</option>
            @foreach ($classes as $class)
                <option value="{{ $class->id }}" @selected((int) old('class_id', $student?->class_id) === $class->id)>
                    {{ $class->name }} &middot; {{ $class->academicYear?->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div>
        <label for="gender" class="mb-1.5 block text-xs font-bold text-muted-strong">Jenis Kelamin</label>
        <select id="gender" name="gender" required
                class="w-full rounded-xl border border-line-strong bg-white px-4 py-2.5 text-sm outline-none transition focus:border-brand focus:ring-2 focus:ring-brand/15">
            <option value="male" @selected(old('gender', $student?->gender ?? 'male') === 'male')>Laki-laki</option>
            <option value="female" @selected(old('gender', $student?->gender) === 'female')>Perempuan</option>
        </select>
    </div>

    <div>
        <label for="status" class="mb-1.5 block text-xs font-bold text-muted-strong">Status Siswa</label>
        <select id="status" name="status" required
                class="w-full rounded-xl border border-line-strong bg-white px-4 py-2.5 text-sm outline-none transition focus:border-brand focus:ring-2 focus:ring-brand/15">
            <option value="active" @selected(old('status', $student?->status ?? 'active') === 'active')>Aktif</option>
            <option value="inactive" @selected(old('status', $student?->status) === 'inactive')>Nonaktif</option>
            <option value="graduated" @selected(old('status', $student?->status) === 'graduated')>Lulus</option>
        </select>
    </div>

    <div>
        <label for="phone" class="mb-1.5 block text-xs font-bold text-muted-strong">No. HP Siswa</label>
        <input id="phone" name="phone" type="text" value="{{ old('phone', $student?->phone) }}"
               class="w-full rounded-xl border border-line-strong bg-white px-4 py-2.5 text-sm outline-none transition focus:border-brand focus:ring-2 focus:ring-brand/15">
    </div>

    <div>
        <label for="parent_name" class="mb-1.5 block text-xs font-bold text-muted-strong">Nama Orang Tua/Wali</label>
        <input id="parent_name" name="parent_name" type="text" value="{{ old('parent_name', $student?->parent_name) }}"
               class="w-full rounded-xl border border-line-strong bg-white px-4 py-2.5 text-sm outline-none transition focus:border-brand focus:ring-2 focus:ring-brand/15">
    </div>

    <div>
        <label for="parent_phone" class="mb-1.5 block text-xs font-bold text-muted-strong">No. HP Orang Tua/Wali</label>
        <input id="parent_phone" name="parent_phone" type="text" value="{{ old('parent_phone', $student?->parent_phone) }}"
               class="w-full rounded-xl border border-line-strong bg-white px-4 py-2.5 text-sm outline-none transition focus:border-brand focus:ring-2 focus:ring-brand/15">
    </div>

    <div class="sm:col-span-2">
        <label for="address" class="mb-1.5 block text-xs font-bold text-muted-strong">Alamat</label>
        <textarea id="address" name="address" rows="2"
                  class="w-full rounded-xl border border-line-strong bg-white px-4 py-2.5 text-sm outline-none transition focus:border-brand focus:ring-2 focus:ring-brand/15">{{ old('address', $student?->address) }}</textarea>
    </div>
</div>

<div class="mt-6 rounded-2xl border border-line bg-cream-soft p-4">
    <p class="text-xs font-bold text-muted-strong">Akun Portal Siswa</p>
    <p class="mt-0.5 text-[11px] text-muted-soft">Opsional. Isi email agar siswa dapat masuk ke portal menggunakan email atau NIS.</p>

    <div class="mt-3 grid gap-4 sm:grid-cols-2">
        <div>
            <label for="email" class="mb-1.5 block text-xs font-bold text-muted-strong">Email</label>
            <input id="email" name="email" type="email" value="{{ old('email', $user?->email) }}"
                   placeholder="siswa@edupay.test"
                   class="w-full rounded-xl border border-line-strong bg-white px-4 py-2.5 text-sm outline-none transition focus:border-brand focus:ring-2 focus:ring-brand/15">
        </div>
        <div>
            <label for="password" class="mb-1.5 block text-xs font-bold text-muted-strong">Kata Sandi</label>
            <input id="password" name="password" type="password"
                   placeholder="{{ $user ? 'Kosongkan jika tidak diubah' : 'Default: password' }}"
                   class="w-full rounded-xl border border-line-strong bg-white px-4 py-2.5 text-sm outline-none transition focus:border-brand focus:ring-2 focus:ring-brand/15">
        </div>
    </div>
</div>
