@extends('layouts.admin')

@section('title', 'Laporan Penjualan (Sales Report)')

@section('content')
    {{-- headder laporan --}}
    <div class="rounded-3xl bg-[#477A25] text-[#E3EE9D] p-6 sm:p-8 mb-8 shadow-md flex flex-col sm:flex-row sm:items-center justify-between gap-4 print-shadow-none">
        <div>
            <h1 class="font-brand text-3xl text-[#E3EE9D]">Laporan Penjualan BookCat</h1>
            <p class="text-sm text-[#E3EE9D]/90 mt-1">
                Periode: <strong>{{ \Carbon\Carbon::parse($startDate)->format('d M Y') }}</strong> s/d <strong>{{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}</strong>
            </p>
        </div>
        <button onclick="window.print()"
                class="no-print px-6 py-3 rounded-full bg-[#E3EE9D] text-[#2C5918] font-bold text-sm hover:bg-white transition shadow-sm">
            🖨️ Cetak / Simpan PDF
        </button>
    </div>

    {{-- form filter --}}
    <div class="no-print rounded-3xl bg-[#B5D352] border border-[#477A25]/40 p-6 mb-8">
        <form action="{{ route('admin.report') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 items-end">
            <div>
                <label class="block text-xs font-bold uppercase text-[#2C5918] mb-1">Tanggal Mulai</label>
                <input type="date" name="start_date" value="{{ $startDate }}"
                       class="w-full px-4 py-2.5 rounded-2xl bg-[#E3EE9D] border border-[#477A25]/50 text-sm font-semibold text-[#1F3D11]">
            </div>
            <div>
                <label class="block text-xs font-bold uppercase text-[#2C5918] mb-1">Tanggal Akhir</label>
                <input type="date" name="end_date" value="{{ $endDate }}"
                       class="w-full px-4 py-2.5 rounded-2xl bg-[#E3EE9D] border border-[#477A25]/50 text-sm font-semibold text-[#1F3D11]">
            </div>
            <div>
                <label class="block text-xs font-bold uppercase text-[#2C5918] mb-1">Status Pembayaran</label>
                <select name="status_pembayaran"
                        class="w-full px-4 py-2.5 rounded-2xl bg-[#E3EE9D] border border-[#477A25]/50 text-sm font-semibold text-[#1F3D11]">
                    <option value="all" {{ $statusPembayaran === 'all' ? 'selected' : '' }}>Semua Status</option>
                    <option value="paid" {{ $statusPembayaran === 'paid' ? 'selected' : '' }}>Paid (Lunas)</option>
                    <option value="unpaid" {{ $statusPembayaran === 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                    <option value="failed" {{ $statusPembayaran === 'failed' ? 'selected' : '' }}>Failed</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold uppercase text-[#2C5918] mb-1">Metode Pembayaran</label>
                <select name="metode_pembayaran"
                        class="w-full px-4 py-2.5 rounded-2xl bg-[#E3EE9D] border border-[#477A25]/50 text-sm font-semibold text-[#1F3D11]">
                    <option value="all" {{ $metodePembayaran === 'all' ? 'selected' : '' }}>Semua Metode</option>
                    <option value="DANA" {{ $metodePembayaran === 'DANA' ? 'selected' : '' }}>DANA</option>
                    <option value="GoPay" {{ $metodePembayaran === 'GoPay' ? 'selected' : '' }}>GoPay</option>
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit"
                        class="w-full py-2.5 px-5 rounded-full bg-[#2C5918] text-[#E3EE9D] font-bold text-sm hover:bg-[#477A25] transition">
                    Filter Data
                </button>
            </div>
        </form>
    </div>

    {{-- ringkasan statistiklaporan --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
        <div class="rounded-3xl bg-[#B5D352] border border-[#477A25]/30 p-5 print-shadow-none">
            <p class="text-xs font-bold uppercase text-[#2C5918]/80">Total Transaksi</p>
            <p class="font-brand text-3xl text-[#2C5918] mt-1">{{ $totalTransaksi }} Order</p>
        </div>
        <div class="rounded-3xl bg-[#2C5918] text-[#E3EE9D] p-5 print-shadow-none">
            <p class="text-xs font-bold uppercase text-[#B5D352]">Total Pendapatan (Paid)</p>
            <p class="font-brand text-2xl sm:text-3xl text-[#E3EE9D] mt-1">Rp {{ number_format($totalOmzetPaid, 0, ',', '.') }}</p>
        </div>
        <div class="rounded-3xl bg-[#B5D352] border border-[#477A25]/30 p-5 print-shadow-none">
            <p class="text-xs font-bold uppercase text-[#2C5918]/80">Total Porsi Terjual</p>
            <p class="font-brand text-3xl text-[#2C5918] mt-1">{{ $totalPorsiTerjual }} Porsi</p>
        </div>
        <div class="rounded-3xl bg-[#B5D352] border border-[#477A25]/30 p-5 print-shadow-none">
            <p class="text-xs font-bold uppercase text-[#2C5918]/80">Rata-Rata Transaksi</p>
            <p class="font-brand text-2xl text-[#2C5918] mt-1">Rp {{ number_format($rataRataOrder, 0, ',', '.') }}</p>
        </div>
    </div>

    {{-- tbl detail penjualan --}}
    <div class="rounded-3xl bg-[#B5D352] border border-[#477A25]/40 p-6 sm:p-8 print-shadow-none">
        <div class="overflow-x-auto rounded-2xl bg-[#E3EE9D] border border-[#477A25]/30">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-[#2C5918] text-[#E3EE9D] text-xs uppercase tracking-wider">
                        <th class="py-3.5 px-4">No</th>
                        <th class="py-3.5 px-4">Tanggal Order</th>
                        <th class="py-3.5 px-4">Pelanggan</th>
                        <th class="py-3.5 px-4">Rincian Item Menu</th>
                        <th class="py-3.5 px-4">Metode</th>
                        <th class="py-3.5 px-4">Status</th>
                        <th class="py-3.5 px-4 text-right">Subtotal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#477A25]/20 text-sm">
                    @forelse($orders as $index => $order)
                        <tr>
                            <td class="py-3 px-4 font-semibold">{{ $index + 1 }}</td>
                            <td class="py-3 px-4 whitespace-nowrap">{{ $order->created_at->format('d/m/Y H:i') }}</td>
                            <td class="py-3 px-4 font-bold text-[#2C5918]">
                                {{ $order->user->nama_pengguna ?? 'Customer #' . $order->pengguna_id }}
                            </td>
                            <td class="py-3 px-4">
                                <ul class="list-disc list-inside text-xs space-y-0.5">
                                    @forelse($order->orderDetails as $detail)
                                        <li>{{ $detail->menu->nama_menu ?? 'Menu' }} ({{ $detail->kuantitas }}x)</li>
                                    @empty
                                        <li class="italic text-[#2C5918]/70">Detail item tidak tersedia</li>
                                    @endforelse
                                </ul>
                            </td>
                            <td class="py-3 px-4 font-semibold uppercase">{{ $order->metode_pembayaran }}</td>
                            <td class="py-3 px-4">
                                <span class="px-2.5 py-1 rounded-full text-xs font-bold {{ $order->status_pembayaran === 'paid' ? 'bg-green-800 text-white' : 'bg-amber-200 text-amber-900' }}">
                                    {{ strtoupper($order->status_pembayaran) }}
                                </span>
                            </td>
                            <td class="py-3 px-4 text-right font-bold text-[#2C5918] whitespace-nowrap">
                                Rp {{ number_format($order->total_harga, 0, ',', '.') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-8 text-center text-[#2C5918] font-medium">
                                Tidak ditemukan data transaksi pada filter periode tersebut.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
                @if($orders->count() > 0)
                    <tfoot>
                        <tr class="bg-[#477A25] text-[#E3EE9D] font-bold">
                            <td colspan="6" class="py-3.5 px-4 text-right uppercase text-xs tracking-wider">
                                Total Keseluruhan Transaksi Terfilter:
                            </td>
                            <td class="py-3.5 px-4 text-right text-base">
                                Rp {{ number_format($orders->sum('total_harga'), 0, ',', '.') }}
                            </td>
                        </tr>
                    </tfoot>
                @endif
            </table>
        </div>
    </div>
@endsection