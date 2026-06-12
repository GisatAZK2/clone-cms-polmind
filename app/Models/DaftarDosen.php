<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DaftarDosen extends Model
{
    protected $table = 'dosen';

    protected $fillable = [
        'name',
        'url_image',
        'type',
        'alt',
    ];
}
