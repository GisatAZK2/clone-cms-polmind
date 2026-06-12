<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Models\Projects;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;

class ProjectsController extends Controller
{
    public function index(Request $request)
    {
        $query = Projects::query();

        if ($request->filled('search')) {
            $query->where('deskripsi', 'like', '%' . $request->search . '%');
        }

        $items = $query->latest()->paginate(10)->withQueryString();

        return view('admin.projects.views_projects', compact('items'));
    }

    public function create()
    {
        return view('admin.projects.form_projects');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'      => 'required|string|max:255',
            'deskripsi'  => 'required|string|max:5000',
            'url_images' => 'required|image|mimes:jpeg,png,gif|max:2048',
            'alt'        => 'nullable|string|max:255',
        ]);

        if (!Storage::disk('public')->exists('projects')) {
            Storage::disk('public')->makeDirectory('projects');
        }

        $file = $request->file('url_images');
        $filename = uniqid() . '.webp';
        $imagePath = 'projects/' . $filename;

        Image::read($file)
            ->toWebp(80)
            ->save(storage_path('app/public/' . $imagePath));

        Projects::create([
            'title'      => $request->title,
            'deskripsi'  => $request->deskripsi,
            'url_images' => $imagePath,
            'alt'        => $request->alt,
        ]);

        return redirect()->route('admin.projects.index')
            ->with('success_key', 'projects.flashCreated');
    }

    public function edit(Projects $project)
    {
        return view('admin.projects.form_projects', compact('project'));
    }

    public function update(Request $request, Projects $project)
    {
        $request->validate([
            'title'      => 'required|string|max:255',
            'deskripsi'  => 'required|string|max:5000',
            'url_images' => 'nullable|image|mimes:jpeg,png,gif|max:2048',
            'alt'        => 'nullable|string|max:255',
        ]);

        $imagePath = $project->url_images;

        if ($request->hasFile('url_images')) {
            // Delete old file
            if ($imagePath && Storage::disk('public')->exists($imagePath)) {
                Storage::disk('public')->delete($imagePath);
            }

            if (!Storage::disk('public')->exists('projects')) {
                Storage::disk('public')->makeDirectory('projects');
            }

            $file = $request->file('url_images');
            $filename = uniqid() . '.webp';
            $imagePath = 'projects/' . $filename;

            Image::read($file)
                ->toWebp(80)
                ->save(storage_path('app/public/' . $imagePath));
        }

        $project->update([
            'title'      => $request->title,
            'deskripsi'  => $request->deskripsi,
            'url_images' => $imagePath,
            'alt'        => $request->alt,
        ]);

        return redirect()->route('admin.projects.index')
            ->with('success_key', 'projects.flashUpdated');
    }

    public function destroy(Projects $project)
    {
        // Delete file from storage
        if (!empty($project->url_images) && Storage::disk('public')->exists($project->url_images)) {
            Storage::disk('public')->delete($project->url_images);
        }

        $project->delete();

        return redirect()->route('admin.projects.index')
             ->with('success_key', 'projects.flashDeleted');
    }

    public function bulkDestroy(Request $request)
    {
        $request->validate([
            'ids'   => 'required|array|min:1',
            'ids.*' => 'integer|exists:projects,id',
        ]);

        $items = Projects::whereIn('id', $request->ids)->get();

        foreach ($items as $project) {
            if (!empty($project->url_images) && Storage::disk('public')->exists($project->url_images)) {
                Storage::disk('public')->delete($project->url_images);
            }

            $project->delete();
        }

        return redirect()->route('admin.projects.index')
            ->with('success_key', 'projects.flashBulkDeleted');
}
}