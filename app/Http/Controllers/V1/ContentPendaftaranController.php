<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Models\Content_pendaftaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;

class ContentPendaftaranController extends Controller
{

    private function storeImage($file): string
    {
        if (!Storage::disk('public')->exists('content-pendaftaran')) {
            Storage::disk('public')->makeDirectory('content-pendaftaran');
        }

        $filename = uniqid('', true) . '.webp';
        $imagePath = 'content-pendaftaran/' . $filename;

        Image::read($file)
            ->toWebp(80)
            ->save(storage_path('app/public/' . $imagePath));

        return $imagePath;
    }
    public function index(Request $request)
    {
        $query = Content_pendaftaran::query();
        if ($request->filled('search')) {
            $query->where('type', 'like', '%' . $request->search . '%');
        }
        $items = $query->latest()->paginate(10)->withQueryString();
        return view('admin.content-pendaftaran.views_content_pendaftaran', compact('items'));
    }

    public function create()
    {
        return view('admin.content-pendaftaran.form_content_pendaftaran');
    }

    public function store(Request $request)
    {
        $request->validate([
            'type'       => 'required|in:atas,bawah',
            'kata_kata'  => 'nullable|string|max:500',
            'tahun_buka'  => 'nullable|date',
            'items'      => 'required|array|min:1',
            'items.*.url_images' => 'required|image|mimes:jpeg,png,gif|max:2048',
            'items.*.alt'        => 'nullable|string|max:255',
            'items.*.link_url'   => 'nullable|string|max:255',
        ]);

        $processedItems = [];

        foreach ($request->input('items', []) as $key => $item) {
            if (!$request->hasFile("items.$key.url_images")) {
                continue;
            }

            $processedItems[] = [
                'url_images' => $this->storeImage($request->file("items.$key.url_images")),
                'alt'        => $item['alt'] ?? null,
                'link_url'   => $item['link_url'] ?? null,
            ];
        }

        Content_pendaftaran::create([
            'type'       => $request->type,
            'content'    => $processedItems,
            'kata_kata'  => $request->kata_kata,
            'tahun_buka' => $request->tahun_buka,
        ]);

        return redirect()->route('admin.content-pendaftaran.index')
            ->with('success_key', 'contentPendaftaran.flashCreated');
    }

    public function edit(Content_pendaftaran $contentPendaftaran)
    {
        return view('admin.content-pendaftaran.form_content_pendaftaran', ['item' => $contentPendaftaran]);
    }

    public function update(Request $request, Content_pendaftaran $contentPendaftaran)
{
    $request->validate([
        'type'       => 'required|in:atas,bawah',
        'kata_kata'  => 'nullable|string|max:500',
        'tahun_buka' => 'nullable|date',
        'items'      => 'required|array|min:1',
        'items.*.url_images'     => 'nullable|image|mimes:jpeg,png,gif|max:2048',
        'items.*.old_url_images' => 'nullable|string',
        'items.*.alt'            => 'nullable|string|max:255',
        'items.*.link_url'       => 'nullable|string|max:255',
    ]);

    $processedItems = [];
    $oldItems = $contentPendaftaran->content ?? [];

    $oldImagePaths = collect($oldItems)
        ->pluck('url_images')
        ->filter()
        ->values()
        ->all();

    $keptImagePaths = [];

    foreach ($request->input('items', []) as $key => $item) {
        $oldImagePath = $item['old_url_images'] ?? null;
        $imagePath = $oldImagePath;

        if ($request->hasFile("items.$key.url_images")) {
            if ($oldImagePath && Storage::disk('public')->exists($oldImagePath)) {
                Storage::disk('public')->delete($oldImagePath);
            }

            $imagePath = $this->storeImage($request->file("items.$key.url_images"));
        }

        if (!$imagePath) {
            continue;
        }

        $keptImagePaths[] = $imagePath;

        $processedItems[] = [
            'url_images' => $imagePath,
            'alt'        => $item['alt'] ?? null,
            'link_url'   => $item['link_url'] ?? null,
        ];
    }

    // Delete gambar lama dari block yang dihapus.
    foreach ($oldImagePaths as $oldImagePath) {
        if (!in_array($oldImagePath, $keptImagePaths, true)
            && Storage::disk('public')->exists($oldImagePath)) {
            Storage::disk('public')->delete($oldImagePath);
        }
    }

    $contentPendaftaran->update([
        'type'       => $request->type,
        'content'    => $processedItems,
        'kata_kata'  => $request->kata_kata,
        'tahun_buka' => $request->tahun_buka,
    ]);

    return redirect()->route('admin.content-pendaftaran.index')
        ->with('success_key', 'contentPendaftaran.flashUpdated');
}

    public function destroy(Content_pendaftaran $contentPendaftaran)
    {
        $items = $contentPendaftaran->content ?? [];
        foreach ($items as $item) {
            if (!empty($item['url_images']) && Storage::disk('public')->exists($item['url_images'])) {
                Storage::disk('public')->delete($item['url_images']);
            }
        }

        $contentPendaftaran->delete();

        return redirect()->route('admin.content-pendaftaran.index')
            ->with('success_key', 'contentPendaftaran.flashDeleted');
    }

    public function bulkDestroy(Request $request)
    {
        $request->validate([
            'ids'   => 'required|array|min:1',
            'ids.*' => 'integer|exists:content_pendaftaran,id',
        ]);

        $contents = Content_pendaftaran::whereIn('id', $request->ids)->get();

        foreach ($contents as $contentPendaftaran) {
            $items = $contentPendaftaran->content ?? [];

            foreach ($items as $item) {
                if (!empty($item['url_images']) && Storage::disk('public')->exists($item['url_images'])) {
                    Storage::disk('public')->delete($item['url_images']);
                }
            }

            $contentPendaftaran->delete();
        }

        return redirect()->route('admin.content-pendaftaran.index')
            ->with('success_key', 'contentPendaftaran.flashBulkDeleted');
    }
}
