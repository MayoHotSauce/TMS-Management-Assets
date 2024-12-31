<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Services\ActivityLogger;

class BarangController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:view assets', ['only' => ['index', 'show']]);
        $this->middleware('permission:create asset', ['only' => ['create', 'store']]);
        $this->middleware('permission:edit asset', ['only' => ['edit', 'update']]);
        $this->middleware('permission:delete asset', ['only' => ['destroy']]);
        $this->middleware('permission:change asset status', ['only' => ['changeStatus']]);
        $this->middleware('permission:print asset label', ['only' => ['printLabel']]);
        $this->middleware('permission:update company logo', ['only' => ['updateLogo']]);
    }

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

    public function changeStatus(Request $request, Barang $barang)
    {
        if (!auth()->user()->can('change asset status')) {
            return back()->with('error', 'Unauthorized action.');
        }

        $request->validate([
            'status' => 'required|in:siap_dipakai,sedang_dipakai,dalam_perbaikan,rusak,siap_dipinjam,sedang_dipinjam,dimusnahkan'
        ]);

        try {
            $barang->status = $request->status;
            $barang->save();

            return redirect()->route('barang.index')
                ->with('success', 'Status asset berhasil diubah.');
        } catch (\Exception $e) {
            return redirect()->route('barang.index')
                ->with('error', 'Gagal mengubah status asset: ' . $e->getMessage());
        }
    }

    public function updateLogo(Request $request)
    {
        if (!auth()->user()->can('update company logo')) {
            return back()->with('error', 'Unauthorized action.');
        }
        // ... kode update logo ...
    }
}