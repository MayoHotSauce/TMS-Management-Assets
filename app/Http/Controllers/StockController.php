<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use PDF;
use App\Models\Asset;

class StockController extends Controller
{
    public function index()
    {
        $assets = Asset::all();
        return view('stock.index', compact('assets'));
    }

    public function confirm(Request $request)
    {
        $checkedAssets = $request->input('assets');

        // Check if any assets were selected
        if (empty($checkedAssets)) {
            return redirect()->back()->with('error', 'No assets selected for confirmation.');
        }

        // Create a directory for confirmations
        $directoryPath = 'stock_confirmations/' . date('Y-m-d');
        if (!Storage::exists($directoryPath)) {
            Storage::makeDirectory($directoryPath);
        }

        // Generate the confirmation content
        $content = view('stock.confirmation', compact('checkedAssets'))->render();
        $fileName = 'stock_confirmation_' . date('Y-m-d_H-i-s') . '.html';
        Storage::put($directoryPath . '/' . $fileName, $content);

        // Generate PDF
        $pdf = PDF::loadView('stock.confirmation', compact('checkedAssets'));
        return $pdf->download('stock_confirmation.pdf'); // This triggers the download
    }
}
