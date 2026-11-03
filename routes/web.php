<?php

use App\Http\Controllers\Admin\ParkirKendaraanController;
use App\Http\Controllers\Admin\PenggunaController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Halaman Login
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect('/index');
});

Route::get('/index', function () {
    return view('index');
})->name('login');

Route::post('/login', [DashboardController::class, 'login'])
    ->name('login.store');
Route::get('/logout', [DashboardController::class, 'logout'])
    ->name('logout');

/*
|--------------------------------------------------------------------------
| Dashboard
|--------------------------------------------------------------------------
*/

Route::get('/dashboard', function () {
    return redirect()->route('dhasboard');
})->name('dashboard');

Route::get('/dhasboard', [DashboardController::class, 'index'])
    ->name('dhasboard');

Route::post('/dashboard/users', [DashboardController::class, 'storeUser'])
    ->name('dashboard.users.store');
Route::put('/dashboard/users/{id}', [DashboardController::class, 'updateUser'])
    ->name('dashboard.users.update');
Route::delete('/dashboard/users/{id}', [DashboardController::class, 'deleteUser'])
    ->name('dashboard.users.delete');
Route::post('/dashboard/tariffs', [DashboardController::class, 'storeTarif'])
    ->name('dashboard.tariffs.store');
Route::post('/dashboard/areas', [DashboardController::class, 'storeArea'])
    ->name('dashboard.areas.store');
Route::put('/dashboard/areas/{id}', [DashboardController::class, 'updateArea'])
    ->name('dashboard.areas.update');
Route::delete('/dashboard/areas/{id}', [DashboardController::class, 'deleteArea'])
    ->name('dashboard.areas.delete');
Route::post('/dashboard/vehicles', [DashboardController::class, 'storeKendaraan'])
    ->name('dashboard.vehicles.store');
Route::post('/dashboard/transactions', [DashboardController::class, 'storeTransaksi'])
    ->name('dashboard.transactions.store');
Route::post('/dashboard/transactions/{id}/close', [DashboardController::class, 'closeTransaksi'])
    ->name('dashboard.transactions.close');

/*
|--------------------------------------------------------------------------
| Admin
|--------------------------------------------------------------------------
*/

Route::prefix('admin')
    ->name('admin.')
    ->group(function () {

        // Kendaraan
        Route::resource('kendaraan', ParkirKendaraanController::class)
            ->except(['show']);

        // Pengguna
        Route::resource('pengguna', PenggunaController::class)
            ->except(['show']);
    });
