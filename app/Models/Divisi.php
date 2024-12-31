<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Divisi extends Model
{
    protected $table = 'divisis';
    protected $primaryKey = 'id_divisi';
    
    protected $fillable = [
        'nama',
        'keterangan'
    ];

    public function users()
    {
        return $this->hasMany(User::class, 'divisi_id', 'id_divisi');
    }
} 