<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') &middot; {{ school_setting('school_name', config('app.name')) }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @media print {
            .no-print { display: none !important; }
            body { background: #fff !important; }
            main { padding: 0 !important; }
        }
    </style>
</head>
<body class="bg-cream text-ink antialiased">
    @php
        $user = auth()->user();
        $isAdmin = $user->isAdmin();

        $links = $isAdmin
            ? [
                ['label' => 'Dashboard', 'route' => 'admin.dashboard', 'active' => 'admin.dashboard'],
                ['label' => 'Data Siswa', 'route' => 'admin.students.index', 'active' => 'admin.students.*'],
                ['label' => 'Kelas', 'route' => 'admin.classes.index', 'active' => 'admin.classes.*'],
                ['label' => 'Tahun Ajaran', 'route' => 'admin.academic-years.index', 'active' => 'admin.academic-years.*'],
                ['label' => 'Tarif SPP', 'route' => 'admin.fees.index', 'active' => 'admin.fees.*'],
                ['label' => 'Tagihan', 'route' => 'admin.bills.index', 'active' => 'admin.bills.*'],
                ['label' => 'Pembayaran', 'route' => 'admin.payments.index', 'active' => 'admin.payments.*'],
                ['label' => 'Laporan', 'route' => 'admin.reports.index', 'active' => 'admin.reports.*'],
                ['label' => 'Pengaturan', 'route' => 'admin.settings.edit', 'active' => 'admin.settings.*'],
            ]
            : [
                ['label' => 'Dashboard', 'route' => 'portal.dashboard', 'active' => 'portal.dashboard'],
                ['label' => 'Tagihan Saya', 'route' => 'portal.bills.index', 'active' => 'portal.bills.*'],
                ['label' => 'Riwayat Pembayaran', 'route' => 'portal.payments.index', 'active' => 'portal.payments.*'],
            ];
    @endphp

    <div class="min-h-screen lg:flex">
        <div id="sidebar-overlay" class="fixed inset-0 z-40 hidden bg-ink/40 lg:hidden" onclick="toggleSidebar()"></div>

        <aside id="sidebar" class="fixed inset-y-0 left-0 z-50 flex w-64 -translate-x-full flex-col border-r border-line bg-white text-muted transition-transform duration-200 lg:static lg:translate-x-0">
            <div class="flex h-16 items-center gap-3 border-b border-line px-5">
                <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-brand text-gold">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 7.74-3.342M6.75 15a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Zm0 0v-3.675A55.378 55.378 0 0 1 12 8.443m-7.007 11.55A5.981 5.981 0 0 0 6.75 15.75v-1.5" />
                    </svg>
                </div>
                <div class="min-w-0">
                    <p class="truncate text-sm font-bold text-ink">EduPay SPP</p>
                    <p class="truncate text-[11px] text-muted-soft">{{ $isAdmin ? 'Panel Administrator' : 'Portal Siswa' }}</p>
                </div>
            </div>

            <nav class="flex-1 space-y-1 overflow-y-auto px-3 py-4">
                @foreach ($links as $link)
                    <a href="{{ route($link['route']) }}"
                       class="flex items-center rounded-xl px-3 py-2.5 text-sm font-medium transition {{ request()->routeIs($link['active']) ? 'bg-brand text-white shadow-lg shadow-brand/10' : 'text-muted hover:bg-cream-soft hover:text-ink' }}">
                        {{ $link['label'] }}
                    </a>
                @endforeach
            </nav>

            <div class="border-t border-line p-4">
                <div class="rounded-xl bg-cream-soft px-3 py-3">
                    <p class="truncate text-sm font-semibold text-ink">{{ $user->name }}</p>
                    <p class="mt-0.5 text-[11px] text-muted-soft">{{ $user->role->label() }}</p>
                </div>
                <form method="POST" action="{{ route('logout') }}" class="mt-3">
                    @csrf
                    <button type="submit" class="w-full rounded-xl border border-line px-3 py-2 text-xs font-semibold text-muted transition hover:bg-rose-50 hover:text-rose-600">
                        Keluar
                    </button>
                </form>
            </div>
        </aside>

        <div class="flex min-w-0 flex-1 flex-col">
            <header class="sticky top-0 z-30 flex h-16 items-center justify-between border-b border-line bg-cream/90 px-4 backdrop-blur sm:px-6 lg:px-8">
                <div class="flex items-center gap-3">
                    <button type="button" onclick="toggleSidebar()" class="rounded-lg border border-line p-2 text-muted lg:hidden">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                        </svg>
                    </button>
                    <div>
                        <p class="text-[11px] font-semibold uppercase tracking-wide text-brand">{{ $isAdmin ? 'Administrasi' : 'Portal Siswa' }}</p>
                        <h1 class="text-base font-bold text-ink">@yield('title', 'Dashboard')</h1>
                    </div>
                </div>
                <div class="hidden text-right sm:block">
                    <p class="text-xs font-semibold text-muted-strong">{{ school_setting('school_name', config('app.name')) }}</p>
                    <p class="text-[11px] text-muted-faint">{{ now()->locale('id')->translatedFormat('l, d F Y') }}</p>
                </div>
            </header>

            <main class="flex-1 p-4 sm:p-6 lg:p-8">
                @if (session('success'))
                    <div class="mb-5 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="mb-5 rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm font-medium text-rose-800">
                        {{ session('error') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mb-5 rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-800">
                        <p class="font-semibold">Periksa kembali data berikut:</p>
                        <ul class="mt-1 list-inside list-disc space-y-0.5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @yield('content')
            </main>

            <footer class="border-t border-line px-4 py-4 text-center text-[11px] text-muted-faint sm:px-6 lg:px-8">
                {{ school_setting('school_name', config('app.name')) }} &middot; Sistem Pembayaran SPP
            </footer>
        </div>
    </div>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebar-overlay');

            sidebar.classList.toggle('-translate-x-full');
            overlay.classList.toggle('hidden');
        }
    </script>

    @stack('scripts')
</body>
</html>
