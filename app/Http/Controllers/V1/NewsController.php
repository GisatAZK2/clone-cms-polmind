<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Intervention\Image\Laravel\Facades\Image;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Carbon\Carbon;

class NewsController extends Controller
{
    /**
     * ==================== FRONTEND PUBLIC METHODS ====================
     */

    public function publicIndex()
    {
        $news = News::where('status', 'published')
            ->latest('published_at')
            ->paginate(12);

        $data = [
            'page_title'       => 'Berita Terkini - Politeknik Mitra Industri',
            'canonical_url'    => url('/berita'),
            'meta_description' => 'Berita terkini dan update terbaru dari Politeknik Mitra Industri',
            'meta_keywords'    => 'berita polmind, berita terkini, update politeknik mitra industri',
            'news'             => $news,
        ];

        return view('pages.berita.news-list', $data);
    }

    public function publicShow(string $slug)
    {
        // Coba cari by numeric id dulu (backward-compatible: /berita/2)
        if (ctype_digit($slug)) {
            $news = News::where('status', 'published')->find((int) $slug);
        }

        // Jika tidak ketemu by id, atau bukan numerik → cari by slug dari title
        if (empty($news)) {
            $news = News::where('status', 'published')->get()->first(function ($item) use ($slug) {
                return Str::slug($item->content['title'] ?? '') === $slug;
            });
        }

        if (!$news) {
            abort(404);
        }

        // Redirect ke canonical slug URL jika diakses via id dan slug tersedia
        $canonicalSlug = $news->slug;
        if (ctype_digit($slug) && $canonicalSlug && $canonicalSlug !== $slug) {
            return redirect()->route('berita.show', $canonicalSlug, 301);
        }

        $relatedNews = News::where('status', 'published')
            ->where('id', '!=', $news->id)
            ->latest('published_at')
            ->limit(4)
            ->get();

        // Extract meta & first image untuk OGP
        $metaDescription = '';
        $ogImage         = null;
        $contentData     = $news->content;

        if (is_array($contentData) && isset($contentData['blocks'])) {
            foreach ($contentData['blocks'] as $block) {
                if ($block['type'] === 'text' && empty($metaDescription)) {
                    $metaDescription = Str::limit(strip_tags($block['content']), 160);
                }
                if ($block['type'] === 'image' && !$ogImage && isset($block['image'])) {
                    $ogImage = asset('storage/' . $block['image']);
                }
            }
        }

        $canonicalUrl = url('/berita/' . $canonicalSlug);

        $data = [
            'page_title'       => ($contentData['title'] ?? 'Berita') . ' - Politeknik Mitra Industri',
            'canonical_url'    => $canonicalUrl,
            'meta_description' => $metaDescription,
            'meta_keywords'    => 'berita polmind, ' . ($contentData['title'] ?? ''),
            'og_title'         => $contentData['title'] ?? 'Berita Polmind',
            'og_description'   => $metaDescription,
            'og_image'         => $ogImage ?? asset('assets/images/og-default.png'),
            'og_url'           => $canonicalUrl,
            'news'             => $news,
            'relatedNews'      => $relatedNews,
            'share_url'        => urlencode($canonicalUrl),
            'share_title'      => urlencode($contentData['title'] ?? 'Berita Polmind'),
        ];

        return view('pages.berita.views_detail_berita', $data);
    }

    /**
     * ==================== ADMIN CRUD METHODS ====================
     */

    /**
     * Display a listing of the news for admin.
     */
    public function index(Request $request)
{
    $search = $request->input('search');
    $authUser = auth()->user();

    $news = News::query()
        ->when($authUser->role === 'admin', function ($query) use ($authUser) {
            $query->where('author', $authUser->name);
        })
        ->when($search, function ($query) use ($search) {
            $query->whereJsonContains('content->title', $search);
        })
        ->orderBy('urutan', 'asc')
        ->orderBy('published_at', 'desc')
        ->paginate(10);

    return view('admin.news.views_news', compact('news'));
}

    /**
     * Show the form for creating a new news.
     */
    public function create()
    {
        return view('admin.news.form_news', ['news' => null]);
    }

