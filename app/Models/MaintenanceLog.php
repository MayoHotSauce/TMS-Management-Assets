<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MaintenanceLog extends Model
{
    protected $table = 'maintenance_logs';

    protected $fillable = [
        'barang_id',
        'maintenance_date',
        'description',
        'cost',
        'performed_by',
        'status',
        'approval_status',
        'technician_name',
        'completion_date',
        'equipment_status',
        'follow_up_priority',
        'actions_taken',
        'results',
        'replaced_parts',
        'total_cost',
        'recommendations',
        'additional_notes',
        'approved_at',
        'archived_at'
    ];

    protected $dates = [
        'maintenance_date',
        'completion_date',
        'archived_at'
    ];

    public function asset()
    {
        return $this->belongsTo(Asset::class, 'barang_id', 'id');
    }
} 