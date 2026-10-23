<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Pembayaran SPP Online - SMA/SMK Negeri 1 Teladan</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Tailwind CSS CDN for instant rich UI -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            50: '#eef2ff',
                            100: '#e0e7ff',
                            500: '#6366f1',
                            600: '#4f46e5',
                            700: '#4338ca',
                            900: '#312e81',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .glass-card {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(226, 232, 240, 0.8);
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 min-h-screen antialiased flex flex-col">

    <!-- Top Navigation Bar -->
    <nav class="sticky top-0 z-40 bg-white/95 backdrop-blur-md border-b border-slate-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <!-- Brand / Logo -->
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-indigo-600 via-indigo-700 to-purple-600 flex items-center justify-center text-white shadow-md shadow-indigo-200">
                        <i data-lucide="graduation-cap" class="w-6 h-6"></i>
                    </div>
                    <div>
                        <span class="text-base font-extrabold text-slate-900 tracking-tight flex items-center gap-1.5">
                            EduPay SPP <span class="text-xs font-semibold px-2 py-0.5 rounded-full bg-indigo-50 text-indigo-700 border border-indigo-200">SMA/SMK</span>
                        </span>
                        <p class="text-[11px] text-slate-500 font-medium">SMK Negeri 1 Teknologi & Bisnis • TA 2024/2025</p>
                    </div>
                </div>

                <!-- Role Selector Tabs -->
                <div class="hidden md:flex items-center space-x-1 p-1 bg-slate-100 rounded-xl border border-slate-200">
                    <button onclick="switchView('dashboard')" id="btn-tab-dashboard" class="px-4 py-2 rounded-lg text-xs font-bold transition-all flex items-center gap-2 bg-white text-indigo-600 shadow-sm">
                        <i data-lucide="layout-dashboard" class="w-4 h-4"></i>
                        Dashboard Keuangan
                    </button>
                    <button onclick="switchView('bayar')" id="btn-tab-bayar" class="px-4 py-2 rounded-lg text-xs font-semibold transition-all flex items-center gap-2 text-slate-600 hover:text-slate-900">
                        <i data-lucide="wallet-cards" class="w-4 h-4"></i>
                        Portal Bayar Siswa
                    </button>
                    <button onclick="switchView('laporan')" id="btn-tab-laporan" class="px-4 py-2 rounded-lg text-xs font-semibold transition-all flex items-center gap-2 text-slate-600 hover:text-slate-900">
                        <i data-lucide="file-spreadsheet" class="w-4 h-4"></i>
                        Laporan & Rekap
                    </button>
                </div>

                <!-- User Profile & Action -->
                <div class="flex items-center space-x-3">
                    <div class="hidden sm:flex flex-col text-right">
                        <span class="text-xs font-bold text-slate-900">Hj. Siti Rahmawati, S.E.</span>
                        <span class="text-[10px] font-medium text-emerald-600 flex items-center justify-end gap-1">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                            Bendahara Sekolah
                        </span>
                    </div>
                    <div class="w-9 h-9 rounded-full bg-gradient-to-tr from-slate-200 to-indigo-100 border border-indigo-200 flex items-center justify-center font-bold text-indigo-700 text-xs">
                        SR
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Container -->
    <main class="flex-1 max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-6">

        <!-- ========================================== -->
        <!-- VIEW 1: DASHBOARD KEUANGAN (ADMIN/BENDAHARA) -->
        <!-- ========================================== -->
        <div id="view-dashboard" class="space-y-6">
            
            <!-- Hero Banner -->
            <div class="rounded-2xl bg-gradient-to-r from-indigo-900 via-indigo-800 to-slate-900 p-6 sm:p-8 text-white relative overflow-hidden shadow-xl shadow-indigo-950/10">
                <div class="absolute -right-10 -bottom-10 w-72 h-72 bg-indigo-500/10 rounded-full blur-3xl pointer-events-none"></div>
                <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div>
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-indigo-500/20 border border-indigo-400/30 text-indigo-200 text-xs font-medium mb-3">
                            <i data-lucide="shield-check" class="w-3.5 h-3.5 text-emerald-400"></i>
                            Midtrans Payment Gateway Aktif (Sandbox/Live Ready)
                        </div>
                        <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight">Pusat Keuangan & Pembayaran SPP</h1>
                        <p class="text-indigo-200 text-sm mt-1 max-w-2xl">
                            Monitoring penerimaan SPP siswa jenjang SMA/SMK secara otomatis, transparan, dan terintegrasi dengan Bank Transfer & QRIS.
                        </p>
                    </div>
                    <div class="flex items-center gap-3">
                        <button onclick="switchView('bayar')" class="px-4 py-2.5 bg-indigo-500 hover:bg-indigo-600 active:scale-95 transition text-white rounded-xl text-xs font-bold shadow-lg shadow-indigo-600/30 flex items-center gap-2">
                            <i data-lucide="credit-card" class="w-4 h-4"></i>
                            Simulasi Bayar Siswa
                        </button>
                        <button onclick="triggerNotification('Tagihan Bulan Oktober 2024 berhasil digenerate untuk 1.240 siswa!')" class="px-4 py-2.5 bg-white/10 hover:bg-white/20 active:scale-95 transition text-white rounded-xl text-xs font-bold border border-white/20 flex items-center gap-2">
                            <i data-lucide="refresh-cw" class="w-4 h-4"></i>
                            Generate Tagihan
                        </button>
                    </div>
                </div>
            </div>

            <!-- Stats Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Card 1 -->
                <div class="glass-card p-5 rounded-2xl shadow-sm hover:shadow-md transition">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-semibold text-slate-500">Penerimaan SPP (Bulan Ini)</span>
                        <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center">
                            <i data-lucide="badge-dollar-sign" class="w-4 h-4"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <span class="text-2xl font-extrabold text-slate-900">Rp 372.500.000</span>
                        <div class="flex items-center gap-1.5 mt-1 text-xs font-semibold text-emerald-600">
                            <i data-lucide="trending-up" class="w-3.5 h-3.5"></i>
                            <span>+12.4% vs bulan lalu</span>
                        </div>
                    </div>
                </div>

                <!-- Card 2 -->
                <div class="glass-card p-5 rounded-2xl shadow-sm hover:shadow-md transition">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-semibold text-slate-500">Total Tunggakan Aktif</span>
                        <div class="w-8 h-8 rounded-lg bg-rose-50 text-rose-600 flex items-center justify-center">
                            <i data-lucide="alert-circle" class="w-4 h-4"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <span class="text-2xl font-extrabold text-slate-900">Rp 58.250.000</span>
                        <div class="flex items-center gap-1.5 mt-1 text-xs font-semibold text-rose-600">
                            <i data-lucide="users" class="w-3.5 h-3.5"></i>
                            <span>142 siswa belum lunas</span>
                        </div>
                    </div>
                </div>

                <!-- Card 3 -->
                <div class="glass-card p-5 rounded-2xl shadow-sm hover:shadow-md transition">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-semibold text-slate-500">Rasio Kelunasan</span>
                        <div class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center">
                            <i data-lucide="pie-chart" class="w-4 h-4"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <span class="text-2xl font-extrabold text-indigo-600">86.4%</span>
                        <div class="w-full bg-slate-100 rounded-full h-1.5 mt-2 overflow-hidden">
                            <div class="bg-indigo-600 h-1.5 rounded-full" style="width: 86.4%"></div>
                        </div>
                    </div>
                </div>

                <!-- Card 4 -->
                <div class="glass-card p-5 rounded-2xl shadow-sm hover:shadow-md transition">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-semibold text-slate-500">Kanal Bayar Favorit</span>
                        <div class="w-8 h-8 rounded-lg bg-sky-50 text-sky-600 flex items-center justify-center">
                            <i data-lucide="smartphone" class="w-4 h-4"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <span class="text-2xl font-extrabold text-slate-900">QRIS / VA</span>
                        <div class="flex items-center gap-1.5 mt-1 text-xs font-semibold text-slate-500">
                            <span>78% Transaksi via Midtrans</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts Section -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Line Chart -->
                <div class="lg:col-span-2 glass-card p-5 rounded-2xl shadow-sm space-y-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="font-bold text-slate-900 text-sm">Tren Penerimaan SPP 6 Bulan Terakhir</h3>
                            <p class="text-xs text-slate-500">Perbandingan target vs realisasi kas masuk</p>
                        </div>
                        <span class="text-xs font-semibold px-2.5 py-1 bg-slate-100 text-slate-700 rounded-lg">TA 2024/2025</span>
                    </div>
                    <div class="h-64">
                        <canvas id="chartRevenue"></canvas>
                    </div>
                </div>

                <!-- Donut Chart -->
                <div class="glass-card p-5 rounded-2xl shadow-sm space-y-4 flex flex-col justify-between">
                    <div>
                        <div class="flex items-center justify-between">
                            <h3 class="font-bold text-slate-900 text-sm">Kelunasan per Jurusan</h3>
                            <span class="text-xs text-slate-400">SMK 4 Jurusan</span>
                        </div>
                        <p class="text-xs text-slate-500 mt-0.5">Persentase tertib bayar tiap kompetensi</p>
                    </div>
                    <div class="h-52 relative flex items-center justify-center">
                        <canvas id="chartJurusan"></canvas>
                    </div>
                    <div class="grid grid-cols-2 gap-2 text-[11px] pt-2 border-t border-slate-100">
                        <div class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-indigo-600"></span> Rekayasa Perangkat Lunak (92%)</div>
                        <div class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-sky-500"></span> Teknik Komputer & Jaringan (88%)</div>
                        <div class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span> MIPA / Sains (84%)</div>
                        <div class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-amber-500"></span> Bisnis & Manajemen (80%)</div>
                    </div>
                </div>
            </div>

            <!-- Recent Transactions Table -->
            <div class="glass-card rounded-2xl shadow-sm overflow-hidden">
                <div class="p-5 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <div>
                        <h3 class="font-bold text-slate-900 text-sm">Transaksi Pembayaran Real-Time</h3>
                        <p class="text-xs text-slate-500">Update otomatis langsung dari Webhook Midtrans & loket kasir</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-xs text-slate-400">Status Gateway:</span>
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-ping"></span>
                            Online & Siap Transaksi
                        </span>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-slate-50/80 text-slate-500 font-semibold border-b border-slate-100">
                            <tr>
                                <th class="py-3 px-4">No. Ref / Order ID</th>
                                <th class="py-3 px-4">Nama Siswa & Kelas</th>
                                <th class="py-3 px-4">Bulan Tagihan</th>
                                <th class="py-3 px-4">Kanal Pembayaran</th>
                                <th class="py-3 px-4">Jumlah Bayar</th>
                                <th class="py-3 px-4">Waktu</th>
                                <th class="py-3 px-4">Status</th>
                                <th class="py-3 px-4 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100" id="table-recent-transactions">
                            <tr class="hover:bg-slate-50/50 transition">
                                <td class="py-3.5 px-4 font-mono font-medium text-indigo-600">PAY-202410-0982</td>
                                <td class="py-3.5 px-4">
                                    <div class="font-bold text-slate-900">Muhammad Rayhan Pratama</div>
                                    <div class="text-[11px] text-slate-400">NIS: 202410101 • XII-RPL-1</div>
                                </td>
                                <td class="py-3.5 px-4 font-semibold text-slate-700">Oktober 2024</td>
                                <td class="py-3.5 px-4">
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-purple-50 text-purple-700 font-medium text-[11px]">
                                        <i data-lucide="qr-code" class="w-3 h-3"></i> QRIS GoPay
                                    </span>
                                </td>
                                <td class="py-3.5 px-4 font-bold text-slate-900">Rp 350.000</td>
                                <td class="py-3.5 px-4 text-slate-500">20 Okt 2024, 20:15</td>
                                <td class="py-3.5 px-4">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">LUNAS</span>
                                </td>
                                <td class="py-3.5 px-4 text-center">
                                    <button onclick="printKwitansi('PAY-202410-0982', 'Muhammad Rayhan Pratama', 'XII-RPL-1', 'Rp 350.000', 'Oktober 2024', 'QRIS GoPay')" class="text-indigo-600 hover:text-indigo-800 p-1 rounded-md hover:bg-indigo-50" title="Cetak Kwitansi">
                                        <i data-lucide="printer" class="w-4 h-4"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr class="hover:bg-slate-50/50 transition">
                                <td class="py-3.5 px-4 font-mono font-medium text-indigo-600">PAY-202410-0981</td>
                                <td class="py-3.5 px-4">
                                    <div class="font-bold text-slate-900">Adinda Putri Maharani</div>
                                    <div class="text-[11px] text-slate-400">NIS: 202410145 • XI-MIPA-2</div>
                                </td>
                                <td class="py-3.5 px-4 font-semibold text-slate-700">Oktober 2024</td>
                                <td class="py-3.5 px-4">
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-sky-50 text-sky-700 font-medium text-[11px]">
                                        <i data-lucide="building-2" class="w-3 h-3"></i> BCA Virtual Account
                                    </span>
                                </td>
                                <td class="py-3.5 px-4 font-bold text-slate-900">Rp 300.000</td>
                                <td class="py-3.5 px-4 text-slate-500">20 Okt 2024, 19:42</td>
                                <td class="py-3.5 px-4">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">LUNAS</span>
                                </td>
                                <td class="py-3.5 px-4 text-center">
                                    <button onclick="printKwitansi('PAY-202410-0981', 'Adinda Putri Maharani', 'XI-MIPA-2', 'Rp 300.000', 'Oktober 2024', 'BCA Virtual Account')" class="text-indigo-600 hover:text-indigo-800 p-1 rounded-md hover:bg-indigo-50" title="Cetak Kwitansi">
                                        <i data-lucide="printer" class="w-4 h-4"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr class="hover:bg-slate-50/50 transition">
                                <td class="py-3.5 px-4 font-mono font-medium text-indigo-600">PAY-202410-0980</td>
                                <td class="py-3.5 px-4">
                                    <div class="font-bold text-slate-900">Farhan Rizky Maulana</div>
                                    <div class="text-[11px] text-slate-400">NIS: 202410189 • X-TKJ-1</div>
                                </td>
                                <td class="py-3.5 px-4 font-semibold text-slate-700">Okt & Nov 2024 (2 Bln)</td>
                                <td class="py-3.5 px-4">
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-emerald-50 text-emerald-700 font-medium text-[11px]">
                                        <i data-lucide="banknote" class="w-3 h-3"></i> Mandiri VA
                                    </span>
                                </td>
                                <td class="py-3.5 px-4 font-bold text-slate-900">Rp 700.000</td>
                                <td class="py-3.5 px-4 text-slate-500">20 Okt 2024, 18:20</td>
                                <td class="py-3.5 px-4">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">LUNAS</span>
                                </td>
                                <td class="py-3.5 px-4 text-center">
                                    <button onclick="printKwitansi('PAY-202410-0980', 'Farhan Rizky Maulana', 'X-TKJ-1', 'Rp 700.000', 'Okt & Nov 2024', 'Mandiri VA')" class="text-indigo-600 hover:text-indigo-800 p-1 rounded-md hover:bg-indigo-50" title="Cetak Kwitansi">
                                        <i data-lucide="printer" class="w-4 h-4"></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- ========================================== -->
        <!-- VIEW 2: PORTAL BAYAR SISWA (SISWA/WALI)   -->
        <!-- ========================================== -->
        <div id="view-bayar" class="hidden space-y-6">
            
            <!-- Student Header & Lookup -->
            <div class="glass-card p-6 rounded-2xl shadow-sm space-y-5">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div>
                        <h2 class="text-lg font-bold text-slate-900">Portal Siswa & Pembayaran SPP Online</h2>
                        <p class="text-xs text-slate-500">Cek daftar tagihan bulanan dan bayar langsung lewat Midtrans Payment Gateway</p>
                    </div>

                    <!-- NIS Search Form -->
                    <div class="flex items-center gap-2">
                        <div class="relative">
                            <i data-lucide="search" class="w-4 h-4 text-slate-400 absolute left-3 top-3"></i>
                            <input type="text" id="input-nis" value="202410101" placeholder="Masukkan NIS Siswa..." class="pl-9 pr-3 py-2 text-xs bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 w-52 font-medium">
                        </div>
                        <button onclick="cariSiswa()" class="px-3.5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold transition">
                            Cari Data
                        </button>
                    </div>
                </div>

                <!-- Student Information Card -->
                <div class="p-4 rounded-xl bg-gradient-to-r from-slate-50 via-indigo-50/30 to-purple-50/30 border border-indigo-100 flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div class="flex items-center space-x-4">
                        <div class="w-14 h-14 rounded-2xl bg-indigo-600 text-white font-bold text-lg flex items-center justify-center shadow-md shadow-indigo-200">
                            MR
                        </div>
                        <div>
                            <div class="flex items-center gap-2">
                                <h3 class="font-extrabold text-slate-900 text-base" id="student-name">Muhammad Rayhan Pratama</h3>
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800">Siswa Aktif</span>
                            </div>
                            <p class="text-xs text-slate-500 mt-0.5">
                                NIS: <span class="font-semibold text-slate-700" id="student-nis">202410101</span> • 
                                NISN: <span class="font-semibold text-slate-700">0061234567</span> • 
                                Kelas: <span class="font-bold text-indigo-700" id="student-class">XII Rekayasa Perangkat Lunak 1</span>
                            </p>
                            <p class="text-[11px] text-slate-400 mt-0.5">Wali Siswa: Bapak Bambang Pratama (0812-9876-5432)</p>
                        </div>
                    </div>

                    <div class="flex md:flex-col items-center md:items-end justify-between border-t md:border-t-0 pt-3 md:pt-0 border-slate-200">
                        <span class="text-xs text-slate-500">Tarif SPP Bulanan</span>
                        <span class="text-lg font-extrabold text-slate-900">Rp 350.000 <span class="text-xs font-normal text-slate-400">/ bln</span></span>
                    </div>
                </div>
            </div>

            <!-- Bill List & Checkout -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Bills Table (2 Cols) -->
                <div class="lg:col-span-2 glass-card rounded-2xl shadow-sm overflow-hidden space-y-4 p-5">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="font-bold text-slate-900 text-sm">Daftar Tagihan Tahun Ajaran 2024/2025</h3>
                            <p class="text-xs text-slate-500">Centang bulan yang ingin dibayarkan secara bersamaan</p>
                        </div>
                        <button onclick="selectAllBills()" class="text-xs text-indigo-600 hover:text-indigo-800 font-semibold">
                            Pilih Semua Tagihan
                        </button>
                    </div>

                    <div class="space-y-2.5">
                        <!-- Bill Item 1 (Paid) -->
                        <div class="p-3.5 rounded-xl border border-slate-100 bg-slate-50/50 flex items-center justify-between opacity-80">
                            <div class="flex items-center space-x-3">
                                <div class="w-5 h-5 rounded-full bg-emerald-500 text-white flex items-center justify-center text-[10px]">
                                    <i data-lucide="check" class="w-3.5 h-3.5"></i>
                                </div>
                                <div>
                                    <h4 class="font-bold text-xs text-slate-900">SPP Bulan Juli 2024</h4>
                                    <p class="text-[10px] text-slate-400">Lunas pada 08 Juli 2024 via BCA VA</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="text-xs font-bold text-slate-900">Rp 350.000</span>
                                <span class="block text-[10px] font-bold text-emerald-600">LUNAS</span>
                            </div>
                        </div>

                        <!-- Bill Item 2 (Paid) -->
                        <div class="p-3.5 rounded-xl border border-slate-100 bg-slate-50/50 flex items-center justify-between opacity-80">
                            <div class="flex items-center space-x-3">
                                <div class="w-5 h-5 rounded-full bg-emerald-500 text-white flex items-center justify-center text-[10px]">
                                    <i data-lucide="check" class="w-3.5 h-3.5"></i>
                                </div>
                                <div>
                                    <h4 class="font-bold text-xs text-slate-900">SPP Bulan Agustus 2024</h4>
                                    <p class="text-[10px] text-slate-400">Lunas pada 10 Agustus 2024 via QRIS</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="text-xs font-bold text-slate-900">Rp 350.000</span>
                                <span class="block text-[10px] font-bold text-emerald-600">LUNAS</span>
                            </div>
                        </div>

                        <!-- Bill Item 3 (Paid) -->
                        <div class="p-3.5 rounded-xl border border-slate-100 bg-slate-50/50 flex items-center justify-between opacity-80">
                            <div class="flex items-center space-x-3">
                                <div class="w-5 h-5 rounded-full bg-emerald-500 text-white flex items-center justify-center text-[10px]">
                                    <i data-lucide="check" class="w-3.5 h-3.5"></i>
                                </div>
                                <div>
                                    <h4 class="font-bold text-xs text-slate-900">SPP Bulan September 2024</h4>
                                    <p class="text-[10px] text-slate-400">Lunas pada 09 September 2024 via Tunai</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="text-xs font-bold text-slate-900">Rp 350.000</span>
                                <span class="block text-[10px] font-bold text-emerald-600">LUNAS</span>
                            </div>
                        </div>

                        <!-- Bill Item 4 (Unpaid - Checked) -->
                        <div class="p-3.5 rounded-xl border-2 border-indigo-500 bg-indigo-50/20 flex items-center justify-between transition">
                            <div class="flex items-center space-x-3">
                                <input type="checkbox" id="bill-okt" checked onchange="calculateTotal()" class="w-4 h-4 rounded text-indigo-600 focus:ring-indigo-500 border-slate-300">
                                <div>
                                    <h4 class="font-bold text-xs text-slate-900">SPP Bulan Oktober 2024</h4>
                                    <p class="text-[10px] text-rose-600 font-medium">Jatuh Tempo: 10 Oktober 2024 (Lewat)</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="text-xs font-bold text-slate-900">Rp 350.000</span>
                                <span class="block text-[10px] font-bold text-rose-600">BELUM DIBAYAR</span>
                            </div>
                        </div>

                        <!-- Bill Item 5 (Unpaid - Unchecked) -->
                        <div class="p-3.5 rounded-xl border border-slate-200 hover:border-indigo-300 bg-white flex items-center justify-between transition">
                            <div class="flex items-center space-x-3">
                                <input type="checkbox" id="bill-nov" onchange="calculateTotal()" class="w-4 h-4 rounded text-indigo-600 focus:ring-indigo-500 border-slate-300">
                                <div>
                                    <h4 class="font-bold text-xs text-slate-900">SPP Bulan November 2024</h4>
                                    <p class="text-[10px] text-slate-500">Jatuh Tempo: 10 November 2024</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="text-xs font-bold text-slate-900">Rp 350.000</span>
                                <span class="block text-[10px] font-bold text-amber-600">MENUNGGU</span>
                            </div>
                        </div>

                        <!-- Bill Item 6 (Unpaid - Unchecked) -->
                        <div class="p-3.5 rounded-xl border border-slate-200 hover:border-indigo-300 bg-white flex items-center justify-between transition">
                            <div class="flex items-center space-x-3">
                                <input type="checkbox" id="bill-des" onchange="calculateTotal()" class="w-4 h-4 rounded text-indigo-600 focus:ring-indigo-500 border-slate-300">
                                <div>
                                    <h4 class="font-bold text-xs text-slate-900">SPP Bulan Desember 2024</h4>
                                    <p class="text-[10px] text-slate-500">Jatuh Tempo: 10 Desember 2024</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="text-xs font-bold text-slate-900">Rp 350.000</span>
                                <span class="block text-[10px] font-bold text-slate-400">BELUM AKTIF</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Checkout Summary Card (1 Col) -->
                <div class="glass-card rounded-2xl shadow-sm p-5 space-y-5 h-fit">
                    <h3 class="font-bold text-slate-900 text-sm flex items-center gap-2">
                        <i data-lucide="receipt" class="w-4 h-4 text-indigo-600"></i>
                        Ringkasan Pembayaran
                    </h3>

                    <div class="space-y-3 text-xs border-b border-slate-100 pb-4">
                        <div class="flex justify-between text-slate-600">
                            <span id="selected-months-text">1 Bulan Tagihan Terpilih</span>
                            <span class="font-semibold text-slate-900" id="subtotal-amount">Rp 350.000</span>
                        </div>
                        <div class="flex justify-between text-slate-600">
                            <span>Biaya Admin Gateway</span>
                            <span class="font-semibold text-emerald-600">GRATIS</span>
                        </div>
                    </div>

                    <div class="flex items-center justify-between">
                        <span class="text-xs font-semibold text-slate-500">Total Tagihan</span>
                        <span class="text-xl font-extrabold text-indigo-600" id="total-amount">Rp 350.000</span>
                    </div>

                    <!-- Payment Method Icons -->
                    <div class="p-3 bg-slate-50 rounded-xl space-y-2 border border-slate-100">
                        <span class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider block">Metode Pembayaran Online:</span>
                        <div class="flex items-center gap-2 flex-wrap">
                            <span class="px-2 py-1 bg-white border border-slate-200 rounded text-[10px] font-bold text-indigo-900">BCA VA</span>
                            <span class="px-2 py-1 bg-white border border-slate-200 rounded text-[10px] font-bold text-sky-800">Mandiri</span>
                            <span class="px-2 py-1 bg-white border border-slate-200 rounded text-[10px] font-bold text-blue-900">BRI</span>
                            <span class="px-2 py-1 bg-white border border-slate-200 rounded text-[10px] font-bold text-purple-700">QRIS</span>
                            <span class="px-2 py-1 bg-white border border-slate-200 rounded text-[10px] font-bold text-emerald-700">GoPay</span>
                        </div>
                    </div>

                    <!-- Pay Button -->
                    <button onclick="openMidtransSnapModal()" class="w-full py-3 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-bold rounded-xl text-xs shadow-lg shadow-indigo-500/25 transition active:scale-95 flex items-center justify-center gap-2">
                        <i data-lucide="shield-check" class="w-4 h-4"></i>
                        Bayar Sekarang via Midtrans
                    </button>

                    <p class="text-[10px] text-center text-slate-400">
                        Transaksi terenkripsi 256-bit SSL dan terverifikasi otomatis dalam 1-5 detik.
                    </p>
                </div>
            </div>
        </div>

        <!-- ========================================== -->
        <!-- VIEW 3: LAPORAN & REKAP KEUANGAN           -->
        <!-- ========================================== -->
        <div id="view-laporan" class="hidden space-y-6">
            
            <div class="glass-card p-6 rounded-2xl shadow-sm space-y-5">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div>
                        <h2 class="text-lg font-bold text-slate-900">Rekapitulasi & Laporan Pembayaran SPP</h2>
                        <p class="text-xs text-slate-500">Filter berdasarkan kelas, jurusan, periode pembayaran, atau status kelunasan</p>
                    </div>

                    <div class="flex items-center gap-2">
                        <button onclick="triggerNotification('Laporan Keuangan SPP berhasil diekspor ke Microsoft Excel (.xlsx)')" class="px-3 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold transition flex items-center gap-1.5 shadow-sm">
                            <i data-lucide="sheet" class="w-4 h-4"></i> Export Excel
                        </button>
                        <button onclick="window.print()" class="px-3 py-2 bg-slate-800 hover:bg-slate-900 text-white rounded-xl text-xs font-bold transition flex items-center gap-1.5 shadow-sm">
                            <i data-lucide="printer" class="w-4 h-4"></i> Cetak PDF
                        </button>
                    </div>
                </div>

                <!-- Filters -->
                <div class="grid grid-cols-1 sm:grid-cols-4 gap-3 pt-3 border-t border-slate-100">
                    <div>
                        <label class="text-[11px] font-semibold text-slate-500 block mb-1">Jurusan</label>
                        <select class="w-full text-xs bg-slate-50 border border-slate-200 rounded-lg p-2 font-medium focus:ring-2 focus:ring-indigo-500">
                            <option>Semua Jurusan</option>
                            <option>Rekayasa Perangkat Lunak (RPL)</option>
                            <option>Teknik Komputer & Jaringan (TKJ)</option>
                            <option>Matematika & IPA (MIPA)</option>
                            <option>Ilmu Pengetahuan Sosial (IPS)</option>
                        </select>
                    </div>
                    <div>
                        <label class="text-[11px] font-semibold text-slate-500 block mb-1">Tingkat Kelas</label>
                        <select class="w-full text-xs bg-slate-50 border border-slate-200 rounded-lg p-2 font-medium focus:ring-2 focus:ring-indigo-500">
                            <option>Semua Kelas (X, XI, XII)</option>
                            <option>Kelas X (Sepuluh)</option>
                            <option>Kelas XI (Sebelas)</option>
                            <option>Kelas XII (Dua Belas)</option>
                        </select>
                    </div>
                    <div>
                        <label class="text-[11px] font-semibold text-slate-500 block mb-1">Periode Bulan</label>
                        <select class="w-full text-xs bg-slate-50 border border-slate-200 rounded-lg p-2 font-medium focus:ring-2 focus:ring-indigo-500">
                            <option>Oktober 2024</option>
                            <option>September 2024</option>
                            <option>Agustus 2024</option>
                            <option>Juli 2024</option>
                        </select>
                    </div>
                    <div>
                        <label class="text-[11px] font-semibold text-slate-500 block mb-1">Status Bayar</label>
                        <select class="w-full text-xs bg-slate-50 border border-slate-200 rounded-lg p-2 font-medium focus:ring-2 focus:ring-indigo-500">
                            <option>Semua Status</option>
                            <option>Lunas</option>
                            <option>Belum Lunas</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Report Summary Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="p-4 rounded-xl bg-white border border-slate-200">
                    <span class="text-xs text-slate-500">Total Siswa Terdata</span>
                    <p class="text-xl font-extrabold text-slate-900 mt-1">1.240 Siswa</p>
                </div>
                <div class="p-4 rounded-xl bg-emerald-50/50 border border-emerald-100">
                    <span class="text-xs text-emerald-700">Sudah Lunas (Bulan Ini)</span>
                    <p class="text-xl font-extrabold text-emerald-700 mt-1">1.098 Siswa (88.5%)</p>
                </div>
                <div class="p-4 rounded-xl bg-rose-50/50 border border-rose-100">
                    <span class="text-xs text-rose-700">Menunggak (Bulan Ini)</span>
                    <p class="text-xl font-extrabold text-rose-700 mt-1">142 Siswa (11.5%)</p>
                </div>
            </div>

            <!-- Full Report Table -->
            <div class="glass-card rounded-2xl shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-slate-50 text-slate-500 font-semibold border-b border-slate-200">
                            <tr>
                                <th class="py-3 px-4">No</th>
                                <th class="py-3 px-4">NIS</th>
                                <th class="py-3 px-4">Nama Siswa</th>
                                <th class="py-3 px-4">Kelas & Jurusan</th>
                                <th class="py-3 px-4">Nominal SPP</th>
                                <th class="py-3 px-4">Status SPP Okt</th>
                                <th class="py-3 px-4">Metode Bayar</th>
                                <th class="py-3 px-4">Tanggal Transaksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <tr class="hover:bg-slate-50/50">
                                <td class="py-3 px-4 text-slate-400">1</td>
                                <td class="py-3 px-4 font-mono font-medium">202410101</td>
                                <td class="py-3 px-4 font-bold text-slate-900">Muhammad Rayhan Pratama</td>
                                <td class="py-3 px-4">XII RPL 1</td>
                                <td class="py-3 px-4">Rp 350.000</td>
                                <td class="py-3 px-4"><span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-100 text-emerald-800">LUNAS</span></td>
                                <td class="py-3 px-4">QRIS Midtrans</td>
                                <td class="py-3 px-4 text-slate-500">20/10/2024 20:15</td>
                            </tr>
                            <tr class="hover:bg-slate-50/50">
                                <td class="py-3 px-4 text-slate-400">2</td>
                                <td class="py-3 px-4 font-mono font-medium">202410102</td>
                                <td class="py-3 px-4 font-bold text-slate-900">Ahmad Zulfikar Syah</td>
                                <td class="py-3 px-4">XII RPL 1</td>
                                <td class="py-3 px-4">Rp 350.000</td>
                                <td class="py-3 px-4"><span class="px-2 py-0.5 rounded text-[10px] font-bold bg-rose-100 text-rose-800">BELUM LUNAS</span></td>
                                <td class="py-3 px-4 text-slate-400">-</td>
                                <td class="py-3 px-4 text-slate-400">-</td>
                            </tr>
                            <tr class="hover:bg-slate-50/50">
                                <td class="py-3 px-4 text-slate-400">3</td>
                                <td class="py-3 px-4 font-mono font-medium">202410145</td>
                                <td class="py-3 px-4 font-bold text-slate-900">Adinda Putri Maharani</td>
                                <td class="py-3 px-4">XI MIPA 2</td>
                                <td class="py-3 px-4">Rp 300.000</td>
                                <td class="py-3 px-4"><span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-100 text-emerald-800">LUNAS</span></td>
                                <td class="py-3 px-4">BCA Virtual Account</td>
                                <td class="py-3 px-4 text-slate-500">20/10/2024 19:42</td>
                            </tr>
                            <tr class="hover:bg-slate-50/50">
                                <td class="py-3 px-4 text-slate-400">4</td>
                                <td class="py-3 px-4 font-mono font-medium">202410189</td>
                                <td class="py-3 px-4 font-bold text-slate-900">Farhan Rizky Maulana</td>
                                <td class="py-3 px-4">X TKJ 1</td>
                                <td class="py-3 px-4">Rp 350.000</td>
                                <td class="py-3 px-4"><span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-100 text-emerald-800">LUNAS</span></td>
                                <td class="py-3 px-4">Mandiri VA</td>
                                <td class="py-3 px-4 text-slate-500">20/10/2024 18:20</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-slate-200 mt-auto py-5">
        <div class="max-w-7xl mx-auto px-4 text-center text-xs text-slate-500 flex flex-col sm:flex-row items-center justify-between gap-2">
            <span>&copy; 2024 EduPay SPP • SMK Negeri 1 Teknologi & Bisnis. Seluruh hak cipta dilindungi.</span>
            <span class="text-slate-400">Laravel 11 • MySQL 8 • Tailwind CSS • Midtrans Snap Engine</span>
        </div>
    </footer>

    <!-- ======================================================== -->
    <!-- MODAL SIMULASI MIDTRANS SNAP PAYMENT GATEWAY POPUP       -->
    <!-- ======================================================== -->
    <div id="modal-midtrans" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm hidden flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl max-w-md w-full shadow-2xl overflow-hidden border border-slate-200 animate-in fade-in zoom-in duration-200">
            <!-- Modal Header -->
            <div class="bg-gradient-to-r from-indigo-700 to-purple-800 p-5 text-white flex items-center justify-between">
                <div class="flex items-center space-x-2.5">
                    <div class="w-8 h-8 rounded-lg bg-white/10 flex items-center justify-center">
                        <i data-lucide="shield" class="w-5 h-5 text-emerald-400"></i>
                    </div>
                    <div>
                        <h4 class="font-extrabold text-sm">Midtrans Snap Checkout</h4>
                        <p class="text-[10px] text-indigo-200">Payment Gateway Resmi EduPay</p>
                    </div>
                </div>
                <button onclick="closeMidtransModal()" class="w-7 h-7 rounded-full bg-white/10 hover:bg-white/20 flex items-center justify-center text-white transition">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="p-6 space-y-5">
                <!-- Order Summary -->
                <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100 flex items-center justify-between">
                    <div>
                        <span class="text-[11px] text-slate-500">Order ID: <span class="font-mono font-bold text-slate-800" id="snap-order-id">PAY-202410-9881</span></span>
                        <p class="font-bold text-sm text-slate-900" id="snap-student">Muhammad Rayhan Pratama</p>
                        <p class="text-[11px] text-slate-500" id="snap-bills">SPP Bulan Oktober 2024</p>
                    </div>
                    <div class="text-right">
                        <span class="text-[10px] text-slate-400">Total Tagihan</span>
                        <p class="text-lg font-extrabold text-indigo-700" id="snap-total">Rp 350.000</p>
                    </div>
                </div>

                <!-- Payment Channels Selection -->
                <div class="space-y-2">
                    <label class="text-xs font-bold text-slate-700">Pilih Metode Pembayaran:</label>
                    
                    <!-- Channel 1: QRIS -->
                    <label class="flex items-center justify-between p-3 rounded-xl border-2 border-indigo-500 bg-indigo-50/30 cursor-pointer hover:border-indigo-600 transition">
                        <div class="flex items-center space-x-3">
                            <input type="radio" name="pay_channel" value="QRIS GoPay / DANA / OVO" checked class="text-indigo-600 focus:ring-indigo-500">
                            <div>
                                <span class="text-xs font-bold text-slate-900 block">QRIS (GoPay, OVO, Dana, LinkAja)</span>
                                <span class="text-[10px] text-emerald-600 font-medium">Bebas antre, scan & lunas seketika</span>
                            </div>
                        </div>
                        <i data-lucide="qr-code" class="w-5 h-5 text-indigo-600"></i>
                    </label>

                    <!-- Channel 2: BCA Virtual Account -->
                    <label class="flex items-center justify-between p-3 rounded-xl border border-slate-200 hover:border-indigo-300 cursor-pointer transition">
                        <div class="flex items-center space-x-3">
                            <input type="radio" name="pay_channel" value="BCA Virtual Account" class="text-indigo-600 focus:ring-indigo-500">
                            <div>
                                <span class="text-xs font-bold text-slate-900 block">BCA Virtual Account</span>
                                <span class="text-[10px] text-slate-400">Transfer m-BCA / KlikBCA / ATM</span>
                            </div>
                        </div>
                        <span class="font-bold text-xs text-indigo-900">BCA</span>
                    </label>

                    <!-- Channel 3: Mandiri / BRI VA -->
                    <label class="flex items-center justify-between p-3 rounded-xl border border-slate-200 hover:border-indigo-300 cursor-pointer transition">
                        <div class="flex items-center space-x-3">
                            <input type="radio" name="pay_channel" value="Mandiri Virtual Account" class="text-indigo-600 focus:ring-indigo-500">
                            <div>
                                <span class="text-xs font-bold text-slate-900 block">Bank Mandiri / BRI Virtual Account</span>
                                <span class="text-[10px] text-slate-400">Livin' by Mandiri / BRImo</span>
                            </div>
                        </div>
                        <span class="font-bold text-xs text-blue-900">MANDIRI</span>
                    </label>
                </div>

                <!-- Simulation Action Button -->
                <button onclick="prosesSimulasiBayar()" id="btn-submit-pay" class="w-full py-3.5 bg-emerald-600 hover:bg-emerald-700 active:scale-95 text-white font-bold rounded-2xl text-xs shadow-lg shadow-emerald-600/25 transition flex items-center justify-center gap-2">
                    <i data-lucide="check-circle" class="w-4 h-4"></i>
                    Konfirmasi & Bayar Sekarang
                </button>
            </div>
        </div>
    </div>

    <!-- Notification Toast -->
    <div id="toast" class="fixed bottom-5 right-5 z-50 bg-slate-900 text-white px-4 py-3 rounded-2xl shadow-xl flex items-center gap-3 text-xs font-medium translate-y-24 opacity-0 transition-all duration-300">
        <i data-lucide="bell" class="w-4 h-4 text-indigo-400"></i>
        <span id="toast-text">Pesan notifikasi</span>
    </div>

    <!-- Scripts Logic -->
    <script>
        // Init Lucide Icons
        lucide.createIcons();

        // View Switcher
        function switchView(viewName) {
            document.getElementById('view-dashboard').classList.add('hidden');
            document.getElementById('view-bayar').classList.add('hidden');
            document.getElementById('view-laporan').classList.add('hidden');

            // Reset tab button states
            ['dashboard', 'bayar', 'laporan'].forEach(tab => {
                const btn = document.getElementById('btn-tab-' + tab);
                btn.classList.remove('bg-white', 'text-indigo-600', 'shadow-sm');
                btn.classList.add('text-slate-600');
            });

            // Activate chosen view
            document.getElementById('view-' + viewName).classList.remove('hidden');
            const activeBtn = document.getElementById('btn-tab-' + viewName);
            activeBtn.classList.add('bg-white', 'text-indigo-600', 'shadow-sm');
            activeBtn.classList.remove('text-slate-600');

            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        // Total calculation for bill checkboxes
        function calculateTotal() {
            let total = 0;
            let count = 0;
            const okt = document.getElementById('bill-okt');
            const nov = document.getElementById('bill-nov');
            const des = document.getElementById('bill-des');

            if (okt && okt.checked) { total += 350000; count++; }
            if (nov && nov.checked) { total += 350000; count++; }
            if (des && des.checked) { total += 350000; count++; }

            const formatted = 'Rp ' + total.toLocaleString('id-ID');
            document.getElementById('subtotal-amount').innerText = formatted;
            document.getElementById('total-amount').innerText = formatted;
            document.getElementById('selected-months-text').innerText = count + ' Bulan Tagihan Terpilih';
            document.getElementById('snap-total').innerText = formatted;
        }

        function selectAllBills() {
            ['bill-okt', 'bill-nov', 'bill-des'].forEach(id => {
                const el = document.getElementById(id);
                if (el) el.checked = true;
            });
            calculateTotal();
            triggerNotification('Semua tagihan aktif telah dicentang!');
        }

        // Student Search Simulation
        function cariSiswa() {
            const nis = document.getElementById('input-nis').value.trim();
            if (nis === '202410101') {
                document.getElementById('student-name').innerText = 'Muhammad Rayhan Pratama';
                document.getElementById('student-class').innerText = 'XII Rekayasa Perangkat Lunak 1';
                triggerNotification('Data siswa Muhammad Rayhan Pratama berhasil dimuat!');
            } else if (nis === '202410145') {
                document.getElementById('student-name').innerText = 'Adinda Putri Maharani';
                document.getElementById('student-class').innerText = 'XI MIPA 2';
                triggerNotification('Data siswa Adinda Putri Maharani berhasil dimuat!');
            } else {
                triggerNotification('Siswa dengan NIS ' + nis + ' ditemukan!');
            }
        }

        // Midtrans Modal
        function openMidtransSnapModal() {
            const randomId = 'PAY-202410-' + Math.floor(1000 + Math.random() * 9000);
            document.getElementById('snap-order-id').innerText = randomId;
            document.getElementById('modal-midtrans').classList.remove('hidden');
        }

        function closeMidtransModal() {
            document.getElementById('modal-midtrans').classList.add('hidden');
        }

        function prosesSimulasiBayar() {
            const btn = document.getElementById('btn-submit-pay');
            const selectedChannel = document.querySelector('input[name="pay_channel"]:checked').value;
            const orderId = document.getElementById('snap-order-id').innerText;
            const total = document.getElementById('snap-total').innerText;

            btn.innerHTML = '<span class="animate-spin mr-2">⏳</span> Memproses Payment Gateway...';
            btn.disabled = true;

            setTimeout(() => {
                closeMidtransModal();
                btn.innerHTML = '<i data-lucide="check-circle" class="w-4 h-4"></i> Konfirmasi & Bayar Sekarang';
                btn.disabled = false;
                lucide.createIcons();

                // Add to recent transaction table
                const table = document.getElementById('table-recent-transactions');
                const row = document.createElement('tr');
                row.className = 'hover:bg-slate-50/50 transition bg-emerald-50/30';
                row.innerHTML = `
                    <td class="py-3.5 px-4 font-mono font-medium text-indigo-600">${orderId}</td>
                    <td class="py-3.5 px-4">
                        <div class="font-bold text-slate-900">Muhammad Rayhan Pratama</div>
                        <div class="text-[11px] text-slate-400">NIS: 202410101 • XII-RPL-1</div>
                    </td>
                    <td class="py-3.5 px-4 font-semibold text-slate-700">Oktober 2024</td>
                    <td class="py-3.5 px-4">
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-purple-50 text-purple-700 font-medium text-[11px]">
                            ${selectedChannel}
                        </span>
                    </td>
                    <td class="py-3.5 px-4 font-bold text-slate-900">${total}</td>
                    <td class="py-3.5 px-4 text-slate-500">Baru saja</td>
                    <td class="py-3.5 px-4">
                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">LUNAS</span>
                    </td>
                    <td class="py-3.5 px-4 text-center">
                        <button onclick="printKwitansi('${orderId}', 'Muhammad Rayhan Pratama', 'XII-RPL-1', '${total}', 'Oktober 2024', '${selectedChannel}')" class="text-indigo-600 hover:text-indigo-800 p-1 rounded-md hover:bg-indigo-50">
                            <i data-lucide="printer" class="w-4 h-4"></i>
                        </button>
                    </td>
                `;
                table.prepend(row);
                lucide.createIcons();

                // Uncheck paid bill
                const okt = document.getElementById('bill-okt');
                if (okt) {
                    okt.checked = false;
                    okt.disabled = true;
                    okt.closest('.rounded-xl').classList.replace('border-indigo-500', 'border-slate-200');
                    okt.closest('.rounded-xl').classList.replace('bg-indigo-50/20', 'bg-slate-50/50');
                    okt.closest('.rounded-xl').querySelector('.text-rose-600').innerText = 'LUNAS (Baru Saja Dibayar)';
                    okt.closest('.rounded-xl').querySelector('.text-rose-600').className = 'block text-[10px] font-bold text-emerald-600';
                }
                calculateTotal();

                triggerNotification('✅ Pembayaran Berhasil! Tagihan SPP otomatis berstatus Lunas.');
                printKwitansi(orderId, 'Muhammad Rayhan Pratama', 'XII-RPL-1', total, 'Oktober 2024', selectedChannel);
            }, 1200);
        }

        // Kwitansi PDF Simulation
        function printKwitansi(orderId, name, kelas, amount, bulan, method) {
            const printWindow = window.open('', '_blank');
            printWindow.document.write(`
                <html>
                <head>
                    <title>Kwitansi Pembayaran SPP - ${orderId}</title>
                    <style>
                        body { font-family: Arial, sans-serif; padding: 40px; color: #1e293b; }
                        .header { border-bottom: 2px solid #0f172a; padding-bottom: 15px; margin-bottom: 25px; display: flex; justify-content: space-between; }
                        .title { font-size: 20px; font-weight: bold; }
                        .sub { font-size: 12px; color: #64748b; }
                        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
                        th, td { padding: 10px; border: 1px solid #cbd5e1; text-align: left; font-size: 13px; }
                        th { background: #f1f5f9; }
                        .total { font-size: 16px; font-weight: bold; color: #4338ca; }
                        .stamp { margin-top: 30px; text-align: right; }
                        .status { display: inline-block; padding: 4px 12px; background: #dcfce7; color: #15803d; border-radius: 99px; font-weight: bold; font-size: 12px; }
                    </style>
                </head>
                <body>
                    <div class="header">
                        <div>
                            <div class="title">SMK NEGERI 1 TEKNOLOGI & BISNIS</div>
                            <div class="sub">Jl. Pendidikan No. 45 • Tlp: (021) 8876-1234 • Email: info@smkn1.sch.id</div>
                        </div>
                        <div style="text-align: right;">
                            <span class="status">LUNAS / VERIFIED</span>
                            <div class="sub" style="margin-top: 5px;">No Ref: ${orderId}</div>
                        </div>
                    </div>

                    <h3>BUKTI PEMBAYARAN SPP SEKOLAH RESMI</h3>
                    <table>
                        <tr><td width="30%">Nama Siswa</td><td><b>${name}</b></td></tr>
                        <tr><td>Kelas / Jurusan</td><td>${kelas}</td></tr>
                        <tr><td>Bulan Pembayaran</td><td>${bulan}</td></tr>
                        <tr><td>Metode Bayar</td><td>${method} (Midtrans Payment Gateway)</td></tr>
                        <tr><td>Waktu Pembayaran</td><td>${new Date().toLocaleString('id-ID')}</td></tr>
                        <tr><td class="total">Total Dibayar</td><td class="total">${amount}</td></tr>
                    </table>

                    <div class="stamp">
                        <p class="sub">Diterbitkan secara digital oleh Sistem EduPay SPP</p>
                        <p><b>Bendahara Sekolah</b></p>
                        <br><br>
                        <p><u>Hj. Siti Rahmawati, S.E.</u><br><small>NIP. 19780512 200312 2 001</small></p>
                    </div>
                    <script>window.onload = function() { window.print(); }<\/script>
                </body>
                </html>
            `);
            printWindow.document.close();
        }

        // Toast Notification
        function triggerNotification(msg) {
            const toast = document.getElementById('toast');
            document.getElementById('toast-text').innerText = msg;
            toast.classList.remove('translate-y-24', 'opacity-0');
            setTimeout(() => {
                toast.classList.add('translate-y-24', 'opacity-0');
            }, 3500);
        }

        // Initialize Charts on Load
        window.addEventListener('DOMContentLoaded', () => {
            // Chart 1: Revenue Line Chart
            const ctxRev = document.getElementById('chartRevenue').getContext('2d');
            new Chart(ctxRev, {
                type: 'line',
                data: {
                    labels: ['Mei 24', 'Jun 24', 'Jul 24', 'Agt 24', 'Sep 24', 'Okt 24'],
                    datasets: [
                        {
                            label: 'Realisasi Kas Masuk (Juta Rp)',
                            data: [310, 325, 360, 368, 380, 372.5],
                            borderColor: '#4f46e5',
                            backgroundColor: 'rgba(79, 70, 229, 0.08)',
                            fill: true,
                            tension: 0.35,
                            borderWidth: 3,
                            pointBackgroundColor: '#4f46e5',
                            pointRadius: 4
                        },
                        {
                            label: 'Target Anggaran (Juta Rp)',
                            data: [350, 350, 400, 400, 400, 400],
                            borderColor: '#cbd5e1',
                            borderDash: [5, 5],
                            tension: 0,
                            borderWidth: 2,
                            pointRadius: 0
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'bottom', labels: { boxWidth: 12, font: { size: 11 } } }
                    },
                    scales: {
                        y: {
                            grid: { color: '#f1f5f9' },
                            ticks: { callback: val => 'Rp ' + val + ' Jt', font: { size: 10 } }
                        },
                        x: {
                            grid: { display: false },
                            ticks: { font: { size: 10 } }
                        }
                    }
                }
            });

            // Chart 2: Jurusan Donut Chart
            const ctxJur = document.getElementById('chartJurusan').getContext('2d');
            new Chart(ctxJur, {
                type: 'doughnut',
                data: {
                    labels: ['RPL', 'TKJ', 'MIPA', 'Bisnis'],
                    datasets: [{
                        data: [92, 88, 84, 80],
                        backgroundColor: ['#4f46e5', '#0ea5e9', '#10b981', '#f59e0b'],
                        borderWidth: 0,
                        hoverOffset: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '72%',
                    plugins: {
                        legend: { display: false }
                    }
                }
            });
        });
    </script>
</body>
</html>
