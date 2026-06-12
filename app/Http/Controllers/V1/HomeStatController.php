<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Models\HomeStat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeStatController extends Controller
{
    public function index(Request $request)
    {
        $query = HomeStat::query();

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('label', 'like', "%{$search}%")
                  ->orWhere('value', 'like', "%{$search}%");
            });
        }

        $stats = $query->orderBy('order')
                       ->orderBy('id')
                       ->paginate(10)
                       ->withQueryString();

        return view('admin.stats.views_statik_beranda', compact('stats'));
    }

    public function create()
{
    $nextOrder = HomeStat::exists()
        ? HomeStat::max('order') + 1
        : 0;

    return view('admin.stats.form_statik_beranda', compact('nextOrder'));
}

    public function store(Request $request)
    {
        $request->validate([
            'icon'        => 'nullable|string|max:100',
            'label'       => 'required|string|max:255',
            'value'       => 'required|string|max:50',
            'description' => 'nullable|string|max:500',
            'is_active'   => 'boolean',
        ]);

        $data = $request->only([
            'icon',
            'label',
            'value',
            'description',
        ]);

        $data['order'] = HomeStat::exists()? HomeStat::max('order') + 1 : 0;
        $data['is_active'] = $request->boolean('is_active');

        HomeStat::create($data);

        return redirect()->route('admin.home-stat.index')
           ->with('success_key', 'homeStat.flashCreated');
    }

    public function edit(HomeStat $homeStat)
    {
        return view('admin.stats.form_statik_beranda', compact('homeStat'));
    }

    public function update(Request $request, HomeStat $homeStat)
    {
        $request->validate([
            'icon'        => 'nullable|string|max:100',
            'label'       => 'required|string|max:255',
            'value'       => 'required|string|max:50',
            'description' => 'nullable|string|max:500',
            'order'       => 'required|integer|min:0',
            'is_active'   => 'boolean',
        ]);

        DB::transaction(function () use ($request, $homeStat) {
            $oldOrder = $homeStat->order;
            $newOrder = (int) $request->order;

            $swapStat = HomeStat::where('id', '!=', $homeStat->id)
                ->where('order', $newOrder)
                ->first();

            if ($swapStat) {
                $swapStat->update([
                    'order' => $oldOrder,
                ]);
            }

            $homeStat->update([
                'icon'        => $request->icon,
                'label'       => $request->label,
                'value'       => $request->value,
                'description' => $request->description,
                'order'       => $newOrder,
                'is_active'   => $request->boolean('is_active'),
            ]);
        });

        return redirect()->route('admin.home-stat.index')
             ->with('success_key', 'homeStat.flashUpdated');
    }

    public function destroy(HomeStat $homeStat)
    {
        $homeStat->delete();

        return redirect()->route('admin.home-stat.index')
           ->with('success_key', 'homeStat.flashDeleted');
    }

    public function bulkDestroy(Request $request)
    {
        $request->validate([
            'ids'   => 'required|array|min:1',
            'ids.*' => 'integer|exists:home_stats,id',
        ]);

        HomeStat::whereIn('id', $request->ids)->delete();

        return redirect()->route('admin.home-stat.index')
            ->with('success_key', 'homeStat.flashBulkDeleted');
    }
}