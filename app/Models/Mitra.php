<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mitra extends Model
{
    protected $table = 'mitra';

    protected $fillable = [
        'nama_mitra',
        'url_images',
        'alt',
        'deskripsi',
    ];
}
