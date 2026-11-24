<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel') — BookCat Bakery & Catering</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #E3EE9D;
            color: #1F3D11;
        }
        .font-brand {
            font-family: 'Fredoka', sans-serif;
        }
        @media print {
            .no-print { display: none !important; }
            body { background-color: #FFFFFF !important; }
            .print-shadow-none { box-shadow: none !important; border: 1px solid #ccc !important; }
        }
    </style>
</head>
<body class="min-h-screen flex flex-col justify-between">

    {{-- Top Navbar ala BookCat --}}
    <header class="no-print sticky top-0 z-40 bg-[#E3EE9D]/95 backdrop-blur border-b border-[#477A25]/25">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">

            {{-- logo BookCat --}}
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 group">
                <div class="w-10 h-10 rounded-xl bg-[#477A25] text-[#E3EE9D] flex items-center justify-center shadow-sm group-hover:scale-105 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6l3 3h10l3-3v11a3 3 0 01-3 3H7a3 3 0 01-3-3V6z"/>
                        <circle cx="9.5" cy="13" r="1" fill="currentColor"/>
                        <circle cx="14.5" cy="13" r="1" fill="currentColor"/>
                    </svg>
                </div>
                <div>
                    <span class="font-brand text-2xl font-bold text-[#2C5918] tracking-tight">BookCat</span>
                    <span class="ml-1.5 text-xs font-bold uppercase tracking-wider px-2.5 py-0.5 rounded-full bg-[#477A25] text-[#E3EE9D]">Admin</span>
                </div>
            </a>

            {{-- Navigasi Admin --}}
            <nav class="flex items-center gap-2 sm:gap-6">
                <a href="{{ route('admin.dashboard') }}"
                   class="px-4 py-2 rounded-full text-sm font-semibold transition {{ request()->routeIs('admin.dashboard') || request()->routeIs('admin.menus.*') ? 'bg-[#477A25] text-[#E3EE9D]' : 'text-[#2C5918] hover:bg-[#B5D352]/60' }}">
                    Dashboard & Menu
                </a>
                <a href="{{ route('admin.report') }}"
                   class="px-4 py-2 rounded-full text-sm font-semibold transition {{ request()->routeIs('admin.report') ? 'bg-[#477A25] text-[#E3EE9D]' : 'text-[#2C5918] hover:bg-[#B5D352]/60' }}">
                    Laporan Penjualan
                </a>
                <a href="{{ url('/') }}"
                   class="hidden sm:inline-flex items-center gap-1.5 px-4 py-2 rounded-full text-sm font-semibold border border-[#477A25] text-[#2C5918] hover:bg-[#477A25] hover:text-white transition">
                    Lihat Web Publik ↗
                </a>
            </nav>
        </div>
    </header>

    {{-- Konten Utama --}}
    <main class="flex-grow max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @if(session('success'))
            <div class="no-print mb-6 px-5 py-4 rounded-2xl bg-[#2C5918] text-[#E3EE9D] font-medium flex items-center justify-between shadow-md">
                <div class="flex items-center gap-3">
                    <span class="w-6 h-6 rounded-full bg-[#B5D352] text-[#2C5918] flex items-center justify-center font-bold text-sm">✓</span>
                    <span>{{ session('success') }}</span>
                </div>
                <button onclick="this.parentElement.remove()" class="text-sm opacity-80 hover:opacity-100">✕</button>
            </div>
        @endif

        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="no-print max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 pb-8">
        <div class="rounded-3xl bg-[#2C5918] text-[#E3EE9D] px-8 py-6 flex flex-col sm:flex-row items-center justify-between gap-4">
            <div>
                <h4 class="font-brand text-xl text-[#B5D352]">BookCat Bakery & Catering</h4>
                <p class="text-xs text-[#E3EE9D]/80 mt-0.5">Panel Pengelola Katalog & Transaksi • Jl. Matcha Hijau No. 24, Jember</p>
            </div>
            <p class="text-xs text-[#E3EE9D]/75">© {{ date('Y') }} Kelompok 9 PWEB — Universitas Jember</p>
        </div>
    </footer>

</body>
</html>