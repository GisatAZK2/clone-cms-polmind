<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dosen extends Model
{
    protected $table = 'dosen';

    protected $fillable = [
        'name',
        'url_image',
        'type',
        'alt',
        'deskripsi',
    ];

    protected $casts = [
        'type' => 'string',
        'deskripsi' => 'json',
    ];
}
