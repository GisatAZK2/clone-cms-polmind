<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Models\Program_Sarjana_Terapan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;

class ProgramSarjanaTerapanController extends Controller
{
    public function publicIndex()
    {
        // Ambil semua program sarjana terapan, urutkan berdasarkan id dari kecil ke besar
        $program_sarjana_terapan = Program_Sarjana_Terapan::orderBy('id', 'asc')->get();

        return view('pages.prodi', compact('program_sarjana_terapan'));
    }
    
    /**
     * Display a listing of program sarjana terapan.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $programs = Program_Sarjana_Terapan::when($search, function($query) use ($search) {
            return $query->whereRaw("JSON_UNQUOTE(JSON_EXTRACT(content, '$.nama_prodi')) LIKE ?", ["%{$search}%"]);
        })
        ->latest()
        ->paginate(10);

        return view('admin.program_sarjana_terapan.views_program_sarjana_terapan', compact('programs'));
    }

    /**
     * Show the form for creating a new program.
     */
    public function create()
    {
        return view('admin.program_sarjana_terapan.form_program_sarjana_terapan', ['program' => null]);
    }

    /**
     * Store a newly created program in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_prodi' => 'required|string|max:255',
            'gelar_sarjana' => 'required|string|max:255',
            'deskripsi_prodi' => 'required|string',
            'semester_1' => 'required|string',
            'semester_2' => 'required|string',
            'semester_3' => 'required|string',
            'semester_4' => 'required|string',
            'semester_5' => 'required|string',
            'semester_6' => 'required|string',
            'semester_7' => 'required|string',
            'semester_8' => 'required|string',
            'gambar_prodi.*' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'image_alt' => 'nullable|array',
            'image_alt.*' => 'nullable|string|max:255',
        ]);

        $imagePaths = [];
        $imageAlt = [];
        
        if ($request->hasFile('gambar_prodi')) {
            if (!Storage::disk('public')->exists('program_sarjana_terapan')) {
                Storage::disk('public')->makeDirectory('program_sarjana_terapan');
            }

            $altTexts = $request->input('image_alt', []);
            
            foreach ($request->file('gambar_prodi') as $index => $file) {
                $filename = uniqid() . '.webp';
                $path = 'program_sarjana_terapan/' . $filename;

                Image::read($file)
                    ->toWebp(80)
                    ->save(storage_path('app/public/' . $path));

                $imagePaths[] = $path;
                $imageAlt[] = $altTexts[$index] ?? '';
            }
        }

        $content = [
            'nama_prodi' => $validated['nama_prodi'],
            'gelar_sarjana' => $validated['gelar_sarjana'],
            'deskripsi_prodi' => $validated['deskripsi_prodi'],
            'semester_1' => $validated['semester_1'],
            'semester_2' => $validated['semester_2'],
            'semester_3' => $validated['semester_3'],
            'semester_4' => $validated['semester_4'],
            'semester_5' => $validated['semester_5'],
            'semester_6' => $validated['semester_6'],
            'semester_7' => $validated['semester_7'],
            'semester_8' => $validated['semester_8'],
            'gambar_prodi' => $imagePaths,
            'image_alt' => $imageAlt,
        ];

        Program_Sarjana_Terapan::create(['content' => $content]);

        return redirect()->route('admin.program_sarjana_terapan.index')->with('success_key', 'programSarjanaTerapan.flashCreated');
    }

    /**
     * Show the form for editing the specified program.
     */
    public function edit($id)
    {
        $program = Program_Sarjana_Terapan::findOrFail($id);
        return view('admin.program_sarjana_terapan.form_program_sarjana_terapan', compact('program'));
    }

