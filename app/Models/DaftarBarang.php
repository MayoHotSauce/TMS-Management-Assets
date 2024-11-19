<?php
// app/Models/DaftarBarang.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DaftarBarang extends Model
{
    protected $table = 'daftar_barang';
    protected $fillable = [
        'name',
        'asset_tag',
        'category_id',
        'room_id',
        'purchase_date',
        'purchase_cost',
        'status',
        'manufacturer',
        'model',
        'serial_number',
        'description'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function maintenanceLogs()
    {
        return $this->hasMany(MaintenanceLog::class, 'barang_id');
    }

    public function transfers()
    {
        return $this->hasMany(AssetTransfer::class, 'barang_id', 'id');
    }
}

// app/Models/Category.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['category_name'];

    public function barangs()
    {
        return $this->hasMany(DaftarBarang::class);
    }
}