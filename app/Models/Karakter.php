<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Karakter extends Model
{
    protected $table = 'karakter';

    protected $fillable = [
        'nama_konten',
        'url_image',
        'alt',
        'deskripsi',
    ];
}
