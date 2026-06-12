<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Models\Dokumentasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;

class DokumentasiController extends Controller
{

    public function publicIndex()
    {
        // Ambil semua dokumentasi, urutkan berdasarkan id dari kecil ke besar
        $dokumentasi = Dokumentasi::orderBy('id', 'asc')->get();

        return view('pages.dokumentasi', compact('dokumentasi'));
    }


    public function index(Request $request)
    {
        $query = Dokumentasi::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('content->title', 'like', "%{$search}%")
                ->orWhere('content->deskripsi', 'like', "%{$search}%");
        }

        $items = $query->latest()->paginate(10)->withQueryString();

        return view('admin.dokumentasi.views_dokumentasi', compact('items'));
    }

    public function create()
    {
        return view('admin.dokumentasi.form_dokumentasi');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'new_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'new_alts.*' => 'nullable|string|max:255',
            'new_orders.*' => 'nullable|integer',
        ]);

        $items = [];
        $newOrders = $request->input('new_orders', []);

        if ($request->hasFile('new_images')) {
            if (!Storage::disk('public')->exists('dokumentasi')) {
                Storage::disk('public')->makeDirectory('dokumentasi');
            }

            foreach ($request->file('new_images') as $index => $file) {
                if (!$file) {
                    continue;
                }

                $alt = $request->input('new_alts.' . $index, '');
                $order = (int) ($newOrders[$index] ?? ($index + 1));
                $filename = uniqid() . '.webp';
                $path = 'dokumentasi/' . $filename;

                Image::read($file)
                    ->toWebp(80)
                    ->save(storage_path('app/public/' . $path));

                $items[] = [
                    'gambar' => $path,
                    'alt' => $alt,
                    'urutan' => $order,
                ];
            }
        }

        usort($items, function ($a, $b) {
            return ($a['urutan'] ?? 0) <=> ($b['urutan'] ?? 0);
        });

        Dokumentasi::create([
            'content' => [
                'title' => $request->title,
                'deskripsi' => $request->deskripsi,
                'items' => $items,
            ],
        ]);

        return redirect()->route('admin.dokumentasi.index')
             ->with('success_key', 'dokumentasi.flashCreated');
    }

    public function edit(Dokumentasi $dokumentasi)
    {
        return view('admin.dokumentasi.form_dokumentasi', compact('dokumentasi'));
    }

    public function update(Request $request, Dokumentasi $dokumentasi)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'existing_images.*.replace' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'existing_images.*.alt' => 'nullable|string|max:255',
            'existing_images.*.urutan' => 'nullable|integer',
            'new_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'new_alts.*' => 'nullable|string|max:255',
            'new_orders.*' => 'nullable|integer',
        ]);

        $items = [];
        $existingImages = $request->input('existing_images', []);
        $existingFiles = $request->file('existing_images', []);

        foreach ($existingImages as $index => $existing) {
            $remove = !empty($existing['remove']);
            $path = $existing['path'] ?? null;
            $alt = $existing['alt'] ?? '';
            $order = (int) ($existing['urutan'] ?? ($index + 1));

            if ($remove) {
                if ($path && Storage::disk('public')->exists($path)) {
                    Storage::disk('public')->delete($path);
                }
                continue;
            }

            $replacement = data_get($existingFiles, "$index.replace");

            if ($replacement) {
                if ($path && Storage::disk('public')->exists($path)) {
                    Storage::disk('public')->delete($path);
                }

                if (!Storage::disk('public')->exists('dokumentasi')) {
                    Storage::disk('public')->makeDirectory('dokumentasi');
                }

                $filename = uniqid() . '.webp';
                $path = 'dokumentasi/' . $filename;

                Image::read($replacement)
                    ->toWebp(80)
                    ->save(storage_path('app/public/' . $path));
            }

            if ($path) {
                $items[] = [
                    'gambar' => $path,
                    'alt' => $alt,
                    'urutan' => $order,
                ];
            }
        }

        $newOrders = $request->input('new_orders', []);

        if ($request->hasFile('new_images')) {
            if (!Storage::disk('public')->exists('dokumentasi')) {
                Storage::disk('public')->makeDirectory('dokumentasi');
            }

            foreach ($request->file('new_images') as $index => $file) {
                if (!$file) {
                    continue;
                }

                $alt = $request->input('new_alts.' . $index, '');
                $order = (int) ($newOrders[$index] ?? (count($items) + $index + 1));
                $filename = uniqid() . '.webp';
                $path = 'dokumentasi/' . $filename;

                Image::read($file)
                    ->toWebp(80)
                    ->save(storage_path('app/public/' . $path));

                $items[] = [
                    'gambar' => $path,
                    'alt' => $alt,
                    'urutan' => $order,
                ];
            }
        }

        usort($items, function ($a, $b) {
            return ($a['urutan'] ?? 0) <=> ($b['urutan'] ?? 0);
        });

        $dokumentasi->update([
            'content' => [
                'title' => $request->title,
                'deskripsi' => $request->deskripsi,
                'items' => $items,
            ],
        ]);

        return redirect()->route('admin.dokumentasi.index')
           ->with('success_key', 'dokumentasi.flashUpdated');
    }

    public function destroy(Dokumentasi $dokumentasi)
    {
        $items = $dokumentasi->content['items'] ?? [];

        foreach ($items as $item) {
            if (!empty($item['gambar']) && Storage::disk('public')->exists($item['gambar'])) {
                Storage::disk('public')->delete($item['gambar']);
            }
        }

        $dokumentasi->delete();

        return redirect()->route('admin.dokumentasi.index')
            ->with('success_key', 'dokumentasi.flashDeleted');
    }

    public function bulkDestroy(Request $request)
    {
        $request->validate([
            'ids'   => 'required|array|min:1',
            'ids.*' => 'integer|exists:dokumentasi,id',
        ]);

        $items = Dokumentasi::whereIn('id', $request->ids)->get();

        foreach ($items as $item) {
            $content = $item->content ?? [];
            $images = $content['items'] ?? [];

            foreach ($images as $image) {
                if (!empty($image['gambar']) && Storage::disk('public')->exists($image['gambar'])) {
                    Storage::disk('public')->delete($image['gambar']);
                }
            }

            $item->delete();
        }

        return redirect()->route('admin.dokumentasi.index')
            ->with('success_key', 'dokumentasi.flashBulkDeleted');
    }
}
