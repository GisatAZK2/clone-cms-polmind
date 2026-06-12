<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DaftarMitra extends Model
{

    protected $table='partner';

    protected $fillable = [
        'nama_partner',
        'url_image',
        'alt',
    ];
}
