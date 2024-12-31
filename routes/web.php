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
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\PengajuanController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\RolePermController;

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

Route::get('/dashboard', function () {
    // Your dashboard route
})->middleware(['auth'])->name('dashboard');

// Authentication Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Protected Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('categories', CategoryController::class);
    Route::resource('rooms', RoomController::class);
    Route::resource('barang', DataBarangController::class);

    // Maintenance routes - order matters!
    Route::get('/maintenance', [MaintenanceController::class, 'index'])->name('maintenance.index');
    Route::get('/maintenance/create', [MaintenanceController::class, 'create'])->name('maintenance.create');
    Route::get('/maintenance/approvals', [MaintenanceController::class, 'approvalList'])->name('maintenance.approvals');
    
    // Status update route
    Route::put('/maintenance/{id}/status', [MaintenanceController::class, 'updateStatus'])->name('maintenance.updateStatus');
    
    // Completion routes
    Route::get('/maintenance/{id}/complete', [MaintenanceController::class, 'showCompletionForm'])->name('maintenance.showCompletion');
    Route::post('/maintenance/{id}/complete', 'MaintenanceController@complete')->name('maintenance.complete');
    Route::post('/maintenance/{id}/submit-completion', 'MaintenanceController@submitCompletion')->name('maintenance.submit-completion');
    
    // Approval routes
    Route::post('/maintenance/{id}/approve', [MaintenanceController::class, 'approve'])->name('maintenance.approve');
    Route::get('/maintenance/{id}', [MaintenanceController::class, 'show'])->name('maintenance.show');
    
    // Delete route
    Route::delete('/maintenance/{id}', [MaintenanceController::class, 'destroy'])->name('maintenance.destroy');

    Route::post('/maintenance', [MaintenanceController::class, 'store'])->name('maintenance.store');
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
    Route::get('/pengajuan/approvals', [PengajuanController::class, 'approvals'])
        ->name('pengajuan.approvals');
    
    Route::get('pengajuan', [PengajuanController::class, 'index'])->name('pengajuan.index');
    Route::get('pengajuan/create', [PengajuanController::class, 'create'])->name('pengajuan.create');
    Route::post('pengajuan', [PengajuanController::class, 'store'])->name('pengajuan.store');
    Route::post('pengajuan/{pengajuan}/approve', [PengajuanController::class, 'approve'])->name('pengajuan.approve');
    Route::post('pengajuan/{pengajuan}/reject', [PengajuanController::class, 'reject'])->name('pengajuan.reject');
    Route::get('pengajuan/{pengajuan}', [PengajuanController::class, 'show'])->name('pengajuan.show');
    Route::patch('pengajuan/{pengajuan}/archive', [PengajuanController::class, 'archive'])
        ->name('pengajuan.archive');
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


// UPDATE COMPANY LOGO
Route::middleware(['auth'])->group(function () {
    Route::get('/company/logo/update', [CompanyController::class, 'showLogoForm'])
        ->name('company.logo.edit');

    Route::post('/company/logo/update', [CompanyController::class, 'updateLogo'])
        ->name('company.logo.update');
});

Route::get('/rooms/{room}', [RoomController::class, 'show'])->name('rooms.show');

Route::get('/barang', [App\Http\Controllers\BarangController::class, 'index'])->name('barang.index');

Route::get('/history', [HistoryController::class, 'index'])->name('history.index');

Route::get('stock', [StockController::class, 'index'])->name('stock.index');
Route::post('stock/confirm', [StockController::class, 'confirm'])->name('stock.confirm');

Route::middleware(['auth'])->group(function () {
    // Stock Management Routes
    Route::prefix('stock')->name('stock.')->middleware(['auth'])->group(function () {
        Route::get('/', [StockController::class, 'index'])->name('index');
        Route::get('/list', [StockController::class, 'stockList'])->name('list');
        Route::get('/show/{id}', [StockController::class, 'show'])->name('show');
        Route::post('/update', [StockController::class, 'update'])->name('update');
        Route::post('/confirm', [StockController::class, 'confirm'])->name('confirm');
        
        // Make sure this route is BEFORE the catch-all show route
        Route::get('/download/csv/{id}', [StockController::class, 'downloadCsv'])->name('download.csv');
    });
});

