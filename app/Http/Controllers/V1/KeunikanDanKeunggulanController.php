<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Models\Keunikan_Dan_Keunggulan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;

class KeunikanDanKeunggulanController extends Controller
{
    public function index()
    {
        $items = Keunikan_Dan_Keunggulan::latest()->get();
        return view('admin.keunikan-dan-keunggulan.views_keunikan_dan_keunggulan', compact('items'));
    }

    public function create()
    {
        return view('admin.keunikan-dan-keunggulan.form_keunikan_dan_keunggulan');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'         => 'required|string|max:255',
            'content_keys'  => 'required|array|min:1',
            'content_keys.*'=> 'required|in:paragraph,image',
        ]);

        $content = $this->buildContent($request);

        Keunikan_Dan_Keunggulan::create([
            'content' => $content,
        ]);

        return redirect()->route('admin.keunikan-dan-keunggulan.index')
           ->with('success_key', 'keunikanKeunggulan.flashCreated');
    }

    public function edit(Keunikan_Dan_Keunggulan $keunikanDanKeunggulan)
    {
        return view('admin.keunikan-dan-keunggulan.form_keunikan_dan_keunggulan', [
            'item' => $keunikanDanKeunggulan,
        ]);
    }

    public function update(Request $request, Keunikan_Dan_Keunggulan $keunikanDanKeunggulan)
    {
        $request->validate([
            'title'         => 'required|string|max:255',
            'content_keys'  => 'required|array|min:1',
            'content_keys.*'=> 'required|in:paragraph,image',
        ]);

        // Kumpulkan semua path gambar lama dari content
        $oldContent  = $keunikanDanKeunggulan->content ?? [];
        $oldImages   = collect($oldContent['blocks'] ?? [])
            ->where('type', 'image')
            ->pluck('path')
            ->filter()
            ->values()
            ->toArray();

        $content     = $this->buildContent($request, $oldImages);
        $newImages   = collect($content['blocks'] ?? [])
            ->where('type', 'image')
            ->pluck('path')
            ->filter()
            ->values()
            ->toArray();

        // Hapus gambar lama yang sudah tidak dipakai
        foreach ($oldImages as $oldPath) {
            if (!in_array($oldPath, $newImages)) {
                Storage::disk('public')->delete($oldPath);
            }
        }

        $keunikanDanKeunggulan->update([
            'content' => $content,
        ]);

        return redirect()->route('admin.keunikan-dan-keunggulan.index')
           ->with('success_key', 'keunikanKeunggulan.flashUpdated');
    }

    public function destroy(Keunikan_Dan_Keunggulan $keunikanDanKeunggulan)
    {
        // Hapus semua gambar dari storage
        $content = $keunikanDanKeunggulan->content ?? [];
        foreach ($content['blocks'] ?? [] as $block) {
            if ($block['type'] === 'image' && !empty($block['path'])) {
                Storage::disk('public')->delete($block['path']);
            }
        }

        $keunikanDanKeunggulan->delete();

        return redirect()->route('admin.keunikan-dan-keunggulan.index')
             ->with('success_key', 'keunikanKeunggulan.flashDeleted');
    }

    public function bulkDestroy(Request $request)
    {
        $request->validate([
            'ids'   => 'required|array|min:1',
            'ids.*' => 'integer|exists:keunikan_dan_keunggulan,id',
        ]);

        $items = Keunikan_Dan_Keunggulan::whereIn('id', $request->ids)->get();

        foreach ($items as $item) {
            $content = $item->content ?? [];

            foreach ($content['blocks'] ?? [] as $block) {
                if (($block['type'] ?? null) === 'image' && !empty($block['path'])) {
                    Storage::disk('public')->delete($block['path']);
                }
            }

            $item->delete();
        }

        return redirect()->route('admin.keunikan-dan-keunggulan.index')
            ->with('success_key', 'keunikanKeunggulan.flashBulkDeleted');
    }

    // ─────────────────────────────────────────────
    // PRIVATE HELPERS
    // ─────────────────────────────────────────────

    /**
     * Bangun array content dari request.
     *
     * Struktur content:
     * {
     *   "title": "...",
     *   "blocks": [
     *     { "type": "paragraph", "value": "<p>...</p>" },
     *     { "type": "image",     "path": "keunikan/xxx.jpg", "alt": "..." },
     *     ...
     *   ]
     * }
     *
     * @param  Request  $request
     * @param  array    $oldImages  path lama agar bisa di-reuse jika tidak diganti
     */
    private function buildContent(Request $request, array $oldImages = []): array
    {
        $blocks        = [];
        $keys          = $request->input('content_keys', []);
        $paragraphs    = $request->input('paragraphs', []);    // indexed by block index
        $imageAlts     = $request->input('image_alts', []);    // indexed by block index
        $imageKeeps    = $request->input('image_keeps', []);   // path lama yg ingin disimpan
        $imageDeletes  = $request->input('image_deletes', []); // flag hapus gambar

        $paraIdx  = 0;
        $imgIdx   = 0;

        foreach ($keys as $blockIdx => $type) {
            if ($type === 'paragraph') {
                $blocks[] = [
                    'type'  => 'paragraph',
                    'value' => $paragraphs[$paraIdx] ?? '',
                ];
                $paraIdx++;
            } elseif ($type === 'image') {
                $alt       = $imageAlts[$imgIdx] ?? '';
                $keepPath  = $imageKeeps[$imgIdx] ?? null;   // path lama dari hidden input
                $doDelete  = !empty($imageDeletes[$imgIdx]); // user centang "hapus"
                $file      = $request->file("images.{$imgIdx}");

                if ($doDelete) {
                    // User hapus gambar → block gambar tetap ada tapi path kosong
                    // (gambar lama akan dihapus di update())
                    $blocks[] = [
                        'type'  => 'image',
                        'path'  => '',
                        'alt'   => $alt,
                    ];
                } elseif ($file) {
                    // User upload gambar baru
                    if (!Storage::disk('public')->exists('keunikan-dan-keunggulan')) {
                        Storage::disk('public')->makeDirectory('keunikan-dan-keunggulan');
                    }

                    $filename = uniqid() . '.webp';
                    $path = 'keunikan-dan-keunggulan/' . $filename;

                    Image::read($file)
                        ->toWebp(80)
                        ->save(storage_path('app/public/' . $path));

                    $blocks[] = [
                        'type'  => 'image',
                        'path'  => $path,
                        'alt'   => $alt,
                    ];
                } else {
                    // Tidak ada file baru → pertahankan gambar lama
                    $blocks[] = [
                        'type'  => 'image',
                        'path'  => $keepPath ?? '',
                        'alt'   => $alt,
                    ];
                }

                $imgIdx++;
            }
        }

        return [
            'title'  => $request->input('title'),
            'blocks' => $blocks,
        ];
    }
}