<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Models\Profile_Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;

class ProfilePageController extends Controller
{

    public function PublicIndex(Request $request)
    {
        $cover = Profile_Page::where('type', 'cover')->first();
        $visiMisi = Profile_Page::where('type', 'visi_misi')->first();
        $profile = Profile_Page::where('type', 'profile')->first();

        return view('pages.profile', compact('cover', 'visiMisi', 'profile'));
    }

    /**
     * Display a listing of profile pages.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $profilePages = Profile_Page::when($search, function ($query) use ($search) {
            return $query->where('type', 'like', "%{$search}%");
        })
            ->latest()
            ->paginate(10);

        return view('admin.profile-page.views_profile_page', compact('profilePages'));
    }

    /**
     * Show the form for creating a new profile page.
     * Passes already-used types so the FE can disable them.
     */
    public function create()
    {
        $usedTypes = Profile_Page::pluck('type')->toArray();
        return view('admin.profile-page.form_profile_page', [
            'profilePage' => null,
            'usedTypes'   => $usedTypes,
        ]);
    }

    /**
     * Store a newly created profile page in storage.
     *
     * Field name convention (sesuai view):
     *   cover   → url_images, alt
     *   profile → profile_url_images, profile_alt, nama_profil, deskripsi_profile
     *   visi_misi → visi, misi
     */
   /**
 * Store a newly created profile page in storage.
 */
public function store(Request $request)
{
    $type = $request->input('type');

    // HANYA 'cover' dan 'visi_misi' yang tidak boleh duplicate
    if (in_array($type, ['cover', 'visi_misi'])) {
        if (Profile_Page::where('type', $type)->exists()) {
            return back()
                ->withInput()
                ->withErrors(['type' => "Tipe '{$type}' sudah ada di database dan tidak dapat dibuat ulang."]);
        }
    }

    // ====================== COVER ======================
    if ($type === 'cover') {
        $validated = $request->validate([
            'url_images' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'alt'        => 'required|string|max:255',
        ]);

        if (!Storage::disk('public')->exists('profile-pages')) {
            Storage::disk('public')->makeDirectory('profile-pages');
        }

        $file = $request->file('url_images');
        $filename = uniqid() . '.webp';
        $imagePath = 'profile-pages/' . $filename;

        Image::read($file)
            ->toWebp(80)
            ->save(storage_path('app/public/' . $imagePath));

        $data = [
            'type'       => $type,
            'url_images' => $imagePath,
            'alt'        => $validated['alt'],
        ];
    } 
    // ====================== VISI MISI ======================
    elseif ($type === 'visi_misi') {
        $validated = $request->validate([
            'visi' => 'required|string',
            'misi' => 'required|string',
        ]);

        $data = [
            'type' => $type,
            'visi' => $validated['visi'],
            'misi' => $validated['misi'],
        ];
    } 
    // ====================== PROFILE (MULTIPLE) ======================
    elseif ($type === 'profile') {
        $validated = $request->validate([
            'profile_url_images' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'profile_alt'        => 'required|string|max:255',
            'nama_profil'        => 'required|string|max:255',
            'deskripsi_profile'  => 'required|string',
        ]);

        if (!Storage::disk('public')->exists('profile-pages')) {
            Storage::disk('public')->makeDirectory('profile-pages');
        }

        $file = $request->file('profile_url_images');
        $filename = uniqid() . '.webp';
        $imagePath = 'profile-pages/' . $filename;

        Image::read($file)
            ->toWebp(80)
            ->save(storage_path('app/public/' . $imagePath));

        $data = [
            'type'       => $type,
            'url_images' => $imagePath,
            'alt'        => $validated['profile_alt'],
            'content'    => [
                'nama_profil'       => $validated['nama_profil'],
                'deskripsi_profile' => $validated['deskripsi_profile'],
            ],
        ];
    } 
    else {
        return back()->withInput()->withErrors(['type' => 'Tipe tidak valid.']);
    }

    Profile_Page::create($data);

    return redirect()->route('admin.profile-page.index')
         ->with('success_key', 'profilePage.flashCreated');
}

