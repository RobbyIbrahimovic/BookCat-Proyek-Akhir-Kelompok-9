@extends('layouts.admin')

@section('title', $menu ? 'Edit Menu — ' . $menu->nama_menu : 'Tambah Menu Baru')

@section('content')
    <div class="max-w-3xl mx-auto">
        <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-2 text-sm font-bold text-[#2C5918] hover:underline mb-4">
            ← Kembali ke Dashboard Admin
        </a>

        <div class="rounded-3xl bg-[#B5D352] border border-[#477A25]/40 p-6 sm:p-10 shadow-md">
            <div class="mb-6 pb-4 border-b border-[#477A25]/25">
                <h1 class="font-brand text-3xl text-[#2C5918]">
                    {{ $menu ? 'Edit Menu Catering' : 'Tambah Menu Catering Baru' }}
                </h1>
                <p class="text-sm text-[#2C5918]/80 mt-1">
                    Lengkapi informasi detail produk untuk ditampilkan pada katalog BookCat Bakery & Catering.
                </p>
            </div>

            @if($errors->any())
                <div class="mb-6 p-4 rounded-2xl bg-red-100 border border-red-400 text-red-900 text-sm">
                    <ul class="list-disc list-inside space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ $menu ? route('admin.menus.update', $menu->id_menu) : route('admin.menus.store') }}"
                  method="POST" enctype="multipart/form-data" class="space-y-5">
                @csrf
                @if($menu)
                    @method('PUT')
                @endif

                {{-- nama menu --}}
                <div>
                    <label for="nama_menu" class="block text-sm font-bold text-[#2C5918] mb-1.5">Nama Menu *</label>
                    <input type="text" id="nama_menu" name="nama_menu"
                           value="{{ old('nama_menu', $menu->nama_menu ?? '') }}"
                           placeholder="Contoh: Matcha Mille Crepe"
                           required
                           class="w-full px-4 py-3 rounded-2xl bg-[#E3EE9D] border border-[#477A25]/50 text-[#1F3D11] font-medium focus:outline-none focus:ring-2 focus:ring-[#2C5918]">
                </div>

                {{-- pricey --}}
                <div>
                    <label for="harga" class="block text-sm font-bold text-[#2C5918] mb-1.5">Harga Satuan / Paket (Rp) *</label>
                    <input type="number" id="harga" name="harga" min="1000" step="500"
                           value="{{ old('harga', isset($menu) ? (int)$menu->harga : '') }}"
                           placeholder="Contoh: 20000"
                           required
                           class="w-full px-4 py-3 rounded-2xl bg-[#E3EE9D] border border-[#477A25]/50 text-[#1F3D11] font-medium focus:outline-none focus:ring-2 focus:ring-[#2C5918]">
                </div>

                {{-- deskripsi --}}
                <div>
                    <label for="deskripsi" class="block text-sm font-bold text-[#2C5918] mb-1.5">Deskripsi Menu</label>
                    <textarea id="deskripsi" name="deskripsi" rows="4"
                              placeholder="Jelaskan komposisi rasa, ukuran porsi, atau keunggulan menu..."
                              class="w-full px-4 py-3 rounded-2xl bg-[#E3EE9D] border border-[#477A25]/50 text-[#1F3D11] font-medium focus:outline-none focus:ring-2 focus:ring-[#2C5918]">{{ old('deskripsi', $menu->deskripsi ?? '') }}</textarea>
                </div>

                {{-- upload foto    menunya --}}
                <div>
                    <label for="foto_menu" class="block text-sm font-bold text-[#2C5918] mb-1.5">Foto Menu (JPG/PNG/WEBP, Maks 2MB)</label>
                    <input type="file" id="foto_menu" name="foto_menu" accept="image/*"
                           onchange="previewImage(event)"
                           class="w-full px-4 py-2.5 rounded-2xl bg-[#E3EE9D] border border-[#477A25]/50 text-sm text-[#1F3D11] file:mr-4 file:py-1.5 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-[#477A25] file:text-[#E3EE9D] hover:file:bg-[#2C5918]">

                    <div class="mt-3">
                        <img id="preview"
                             src="{{ isset($menu) && $menu->foto_menu ? asset('storage/' . $menu->foto_menu) : '' }}"
                             class="{{ isset($menu) && $menu->foto_menu ? '' : 'hidden' }} w-36 h-36 rounded-2xl object-cover border-2 border-[#477A25] bg-white">
                    </div>
                </div>

                {{-- cekbox rekomen --}}
                <div class="p-4 rounded-2xl bg-[#E3EE9D]/80 border border-[#477A25]/30 flex items-center gap-3">
                    <input type="checkbox" id="is_recommended" name="is_recommended" value="1"
                           {{ old('is_recommended', $menu->is_recommended ?? false) ? 'checked' : '' }}
                           class="w-5 h-5 accent-[#2C5918] rounded cursor-pointer">
                    <label for="is_recommended" class="text-sm font-bold text-[#2C5918] cursor-pointer">
                        Jadikan Menu Rekomendasi Utama (Tampil di Section Recommended Halaman Depan)
                    </label>
                </div>

                {{-- submit --}}
                <div class="pt-4 flex items-center justify-end gap-3">
                    <a href="{{ route('admin.dashboard') }}"
                       class="px-6 py-3 rounded-full border border-[#2C5918] text-[#2C5918] font-bold text-sm hover:bg-[#2C5918]/10 transition">
                        Batal
                    </a>
                    <button type="submit"
                            class="px-8 py-3 rounded-full bg-[#2C5918] text-[#E3EE9D] font-bold text-sm hover:bg-[#477A25] transition shadow-md">
                        {{ $menu ? 'Simpan Perubahan' : 'Simpan Menu Baru' }}
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function previewImage(event) {
            const reader = new FileReader();
            reader.onload = function () {
                const output = document.getElementById('preview');
                output.src = reader.result;
                output.classList.remove('hidden');
            };
            if (event.target.files[0]) {
                reader.readAsDataURL(event.target.files[0]);
            }
        }
    </script>
@endsection