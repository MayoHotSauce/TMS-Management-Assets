<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\DataBarangController;
use App\Http\Controllers\MaintenanceController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\AssetRequestController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\PengajuanController;
use App\Http\Controllers\BarangController;

// Redirect root to dashboard
Route::get('/', function () {
    return redirect()->route('dashboard');
});

// Authentication Routes
Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

// Protected Routes Group
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Basic Resources
    Route::resources([
        'categories' => CategoryController::class,
        'rooms' => RoomController::class,
        'barang' => DataBarangController::class,
    ]);

    // Asset Management Routes
    Route::prefix('barang')->name('barang.')->group(function () {
        Route::get('/', [BarangController::class, 'index'])->name('index');
        Route::put('/{barang}/change-status', [BarangController::class, 'changeStatus'])->name('change-status');
    });

    // Maintenance Routes
    Route::prefix('maintenance')->name('maintenance.')->group(function () {
        Route::get('/', [MaintenanceController::class, 'index'])->name('index');
        Route::get('/create', [MaintenanceController::class, 'create'])->name('create');
        Route::post('/', [MaintenanceController::class, 'store'])->name('store');
        
        // Approval Related
        Route::get('/approvals', [MaintenanceController::class, 'approvalList'])->name('approvals');
        Route::get('/approval-detail/{id}', [MaintenanceController::class, 'showApprovalDetail'])->name('approval-detail');
        Route::post('/approvals/approve/{id}', [MaintenanceController::class, 'approve'])->name('approvals.approve');
        
        // Status Management
        Route::put('/{id}/status', [MaintenanceController::class, 'updateStatus'])->name('updateStatus');
        Route::put('/{id}/start', [MaintenanceController::class, 'startWork'])->name('start');
        Route::put('/{id}/archive', [MaintenanceController::class, 'archive'])->name('archive');
        
        // Completion Related
        Route::get('/{id}/completion', [MaintenanceController::class, 'showCompletionForm'])->name('completion.form');
        Route::put('/{id}/complete', [MaintenanceController::class, 'complete'])->name('complete');
        Route::post('/{id}/submit-completion', [MaintenanceController::class, 'submitCompletion'])->name('submit-completion');
        
        // Basic CRUD
        Route::get('/{id}', [MaintenanceController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [MaintenanceController::class, 'edit'])->name('edit');
        Route::put('/{id}', [MaintenanceController::class, 'update'])->name('update');
        Route::delete('/{id}', [MaintenanceController::class, 'destroy'])->name('destroy');
        
        // Additional Actions
        Route::post('/{maintenance}/revise', [MaintenanceController::class, 'revise'])->name('revise');
        Route::post('/{maintenance}/restart', [MaintenanceController::class, 'restart'])->name('restart');
    });

    // Asset Request (Pengajuan) Routes
    Route::prefix('pengajuan')->name('pengajuan.')->group(function () {
        // Basic CRUD
        Route::get('/', [PengajuanController::class, 'index'])->name('index');
        Route::get('/create', [PengajuanController::class, 'create'])->name('create');
        Route::post('/', [PengajuanController::class, 'store'])->name('store');
        Route::get('/{pengajuan}', [PengajuanController::class, 'show'])->name('show');
        
        // Approval Process
        Route::get('/approvals', [PengajuanController::class, 'approvals'])->name('approvals');
        Route::post('/{pengajuan}/approve', [PengajuanController::class, 'approve'])->name('approve');
        Route::post('/{pengajuan}/reject', [PengajuanController::class, 'reject'])->name('reject');
        
        // Proof and Final Approval
        Route::get('/{pengajuan}/submit-proof', [PengajuanController::class, 'showSubmitProofForm'])->name('show-proof-form');
        Route::post('/{pengajuan}/submit-proof', [PengajuanController::class, 'submitProof'])->name('submit-proof');
        Route::post('/{pengajuan}/final-approve', [PengajuanController::class, 'finalApprove'])->name('final-approve');
        Route::post('/{pengajuan}/final-reject', [PengajuanController::class, 'finalReject'])->name('final-reject');
    });

    // Stock Management Routes
    Route::prefix('stock')->name('stock.')->group(function () {
        Route::get('/', [StockController::class, 'index'])->name('index');
        Route::get('/list', [StockController::class, 'stockList'])->name('list');
        Route::get('/show/{id}', [StockController::class, 'show'])->name('show');
        Route::post('/update', [StockController::class, 'update'])->name('update');
        Route::post('/confirm', [StockController::class, 'confirm'])->name('confirm');
        Route::get('/download/csv/{id}', [StockController::class, 'downloadCsv'])->name('download.csv');
    });

    // Company Management Routes
    Route::prefix('company')->name('company.')->group(function () {
        Route::get('/logo/update', [CompanyController::class, 'showLogoForm'])->name('logo.edit');
        Route::post('/logo/update', [CompanyController::class, 'updateLogo'])->name('logo.update');
    });

    // History Routes
    Route::get('/history', [HistoryController::class, 'index'])->name('history.index');
});

// Public Approval Routes
Route::prefix('approval')->name('approval.')->group(function () {
    Route::get('/{id}/approve/{token}', [AssetRequestController::class, 'handleApproval'])->name('approve');
    Route::get('/{id}/decline/{token}', [AssetRequestController::class, 'handleDecline'])->name('decline');
});

// Remove or comment out test routes in production
Route::get('/test-email', function() {
    Mail::raw('Test email', function($message) {
        $message->to('adityanathaniel44@gmail.com')
                ->subject('Test Email');
    });
    return 'Test email sent!';
});