    /**
     * Show the form for editing the specified profile page.
     */
    public function edit($id)
    {
        $profilePage = Profile_Page::findOrFail($id);
        return view('admin.profile-page.form_profile_page', compact('profilePage'));
    }

    /**
     * Update the specified profile page in storage.
     * Type cannot be changed on update.
     */
    public function update(Request $request, $id)
    {
        $profilePage = Profile_Page::findOrFail($id);

        // Enforce original type — always use DB value, ignore submitted value
        $type = $profilePage->type;

        if ($type === 'cover') {
            $validated = $request->validate([
                'url_images' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'alt'        => 'required|string|max:255',
            ]);

            $imagePath = $profilePage->url_images;
            if ($request->hasFile('url_images')) {
                $this->deleteOldImage($profilePage->url_images);

                if (!Storage::disk('public')->exists('profile-pages')) {
                    Storage::disk('public')->makeDirectory('profile-pages');
                }

                $file = $request->file('url_images');
                $filename = uniqid() . '.webp';
                $imagePath = 'profile-pages/' . $filename;

                Image::read($file)
                    ->toWebp(80)
                    ->save(storage_path('app/public/' . $imagePath));
            }

            $data = [
                'type'       => $type,
                'url_images' => $imagePath,
                'alt'        => $validated['alt'],
                'visi'       => null,
                'misi'       => null,
                'content'    => null,
            ];

        } elseif ($type === 'visi_misi') {
            $validated = $request->validate([
                'visi' => 'required|string',
                'misi' => 'required|string',
            ]);

            $data = [
                'type'       => $type,
                'visi'       => $validated['visi'],
                'misi'       => $validated['misi'],
                'url_images' => null,
                'alt'        => null,
                'content'    => null,
            ];

        } elseif ($type === 'profile') {
            // Note: field names are profile_url_images & profile_alt (see view)
            $validated = $request->validate([
                'profile_url_images' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'profile_alt'        => 'required|string|max:255',
                'nama_profil'        => 'required|string|max:255',
                'deskripsi_profile'  => 'required|string',
            ]);

            $imagePath = $profilePage->url_images;
            if ($request->hasFile('profile_url_images')) {
                $this->deleteOldImage($profilePage->url_images);

                if (!Storage::disk('public')->exists('profile-pages')) {
                    Storage::disk('public')->makeDirectory('profile-pages');
                }

                $file = $request->file('profile_url_images');
                $filename = uniqid() . '.webp';
                $imagePath = 'profile-pages/' . $filename;

                Image::read($file)
                    ->toWebp(80)
                    ->save(storage_path('app/public/' . $imagePath));
            }

            $data = [
                'type'       => $type,
                'url_images' => $imagePath,
                'alt'        => $validated['profile_alt'],
                'content'    => [
                    'nama_profil'       => $validated['nama_profil'],
                    'deskripsi_profile' => $validated['deskripsi_profile'],
                ],
                'visi' => null,
                'misi' => null,
            ];

        } else {
            return back()->withInput()->withErrors(['type' => 'Tipe tidak valid.']);
        }

        $profilePage->update($data);

        return redirect()->route('admin.profile-page.index')
            ->with('success_key', 'profilePage.flashUpdated');
    }

    /**
     * Remove the specified profile page from storage.
     */
    public function destroy($id)
    {
        $profilePage = Profile_Page::findOrFail($id);
        $this->deleteOldImage($profilePage->url_images);
        $profilePage->delete();

        return redirect()->route('admin.profile-page.index')
             ->with('success_key', 'profilePage.flashDeleted');
    }

    public function bulkDestroy(Request $request)
    {
        $request->validate([
            'ids'   => 'required|array|min:1',
            'ids.*' => 'integer|exists:profile_page,id',
        ]);

        $profilePages = Profile_Page::whereIn('id', $request->ids)->get();

        foreach ($profilePages as $profilePage) {
            $this->deleteOldImage($profilePage->url_images);
            $profilePage->delete();
        }

        return redirect()->route('admin.profile-page.index')
            ->with('success_key', 'profilePage.flashBulkDeleted');
    }

    /**
     * Helper: delete image from public storage if it exists.
     */
    private function deleteOldImage(?string $path): void
    {
        if ($path && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }
}