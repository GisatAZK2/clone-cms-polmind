<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Models\Karakter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;

class KarakterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Karakter::query();

        if ($request->filled('search')) {
            $query->where('nama_konten', 'like', '%' . $request->search . '%')
                  ->orWhere('deskripsi', 'like', '%' . $request->search . '%');
        }

        $karakters = $query->latest()->paginate(10)->withQueryString();

        return view('admin.karakter.views_karakter', compact('karakters'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.karakter.form_karakter');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_konten' => 'required|string|max:255',
            'url_image'   => 'required|image|mimes:jpeg,png,gif,jpg|max:2048',
            'alt'         => 'nullable|string|max:255',
            'deskripsi'   => 'nullable|string',
        ]);

        if (!Storage::disk('public')->exists('karakter')) {
            Storage::disk('public')->makeDirectory('karakter');
        }

        $image = $request->file('url_image');
        $filename = uniqid() . '.webp';
        $imagePath = 'karakter/' . $filename;

        Image::read($image)
            ->toWebp(80)
            ->save(storage_path('app/public/' . $imagePath));

        Karakter::create([
            'nama_konten' => $request->nama_konten,
            'url_image'   => $imagePath,
            'alt'         => $request->alt,
            'deskripsi'   => $request->deskripsi,
        ]);

        return redirect()->route('admin.karakter.index')
            ->with('success_key', 'karakter.flashCreated');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Karakter $karakter)
    {
        return view('admin.karakter.form_karakter', compact('karakter'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Karakter $karakter)
    {
        $request->validate([
            'nama_konten' => 'required|string|max:255',
            'url_image'   => 'nullable|image|mimes:jpeg,png,gif,jpg|max:2048',
            'alt'         => 'nullable|string|max:255',
            'deskripsi'   => 'nullable|string',
        ]);

        $imagePath = $karakter->url_image;

        if ($request->hasFile('url_image')) {
            if ($imagePath && Storage::disk('public')->exists($imagePath)) {
                Storage::disk('public')->delete($imagePath);
            }

            if (!Storage::disk('public')->exists('karakter')) {
                Storage::disk('public')->makeDirectory('karakter');
            }

            $image = $request->file('url_image');
            $filename = uniqid() . '.webp';
            $imagePath = 'karakter/' . $filename;

            Image::read($image)
                ->toWebp(80)
                ->save(storage_path('app/public/' . $imagePath));
        }

        $karakter->update([
            'nama_konten' => $request->nama_konten,
            'url_image'   => $imagePath,
            'alt'         => $request->alt,
            'deskripsi'   => $request->deskripsi,
        ]);

        return redirect()->route('admin.karakter.index')
            ->with('success_key', 'karakter.flashUpdated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Karakter $karakter)
    {
        if (!empty($karakter->url_image) && Storage::disk('public')->exists($karakter->url_image)) {
            Storage::disk('public')->delete($karakter->url_image);
        }

        $karakter->delete();

        return redirect()->route('admin.karakter.index')
            ->with('success_key', 'karakter.flashDeleted');
    }

    public function bulkDestroy(Request $request)
    {
        $request->validate([
            'ids'   => 'required|array|min:1',
            'ids.*' => 'integer|exists:karakter,id',
        ]);

        $karakters = Karakter::whereIn('id', $request->ids)->get();

        foreach ($karakters as $karakter) {
            if (!empty($karakter->url_image) && Storage::disk('public')->exists($karakter->url_image)) {
                Storage::disk('public')->delete($karakter->url_image);
            }

            $karakter->delete();
        }

        return redirect()->route('admin.karakter.index')
            ->with('success_key', 'karakter.flashBulkDeleted');
    }
}
