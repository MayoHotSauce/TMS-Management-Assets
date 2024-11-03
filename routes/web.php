<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\DataBarangController;
use App\Http\Controllers\MaintenanceController;
use App\Http\Controllers\Auth\LoginController;

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
    return view('welcome');
});

// Authentication Routes
Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

// Protected Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('categories', CategoryController::class);
    Route::resource('rooms', RoomController::class);
    Route::resource('barang', DataBarangController::class);
    Route::resource('maintenance', MaintenanceController::class);
});

// Test Route
Route::get('/test-barang', function() {
    return DaftarBarang::create([
        'name' => 'Test Item',
        'description' => 'Test Description',
        'room' => 'Ruang Utama',
        'category_id' => 1,
        'tahun_pengadaan' => 2024,
        'condition' => 'good',
        'status' => 'active'
    ]);
});
