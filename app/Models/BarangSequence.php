<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BarangSequence extends Model
{
    protected $table = 'barang_seq';
    protected $fillable = ['next_val'];
}