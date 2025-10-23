<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FireController;

Route::get('/', function () {
    return redirect()->route('fire.dashboard');
});

// Routes pour le système d'alerte incendie
Route::prefix('fire')->name('fire.')->group(function () {
    Route::get('/dashboard', [FireController::class, 'dashboard'])->name('dashboard');
    Route::get('/sensors', [FireController::class, 'sensors'])->name('sensors');
    Route::get('/alerts', [FireController::class, 'alerts'])->name('alerts');
    Route::get('/zones', [FireController::class, 'zones'])->name('zones');
});
