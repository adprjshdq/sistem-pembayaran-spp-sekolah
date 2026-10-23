@extends('layouts.app')

@section('title', 'Edit Siswa')

@section('content')
    <div class="mx-auto max-w-3xl">
        <div class="mb-5">
            <a href="{{ route('admin.students.index') }}" class="text-xs font-semibold text-brand hover:text-brand-dark">&larr; Kembali ke data siswa</a>
            <h2 class="mt-2 text-lg font-bold text-ink">Edit Siswa</h2>
            <p class="mt-0.5 text-xs text-muted-soft">{{ $student->name }} &middot; NIS {{ $student->nis }}</p>
        </div>

        <form method="POST" action="{{ route('admin.students.update', $student) }}" class="rounded-2xl border border-line bg-white p-5 shadow-sm sm:p-6">
            @csrf
            @method('PUT')
            @include('admin.students._form', ['student' => $student])

            <div class="mt-6 flex items-center justify-end gap-2">
                <a href="{{ route('admin.students.index') }}" class="rounded-xl border border-line px-4 py-2.5 text-xs font-bold text-muted transition hover:bg-cream-soft">Batal</a>
                <button type="submit" class="rounded-xl bg-brand px-4 py-2.5 text-xs font-bold text-white shadow-sm transition hover:bg-brand-dark">Simpan Perubahan</button>
            </div>
        </form>
    </div>
@endsection
