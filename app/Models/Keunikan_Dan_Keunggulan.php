<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Keunikan_Dan_Keunggulan extends Model
{
    protected $table = 'keunikan_dan_keunggulan';

    protected $fillable = [
        'content',
    ];

    protected $casts = [
        'content' => 'array',
    ];
}
