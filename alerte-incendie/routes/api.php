<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FireController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Routes pour Arduino - Système d'alerte incendie
Route::prefix('fire')->group(function () {
    // Endpoint pour recevoir les données des capteurs
    Route::post('/sensor-data', [FireController::class, 'receiveSensorData']);
    
    // Endpoint pour déclencher une alerte manuelle
    Route::post('/alert', [FireController::class, 'triggerAlert']);
    
    // Endpoint pour obtenir le statut du système
    Route::get('/system-status', [FireController::class, 'getSystemStatus']);
});

// Route de test pour vérifier que l'API fonctionne
Route::get('/health', function () {
    return response()->json([
        'status' => 'ok',
        'timestamp' => now()->toISOString(),
        'version' => '1.0.0'
    ]);
});
