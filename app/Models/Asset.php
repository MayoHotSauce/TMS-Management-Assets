<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    protected $table = 'assets'; // Ensure this matches your table name
    protected $fillable = [
        'id', // Include other fields as necessary
        'description',
        'room',
        'category',
        'year',
        'created_at',
    ];

    public function getFormattedIdAttribute()
    {
        return "TMS-{$this->room}-{$this->id}"; // Adjust this based on your actual room and ID structure
    }
} 