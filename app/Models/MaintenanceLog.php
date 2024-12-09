<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MaintenanceLog extends Model
{
    protected $fillable = [
        'barang_id',
        'maintenance_date',
        'description',
        'cost',
        'performed_by',
        'status',
        'approval_status'
    ];

    protected $dates = [
        'maintenance_date',
        'completion_date'
    ];

    public function asset()
    {
        return $this->belongsTo(Asset::class, 'barang_id');
    }
} 