// Add this new route for the approval list
Route::get('/maintenance/approval-list', [MaintenanceController::class, 'approvalList'])->name('maintenance.approval-list');
Route::post('/maintenance/{id}/submit-completion', [MaintenanceController::class, 'submitCompletion'])->name('maintenance.submit-completion');
Route::post('/maintenance/{id}/approve', [MaintenanceController::class, 'approve'])->name('maintenance.approve');

Route::get('/maintenance/{id}/completion-form', [MaintenanceController::class, 'showCompletionForm'])->name('maintenance.completion-form');
Route::put('/maintenance/{id}/submit-completion', [MaintenanceController::class, 'submitCompletion'])->name('maintenance.submit-completion');

Route::put('/maintenance/{id}/start', [MaintenanceController::class, 'startWork'])->name('maintenance.start');

Route::get('/maintenance/{id}/approval-detail', [MaintenanceController::class, 'showApprovalDetail'])->name('maintenance.approval-detail');

Route::prefix('maintenance')->name('maintenance.')->group(function () {
    // Other maintenance routes...
    
    Route::get('/approvals', [MaintenanceController::class, 'approvalList'])->name('approvals');
    Route::get('/approval-detail/{id}', [MaintenanceController::class, 'showApprovalDetail'])->name('approval-detail');
    Route::post('/approvals/approve/{id}', [MaintenanceController::class, 'approve'])->name('approvals.approve');
    Route::get('/{id}', [MaintenanceController::class, 'show'])->name('show');
    Route::get('/{id}/edit', [MaintenanceController::class, 'edit'])->name('edit');
    Route::put('/{id}', [MaintenanceController::class, 'update'])->name('update');
    Route::delete('/{id}', [MaintenanceController::class, 'destroy'])->name('destroy');
    Route::put('/{id}/archive', [MaintenanceController::class, 'archive'])->name('archive');
});

Route::post('/maintenance/{id}/approve', [MaintenanceController::class, 'approve'])
    ->name('maintenance.approve');

Route::get('/maintenance/{id}/completion', [MaintenanceController::class, 'showCompletionForm'])->name('maintenance.completion.form');
Route::put('/maintenance/{id}/complete', [MaintenanceController::class, 'complete'])->name('maintenance.complete');

Route::get('/maintenance/{id}/approval-detail', [MaintenanceController::class, 'showApprovalDetail'])->name('maintenance.approval-detail');

Route::put('/maintenance/{id}/approve', [MaintenanceController::class, 'approve'])->name('maintenance.approve');
Route::put('/maintenance/{id}/reject', [MaintenanceController::class, 'reject'])->name('maintenance.reject');

Route::middleware(['auth'])->group(function () {
    // Pengajuan routes with explicit names
    Route::prefix('pengajuan')->name('pengajuan.')->group(function () {
        Route::get('/approvals', [PengajuanController::class, 'approvals'])->name('approvals');
        
        Route::get('/', [PengajuanController::class, 'index'])->name('index');
        Route::get('/create', [PengajuanController::class, 'create'])->name('create');
        Route::post('/', [PengajuanController::class, 'store'])->name('store');
        Route::post('/{pengajuan}/approve', [PengajuanController::class, 'approve'])->name('approve');
        Route::post('/{pengajuan}/reject', [PengajuanController::class, 'reject'])->name('reject');
        
        Route::get('/{pengajuan}', [PengajuanController::class, 'show'])->name('show');
    });
});

Route::post('/maintenance/{maintenance}/revise', [MaintenanceController::class, 'revise'])
    ->name('maintenance.revise');

Route::post('/maintenance/{maintenance}/restart', [MaintenanceController::class, 'restart'])
    ->name('maintenance.restart');

