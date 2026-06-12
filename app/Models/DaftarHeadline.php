<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DaftarHeadline extends Model
{
    protected $table="headline";

    protected $fillable = [
        'title',
        'url_image',
        'alt',
    ];
}
