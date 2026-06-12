<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class News extends Model
{
    protected $fillable = [
        'content',
        'published_at',
        'status',
        'jenis_content',
        'urutan',
        'author',
        'changed_by',
    ];

    protected $casts = [
        'content' => 'array',
        'published_at' => 'datetime',
    ];

    /**
     * Get the title from JSON content
     */
    public function getTitle()
    {
        return $this->content['title'] ?? 'Untitled';
    }

     /**
     * Generate slug dari title di content JSON.
     * Contoh: "Berita Terbaru 2025" → "berita-terbaru-2025"
     */
    public function getSlugAttribute(): string
    {
        $title = $this->content['title'] ?? '';
        return $title ? Str::slug($title) : (string) $this->id;
    }

    /**
     * URL publik berita — default pakai slug, fallback ke id jika slug kosong.
     */
    public function getPublicUrlAttribute(): string
    {
        $slug = $this->slug;
        // Jika slug sama dengan id (artinya title kosong), pakai id saja
        return route('berita.show', $slug ?: $this->id);
    }
}
