<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MaintenanceLog extends Model
{
    protected $fillable = [
        'asset_id',
        'type',
        'description',
        'status',
        'technician_name',
        'completion_notes',
        'completed_at',
        'user_id'
    ];

    protected $table = 'maintenance_logs';

    public function asset(): BelongsTo
    {
        return $this->belongsTo(Asset::class, 'barang_id', 'id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
} 