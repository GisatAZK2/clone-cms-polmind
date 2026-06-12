<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Profile_Page extends Model
{
    protected $table = 'profile_page';

    protected $fillable = [
        'url_images',
        'alt',
        'visi',
        'misi',
        'content',
        'type',
    ];

    protected $casts = [
        'content' => 'array',
        
    ];
}
