<?php

use App\Http\Controllers\back\DashboardController;
use App\Http\Controllers\back\LogoController;
use App\Http\Controllers\back\LogoSectionController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// User::create([
//     'name' => 'Admin',
//     'email' => 'admin@gmail.com',
//     'password' => Hash::make('12345678')
// ]);

// Route::get('/', function () {
//     return view('welcome');
// });
Route::get('/', [IndexController::class, 'index'])
    ->name('home');

Route::get('/dashboard', function () {
    return view('dashboard.index');
})->middleware(['auth'])->name('dashboard');

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

Route::get('/designer', [OrderController::class, 'index'])->name('designer');

Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');

Route::middleware(['auth'])->group(function () {
    Route::get('/orders',                    [OrderController::class, 'adminIndex'])->name('orders.index');
    Route::get('/orders/{order}',            [OrderController::class, 'adminShow'])->name('orders.show');
    Route::patch('/orders/{order}/status',      [OrderController::class, 'updateStatus'])->name('orders.status');
    Route::patch('/orders/{order}/status-ajax', [OrderController::class, 'updateStatusAjax'])->name('orders.status.ajax');
    Route::delete('/orders/{order}',   [OrderController::class, 'destroy'])->name('orders.destroy');
});

require __DIR__.'/auth.php';
