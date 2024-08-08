<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RelayController;
use App\Http\Controllers\RemoteControlController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('auth')->name('auth.')->group(function () {
    // Login route
    Route::get('login', [AuthController::class, 'login'])->name('login');
    Route::post('login', [AuthController::class, 'loginStore']);
});

Route::middleware(['auth'])->group(function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Remote Control
    Route::prefix('remote-control')->name('remote-control.')->group(function () {
        Route::get('', [RemoteControlController::class, 'index'])->name('index');
        Route::get('light', [RemoteControlController::class, 'light'])->name('light');
    });

    // Relays
    Route::post('control-relay', [RelayController::class, 'controlRelay']);
    Route::get('relay-status', [RelayController::class, 'getRelayStatus']);

    // Logout
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');
});
