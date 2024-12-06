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
        'status',
        'completion_date',
        'actions_taken',
        'results',
        'replaced_parts',
        'total_cost',
        'equipment_status',
        'recommendations',
        'additional_notes',
        'technician_name',
        'follow_up_priority'
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