<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Artikel extends Model
{
    //
    protected $table = 'tb_artikel';
    protected $fillable = [
        'judul', 'deskripsi', 'image'
    ];


}
