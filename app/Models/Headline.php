<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Headline extends Model
{
    protected $table = 'headline';

    protected $fillable = [
        'title',
        'url_image',
        'alt',
        'status',
        'urutan',
    ];
        protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

}
