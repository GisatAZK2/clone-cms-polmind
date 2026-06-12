<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Models\Dosen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;

class DosenController extends Controller
{

    /**
     * Display public list of dosens (for frontend)
     */
    public function publicIndex()
    {
        // Ambil semua dosen, urutkan berdasarkan nama atau terbaru
        $dosens = Dosen::orderBy('name')->get();
        
        return view('pages.daftar_dosen', compact('dosens'));
    }

    public function tendik()
    {
        // Ambil semua dosen, urutkan berdasarkan nama atau terbaru
        $dosens = Dosen::orderBy('name')->get();
        
        return view('pages.daftar_tendik', compact('dosens'));
    }


    /**
     * Display a listing of dosens.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $dosens = Dosen::when($search, function($query) use ($search) {
            return $query->where('name', 'like', "%{$search}%");
        })
        ->latest()
        ->paginate(10);
        
        return view('admin.dosen.views_dosen', compact('dosens'));
    }

    /**
     * Show the form for creating a new dosen.
     */
    public function create()
    {
        return view('admin.dosen.form_dosen', ['dosen' => null]);
    }

  /**
 * Store Multiple Dosen
 */
public function store(Request $request)
{
    $request->validate([
        'dosen' => 'required|array|min:1',
        'dosen.*.name' => 'required|string|max:255',
        'dosen.*.type' => 'required|in:Dosen_Internal,Expert_industri,Tenaga_Pendidik',
        'dosen.*.alt' => 'required|string|max:255',
        'dosen.*.url_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        'dosen.*.deskripsi' => 'nullable|string',
    ], [
        'dosen.*.name.required' => 'Nama dosen wajib diisi',
        'dosen.*.type.required' => 'Tipe dosen wajib dipilih',
        'dosen.*.alt.required' => 'Alt text wajib diisi',
        'dosen.*.url_image.required' => 'Foto dosen wajib diupload',
    ]);

    $successCount = 0;

    foreach ($request->dosen as $index => $data) {
        $imagePath = null;

        if ($request->hasFile("dosen.{$index}.url_image")) {
            $file = $request->file("dosen.{$index}.url_image");

            if (!Storage::disk('public')->exists('dosens')) {
                Storage::disk('public')->makeDirectory('dosens');
            }

            $filename = uniqid() . '.webp';
            $imagePath = 'dosens/' . $filename;

            Image::read($file)
                ->toWebp(80)
                ->save(storage_path('app/public/' . $imagePath));
        }

        Dosen::create([
            'name'       => $data['name'],
            'url_image'  => $imagePath,
            'type'       => $data['type'],
            'alt'        => $data['alt'],
            'deskripsi'  => $data['deskripsi'] ?? null,   // Tambahan
        ]);

        $successCount++;
    }

    return redirect()->route('admin.dosen.index')
    ->with('success_key', 'dosen.flashCreated')
    ->with('success_params', [
        'count' => $successCount,
    ]);
}

    /**
     * Show the form for editing the specified dosen.
     */
    public function edit($id)
    {
        $dosen = Dosen::findOrFail($id);
        return view('admin.dosen.form_dosen', compact('dosen'));
    }

  /**
 * Update the specified dosen in storage.
 */
public function update(Request $request, $id)
{
    $dosen = Dosen::findOrFail($id);

    $validated = $request->validate([
        'dosen.0.name'      => 'required|string|max:255',
        'dosen.0.type'      => 'required|in:Dosen_Internal,Expert_industri,Tenaga_Pendidik',
        'dosen.0.alt'       => 'required|string|max:255',
        'dosen.0.deskripsi' => 'nullable|string',
        'dosen.0.url_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    $data = $validated['dosen'][0];   // Ambil data index 0

    $imagePath = $dosen->url_image;

    if ($request->hasFile('dosen.0.url_image')) {
        // Hapus gambar lama
        if ($dosen->url_image && Storage::disk('public')->exists($dosen->url_image)) {
            Storage::disk('public')->delete($dosen->url_image);
        }

        if (!Storage::disk('public')->exists('dosens')) {
            Storage::disk('public')->makeDirectory('dosens');
        }

        $file = $request->file('dosen.0.url_image');
        $filename = uniqid() . '.webp';
        $imagePath = 'dosens/' . $filename;

        Image::read($file)
            ->toWebp(80)
            ->save(storage_path('app/public/' . $imagePath));
    }

    $dosen->update([
        'name'       => $data['name'],
        'type'       => $data['type'],
        'alt'        => $data['alt'],
        'deskripsi'  => $data['deskripsi'] ?? $dosen->deskripsi,
        'url_image'  => $imagePath,
    ]);

    return redirect()->route('admin.dosen.index')
                     ->with('success_key', 'dosen.flashUpdated');
}

    /**
     * Remove the specified dosen from storage.
     */
    public function destroy($id)
    {
        $dosen = Dosen::findOrFail($id);
        
        // Delete image if exists
        if ($dosen->url_image && Storage::disk('public')->exists($dosen->url_image)) {
            Storage::disk('public')->delete($dosen->url_image);
        }
        
        $dosen->delete();

        return redirect()->route('admin.dosen.index')->with('success_key', 'dosen.flashDeleted');;
    }

    public function bulkDestroy(Request $request)
    {
        $request->validate([
            'ids'   => 'required|array|min:1',
            'ids.*' => 'integer|exists:dosen,id',
        ]);

        $dosens = Dosen::whereIn('id', $request->ids)->get();

        foreach ($dosens as $dosen) {
            if ($dosen->url_image && Storage::disk('public')->exists($dosen->url_image)) {
                Storage::disk('public')->delete($dosen->url_image);
            }

            $dosen->delete();
        }

        return redirect()->route('admin.dosen.index')
            ->with('success_key', 'dosen.flashBulkDeleted');
    }
}
