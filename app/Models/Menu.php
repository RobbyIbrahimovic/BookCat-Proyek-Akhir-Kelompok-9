<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;

    protected $table = 'menus';
    protected $primaryKey = 'id_menu';

    protected $fillable = [
        'nama_menu',
        'deskripsi',
        'harga',
        'foto_menu',
        'is_recommended',
    ];

    protected $casts = [
        'harga' => 'decimal:2',
        'is_recommended' => 'boolean',
    ];

    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class, 'menu_id', 'id_menu');
    }
}