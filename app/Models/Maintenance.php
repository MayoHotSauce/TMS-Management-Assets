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
        'approval_status',
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