    /**
     * Store a newly created news in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'             => 'required|string|max:255',
            'status'            => 'required|in:draft,published',
            'jenis_content'     => 'required|in:Umum,Prestasi,Kerjasama',
            'blocks'            => 'nullable|array',
            'blocks.*.type'     => 'nullable|in:text,image',
            'blocks.*.content'  => 'nullable|string',
            'blocks.*.image'    => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'blocks.*.alt'      => 'nullable|string|max:255',
        ]);

        $publishedAt = Carbon::createFromFormat(
            'Y-m-d\TH:i',
            $request->published_at,
            config('app.timezone')
        );

        if (!Storage::disk('public')->exists('news')) {
            Storage::disk('public')->makeDirectory('news');
        }

        $blocks = [];
        if ($request->has('blocks') && is_array($request->input('blocks'))) {
            foreach ($request->input('blocks') as $index => $block) {
                if ($block['type'] === 'text') {
                    $blocks[] = [
                        'type'    => 'text',
                        'content' => $block['content'],
                    ];
                } elseif ($block['type'] === 'image' && $request->hasFile("blocks.{$index}.image")) {
                    $image = $request->file("blocks.{$index}.image");
                    $filename = uniqid() . '.webp';
                    $path = 'news/' . $filename;

                    Image::read($image)
                        ->toWebp(80)
                        ->save(storage_path('app/public/' . $path));

                    $blocks[] = [
                        'type'  => 'image',
                        'image' => $path,
                        'alt'   => $block['alt'],
                    ];
                }
            }
        }

        $content = [
            'title'  => $validated['title'],
            'blocks' => $blocks,
        ];

        $maxUrutan = News::max('urutan');
        $news = News::create([
            'content'       => $content,
            'published_at' => $publishedAt,
            'status'        => $validated['status'],
            'jenis_content' => $validated['jenis_content'],
            'urutan'        => $maxUrutan !== null ? $maxUrutan + 1 : 1,
            'author'         => auth()->user()->name ?? 'Unknown',
        ]);

        return redirect()->route('admin.news.index')->with('success_key', 'news.flashCreated');
    }

    /**
     * Reorder news items.
     */
    public function reorder(Request $request)
    {
        $validated = $request->validate([
            'order' => 'required|string',
        ]);

        $ids = array_filter(explode(',', $validated['order']), function ($id) {
            return is_numeric($id);
        });

        foreach ($ids as $position => $id) {
    News::where('id', $id)->update([
        'urutan' => $position + 1
    ]);
}

        return redirect()->route('admin.news.index')->with('success_key', 'news.flashReordered');
    }

    /**
     * Display the specified news (for admin preview/detail).
     */
    public function show(string $id)
    {
        $news = News::findOrFail($id);
        return view('admin.news.form_news', compact('news'));
    }

    /**
     * Show the form for editing the specified news.
     */
    public function edit(string $id)
    {
        $news = News::findOrFail($id);
        return view('admin.news.form_news', compact('news'));
    }

