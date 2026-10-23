@extends('layouts.app')

@section('title', 'Pengaturan Sekolah')

@section('content')
    <div class="mx-auto max-w-3xl">
        <div class="mb-5">
            <h2 class="text-lg font-bold text-ink">Pengaturan Sekolah</h2>
            <p class="mt-0.5 text-xs text-muted-soft">Informasi ini digunakan pada kwitansi dan laporan.</p>
        </div>

        <form method="POST" action="{{ route('admin.settings.update') }}" class="rounded-2xl border border-line bg-white p-5 shadow-sm sm:p-6">
            @csrf
            @method('PUT')

            <div class="grid gap-4 sm:grid-cols-2">
                @foreach ($labels as $key => $label)
                    <div class="{{ in_array($key, ['school_address', 'receipt_note'], true) ? 'sm:col-span-2' : '' }}">
                        <label for="{{ $key }}" class="mb-1.5 block text-xs font-bold text-muted-strong">{{ $label }}</label>

                        @if ($key === 'receipt_note')
                            <textarea id="{{ $key }}" name="{{ $key }}" rows="2"
                                      class="w-full rounded-xl border border-line-strong bg-white px-4 py-2.5 text-sm outline-none focus:border-brand focus:ring-2 focus:ring-brand/15">{{ old($key, $settings[$key]) }}</textarea>
                        @else
                            <input id="{{ $key }}" name="{{ $key }}" type="{{ $key === 'school_email' ? 'email' : 'text' }}"
                                   value="{{ old($key, $settings[$key]) }}" @required($key === 'school_name')
                                   class="w-full rounded-xl border border-line-strong bg-white px-4 py-2.5 text-sm outline-none focus:border-brand focus:ring-2 focus:ring-brand/15">
                        @endif
                    </div>
                @endforeach
            </div>

            <div class="mt-6 flex justify-end">
                <button type="submit" class="rounded-xl bg-brand px-4 py-2.5 text-xs font-bold text-white shadow-sm transition hover:bg-brand-dark">Simpan Pengaturan</button>
            </div>
        </form>
    </div>
@endsection