    /**
     * Update the specified program in storage.
     */
    public function update(Request $request, $id)
    {
        $program = Program_Sarjana_Terapan::findOrFail($id);

        $validated = $request->validate([
            'nama_prodi' => 'required|string|max:255',
            'gelar_sarjana' => 'required|string|max:255',
            'deskripsi_prodi' => 'required|string',
            'semester_1' => 'required|string',
            'semester_2' => 'required|string',
            'semester_3' => 'required|string',
            'semester_4' => 'required|string',
            'semester_5' => 'required|string',
            'semester_6' => 'required|string',
            'semester_7' => 'required|string',
            'semester_8' => 'required|string',
            'gambar_prodi.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'existing_images' => 'nullable|array',
            'existing_images.*' => 'string',
            'image_order' => 'nullable|array',
            'image_order.*' => 'string',
            'image_alt' => 'nullable|array',
            'image_alt.*' => 'nullable|string|max:255',
        ]);

        $oldImages = $program->content['gambar_prodi'] ?? [];
        $oldAlt = $program->content['image_alt'] ?? [];
        $existingImages = $request->input('existing_images', []);
        $imageOrder = $request->input('image_order', []);
        $imageAltInputs = $request->input('image_alt', []);
        $newImages = [];

        // Handle new uploads
        if ($request->hasFile('gambar_prodi')) {
            if (!Storage::disk('public')->exists('program_sarjana_terapan')) {
                Storage::disk('public')->makeDirectory('program_sarjana_terapan');
            }

            foreach ($request->file('gambar_prodi') as $file) {
                $filename = uniqid() . '.webp';
                $path = 'program_sarjana_terapan/' . $filename;

                Image::read($file)
                    ->toWebp(80)
                    ->save(storage_path('app/public/' . $path));

                $newImages[] = $path;
            }
        }

        $orderedImages = [];
        $orderedAlt = [];
        
        foreach ($imageOrder as $orderIdx => $orderValue) {
            if (str_starts_with($orderValue, 'new-')) {
                $newIndex = intval(substr($orderValue, 4));
                if (isset($newImages[$newIndex])) {
                    $orderedImages[] = $newImages[$newIndex];
                    $orderedAlt[] = $imageAltInputs[$orderIdx] ?? '';
                }
            } elseif (in_array($orderValue, $existingImages, true)) {
                $orderedImages[] = $orderValue;
                $orderedAlt[] = $imageAltInputs[$orderIdx] ?? '';
            }
        }

        if (empty($orderedImages)) {
            $orderedImages = array_merge($existingImages, $newImages);
            $orderedAlt = array_merge($imageAltInputs, array_fill(0, count($newImages), ''));
        }

        // Delete removed images
        $removedImages = array_diff($oldImages, $existingImages);
        foreach ($removedImages as $image) {
            if (Storage::disk('public')->exists($image)) {
                Storage::disk('public')->delete($image);
            }
        }

        $content = [
            'nama_prodi' => $validated['nama_prodi'],
            'gelar_sarjana' => $validated['gelar_sarjana'],
            'deskripsi_prodi' => $validated['deskripsi_prodi'],
            'semester_1' => $validated['semester_1'],
            'semester_2' => $validated['semester_2'],
            'semester_3' => $validated['semester_3'],
            'semester_4' => $validated['semester_4'],
            'semester_5' => $validated['semester_5'],
            'semester_6' => $validated['semester_6'],
            'semester_7' => $validated['semester_7'],
            'semester_8' => $validated['semester_8'],
            'gambar_prodi' => $orderedImages,
            'image_alt' => $orderedAlt,
        ];

        $program->update(['content' => $content]);

        return redirect()->route('admin.program_sarjana_terapan.index')->with('success_key', 'programSarjanaTerapan.flashUpdated');
    }

    /**
     * Remove the specified program from storage.
     */
    public function destroy($id)
    {
        $program = Program_Sarjana_Terapan::findOrFail($id);

        // Delete all images
        if (isset($program->content['gambar_prodi'])) {
            foreach ($program->content['gambar_prodi'] as $image) {
                if (Storage::disk('public')->exists($image)) {
                    Storage::disk('public')->delete($image);
                }
            }
        }

        $program->delete();

        return redirect()->route('admin.program_sarjana_terapan.index')->with('success_key', 'programSarjanaTerapan.flashDeleted');
    }

    public function bulkDestroy(Request $request)
    {
        $request->validate([
            'ids'   => 'required|array|min:1',
            'ids.*' => 'integer|exists:program_sarjana_terapan,id',
        ]);

        $programs = Program_Sarjana_Terapan::whereIn('id', $request->ids)->get();

        foreach ($programs as $program) {
            if (isset($program->content['gambar_prodi']) && is_array($program->content['gambar_prodi'])) {
                foreach ($program->content['gambar_prodi'] as $image) {
                    if ($image && Storage::disk('public')->exists($image)) {
                        Storage::disk('public')->delete($image);
                    }
                }
            }

            $program->delete();
        }

        return redirect()->route('admin.program_sarjana_terapan.index')
            ->with('success_key', 'programSarjanaTerapan.flashBulkDeleted');
    }
}