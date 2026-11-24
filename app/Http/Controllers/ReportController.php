<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->input('start_date', now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', now()->toDateString());
        $statusPembayaran = $request->input('status_pembayaran', 'all');
        $metodePembayaran = $request->input('metode_pembayaran', 'all');

        $query = Order::with(['user', 'orderDetails.menu'])
            ->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate);

        if ($statusPembayaran !== 'all') {
            $query->where('status_pembayaran', $statusPembayaran);
        }

        if ($metodePembayaran !== 'all') {
            $query->where('metode_pembayaran', $metodePembayaran);
        }

        $orders = $query->latest('created_at')->get();

        $totalTransaksi = $orders->count();
        $totalOmzetPaid = $orders->where('status_pembayaran', 'paid')->sum('total_harga');
        $totalPorsiTerjual = $orders->where('status_pembayaran', 'paid')
            ->flatMap->orderDetails
            ->sum('kuantitas');
        $rataRataOrder = $orders->where('status_pembayaran', 'paid')->count() > 0
            ? $totalOmzetPaid / $orders->where('status_pembayaran', 'paid')->count()
            : 0;

        return view('admin.report', compact(
            'orders',
            'startDate',
            'endDate',
            'statusPembayaran',
            'metodePembayaran',
            'totalTransaksi',
            'totalOmzetPaid',
            'totalPorsiTerjual',
            'rataRataOrder'
        ));
    }
}