<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MaintenanceLog extends Model
{
    protected $fillable = [
        'barang_id',
        'description',
        'maintenance_date',
        'cost',
        'performed_by',
        'status'
    ];

    protected $dates = ['maintenance_date'];

    public function asset()
    {
        return $this->belongsTo(Asset::class, 'barang_id');
    }
} 