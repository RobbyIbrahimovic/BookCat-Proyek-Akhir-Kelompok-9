@extends('layouts.admin')

@section('title', 'Dashboard Admin')

@section('content')
    {{-- Banner Header Deep Matcha --}}
    <div class="rounded-3xl bg-[#477A25] text-[#E3EE9D] p-6 sm:p-8 mb-8 shadow-md flex flex-col md:flex-row md:items-center md:justify-between gap-6">
        <div>
            <span class="inline-block px-3.5 py-1 rounded-full text-xs font-semibold border border-[#E3EE9D]/40 mb-3">
                Signature Matcha & Pistachio Catering
            </span>
            <h1 class="font-brand text-3xl sm:text-4xl text-[#E3EE9D]">Dashboard Pengelola BookCat</h1>
            <p class="text-sm sm:text-base text-[#E3EE9D]/90 mt-1.5 max-w-2xl">
                Kelola katalog menu catering, tentukan menu rekomendasi utama di halaman depan, dan pantau status pesanan pelanggan secara real-time.
            </p>
        </div>
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('admin.menus.create') }}"
               class="px-6 py-3 rounded-full bg-[#E3EE9D] text-[#2C5918] font-bold text-sm hover:bg-white transition shadow-sm">
                + Tambah Menu Baru
            </a>
            <a href="{{ route('admin.report') }}"
               class="px-6 py-3 rounded-full bg-[#2C5918] text-[#E3EE9D] font-semibold text-sm border border-[#E3EE9D]/30 hover:bg-[#1f4010] transition">
                Lihat Laporan Penjualan
            </a>
        </div>
    </div>

    {{-- Kartu Ringkasan Statistik --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
        <div class="rounded-3xl bg-[#B5D352] border border-[#477A25]/30 p-5">
            <p class="text-xs font-bold uppercase tracking-wider text-[#2C5918]/80">Total Katalog Menu</p>
            <p class="font-brand text-3xl text-[#2C5918] mt-1">{{ $stats['total_menu'] }} Item</p>
        </div>
        <div class="rounded-3xl bg-[#B5D352] border border-[#477A25]/30 p-5">
            <p class="text-xs font-bold uppercase tracking-wider text-[#2C5918]/80">Menu Rekomendasi</p>
            <p class="font-brand text-3xl text-[#2C5918] mt-1">{{ $stats['menu_recommended'] }} Terpilih</p>
        </div>
        <div class="rounded-3xl bg-[#B5D352] border border-[#477A25]/30 p-5">
            <p class="text-xs font-bold uppercase tracking-wider text-[#2C5918]/80">Pesanan Aktif</p>
            <p class="font-brand text-3xl text-[#2C5918] mt-1">{{ $stats['pesanan_aktif'] }} Order</p>
        </div>
        <div class="rounded-3xl bg-[#2C5918] text-[#E3EE9D] p-5">
            <p class="text-xs font-bold uppercase tracking-wider text-[#B5D352]">Total Pendapatan (Paid)</p>
            <p class="font-brand text-2xl sm:text-3xl text-[#E3EE9D] mt-1">
                Rp {{ number_format($stats['total_pendapatan'], 0, ',', '.') }}
            </p>
        </div>
    </div>

    {{-- SECTION 1: MANAJEMEN KATALOG MENU (CRUD) --}}
    <section class="rounded-3xl bg-[#B5D352] border border-[#477A25]/40 p-6 sm:p-8 mb-10 shadow-sm">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
            <div>
                <h2 class="font-brand text-2xl sm:text-3xl text-[#2C5918]">Manajemen Menu Catering</h2>
                <p class="text-sm text-[#2C5918]/85">Menu berlabel <strong>Recommended</strong> akan tampil di halaman depan website.</p>
            </div>

            {{-- Form Pencarian Menu --}}
            <form action="{{ route('admin.dashboard') }}" method="GET" class="flex items-center gap-2">
                <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama menu..."
                       class="px-4 py-2 rounded-full bg-[#E3EE9D] border border-[#477A25]/40 text-sm text-[#1F3D11] placeholder-[#2C5918]/60 focus:outline-none focus:ring-2 focus:ring-[#2C5918]">
                <button type="submit" class="px-5 py-2 rounded-full bg-[#477A25] text-[#E3EE9D] text-sm font-semibold hover:bg-[#2C5918] transition">
                    Cari
                </button>
                @if($search)
                    <a href="{{ route('admin.dashboard') }}" class="text-xs font-bold text-[#2C5918] underline">Reset</a>
                @endif
            </form>
        </div>

        <div class="overflow-x-auto rounded-2xl bg-[#E3EE9D]/90 border border-[#477A25]/30">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-[#477A25] text-[#E3EE9D] text-xs uppercase tracking-wider">
                        <th class="py-3.5 px-4">Foto</th>
                        <th class="py-3.5 px-4">Nama Menu & Deskripsi</th>
                        <th class="py-3.5 px-4">Harga</th>
                        <th class="py-3.5 px-4">Status</th>
                        <th class="py-3.5 px-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#477A25]/20 text-sm">
                    @forelse($menus as $menu)
                        <tr class="hover:bg-[#B5D352]/40 transition">
                            <td class="py-3.5 px-4">
                                @if($menu->foto_menu)
                                    <img src="{{ asset('storage/' . $menu->foto_menu) }}" alt="{{ $menu->nama_menu }}"
                                         class="w-16 h-16 rounded-2xl object-cover border border-[#477A25]/30 bg-white">
                                @else
                                    <div class="w-16 h-16 rounded-2xl bg-[#E5E7EB] border border-[#477A25]/30 flex items-center justify-center text-[#477A25] font-brand text-xs text-center p-1">
                                        BookCat
                                    </div>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 max-w-md">
                                <p class="font-bold text-base text-[#2C5918]">{{ $menu->nama_menu }}</p>
                                <p class="text-xs text-[#1F3D11]/80 line-clamp-2 mt-0.5">{{ $menu->deskripsi ?? 'Tidak ada deskripsi.' }}</p>
                            </td>
                            <td class="py-3.5 px-4 font-bold text-[#2C5918] whitespace-nowrap">
                                Rp {{ number_format($menu->harga, 0, ',', '.') }}
                            </td>
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                @if($menu->is_recommended)
                                    <span class="px-3 py-1 rounded-full text-xs font-bold bg-[#2C5918] text-[#E3EE9D]">
                                        ★ Best Seller / Recommended
                                    </span>
                                @else
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold bg-[#477A25]/20 text-[#2C5918]">
                                        Katalog Reguler
                                    </span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('admin.menus.edit', $menu->id_menu) }}"
                                       class="px-4 py-1.5 rounded-full bg-[#477A25] text-[#E3EE9D] text-xs font-semibold hover:bg-[#2C5918] transition">
                                        Edit
                                    </a>
                                    <form action="{{ route('admin.menus.destroy', $menu->id_menu) }}" method="POST"
                                          onsubmit="return confirm('Yakin ingin menghapus menu {{ $menu->nama_menu }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="px-4 py-1.5 rounded-full bg-red-700 text-white text-xs font-semibold hover:bg-red-800 transition">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-8 text-center text-[#2C5918] font-medium">
                                Belum ada data menu catering. Klik <strong>+ Tambah Menu Baru</strong> untuk memulai.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $menus->links() }}
        </div>
    </section>

    {{-- SECTION 2: MONITORING & UPDATE STATUS PESANAN MASUK --}}
    <section class="rounded-3xl bg-[#B5D352] border border-[#477A25]/40 p-6 sm:p-8 shadow-sm">
        <div class="mb-6">
            <h2 class="font-brand text-2xl sm:text-3xl text-[#2C5918]">Daftar Pesanan Masuk</h2>
            <p class="text-sm text-[#2C5918]/85">Perbarui status pengerjaan catering dan pengiriman pelanggan sesuai jadwal slot waktu.</p>
        </div>

        <div class="overflow-x-auto rounded-2xl bg-[#E3EE9D]/90 border border-[#477A25]/30">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-[#2C5918] text-[#E3EE9D] text-xs uppercase tracking-wider">
                        <th class="py-3.5 px-4">ID Order</th>
                        <th class="py-3.5 px-4">Pelanggan & Alamat</th>
                        <th class="py-3.5 px-4">Jadwal & Slot Waktu</th>
                        <th class="py-3.5 px-4">Total & Pembayaran</th>
                        <th class="py-3.5 px-4">Update Status Pesanan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#477A25]/20 text-sm">
                    @forelse($orders as $order)
                        <tr class="hover:bg-[#B5D352]/40 transition">
                            <td class="py-3.5 px-4 font-bold text-[#2C5918]">
                                #BC-{{ str_pad($order->id_order, 4, '0', STR_PAD_LEFT) }}
                            </td>
                            <td class="py-3.5 px-4">
                                <p class="font-bold text-[#2C5918]">{{ $order->user->nama_pengguna ?? 'Customer #' . $order->pengguna_id }}</p>
                                <p class="text-xs text-[#1F3D11]/80 mt-0.5">{{ $order->alamat_pengiriman }}</p>
                            </td>
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                <p class="font-semibold text-[#2C5918]">{{ \Carbon\Carbon::parse($order->tanggal_pengiriman)->format('d M Y') }}</p>
                                <span class="inline-block mt-0.5 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-[#477A25]/20 text-[#2C5918]">
                                    {{ $order->slot_waktu }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                <p class="font-bold text-[#2C5918]">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</p>
                                <p class="text-xs mt-0.5">
                                    <span class="uppercase font-semibold">{{ $order->metode_pembayaran }}</span> •
                                    <span class="font-bold {{ $order->status_pembayaran === 'paid' ? 'text-green-800' : 'text-amber-800' }}">
                                        {{ strtoupper($order->status_pembayaran) }}
                                    </span>
                                </p>
                            </td>
                            <td class="py-3.5 px-4">
                                <form action="{{ route('admin.orders.updateStatus', $order->id_order) }}" method="POST" class="flex flex-wrap items-center gap-2">
                                    @csrf
                                    @method('PATCH')

                                    <select name="status_pesanan"
                                            class="px-3 py-1.5 rounded-full bg-white border border-[#477A25] text-xs font-semibold text-[#2C5918] focus:outline-none">
                                        @foreach(['pending' => 'Pending', 'diproses' => 'Diproses', 'dikirim' => 'Dikirim', 'selesai' => 'Selesai', 'dibatalkan' => 'Dibatalkan'] as $val => $label)
                                            <option value="{{ $val }}" {{ $order->status_pesanan === $val ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    </select>

                                    <select name="status_pembayaran"
                                            class="px-3 py-1.5 rounded-full bg-white border border-[#477A25] text-xs font-semibold text-[#2C5918] focus:outline-none">
                                        <option value="unpaid" {{ $order->status_pembayaran === 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                                        <option value="paid" {{ $order->status_pembayaran === 'paid' ? 'selected' : '' }}>Paid</option>
                                        <option value="failed" {{ $order->status_pembayaran === 'failed' ? 'selected' : '' }}>Failed</option>
                                    </select>

                                    <button type="submit"
                                            class="px-4 py-1.5 rounded-full bg-[#2C5918] text-[#E3EE9D] text-xs font-bold hover:bg-[#477A25] transition">
                                        Simpan
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-8 text-center text-[#2C5918] font-medium">
                                Belum ada pesanan masuk dari pelanggan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
@endsection