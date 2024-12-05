<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use PDF;
use App\Models\Asset;
use App\Models\StockCheck;
use App\Models\StockCheckItem;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\StockCheckExport;
use Carbon\Carbon;

class StockController extends Controller
{
    public function index()
    {
        $currentCheck = StockCheck::where('created_by', auth()->id())
            ->where('status', 'ongoing')
            ->with('items.asset')
            ->first();

        $assets = Asset::all()->map(function($asset) use ($currentCheck) {
            if ($currentCheck) {
                $item = $currentCheck->items->where('asset_id', $asset->id)->first();
                $asset->pivot = $item;
            } else {
                $asset->pivot = null;
            }
            return $asset;
        });

        return view('stock.index', compact('assets', 'currentCheck'));
    }

    public function update(Request $request)
    {
        $stockCheck = StockCheck::firstOrCreate(
            ['created_by' => auth()->id(), 'status' => 'ongoing'],
            ['last_updated_at' => now()]
        );

        foreach ($request->input('assets', []) as $assetId) {
            StockCheckItem::updateOrCreate(
                [
                    'stock_check_id' => $stockCheck->id,
                    'asset_id' => $assetId
                ],
                [
                    'description' => $request->input("descriptions.$assetId"),
                    'is_checked' => true
                ]
            );
        }

        return redirect()->back()->with('success', 'Stock check updated successfully');
    }

    public function confirm(Request $request)
    {
        $stockCheck = StockCheck::where('created_by', auth()->id())
            ->where('status', 'ongoing')
            ->firstOrFail();

        $stockCheck->update([
            'status' => 'completed',
            'completed_at' => now()
        ]);

        return response()->json([
            'message' => 'Stock check completed successfully',
            'excel_url' => route('stock.download.csv', $stockCheck->id)
        ]);
    }

