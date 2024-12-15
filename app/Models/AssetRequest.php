<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssetRequest extends Model
{
    protected $table = 'asset_requests';
    
    protected $fillable = [
        'name',
        'category',
        'room_id',
        'price',
        'description',
        'requester_email',
        'status',
        'notes',
        'user_id',
        'approved_at'
    ];

    // Explicitly enable timestamps
    public $timestamps = true;

    protected $dates = [
        'created_at',
        'updated_at',
        'approved_at'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function requester()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }
}
