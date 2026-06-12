<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dokumentasi extends Model
{
    protected $table = 'dokumentasi';

    protected $fillable = [
        'content',
    ];

    protected $casts = [
        'content' => 'array',
    ];
}