    /**
     * Update the specified news in storage.
     */
    public function update(Request $request, string $id)
    {
        $news = News::findOrFail($id);

        $validated = $request->validate([
            'title'             => 'sometimes|required|string|max:255',
            'published_at'      => 'nullable|date',
            'status'            => 'sometimes|required|in:draft,published',
            'jenis_content'     => 'sometimes|required|in:Umum,Prestasi,Kerjasama',
            'blocks'            => 'nullable|array',
            'blocks.*.type'     => 'nullable|in:text,image',
            'blocks.*.content'  => 'nullable|string',
            'blocks.*.image'    => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'blocks.*.alt'      => 'nullable|string|max:255',
        ]);

        // Get old blocks untuk cleanup gambar
        $oldBlocks = $news->content['blocks'] ?? [];

        // Tentukan indices blocks yang ada di request (yang tidak dihapus dari UI)
        $newBlockIndices = $request->has('blocks') ? array_keys((array)$request->input('blocks')) : [];

        // ============ CLEANUP OLD IMAGES ============
        // Hapus gambar dari blocks yang dihapus atau diganti imagenya
        foreach ($oldBlocks as $index => $oldBlock) {
            $blockInRequest = in_array($index, $newBlockIndices);

            if ($oldBlock['type'] === 'image' && isset($oldBlock['image'])) {
                if (!$blockInRequest) {
                    // Block dihapus dari UI - hapus gambarnya dari storage
                    if (Storage::disk('public')->exists($oldBlock['image'])) {
                        Storage::disk('public')->delete($oldBlock['image']);
                    }
                } else if ($request->hasFile("blocks.{$index}.image")) {
                    // Block image di-replace dengan image baru - hapus image lama
                    if (Storage::disk('public')->exists($oldBlock['image'])) {
                        Storage::disk('public')->delete($oldBlock['image']);
                    }
                }
            }
        }

        // ============ PROCESS NEW BLOCKS (REPLACE, jangan APPEND) ============
        $blocks = [];
        if ($request->has('blocks') && is_array($request->input('blocks'))) {
            foreach ($request->input('blocks') as $index => $block) {
                if ($block['type'] === 'text') {
                    // Text block
                    $blocks[$index] = [
                        'type'    => 'text',
                        'content' => $block['content'] ?? '',
                    ];
                } elseif ($block['type'] === 'image') {
                    if ($request->hasFile("blocks.{$index}.image")) {
                        // Ada image upload baru
                        $image    = $request->file("blocks.{$index}.image");
                        $filename = uniqid() . '.webp';
                        $path     = 'news/' . $filename;

                        Image::read($image)
                            ->toWebp(80)
                            ->save(storage_path('app/public/' . $path));

                        $blocks[$index] = [
                            'type'  => 'image',
                            'image' => $path,
                            'alt'   => $block['alt'] ?? '',
                        ];
                    } else if (isset($oldBlocks[$index]) && $oldBlocks[$index]['type'] === 'image') {
                        // Tidak ada upload baru - gunakan image lama
                        $blocks[$index] = $oldBlocks[$index];
                        // Update alt text jika berubah
                        if (isset($block['alt'])) {
                            $blocks[$index]['alt'] = $block['alt'];
                        }
                    }
                }
            }
        }

        $content = [
            'title'  => $validated['title'] ?? $news->content['title'],
            'blocks' => $blocks,
        ];

        $news->update([
            'content'       => $content,
            'published_at'  => $validated['published_at'] ?? $news->published_at,
            'status'        => $validated['status'] ?? $news->status,
            'jenis_content' => $validated['jenis_content'] ?? $news->jenis_content,
            'changed_by'    => auth()->user()->name ?? 'Unknown',
        ]);

        return redirect()->route('admin.news.index')->with('success_key', 'news.flashUpdated');
    }

    /**
     * Remove the specified news from storage.
     */
    public function destroy(string $id)
    {
        $news = News::findOrFail($id);

        // Hapus semua gambar yang terkait
        if (isset($news->content['blocks']) && is_array($news->content['blocks'])) {
            foreach ($news->content['blocks'] as $block) {
                if ($block['type'] === 'image' && isset($block['image']) && Storage::disk('public')->exists($block['image'])) {
                    Storage::disk('public')->delete($block['image']);
                }
            }
        }

        $news->delete();

        return redirect()->route('admin.news.index')->with('success_key', 'news.flashDeleted');
    }

    public function bulkDestroy(Request $request)
    {
        $request->validate([
            'ids'   => 'required|array|min:1',
            'ids.*' => 'integer|exists:news,id',
        ]);

        $query = News::whereIn('id', $request->ids);

        if (auth()->user()?->role === 'admin') {
            $query->where('author', auth()->user()->name);
        }

        $newsItems = $query->get();

        foreach ($newsItems as $news) {
            if (isset($news->content['blocks']) && is_array($news->content['blocks'])) {
                foreach ($news->content['blocks'] as $block) {
                    if (
                        ($block['type'] ?? null) === 'image'
                        && !empty($block['image'])
                        && Storage::disk('public')->exists($block['image'])
                    ) {
                        Storage::disk('public')->delete($block['image']);
                    }
                }
            }

            $news->delete();
        }

        return redirect()->route('admin.news.index')
            ->with('success_key', 'news.flashBulkDeleted');
    }
}