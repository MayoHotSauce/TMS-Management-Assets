<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Services\ActivityLogger;

class BarangController extends Controller
{
    public function index()
    {
        // Get maintenance counts
        $maintenanceCounts = \DB::table('maintenance_logs')
            ->select('barang_id', \DB::raw('COUNT(*) as count'))
            ->where('status', 'completed')
            ->groupBy('barang_id')
            ->get();

        // Get assets with their relationships
        $barang = Asset::with(['room', 'category'])
            ->paginate(10);

        // Add maintenance counts to assets
        $barang->each(function($item) use ($maintenanceCounts) {
            $count = $maintenanceCounts->where('barang_id', $item->id)->first();
            $item->maintenance_count = $count ? $count->count : 0;
        });

        return view('barang.index', compact('barang'));
    }
}