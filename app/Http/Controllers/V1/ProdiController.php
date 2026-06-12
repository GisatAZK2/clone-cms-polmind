<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Models\Prodi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;

class ProdiController extends Controller
{
    public function index(Request $request)
    {
        $query = Prodi::query();

        if ($request->filled('search')) {
            $query->where('type', 'like', '%' . $request->search . '%');
        }

        $items = $query->latest()->paginate(10)->withQueryString();

        return view('admin.prodi.views_prodi', compact('items'));
    }

    public function create()
    {
        return view('admin.prodi.form_prodi');
    }

    public function store(Request $request)
    {
        $request->validate([
            'type'               => 'required|in:image,card',
            'url_images'         => 'required|image|mimes:jpeg,png,gif|max:2048',
            'alt'                => 'nullable|string|max:255',
            'title'              => 'required_if:type,card|nullable|string|max:255',
            // card-only fields
            'deskripsi'          => 'required_if:type,card|nullable|array|min:1',
            'deskripsi.*'        => 'required_if:type,card|nullable|string|max:500',
        ]);

        if (!Storage::disk('public')->exists('prodi')) {
            Storage::disk('public')->makeDirectory('prodi');
        }

        $image = $request->file('url_images');
        $filename = uniqid() . '.webp';
        $imagePath = 'prodi/' . $filename;

        Image::read($image)
            ->toWebp(80)
            ->save(storage_path('app/public/' . $imagePath));

        if ($request->type === 'image') {
            $content = [
                'url_images' => $imagePath,
                'alt'        => $request->alt,
            ];
        } else {
            $content = [
                'url_images' => $imagePath,
                'alt'        => $request->alt,
                'title'      => $request->title,
                'deskripsi'  => array_values(array_filter($request->deskripsi ?? [])),
            ];
        }

        Prodi::create([
            'type'    => $request->type,
            'content' => $content,
        ]);

        return redirect()->route('admin.prodi.index')
             ->with('success_key', 'prodi.flashCreated');
    }

    public function edit(Prodi $prodi)
    {
        return view('admin.prodi.form_prodi', compact('prodi'));
    }

    public function update(Request $request, Prodi $prodi)
    {
        // type cannot be changed
        $type = $prodi->type;

        $request->validate([
            'url_images'  => 'nullable|image|mimes:jpeg,png,gif|max:2048',
            'alt'         => 'nullable|string|max:255',
            'title'       => 'required_if:existing_type,card|nullable|string|max:255',
            'deskripsi'   => 'required_if:existing_type,card|nullable|array|min:1',
            'deskripsi.*' => 'nullable|string|max:500',
        ]);

        $imagePath = $prodi->content['url_images'] ?? null;

        if ($request->hasFile('url_images')) {
            // Delete old file
            if ($imagePath && Storage::disk('public')->exists($imagePath)) {
                Storage::disk('public')->delete($imagePath);
            }

            if (!Storage::disk('public')->exists('prodi')) {
                Storage::disk('public')->makeDirectory('prodi');
            }

            $image = $request->file('url_images');
            $filename = uniqid() . '.webp';
            $imagePath = 'prodi/' . $filename;

            Image::read($image)
                ->toWebp(80)
                ->save(storage_path('app/public/' . $imagePath));
        }

        if ($type === 'image') {
            $content = [
                'url_images' => $imagePath,
                'alt'        => $request->alt,
            ];
        } else {
            $content = [
                'url_images' => $imagePath,
                'alt'        => $request->alt,
                'title'      => $request->title,
                'deskripsi'  => array_values(array_filter($request->deskripsi ?? [])),
            ];
        }

        $prodi->update(['content' => $content]);

        return redirect()->route('admin.prodi.index')
           ->with('success_key', 'prodi.flashUpdated');
    }

    public function destroy(Prodi $prodi)
    {
        // Delete file from storage
        if (!empty($prodi->content['url_images'])) {
            $imagePath = $prodi->content['url_images'];
            if (Storage::disk('public')->exists($imagePath)) {
                Storage::disk('public')->delete($imagePath);
            }
        }

        $prodi->delete();

        return redirect()->route('admin.prodi.index')
             ->with('success_key', 'prodi.flashDeleted');
    }

    public function bulkDestroy(Request $request)
    {
        $request->validate([
            'ids'   => 'required|array|min:1',
            'ids.*' => 'integer|exists:prodi,id',
        ]);

        $items = Prodi::whereIn('id', $request->ids)->get();

        foreach ($items as $prodi) {
            $imagePath = $prodi->content['url_images'] ?? null;

            if ($imagePath && Storage::disk('public')->exists($imagePath)) {
                Storage::disk('public')->delete($imagePath);
            }

            $prodi->delete();
        }

        return redirect()->route('admin.prodi.index')
            ->with('success_key', 'prodi.flashBulkDeleted');
    }
}