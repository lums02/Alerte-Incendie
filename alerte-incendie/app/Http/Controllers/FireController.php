<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\Sensor;
use App\Models\SensorReading;
use App\Models\Alert;
use App\Models\Zone;
use App\Services\FireDetectionService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class FireController extends Controller
{
    protected FireDetectionService $fireDetectionService;

    public function __construct(FireDetectionService $fireDetectionService)
    {
        $this->fireDetectionService = $fireDetectionService;
    }

    /**
     * Endpoint pour recevoir les données des capteurs Arduino
     * POST /api/fire/sensor-data
     */
    public function receiveSensorData(Request $request): JsonResponse
    {
        // Validation de la clé API
        $apiKey = $request->header('X-API-KEY');
        if (!$apiKey) {
            return response()->json(['error' => 'API key required'], 401);
        }

        $device = Device::where('api_key', $apiKey)->first();
        if (!$device) {
            return response()->json(['error' => 'Invalid API key'], 401);
        }

        // Validation des données
        $validator = Validator::make($request->all(), [
            'readings' => 'required|array',
            'readings.*.sensor_type' => 'required|string|in:smoke,temperature,humidity,flame,gas',
            'readings.*.value' => 'required|numeric',
            'readings.*.timestamp' => 'nullable|date',
            'device_status' => 'nullable|string|in:online,offline,error'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        try {
            // Mettre à jour le statut du device
            if ($request->has('device_status')) {
                $device->update([
                    'status' => $request->device_status,
                    'last_seen_at' => now()
                ]);
            }

            $processedReadings = [];
            $alerts = [];

            // Traiter chaque lecture
            foreach ($request->readings as $reading) {
                $sensor = Sensor::where('device_id', $device->id)
                    ->where('type', $reading['sensor_type'])
                    ->first();

                if (!$sensor) {
                    Log::warning("Sensor not found for device {$device->id} and type {$reading['sensor_type']}");
                    continue;
                }

                // Créer la lecture
                $sensorReading = SensorReading::create([
                    'sensor_id' => $sensor->id,
                    'value' => $reading['value'],
                    'unit' => $sensor->unit,
                    'measured_at' => $reading['timestamp'] ?? now(),
                    'raw_data' => $reading,
                    'quality' => $this->assessDataQuality($reading['value'], $sensor)
                ]);

                $processedReadings[] = $sensorReading;

                // Vérifier les alertes
                $alertLevel = $sensor->checkAlert($reading['value']);
                if ($alertLevel) {
                    $alert = $this->fireDetectionService->createAlert(
                        $device,
                        $sensor,
                        $alertLevel,
                        $reading['value']
                    );
                    $alerts[] = $alert;
                }
            }

            return response()->json([
                'success' => true,
                'processed_readings' => count($processedReadings),
                'alerts_triggered' => count($alerts),
                'device_status' => $device->status
            ]);

        } catch (\Exception $e) {
            Log::error('Error processing sensor data: ' . $e->getMessage());
            return response()->json(['error' => 'Internal server error'], 500);
        }
    }

    /**
     * Endpoint pour déclencher une alerte manuelle
     * POST /api/fire/alert
     */
    public function triggerAlert(Request $request): JsonResponse
    {
        $apiKey = $request->header('X-API-KEY');
        if (!$apiKey) {
            return response()->json(['error' => 'API key required'], 401);
        }

        $device = Device::where('api_key', $apiKey)->first();
        if (!$device) {
            return response()->json(['error' => 'Invalid API key'], 401);
        }

        $validator = Validator::make($request->all(), [
            'level' => 'required|string|in:info,warning,critical,emergency',
            'message' => 'required|string',
            'sensor_type' => 'nullable|string|in:smoke,temperature,humidity,flame,gas'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        try {
            $sensor = null;
            if ($request->sensor_type) {
                $sensor = Sensor::where('device_id', $device->id)
                    ->where('type', $request->sensor_type)
                    ->first();
            }

            $alert = $this->fireDetectionService->createAlert(
                $device,
                $sensor,
                $request->level,
                null,
                $request->message
            );

            return response()->json([
                'success' => true,
                'alert_id' => $alert->id,
                'level' => $alert->level
            ]);

        } catch (\Exception $e) {
            Log::error('Error triggering alert: ' . $e->getMessage());
            return response()->json(['error' => 'Internal server error'], 500);
        }
    }

    /**
     * Endpoint pour obtenir le statut du système
     * GET /api/fire/system-status
     */
    public function getSystemStatus(Request $request): JsonResponse
    {
        $apiKey = $request->header('X-API-KEY');
        if (!$apiKey) {
            return response()->json(['error' => 'API key required'], 401);
        }

        $device = Device::where('api_key', $apiKey)->first();
        if (!$device) {
            return response()->json(['error' => 'Invalid API key'], 401);
        }

        $sensors = $device->sensors()->with('latestReading')->get();
        $activeAlerts = Alert::where('device_id', $device->id)
            ->where('status', 'active')
            ->count();

        return response()->json([
            'device' => [
                'id' => $device->id,
                'name' => $device->name,
                'status' => $device->status,
                'last_seen' => $device->last_seen_at
            ],
            'sensors' => $sensors->map(function ($sensor) {
                return [
                    'id' => $sensor->id,
                    'type' => $sensor->type,
                    'name' => $sensor->name,
                    'status' => $sensor->status,
                    'latest_value' => $sensor->latestReading?->value,
                    'unit' => $sensor->unit,
                    'threshold_warn' => $sensor->threshold_warn,
                    'threshold_alarm' => $sensor->threshold_alarm
                ];
            }),
            'active_alerts' => $activeAlerts,
            'system_time' => now()->toISOString()
        ]);
    }

    /**
     * Dashboard - Vue principale
     */
    public function dashboard()
    {
        $devices = Device::with(['sensors.latestReading', 'alerts' => function($query) {
            $query->where('status', 'active')->latest();
        }])->get();

        $zones = Zone::with('sensors.latestReading')->get();
        $recentAlerts = Alert::with(['device', 'sensor', 'zone'])
            ->latest()
            ->limit(10)
            ->get();

        return view('fire.dashboard', compact('devices', 'zones', 'recentAlerts'));
    }

    /**
     * Gestion des capteurs
     */
    public function sensors()
    {
        $sensors = Sensor::with(['device', 'zone', 'latestReading'])->get();
        $devices = Device::all();
        $zones = Zone::all();

        return view('fire.sensors', compact('sensors', 'devices', 'zones'));
    }

    /**
     * Historique des alertes
     */
    public function alerts()
    {
        $alerts = Alert::with(['device', 'sensor', 'zone'])
            ->latest()
            ->paginate(20);

        return view('fire.alerts', compact('alerts'));
    }

    /**
     * Configuration des zones
     */
    public function zones()
    {
        $zones = Zone::with('sensors')->get();
        return view('fire.zones', compact('zones'));
    }

    /**
     * Évaluer la qualité des données
     */
    private function assessDataQuality(float $value, Sensor $sensor): string
    {
        // Logique simple pour évaluer la qualité
        if ($sensor->threshold_alarm && $value >= $sensor->threshold_alarm) {
            return 'error';
        }
        
        if ($sensor->threshold_warn && $value >= $sensor->threshold_warn) {
            return 'warning';
        }
        
        return 'good';
    }
}
