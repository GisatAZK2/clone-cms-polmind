<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Program_Sarjana_Terapan extends Model
{
    protected $table = 'program_sarjana_terapan';

    protected $fillable = [
        'content',
    ];

    protected $casts = [
        'content' => 'array',
    ];
    
}
