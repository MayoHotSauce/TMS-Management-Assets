<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockCheck extends Model
{
    use HasFactory;

    protected $fillable = [
        'created_by',
        'status',
        'completed_at',
        'last_updated_at'
    ];

    protected $casts = [
        'completed_at' => 'datetime',
        'last_updated_at' => 'datetime'
    ];

    public function creator()
    {
        return $this->belongsTo(Member::class, 'created_by', 'id_member');
    }

    public function items()
    {
        return $this->hasMany(StockCheckItem::class);
    }
} 