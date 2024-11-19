<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Maintenance extends Model
{
    protected $table = 'maintenance_logs'; // If your table name is different, adjust this

    protected $fillable = [
        'barang_id',
        'maintenance_date',
        'description',
        'cost',
        'performed_by',
        'status',
        'updated_at'
    ];

    protected $casts = [
        'maintenance_date' => 'date',
        'cost' => 'decimal:2',
    ];

    // If you have a relationship with Barang model
    public function barang()
    {
        return $this->belongsTo(Barang::class, 'barang_id');
    }

    public function asset()
    {
        return $this->belongsTo(Asset::class, 'barang_id');
    }
}