<?php

namespace Database\Seeders;

use App\Models\Menu;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        $menus = [
            [
                'nama_menu' => 'Matcha Mille Crepe',
                'deskripsi' => '20 lapis crepe lembut dengan krim Uji matcha autentik khas BookCat.',
                'harga' => 20000,
                'foto_menu' => null,
                'is_recommended' => true,
            ],
            [
                'nama_menu' => 'Pistachio Cream Tart',
                'deskripsi' => 'Pie renyah gurih dengan isian custard pistachio panggang premium.',
                'harga' => 20000,
                'foto_menu' => null,
                'is_recommended' => true,
            ],
            [
                'nama_menu' => 'Pandan Chiffon Roll',
                'deskripsi' => 'Bolu gulung pandan super ringan dengan krim kelapa segar alami.',
                'harga' => 20000,
                'foto_menu' => null,
                'is_recommended' => true,
            ],
            [
                'nama_menu' => 'Matcha Catering Box (Isi 12)',
                'deskripsi' => 'Paket hampers catering berisi kombinasi Matcha Crepe & Tart untuk acara spesial.',
                'harga' => 225000,
                'foto_menu' => null,
                'is_recommended' => false,
            ],
            [
                'nama_menu' => 'Pistachio Opera Cake Whole',
                'deskripsi' => 'Kue loyang utuh 20x20 cm berlapis ganache cokelat putih dan pasta pistachio.',
                'harga' => 185000,
                'foto_menu' => null,
                'is_recommended' => false,
            ],
        ];

        foreach ($menus as $menu) {
            Menu::updateOrCreate(
                ['nama_menu' => $menu['nama_menu']],
                $menu
            );
        }
    }
}