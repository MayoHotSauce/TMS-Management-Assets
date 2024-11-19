<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Asset extends Model
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
        'asset_tag',
        'manufacturer',
        'model',
        'serial_number'
    ];

    public function maintenances()
    {
        return $this->hasMany(Maintenance::class, 'barang_id');
    }

    public static function getAvailableAssets()
    {
        return self::where('status', 'available')->get();
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
} 