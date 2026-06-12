<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prodi extends Model
{
    protected $table = 'prodi';

    protected $fillable = [
        'content',
        'type',
    ];

    protected $casts = [
        'content' => 'array',
    ];
}
