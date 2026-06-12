<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DaftarSlider extends Model
{
    protected $table = 'sliders';

    protected $fillable = [
        'url_image',
        'alt',
    ];
}
