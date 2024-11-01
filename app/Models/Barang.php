<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    protected $table = 'daftar_barang';
    
    protected $fillable = [
        'id',
        'description',
        'room',
        'category_id',
        'tahun_pengadaan',
        'created_at',
        'condition',
        'last_maintenance',
        'next_maintenance',
        'status'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
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