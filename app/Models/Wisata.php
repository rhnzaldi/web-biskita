<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wisata extends Model
{
    //
    protected $table = 'tb_wisata';
    protected $fillable = [
        'nama', 'kategori', 'latitude', 'longitude', 'image'
    ];
}
