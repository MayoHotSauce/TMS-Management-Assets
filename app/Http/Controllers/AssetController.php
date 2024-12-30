<?php
namespace App\Http\Controllers;

use App\Models\Asset;
use Illuminate\Http\Request;
use App\Services\ActivityLogger;

class AssetController extends Controller
{
    public function index()
    {
        $this->authorize('view_assets');
        \DB::enableQueryLog();

        // First get the maintenance counts
        $maintenanceCounts = \DB::table('maintenance_logs')
            ->select('barang_id', \DB::raw('COUNT(*) as count'))
            ->where('status', 'completed')
            ->groupBy('barang_id')
            ->get();

        // Debug data to pass to view
        $debugData = [
            'maintenance_counts' => $maintenanceCounts,
            'raw_query' => \DB::getQueryLog(),
        ];

        // Then get the assets with their basic info
        $barang = Asset::with(['room', 'category'])
            ->paginate(10);

        // Add the maintenance counts to each asset
        $barang->each(function($item) use ($maintenanceCounts) {
            $count = $maintenanceCounts->where('barang_id', $item->id)->first();
            $item->maintenance_count = $count ? $count->count : 0;
        });

        return view('barang.index', compact('barang', 'debugData'));
    }

    public function create()
    {
        $this->authorize('create_assets');
        $categories = Category::all();
        $locations = Location::all();
        return view('assets.create', compact('categories', 'locations'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'asset_tag' => 'required|string|unique:assets',
            'category_id' => 'required|exists:categories,id',
            'location_id' => 'required|exists:locations,id',
            'purchase_date' => 'required|date',
            'purchase_cost' => 'required|numeric',
            'status' => 'required|in:available,in_use,maintenance,retired'
        ]);

        $asset = Asset::create($validated);

        ActivityLogger::log(
            'create',
            'asset',
            'Created new asset: ' . $asset->name,
            null,
            $asset->toArray()
        );

        return redirect()->route('assets.index')->with('success', 'Asset created successfully');
    }

    public function edit(Asset $asset)
    {
        $this->authorize('edit_assets');
        // ... rest of the code
    }

    public function destroy(Asset $asset)
    {
        $this->authorize('delete_assets');
        // ... rest of the code
    }
}