// Existing routes
Route::prefix('pengajuan')->group(function () {
    // Basic CRUD routes
    Route::get('/', [PengajuanController::class, 'index'])->name('pengajuan.index');
    Route::get('/create', [PengajuanController::class, 'create'])->name('pengajuan.create');
    Route::post('/', [PengajuanController::class, 'store'])->name('pengajuan.store');
    Route::get('/{pengajuan}', [PengajuanController::class, 'show'])->name('pengajuan.show');
    
    // Approval routes
    Route::get('/approvals/list', [PengajuanController::class, 'approvals'])->name('pengajuan.approvals');
    Route::post('/{pengajuan}/approve', [PengajuanController::class, 'approve'])->name('pengajuan.approve');
    Route::post('/{pengajuan}/reject', [PengajuanController::class, 'reject'])->name('pengajuan.reject');
    
    // New routes for proof submission and final approval
    Route::get('/{pengajuan}/submit-proof', [PengajuanController::class, 'showProofForm'])->name('pengajuan.proof-form');
    Route::post('/{pengajuan}/submit-proof', [PengajuanController::class, 'submitProof'])->name('pengajuan.submit-proof');
    Route::post('/{pengajuan}/final-approve', [PengajuanController::class, 'finalApprove'])->name('pengajuan.final-approve');
    Route::post('/{pengajuan}/final-reject', [PengajuanController::class, 'finalReject'])->name('pengajuan.final-reject');
});

Route::get('/pengajuan/{pengajuan}/submit-proof', [PengajuanController::class, 'showSubmitProofForm'])
    ->name('pengajuan.show-proof-form');
Route::post('/pengajuan/{pengajuan}/submit-proof', [PengajuanController::class, 'submitProof'])
    ->name('pengajuan.submit-proof');

Route::post('/pengajuan/{pengajuan}/final-approve', [PengajuanController::class, 'finalApprove'])
    ->name('pengajuan.final-approve');
Route::post('/pengajuan/{pengajuan}/final-reject', [PengajuanController::class, 'finalReject'])
    ->name('pengajuan.final-reject');

Route::put('/barang/{barang}/change-status', [App\Http\Controllers\BarangController::class, 'changeStatus'])
    ->name('barang.change-status');

Route::get('/role-permissions', [RolePermController::class, 'index'])->name('roleperm.index');

// Add this new route for assigning roles
Route::post('/role-permissions/assign', [RolePermController::class, 'assign'])->name('roleperm.assign');

Route::get('/role-permissions/{id}/edit', [RolePermController::class, 'edit'])->name('roleperm.edit');

Route::get('/get-users-by-jabatan/{jabatan_id}', [RolePermController::class, 'getUsersByJabatan'])->name('users.by.jabatan');

Route::get('/get-role-permissions/{role}', [RolePermController::class, 'getRolePermissions']);
Route::get('/get-user-permissions/{user}', [RolePermController::class, 'getUserPermissions']);
Route::group(['middleware' => ['auth']], function () {
    Route::get('/role-permissions', [RolePermController::class, 'index'])->name('roleperm.index');
    Route::post('/role-permissions/assign', [RolePermController::class, 'assign'])->name('roleperm.assign');
});

Route::group(['middleware' => ['auth']], function () {
    Route::get('/roleperm', [RolePermController::class, 'index'])->name('roleperm.index');
    Route::post('/roleperm/assign', [RolePermController::class, 'assign'])->name('roleperm.assign');
    Route::put('/roleperm/{id}', [RolePermController::class, 'update'])->name('roleperm.update');
});

Route::get('/get-all-users', [RolePermController::class, 'getAllUsers'])->name('users.all');

Route::get('/role-permissions/users/{jabatan}', [RolePermController::class, 'getUsersByJabatan'])
     ->name('roleperm.users');

Route::delete('/role-permissions/{id}', [RolePermController::class, 'destroy'])->name('roleperm.destroy');
Route::put('/role-permissions/{id}/level', [RolePermController::class, 'updateLevel'])->name('roleperm.updateLevel');