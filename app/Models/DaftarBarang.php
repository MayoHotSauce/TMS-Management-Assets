<?php
// app/Models/DaftarBarang.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DaftarBarang extends Model
{
    protected $table = 'daftar_barang';
    protected $fillable = ['description', 'room', 'category_id', 'tahun_pengadaan'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}

// app/Models/Category.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['category_name'];

    public function barangs()
    {
        return $this->hasMany(DaftarBarang::class);
    }
}