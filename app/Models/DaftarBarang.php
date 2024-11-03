<?php
// app/Models/DaftarBarang.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DaftarBarang extends Model
{
    protected $table = 'assets';
    protected $fillable = [
        'name',
        'description',
        'room_id',
        'category_id',
        'purchase_date',
        'purchase_cost',
        'status',
        'asset_tag'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function maintenanceLogs()
    {
        return $this->hasMany(MaintenanceLog::class, 'barang_id', 'id');
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