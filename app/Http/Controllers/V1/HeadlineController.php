<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Models\Headline;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;

class HeadlineController extends Controller
{
    /**
     * Display a listing of headlines.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $headlines = Headline::when($search, function($query) use ($search) {
                return $query->where('title', 'like', "%{$search}%");
            })
            ->orderBy('urutan', 'asc')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        return view('admin.headline.views_headline', compact('headlines'));
    }

    /**
     * Show the form for creating a new headline.
     */
    public function create()
    {
        return view('admin.headline.form_headline', ['headline' => null]);
    }

    /**
     * Store a newly created headline in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'     => 'required|string|max:255',
            'url_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'alt'       => 'nullable|string|max:255',
            'status'    => 'required|in:active,inactive',
        ]);

        if ($request->hasFile('url_image')) {
            if (!Storage::disk('public')->exists('headlines')) {
                Storage::disk('public')->makeDirectory('headlines');
            }

            $file = $request->file('url_image');
            $filename = uniqid() . '.webp';
            $storedPath = 'headlines/' . $filename;

            Image::read($file)
                ->toWebp(80)
                ->save(storage_path('app/public/' . $storedPath));

            $validated['url_image'] = '/storage/' . $storedPath;
        }

        $validated['urutan'] = (Headline::max('urutan') ?? 0) + 1;
        Headline::create($validated);

        return redirect()->route('admin.headline.index')->with('success_key', 'headline.flashCreated');;
    }

    /**
     * Reorder headline items.
     */
    public function reorder(Request $request)
    {
        $validated = $request->validate([
            'order' => 'required|string',
        ]);

        $ids = array_filter(explode(',', $validated['order']), function ($id) {
            return is_numeric($id);
        });

        foreach ($ids as $position => $id) {
            Headline::where('id', $id)->update(['urutan' => $position + 1]);
        }

        return redirect()->route('admin.headline.index')->with('success_key', 'headline.flashReordered');;
    }

    /**
     * Show the form for editing the specified headline.
     */
    public function edit($id)
    {
        $headline = Headline::findOrFail($id);
        
        return view('admin.headline.form_headline', compact('headline'));
    }

    /**
     * Update the specified headline in storage.
     */
    public function update(Request $request, $id)
    {
        $headline = Headline::findOrFail($id);

        $validated = $request->validate([
            'title'     => 'required|string|max:255',
            'url_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'alt'       => 'nullable|string|max:255',
            'status'    => 'required|in:active,inactive',
        ]);

        if ($request->hasFile('url_image')) {
            $oldPath = $this->storagePath($headline->url_image);
            if ($oldPath && Storage::disk('public')->exists($oldPath)) {
                Storage::disk('public')->delete($oldPath);
            }

            if (!Storage::disk('public')->exists('headlines')) {
                Storage::disk('public')->makeDirectory('headlines');
            }

            $file = $request->file('url_image');
            $filename = uniqid() . '.webp';
            $storedPath = 'headlines/' . $filename;

            Image::read($file)
                ->toWebp(80)
                ->save(storage_path('app/public/' . $storedPath));

            $validated['url_image'] = '/storage/' . $storedPath;
        }

        $headline->update($validated);

        return redirect()->route('admin.headline.index')->with('success_key', 'headline.flashUpdated');;
    }

    /**
     * Remove the specified headline from storage.
     */
    public function destroy($id)
    {
        $headline = Headline::findOrFail($id);

        // Delete image
        $oldPath = $this->storagePath($headline->url_image);
        if ($oldPath && Storage::disk('public')->exists($oldPath)) {
            Storage::disk('public')->delete($oldPath);
        }

        $headline->delete();

        return redirect()->route('admin.headline.index')->with('success_key', 'headline.flashDeleted');
    }

    public function bulkDestroy(Request $request)
    {
        $request->validate([
            'ids'   => 'required|array|min:1',
            'ids.*' => 'integer|exists:headline,id',
        ]);

        $headlines = Headline::whereIn('id', $request->ids)->get();

        foreach ($headlines as $headline) {
            $oldPath = $this->storagePath($headline->url_image);

            if ($oldPath && Storage::disk('public')->exists($oldPath)) {
                Storage::disk('public')->delete($oldPath);
            }

            $headline->delete();
        }

        return redirect()->route('admin.headline.index')
            ->with('success_key', 'headline.flashBulkDeleted');
    }

    private function storagePath(?string $urlImage): ?string
    {
        if (empty($urlImage)) {
            return null;
        }

        // Remove /storage/ prefix correctly
        if (str_starts_with($urlImage, '/storage/')) {
            return substr($urlImage, strlen('/storage/'));
        }

        return $urlImage;
    }

}
