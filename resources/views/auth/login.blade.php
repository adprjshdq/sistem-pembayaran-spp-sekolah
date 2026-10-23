<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Masuk &middot; EduPay SPP</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
    <body class="min-h-screen bg-[#F7F4EC] text-slate-800 antialiased">
    <div class="grid min-h-screen lg:grid-cols-2">
        <div class="relative hidden overflow-hidden bg-[#123C36] p-12 text-white lg:flex lg:flex-col lg:justify-between">
            <div class="relative">
                <div class="flex items-center gap-3">
                    <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-[#C9A227] text-[#123C36]">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 7.74-3.342M6.75 15a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Zm0 0v-3.675A55.378 55.378 0 0 1 12 8.443m-7.007 11.55A5.981 5.981 0 0 0 6.75 15.75v-1.5" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-lg font-extrabold tracking-tight">EduPay SPP</p>
                        <p class="text-xs text-[#CFE0D8]">Sistem Pembayaran SPP Sekolah</p>
                    </div>
                </div>
            </div>

            <div class="relative max-w-md">
                <div class="mb-6 h-1 w-12 rounded-full bg-[#C9A227]"></div>
                <h2 class="text-3xl font-extrabold leading-tight">Kelola pembayaran SPP lebih cepat, rapi, dan transparan.</h2>
                <p class="mt-4 text-sm leading-relaxed text-[#CFE0D8]">
                    Pantau tagihan, catat pembayaran, cetak kwitansi, dan hasilkan laporan keuangan sekolah dalam satu tempat.
                </p>

                <div class="mt-8 grid grid-cols-2 gap-3 text-xs">
                    <div class="rounded-xl border border-[#2A5A52] bg-[#17463F] p-4">
                        <p class="font-bold text-white">Admin &amp; Bendahara</p>
                        <p class="mt-1 text-[#CFE0D8]">Kelola data, tagihan, dan laporan.</p>
                    </div>
                    <div class="rounded-xl border border-[#2A5A52] bg-[#17463F] p-4">
                        <p class="font-bold text-white">Siswa &amp; Wali</p>
                        <p class="mt-1 text-[#CFE0D8]">Cek tagihan dan riwayat pembayaran.</p>
                    </div>
                </div>
            </div>

            <p class="relative text-[11px] text-[#9DB5AE]">&copy; {{ now()->year }} EduPay SPP. Seluruh hak cipta dilindungi.</p>
        </div>

        <div class="flex items-center justify-center p-6 sm:p-10">
            <div class="w-full max-w-md rounded-2xl border border-[#E5E0D5] bg-white p-6 shadow-sm sm:p-8">
                <div class="mb-8 lg:hidden">
                    <div class="flex items-center gap-3">
                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-[#123C36] text-[#C9A227]">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 7.74-3.342M6.75 15a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Zm0 0v-3.675A55.378 55.378 0 0 1 12 8.443m-7.007 11.55A5.981 5.981 0 0 0 6.75 15.75v-1.5" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-base font-extrabold text-slate-900">EduPay SPP</p>
                            <p class="text-[11px] text-slate-500">Sistem Pembayaran SPP Sekolah</p>
                        </div>
                    </div>
                </div>

                <h1 class="text-2xl font-extrabold tracking-tight text-slate-900">Masuk ke akun Anda</h1>
                <p class="mt-1 text-sm text-slate-500">Gunakan email atau NIS siswa untuk melanjutkan.</p>

                @if (session('status'))
                    <div class="mt-5 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
                        {{ session('status') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mt-5 rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-800">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login.store') }}" class="mt-6 space-y-5">
                    @csrf

                    <div>
                        <label for="login" class="mb-1.5 block text-xs font-bold text-slate-700">Email / NIS</label>
                        <input id="login" name="login" type="text" value="{{ old('login') }}" required autofocus
                               placeholder="admin@edupay.test atau 20261001"
                               class="w-full rounded-xl border border-[#D8D2C4] bg-white px-4 py-3 text-sm shadow-sm outline-none transition focus:border-[#123C36] focus:ring-2 focus:ring-[#123C36]/15">
                    </div>

                    <div>
                        <label for="password" class="mb-1.5 block text-xs font-bold text-slate-700">Kata Sandi</label>
                        <input id="password" name="password" type="password" required placeholder="••••••••"
                               class="w-full rounded-xl border border-[#D8D2C4] bg-white px-4 py-3 text-sm shadow-sm outline-none transition focus:border-[#123C36] focus:ring-2 focus:ring-[#123C36]/15">
                    </div>

                    <label class="flex items-center gap-2 text-xs font-medium text-slate-600">
                        <input type="checkbox" name="remember" value="1" class="h-4 w-4 rounded border-slate-300 text-[#123C36] focus:ring-[#123C36]">
                        Ingat saya
                    </label>

                    <button type="submit" class="w-full rounded-xl bg-[#123C36] px-4 py-3 text-sm font-bold text-white shadow-lg shadow-[#123C36]/15 transition hover:bg-[#0B2C27] active:scale-[0.99]">
                        Masuk
                    </button>
                </form>

                <div class="mt-7 rounded-xl border border-[#E5E0D5] bg-[#F7F4EC] p-4">
                    <p class="text-[11px] font-bold uppercase tracking-wide text-[#A07C12]">Akun demo</p>
                    <div class="mt-2 space-y-1.5 text-xs text-slate-600">
                        <p><span class="font-semibold text-slate-800">Admin:</span> admin@edupay.test / password</p>
                        <p><span class="font-semibold text-slate-800">Siswa:</span> 20261001 / password</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
