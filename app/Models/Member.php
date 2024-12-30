<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    protected $table = 'members';
    protected $primaryKey = 'id_member';

    protected $fillable = [
        'nama',
        'email',
        'nik',
        // ... other fillable fields
    ];
} 