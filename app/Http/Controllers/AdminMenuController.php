<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminMenuController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');

        $menus = Menu::when($search, function ($query, $search) {
                $query->where('nama_menu', 'like', "%{$search}%")
                      ->orWhere('deskripsi', 'like', "%{$search}%");
            })
            ->orderByDesc('is_recommended')
            ->latest('id_menu')
            ->paginate(8)
            ->withQueryString();

        $orders = Order::with(['user', 'orderDetails.menu'])
            ->latest('id_order')
            ->get();

        $stats = [
            'total_menu' => Menu::count(),
            'menu_recommended' => Menu::where('is_recommended', true)->count(),
            'pesanan_aktif' => Order::whereIn('status_pesanan', ['pending', 'diproses', 'dikirim'])->count(),
            'total_pendapatan' => Order::where('status_pembayaran', 'paid')->sum('total_harga'),
        ];

        return view('admin.dashboard', compact('menus', 'orders', 'stats', 'search'));
    }

    public function create()
    {
        return view('admin.menus.form', ['menu' => null]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_menu' => 'required|string|max:150',
            'deskripsi' => 'nullable|string|max:1000',
            'harga' => 'required|numeric|min:1000',
            'foto_menu' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'is_recommended' => 'nullable|boolean',
        ], [
            'nama_menu.required' => 'Nama menu wajib diisi.',
            'harga.required' => 'Harga menu wajib diisi.',
            'harga.min' => 'Harga minimal Rp 1.000.',
            'foto_menu.image' => 'File harus berupa gambar.',
            'foto_menu.max' => 'Ukuran gambar maksimal 2MB.',
        ]);

        if ($request->hasFile('foto_menu')) {
            $validated['foto_menu'] = $request->file('foto_menu')->store('menus', 'public');
        }

        $validated['is_recommended'] = $request->boolean('is_recommended');

        Menu::create($validated);

        return redirect()
            ->route('admin.dashboard')
            ->with('success', 'Menu catering baru berhasil ditambahkan!');
    }

    public function edit(Menu $menu)
    {
        return view('admin.menus.form', compact('menu'));
    }

    public function update(Request $request, Menu $menu)
    {
        $validated = $request->validate([
            'nama_menu' => 'required|string|max:150',
            'deskripsi' => 'nullable|string|max:1000',
            'harga' => 'required|numeric|min:1000',
            'foto_menu' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'is_recommended' => 'nullable|boolean',
        ]);

        if ($request->hasFile('foto_menu')) {
            if ($menu->foto_menu && Storage::disk('public')->exists($menu->foto_menu)) {
                Storage::disk('public')->delete($menu->foto_menu);
            }
            $validated['foto_menu'] = $request->file('foto_menu')->store('menus', 'public');
        }

        $validated['is_recommended'] = $request->boolean('is_recommended');

        $menu->update($validated);

        return redirect()
            ->route('admin.dashboard')
            ->with('success', "Menu '{$menu->nama_menu}' berhasil diperbarui!");
    }

    public function destroy(Menu $menu)
    {
        if ($menu->foto_menu && Storage::disk('public')->exists($menu->foto_menu)) {
            Storage::disk('public')->delete($menu->foto_menu);
        }

        $namaMenu = $menu->nama_menu;
        $menu->delete();

        return redirect()
            ->route('admin.dashboard')
            ->with('success', "Menu '{$namaMenu}' berhasil dihapus dari katalog.");
    }

    public function updateOrderStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status_pesanan' => 'required|in:pending,diproses,dikirim,selesai,dibatalkan',
            'status_pembayaran' => 'nullable|in:unpaid,paid,failed',
        ]);

        $order->update([
            'status_pesanan' => $validated['status_pesanan'],
            'status_pembayaran' => $validated['status_pembayaran'] ?? $order->status_pembayaran,
        ]);

        return back()->with('success', "Status pesanan #BC-{$order->id_order} berhasil diperbarui!");
    }
}