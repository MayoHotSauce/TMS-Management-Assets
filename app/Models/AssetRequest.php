<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetRequest extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'name',
        'category',
        'price',
        'description',
        'requester_email',
        'approver_email',
        'status',
        'user_id',
        'approval_token'
    ];
}
