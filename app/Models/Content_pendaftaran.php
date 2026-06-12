<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Content_pendaftaran extends Model
{
    protected $table = 'content_pendaftaran';

    protected $fillable = [
        'type',
        'content',
        'kata_kata',
        'tahun_buka',
    ];

    protected $casts = [
        'content'     => 'array',
        'tahun_buka'  => 'date', 
    ];
}