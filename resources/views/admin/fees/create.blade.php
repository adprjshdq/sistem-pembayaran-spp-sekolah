@extends('layouts.app')

@section('title', 'Tambah Tarif SPP')

@section('content')
    <div class="mx-auto max-w-2xl">
        <div class="mb-5">
            <a href="{{ route('admin.fees.index') }}" class="text-xs font-semibold text-brand hover:text-brand-dark">&larr; Kembali ke tarif SPP</a>
            <h2 class="mt-2 text-lg font-bold text-ink">Tambah Tarif SPP</h2>
        </div>

        <form method="POST" action="{{ route('admin.fees.store') }}" class="rounded-2xl border border-line bg-white p-5 shadow-sm sm:p-6">
            @csrf
            @include('admin.fees._form')

            <div class="mt-6 flex items-center justify-end gap-2">
                <a href="{{ route('admin.fees.index') }}" class="rounded-xl border border-line px-4 py-2.5 text-xs font-bold text-muted transition hover:bg-cream-soft">Batal</a>
                <button type="submit" class="rounded-xl bg-brand px-4 py-2.5 text-xs font-bold text-white shadow-sm transition hover:bg-brand-dark">Simpan Tarif</button>
            </div>
        </form>
    </div>
@endsection
