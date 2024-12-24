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

    public function changeStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:siap_dipakai,sedang_dipakai,dalam_perbaikan,rusak,siap_dipinjam,sedang_dipinjam,dimusnahkan'
        ]);

        try {
            $barang = Asset::findOrFail($id);
            $barang->status = $request->status;
            $barang->save();

            return redirect()->route('barang.index')
                ->with('success', 'Status asset berhasil diubah.');
        } catch (\Exception $e) {
            return redirect()->route('barang.index')
                ->with('error', 'Gagal mengubah status asset: ' . $e->getMessage());
        }
    }
}