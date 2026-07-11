<?php

use App\Http\Controllers\back\DashboardController;
use App\Http\Controllers\back\LogoController;
use App\Http\Controllers\back\LogoSectionController;
use App\Http\Controllers\ColorController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// User::create([
    //     'name' => 'Admin',
//     'email' => 'admin@gmail.com',
//     'password' => Hash::make('12345678')
// ]);

// Landing Page
Route::get('/', function () {
    return view('landing');
    })->name('home');
    Route::get('/designer', [IndexController::class, 'index'])->name('designer');


    Route::get('/clear-cache', function () {
    // Run Cache Clear Commands
    Artisan::call('config:clear');
    Artisan::call('cache:clear');
    Artisan::call('clear-compiled');
    Artisan::call('optimize:clear');

    // Get current time in a human-readable format
    $timeNow = now()->format('Y-m-d H:i:s');

    // Prepare the config file content
    $configData = "<?php return [
        'minutes_to_clear_menu' => '$timeNow',
        'minutes_to_clear_home' => '$timeNow'
    ];";

    // Save to config/cache_clear.php
    File::put(config_path('cache_clear.php'), $configData);

    // Reload the configuration
    Artisan::call('config:clear');

    return "<div style='font-weight: bold; flex-direction: column; gap: 15px; padding: 10px; border-radius: 7px; font-size: 26px; color: green;'>
                <span>Cache Cleared!</span>
            </div>
            <style>body{background: #f8faf8; display: flex; justify-content: center; align-items: center; min-height: 95vh;}</style>
            <script>setTimeout(()=>history.back(),1500);</script>
            ";
})->name('clear-cache');
    
    //dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth'])->name('dashboard');
Route::get('dashboard/login', [DashboardController::class, 'login'])
    ->name('dashboard.login');

// تنفيذ اللوجين
Route::post('dashboard/login', [DashboardController::class, 'authenticate'])
    ->name('dashboard.authenticate');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::resource('sections', LogoSectionController::class);
Route::resource('logos', LogoController::class);
Route::resource('colors', ColorController::class);
Route::resource('governorates', App\Http\Controllers\GovernorateController::class);
Route::post('colors/{color}/toggle', [ColorController::class, 'toggleActive'])->name('colors.toggle');
Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');



Route::middleware(['auth'])->group(function () {
    Route::get('/orders',                    [OrderController::class, 'adminIndex'])->name('orders.index');
    Route::get('/orders/{order}',            [OrderController::class, 'adminShow'])->name('orders.show');
    Route::patch('/orders/{order}/status',      [OrderController::class, 'updateStatus'])->name('orders.status');
    Route::patch('/orders/{order}/status-ajax', [OrderController::class, 'updateStatusAjax'])->name('orders.status.ajax');
    Route::delete('/orders/{order}',   [OrderController::class, 'destroy'])->name('orders.destroy');
});

Route::post('/logos/upload-temp', [OrderController::class, 'uploadTemp']);

require __DIR__.'/auth.php';
