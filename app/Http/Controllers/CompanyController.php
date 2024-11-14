<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CompanyController extends Controller
{
    public function updateLogo(Request $request)
    {
        $request->validate([
            'logo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        try {
            if ($request->hasFile('logo')) {
                $path = Storage::disk('public')->put('company', $request->file('logo'));
                
                // Rename the file to logo.png
                $newPath = 'company/logo.png';
                Storage::disk('public')->delete($newPath); // Delete old logo if exists
                Storage::disk('public')->copy($path, $newPath);
                Storage::disk('public')->delete($path); // Delete the temporary uploaded file

                return redirect()->back()->with('success', 'Company logo updated successfully');
            }

            return redirect()->back()->with('error', 'No logo file provided');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update company logo: ' . $e->getMessage());
        }
    }

    public function showLogoForm()
    {
        return view('company.logo-form');
    }
}