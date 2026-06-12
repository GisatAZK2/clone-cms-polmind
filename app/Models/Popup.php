<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Popup extends Model
{
    protected $fillable = [
        'url_image',
        'alt',
        'content',
        'is_active',
    ];
}
