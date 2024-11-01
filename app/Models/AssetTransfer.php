<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssetTransfer extends Model
{
    protected $fillable = [
        'barang_id',
        'from_room',
        'to_room',
        'transfer_date',
        'reason',
        'approved_by',
        'status'
    ];

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'barang_id', 'id');
    }
}