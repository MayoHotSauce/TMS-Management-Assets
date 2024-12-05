<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockCheckItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'stock_check_id',
        'asset_id',
        'description',
        'is_checked'
    ];

    protected $casts = [
        'is_checked' => 'boolean'
    ];

    public function stockCheck()
    {
        return $this->belongsTo(StockCheck::class);
    }

    public function asset()
    {
        return $this->belongsTo(Asset::class);
    }
} 