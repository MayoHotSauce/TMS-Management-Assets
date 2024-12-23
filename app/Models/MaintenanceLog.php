<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MaintenanceLog extends Model
{
    protected $fillable = [
        'barang_id',
        'maintenance_date',
        'description',
        'status',
        'cost',
        'performed_by',
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