<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\DataBarangController;
use App\Http\Controllers\MaintenanceController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\AssetRequestController;
use Illuminate\Support\Facades\Mail;

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
    return redirect()->route('dashboard');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

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
    Route::group(['middleware' => ['auth']], function () {
        Route::resource('maintenance', MaintenanceController::class);
    });
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

// PENGAJUAN ASSET
Route::middleware(['auth'])->group(function () {
    Route::get('/pengajuan', [AssetRequestController::class, 'index'])->name('pengajuan.index');
    Route::get('/pengajuan/create', [AssetRequestController::class, 'create'])->name('pengajuan.create');
    Route::post('/pengajuan', [AssetRequestController::class, 'store'])->name('pengajuan.store');
    Route::get('/pengajuan/{id}', [AssetRequestController::class, 'show'])->name('pengajuan.show');
    Route::get('/pengajuan/{id}/approve/{token}', [AssetRequestController::class, 'approve'])->name('pengajuan.approve');
    Route::get('/pengajuan/{id}/decline/{token}', [AssetRequestController::class, 'decline'])->name('pengajuan.decline');
});

Route::get('/test-email', function() {
    Mail::raw('Test email', function($message) {
        $message->to('adityanathaniel44@gmail.com')
                ->subject('Test Email');
    });
    
    return 'Test email sent!';
});

// Public approval routes (no auth required)
Route::get('/approval/{id}/approve/{token}', [AssetRequestController::class, 'handleApproval'])
    ->name('approval.approve');
Route::get('/approval/{id}/decline/{token}', [AssetRequestController::class, 'handleDecline'])
    ->name('approval.decline');
