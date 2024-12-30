<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Jabatan extends Model
{
    protected $table = 'jabatans';
    protected $primaryKey = 'id_jabatan';
    
    protected $fillable = [
        'nama',
        'keterangan'
    ];

    public function users()
    {
        return $this->hasMany(User::class, 'jabatan_id', 'id_jabatan');
    }
} 