<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Models\Mitra;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;

class MitraController extends Controller
{
    public function index(Request $request)
    {
        $query = Mitra::query();

        if ($request->filled('search')) {
            $query->where('nama_mitra', 'like', '%' . $request->search . '%');
        }

        $items = $query->latest()->paginate(10)->withQueryString();

        return view('admin.mitra.views_mitra', compact('items'));
    }

    public function create()
    {
        return view('admin.mitra.form_mitra');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_mitra' => 'required|string|max:255',
            'url_images' => 'required|image|mimes:jpeg,png,gif|max:2048',
            'alt'        => 'nullable|string|max:255',
            'deskripsi'  => 'nullable|string|max:5000',
        ]);

        if (!Storage::disk('public')->exists('mitra')) {
            Storage::disk('public')->makeDirectory('mitra');
        }

        $image = $request->file('url_images');
        $filename = uniqid() . '.webp';
        $imagePath = 'mitra/' . $filename;

        Image::read($image)
            ->toWebp(80)
            ->save(storage_path('app/public/' . $imagePath));

        Mitra::create([
            'nama_mitra' => $request->nama_mitra,
            'url_images' => $imagePath,
            'alt'        => $request->alt,
            'deskripsi'  => $request->deskripsi,
        ]);

        return redirect()->route('admin.mitra.index')
            ->with('success_key', 'mitra.flashCreated');
    }

    public function edit(Mitra $mitra)
    {
        return view('admin.mitra.form_mitra', compact('mitra'));
    }

    public function update(Request $request, Mitra $mitra)
    {
        $request->validate([
            'nama_mitra' => 'required|string|max:255',
            'url_images' => 'nullable|image|mimes:jpeg,png,gif|max:2048',
            'alt'        => 'nullable|string|max:255',
            'deskripsi'  => 'nullable|string|max:5000',
        ]);

        $imagePath = $mitra->url_images;

        if ($request->hasFile('url_images')) {
            // Delete old file
            if ($imagePath && Storage::disk('public')->exists($imagePath)) {
                Storage::disk('public')->delete($imagePath);
            }

            if (!Storage::disk('public')->exists('mitra')) {
                Storage::disk('public')->makeDirectory('mitra');
            }

            $image = $request->file('url_images');
            $filename = uniqid() . '.webp';
            $imagePath = 'mitra/' . $filename;

            Image::read($image)
                ->toWebp(80)
                ->save(storage_path('app/public/' . $imagePath));
        }

        $mitra->update([
            'nama_mitra' => $request->nama_mitra,
            'url_images' => $imagePath,
            'alt'        => $request->alt,
            'deskripsi'  => $request->deskripsi,
        ]);

        return redirect()->route('admin.mitra.index')
            ->with('success_key', 'mitra.flashUpdated');
    }

    public function destroy(Mitra $mitra)
    {
        // Delete file from storage
        if (!empty($mitra->url_images) && Storage::disk('public')->exists($mitra->url_images)) {
            Storage::disk('public')->delete($mitra->url_images);
        }

        $mitra->delete();

        return redirect()->route('admin.mitra.index')
           ->with('success_key', 'mitra.flashDeleted');
    }

    public function bulkDestroy(Request $request)
    {
        $request->validate([
            'ids'   => 'required|array|min:1',
            'ids.*' => 'integer|exists:mitra,id',
        ]);

        $items = Mitra::whereIn('id', $request->ids)->get();

        foreach ($items as $mitra) {
            if (!empty($mitra->url_images) && Storage::disk('public')->exists($mitra->url_images)) {
                Storage::disk('public')->delete($mitra->url_images);
            }

            $mitra->delete();
        }

        return redirect()->route('admin.mitra.index')
            ->with('success_key', 'mitra.flashBulkDeleted');
    }
}