<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    protected $table = 'members';
    protected $primaryKey = 'id_member';
    
    protected $fillable = [
        'email',
        'nik',
        'nama'
    ];

    public function user()
    {
        return $this->hasOne(User::class, 'member_id', 'id_member');
    }
}