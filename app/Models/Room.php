<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $fillable = [
        'name',
        'floor',
        'building',
        'capacity',
        'responsible_person'
    ];

    public function barang()
    {
        return $this->hasMany(Barang::class, 'room', 'name');
    }
}