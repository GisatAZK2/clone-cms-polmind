<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pendaftaran_Mahasiswa_Baru extends Model
{
    protected $table = 'pendaftaran_mahasiswa_baru';

    protected $fillable = ['content', 'persyaratan_administrasi'];

    protected $casts = [
        'content' => 'array',
    ];
}
