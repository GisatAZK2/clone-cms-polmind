<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Models\Sambutan_Direktur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;

class SambutanDirekturController extends Controller
{
    public function index(Request $request)
    {
        $items = Sambutan_Direktur::latest()->paginate(10)->withQueryString();

        return view('admin.sambutan-direktur.views_sambutan_direktur', compact('items'));
    }

    public function create()
    {
        return view('admin.sambutan-direktur.form_sambutan_direktur');
    }

    public function store(Request $request)
    {
        $request->validate([
            'foto_direktur'   => 'required|image|mimes:jpeg,png,gif|max:2048',
            'judul_sambutan'  => 'required|string|max:255',
            'kata_sambutan'   => 'required|string|max:5000',
        ]);

        if (!Storage::disk('public')->exists('sambutan-direktur')) {
            Storage::disk('public')->makeDirectory('sambutan-direktur');
        }

        $file = $request->file('foto_direktur');
        $filename = uniqid() . '.webp';
        $fotoPath = 'sambutan-direktur/' . $filename;

        Image::read($file)
            ->toWebp(80)
            ->save(storage_path('app/public/' . $fotoPath));

        Sambutan_Direktur::create([
            'content' => [
                'foto_direktur'  => $fotoPath,
                'judul_sambutan' => $request->judul_sambutan,
                'kata_sambutan'  => $request->kata_sambutan,
            ],
        ]);

        return redirect()->route('admin.sambutan-direktur.index')
             ->with('success_key', 'sambutanDirektur.flashCreated');
    }

    public function edit(Sambutan_Direktur $sambutanDirektur)
    {
        return view('admin.sambutan-direktur.form_sambutan_direktur', ['item' => $sambutanDirektur]);
    }

    public function update(Request $request, Sambutan_Direktur $sambutanDirektur)
    {
        $request->validate([
            'foto_direktur'   => 'nullable|image|mimes:jpeg,png,gif|max:2048',
            'judul_sambutan'  => 'required|string|max:255',
            'kata_sambutan'   => 'required|string|max:5000',
        ]);

        $fotoPath = $sambutanDirektur->content['foto_direktur'] ?? null;

        if ($request->hasFile('foto_direktur')) {
            // Delete old file
            if ($fotoPath && Storage::disk('public')->exists($fotoPath)) {
                Storage::disk('public')->delete($fotoPath);
            }

            if (!Storage::disk('public')->exists('sambutan-direktur')) {
                Storage::disk('public')->makeDirectory('sambutan-direktur');
            }

            $file = $request->file('foto_direktur');
            $filename = uniqid() . '.webp';
            $fotoPath = 'sambutan-direktur/' . $filename;

            Image::read($file)
                ->toWebp(80)
                ->save(storage_path('app/public/' . $fotoPath));
        }

        $sambutanDirektur->update([
            'content' => [
                'foto_direktur'  => $fotoPath,
                'judul_sambutan' => $request->judul_sambutan,
                'kata_sambutan'  => $request->kata_sambutan,
            ],
        ]);

        return redirect()->route('admin.sambutan-direktur.index')
             ->with('success_key', 'sambutanDirektur.flashUpdated');
    }

    public function destroy(Sambutan_Direktur $sambutanDirektur)
    {
        // Delete file from storage
        if (!empty($sambutanDirektur->content['foto_direktur'])) {
            $fotoPath = $sambutanDirektur->content['foto_direktur'];
            if (Storage::disk('public')->exists($fotoPath)) {
                Storage::disk('public')->delete($fotoPath);
            }
        }

        $sambutanDirektur->delete();

        return redirect()->route('admin.sambutan-direktur.index')
           ->with('success_key', 'sambutanDirektur.flashDeleted');
    }

    public function bulkDestroy(Request $request)
    {
        $request->validate([
            'ids'   => 'required|array|min:1',
            'ids.*' => 'integer|exists:sambutan_direktur,id',
        ]);

        $items = Sambutan_Direktur::whereIn('id', $request->ids)->get();

        foreach ($items as $sambutanDirektur) {
            if (!empty($sambutanDirektur->content['foto_direktur'])) {
                $fotoPath = $sambutanDirektur->content['foto_direktur'];

                if (Storage::disk('public')->exists($fotoPath)) {
                    Storage::disk('public')->delete($fotoPath);
                }
            }

            $sambutanDirektur->delete();
        }

        return redirect()->route('admin.sambutan-direktur.index')
            ->with('success_key', 'sambutanDirektur.flashBulkDeleted');
    }
}