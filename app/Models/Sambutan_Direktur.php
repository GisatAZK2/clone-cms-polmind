<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sambutan_Direktur extends Model
{
    protected $table = 'sambutan_direktur';

    protected $fillable = [
        'content',
    ];

    protected $casts = [
        'content' => 'array',
    ];
}
