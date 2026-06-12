<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Models\Keunggulan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;

class KeunggulanController extends Controller
{
    public function index(Request $request)
    {
        $query = Keunggulan::query();

        if ($request->filled('search')) {
            $query->where('keunggulan', 'like', '%' . $request->search . '%');
        }

        $items = $query->latest()->paginate(10)->withQueryString();

        return view('admin.keunggulan.views_keunggulan', compact('items'));
    }

    public function create()
    {
        return view('admin.keunggulan.form_keunggulan');
    }

    public function store(Request $request)
    {
        $request->validate([
            'keunggulan' => 'required|string|max:5000',
            'url_images' => 'required|image|mimes:jpeg,png,gif|max:2048',
            'alt'        => 'nullable|string|max:255',
        ]);

        if (!Storage::disk('public')->exists('keunggulan')) {
            Storage::disk('public')->makeDirectory('keunggulan');
        }

        $image = $request->file('url_images');
        $filename = uniqid() . '.webp';
        $imagePath = 'keunggulan/' . $filename;

        Image::read($image)
            ->toWebp(80)
            ->save(storage_path('app/public/' . $imagePath));

        Keunggulan::create([
            'keunggulan' => $request->keunggulan,
            'url_images' => $imagePath,
            'alt'        => $request->alt,
        ]);

        return redirect()->route('admin.keunggulan.index')
            ->with('success_key', 'keunggulan.flashCreated');
    }

    public function edit(Keunggulan $keunggulan)
    {
        return view('admin.keunggulan.form_keunggulan', compact('keunggulan'));
    }

    public function update(Request $request, Keunggulan $keunggulan)
    {
        $request->validate([
            'keunggulan' => 'required|string|max:5000',
            'url_images' => 'nullable|image|mimes:jpeg,png,gif|max:2048',
            'alt'        => 'nullable|string|max:255',
        ]);

        $imagePath = $keunggulan->url_images;

        if ($request->hasFile('url_images')) {
            if ($imagePath && Storage::disk('public')->exists($imagePath)) {
                Storage::disk('public')->delete($imagePath);
            }

            if (!Storage::disk('public')->exists('keunggulan')) {
                Storage::disk('public')->makeDirectory('keunggulan');
            }

            $image = $request->file('url_images');
            $filename = uniqid() . '.webp';
            $imagePath = 'keunggulan/' . $filename;

            Image::read($image)
                ->toWebp(80)
                ->save(storage_path('app/public/' . $imagePath));
        }

        $keunggulan->update([
            'keunggulan' => $request->keunggulan,
            'url_images' => $imagePath,
            'alt'        => $request->alt,
        ]);

        return redirect()->route('admin.keunggulan.index')
            ->with('success_key', 'keunggulan.flashUpdated');
    }

    public function destroy(Keunggulan $keunggulan)
    {
        if (!empty($keunggulan->url_images) && Storage::disk('public')->exists($keunggulan->url_images)) {
            Storage::disk('public')->delete($keunggulan->url_images);
        }

        $keunggulan->delete();

        return redirect()->route('admin.keunggulan.index')
            ->with('success_key', 'keunggulan.flashDeleted');
    }

    public function bulkDestroy(Request $request)
    {
        $request->validate([
            'ids'   => 'required|array|min:1',
            'ids.*' => 'integer|exists:keunggulan,id',
        ]);

        $items = Keunggulan::whereIn('id', $request->ids)->get();

        foreach ($items as $keunggulan) {
            if (!empty($keunggulan->url_images) && Storage::disk('public')->exists($keunggulan->url_images)) {
                Storage::disk('public')->delete($keunggulan->url_images);
            }

            $keunggulan->delete();
        }

        return redirect()->route('admin.keunggulan.index')
             ->with('success_key', 'keunggulan.flashBulkDeleted');
    }
}