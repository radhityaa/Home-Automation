<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MqttController;
use App\Http\Controllers\PublisherController;
use App\Http\Controllers\RelayController;
use App\Http\Controllers\RemoteControlController;
use App\Http\Controllers\StateController;
use Illuminate\Support\Facades\Route;

Route::get('/', [AuthController::class, 'login'])->name('login');;

Route::prefix('auth')->name('auth.')->group(function () {
    // Login route
    Route::get('login', [AuthController::class, 'login'])->name('login');
    Route::post('login', [AuthController::class, 'loginStore']);
});

Route::middleware(['auth'])->group(function () {
    // Admin Route
    Route::prefix('admin')->name('admin.')->group(function () {

        // MQTT Settings Route
        Route::prefix('settings')->name('settings.')->group(function () {
            Route::prefix('mqtt')->name('mqtt.')->group(function () {
                Route::get('', [MqttController::class, 'index'])->name('index');
                Route::post('check-connection', [MqttController::class, 'check'])->name('check');
                Route::put('{mqtt}/update', [MqttController::class, 'update'])->name('update');
            });
        });
    });

    // Client Route
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Remote Control
    Route::prefix('remote-control')->name('remote-control.')->group(function () {
        Route::get('', [RemoteControlController::class, 'index'])->name('index');
        Route::get('view/{device}', [RemoteControlController::class, 'view'])->name('view');
        Route::post('', [RemoteControlController::class, 'store'])->name('store');
        Route::delete('{device}', [RemoteControlController::class, 'destroy'])->name('destroy');
        Route::get('{device}', [RemoteControlController::class, 'edit'])->name('edit');
        Route::put('{device}/update', [RemoteControlController::class, 'update'])->name('update');
    });
    // Publisher Route
    Route::prefix('publisher')->name('publisher.')->group(function () {
        Route::post('', [PublisherController::class, 'store'])->name('store');
        Route::get('get/{id}', [PublisherController::class, 'edit'])->name('edit');
    });

    // Relays
    Route::post('control-relay', [RelayController::class, 'controlRelay']);
    Route::get('relay-status', [RelayController::class, 'getRelayStatus']);

    // State Route
    Route::get('state/{id}', [StateController::class, 'getState'])->name('get.state');

    // Logout
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');
});
