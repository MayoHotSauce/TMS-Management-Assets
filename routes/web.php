<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DataBarangController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('/welcome');
});

Route::get('/data-barang', [DataBarangController::class, 'index'])->name('data-barang.index');
Route::resource('categories', DataBarangController::class);

Route::get('/dashboard', function () {
    return view('welcome');
})->name('dashboard');

Route::resource('data-barang', DataBarangController::class);

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', 'DashboardController@index')->name('dashboard');
    Route::resource('assets', 'AssetController');
    Route::resource('categories', 'CategoryController');
    Route::resource('locations', 'LocationController');
    Route::get('/reports', 'ReportController@index')->name('reports');
});
