<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    protected $fillable = [
        'name',
        'asset_tag',
        'category_id',
        'location_id',
        'purchase_date',
        'purchase_cost',
        'status',
        'description',
        'manufacturer',
        'model',
        'serial_number'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function maintenances()
    {
        return $this->hasMany(Maintenance::class, 'barang_id');
    }
}
