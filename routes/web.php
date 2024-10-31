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

Route::get('/data-barang', function () {
    return view('data_barang.data');
})->name('data-barang');

Route::get('/dashboard', function () {
    return view('welcome');
})->name('dashboard');

Route::resource('data-barang', DataBarangController::class);
