<?php
namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Popup;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Laravel\Facades\Image;

class PopupController extends Controller
{
    public function index()
    {
        $popups = Popup::paginate(10);
        return view('admin.popup.views_popup', compact('popups'));
    }

    public function show(Popup $popup)
    {
        $popup = Popup::find($popup->id);
        return view('admin.popup.form_popup', compact('popup'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.popup.form_popup', ['popup' => null]);
    }

    public function store(Request $request)
    {
        if (Popup::count() >= 1) {
            return redirect()->back()->with('error_key', 'popup.flashAlreadyExists');
        }

        $validated = $request->validate([
            'url_image' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
            'alt'       => 'required|string|max:255',
            'content'   => 'required|string',
            'is_active' => 'sometimes|boolean',
        ]);

        if (!Storage::disk('public')->exists('popups')) {
            Storage::disk('public')->makeDirectory('popups');
        }

        $file = $request->file('url_image');
        $filename = Str::uuid() . '.webp';
        $path = 'popups/' . $filename;

        Image::read($file)
            ->toWebp(80)
            ->save(storage_path('app/public/' . $path));

        $popup = Popup::create([
            'url_image' => $path,
            'alt'       => $validated['alt'],
            'content'   => $validated['content'],
            'is_active' => $validated['is_active'] ?? false,
        ]);

        return redirect()->route('admin.popup.index')->with('success_key', 'popup.flashCreated');
    }

     /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $popup = Popup::findOrFail($id);
        return view('admin.popup.form_popup', compact('popup'));
    }

    public function update(Request $request, string $id)
    {
        $popup = Popup::findOrFail($id);

        $validated = $request->validate([
            'url_image' => 'sometimes|image|mimes:jpg,jpeg,png,webp|max:2048',
            'alt'       => 'sometimes|string|max:255',
            'content'   => 'sometimes|string',
            'is_active' => 'sometimes|boolean',
        ]);

        // handle image
        if ($request->hasFile('url_image')) {

            if ($popup->url_image && Storage::disk('public')->exists($popup->url_image)) {
                Storage::disk('public')->delete($popup->url_image);
            }

            if (!Storage::disk('public')->exists('popups')) {
                Storage::disk('public')->makeDirectory('popups');
            }

            $file = $request->file('url_image');
            $filename = Str::uuid() . '.webp';
            $path = 'popups/' . $filename;

            Image::read($file)
                ->toWebp(80)
                ->save(storage_path('app/public/' . $path));

            $validated['url_image'] = $path;
        }

        if (empty($validated)) {
            return redirect()->back()->with('error_key', 'popup.flashNoDataUpdated');
        }

        $popup->update($validated);

        return redirect()->route('admin.popup.index')->with('success_key', 'popup.flashUpdated');
    }

    public function destroy(string $id)
    {
        $popup = Popup::findOrFail($id);

        if ($popup->url_image && Storage::disk('public')->exists($popup->url_image)) {
            Storage::disk('public')->delete($popup->url_image);
        }

        $popup->delete();

        return redirect()->route('admin.popup.index')->with('success_key', 'popup.flashDeleted');
    }

    public function bulkDestroy(Request $request)
    {
        $request->validate([
            'ids'   => 'required|array|min:1',
            'ids.*' => 'integer|exists:popups,id',
        ]);

        $popups = Popup::whereIn('id', $request->ids)->get();

        foreach ($popups as $popup) {
            if ($popup->url_image && Storage::disk('public')->exists($popup->url_image)) {
                Storage::disk('public')->delete($popup->url_image);
            }

            $popup->delete();
        }

        return redirect()->route('admin.popup.index')
            ->with('success_key', 'popup.flashBulkDeleted');
    }
}