    public function downloadCsv($id)
    {
        try {
            // Get the stock check with all related assets
            $stockCheck = StockCheck::with(['creator'])->findOrFail($id);
            
            // Get all assets and their check status
            $assets = Asset::leftJoin('stock_check_items', function($join) use ($id) {
                $join->on('assets.id', '=', 'stock_check_items.asset_id')
                     ->where('stock_check_items.stock_check_id', '=', $id);
            })
            ->select(
                'assets.*',
                'stock_check_items.is_checked',
                'stock_check_items.description',
                'stock_check_items.updated_at as checked_at'
            )
            ->get();

            $filename = 'laporan_stock_' . $id . '_' . now()->format('Y-m-d') . '.csv';
            
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            ];
            
            // Column widths
            $colWidth = [
                'nama' => 40,
                'desc' => 30,
                'status' => 25,
                'tanggal' => 25
            ];
            
            $callback = function() use ($stockCheck, $assets, $colWidth) {
                $file = fopen('php://output', 'w');
                
                // Title and header info
                fputcsv($file, ['=== LAPORAN PEMERIKSAAN ASET ===']);
                fputcsv($file, []);
                fputcsv($file, ['Detail Laporan:']);
                fputcsv($file, ['Dibuat Oleh', $stockCheck->creator->name]);
                fputcsv($file, ['Tanggal', $stockCheck->created_at->format('d-m-Y H:i')]);
                fputcsv($file, ['Status', $stockCheck->status === 'completed' ? 'Selesai' : 'Sedang Berlangsung']);
                fputcsv($file, ['ID Laporan', '#' . $stockCheck->id]);
                fputcsv($file, []);
                
                // Table borders
                $border = '+' . str_repeat('=', $colWidth['nama']) . 
                         '+' . str_repeat('=', $colWidth['desc']) . 
                         '+' . str_repeat('=', $colWidth['status']) . 
                         '+' . str_repeat('=', $colWidth['tanggal']) . '+';
                
                $separator = '+' . str_repeat('-', $colWidth['nama']) . 
                            '+' . str_repeat('-', $colWidth['desc']) . 
                            '+' . str_repeat('-', $colWidth['status']) . 
                            '+' . str_repeat('-', $colWidth['tanggal']) . '+';
                
                // Data rows
                foreach ($assets as $asset) {
                    $status = $asset->is_checked ? 'SUDAH DIPERIKSA' : 'BELUM DIPERIKSA ⚠️';
                    $description = $asset->description ?? 'Tidak ada';
                    $checkDate = $asset->checked_at ? Carbon::parse($asset->checked_at)->format('d-m-Y H:i') : '-';
                    
                    fputcsv($file, ['| ' . str_pad($asset->name, $colWidth['nama']-2) . 
                                   ' | ' . str_pad($description, $colWidth['desc']-2) . 
                                   ' | ' . str_pad($status, $colWidth['status']-2) . 
                                   ' | ' . str_pad($checkDate, $colWidth['tanggal']-2) . ' |']);
                    fputcsv($file, [$separator]);
                }
                
                // Summary section
                $totalAssets = $assets->count();
                $checkedAssets = $assets->where('is_checked', true)->count();
                $uncheckedAssets = $totalAssets - $checkedAssets;
                
                fputcsv($file, []);
                fputcsv($file, ['RINGKASAN:']);
                $summaryBorder = '+' . str_repeat('=', 35) . '+' . str_repeat('=', 15) . '+';
                fputcsv($file, [$summaryBorder]);
                fputcsv($file, ['| ' . str_pad('Total Aset', 33) . ' | ' . str_pad($totalAssets, 13) . ' |']);
                fputcsv($file, ['| ' . str_pad('Aset Diperiksa', 33) . ' | ' . str_pad($checkedAssets, 13) . ' |']);
                fputcsv($file, ['| ' . str_pad('Aset Belum Diperiksa', 33) . ' | ' . str_pad($uncheckedAssets, 13) . ' |']);
                fputcsv($file, ['| ' . str_pad('Persentase Selesai', 33) . ' | ' . str_pad(round(($checkedAssets/$totalAssets) * 100) . '%', 13) . ' |']);
                fputcsv($file, [$summaryBorder]);
                
                // List unchecked items
                if ($uncheckedAssets > 0) {
                    fputcsv($file, []);
                    fputcsv($file, ['ASET YANG BELUM DIPERIKSA:']);
                    $uncheckedBorder = '+' . str_repeat('=', 50) . '+';
                    fputcsv($file, [$uncheckedBorder]);
                    
                    foreach ($assets->where('is_checked', false) as $asset) {
                        fputcsv($file, ['| ' . str_pad($asset->name, 48) . ' |']);
                        fputcsv($file, ['+' . str_repeat('-', 50) . '+']);
                    }
                }
                
                // Footer
                fputcsv($file, []);
                fputcsv($file, ['Laporan dibuat pada: ' . now()->format('d-m-Y H:i:s')]);
                fputcsv($file, ['Sistem Manajemen TMS']);
                
                fclose($file);
            };
            
            return response()->stream($callback, 200, $headers);
            
        } catch (\Exception $e) {
            \Log::error('CSV Download Error: ' . $e->getMessage());
            return response()->json([
                'error' => 'Failed to generate CSV file'
            ], 500);
        }
    }

    public function stockList()
    {
        $stockChecks = StockCheck::with('creator')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($check) {
                $check->last_updated_at = $check->last_updated_at ? Carbon::parse($check->last_updated_at) : null;
                $check->completed_at = $check->completed_at ? Carbon::parse($check->completed_at) : null;
                return $check;
            });

        return view('stock.list', compact('stockChecks'));
    }

    public function show($id)
    {
        $stockCheck = StockCheck::with(['creator', 'items.asset'])->findOrFail($id);
        $allAssets = Asset::all();
        
        return view('stock.show', compact('stockCheck', 'allAssets'));
    }
}
