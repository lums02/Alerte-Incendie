<?php

namespace App\Services;

use App\Models\Device;
use App\Models\Sensor;
use App\Models\Alert;
use App\Models\Zone;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class FireDetectionService
{
    /**
     * Créer une alerte
     */
    public function createAlert(
        Device $device,
        ?Sensor $sensor = null,
        string $level = 'warning',
        ?float $value = null,
        ?string $customMessage = null
    ): Alert {
        $zone = $sensor?->zone;
        
        // Générer le titre et message selon le niveau et le type de capteur
        $title = $this->generateAlertTitle($level, $sensor?->type);
        $message = $customMessage ?? $this->generateAlertMessage($level, $sensor, $value, $zone);

        $alert = Alert::create([
            'device_id' => $device->id,
            'sensor_id' => $sensor?->id,
            'zone_id' => $zone?->id,
            'level' => $level,
            'title' => $title,
            'message' => $message,
            'data' => [
                'value' => $value,
                'threshold_warn' => $sensor?->threshold_warn,
                'threshold_alarm' => $sensor?->threshold_alarm,
                'device_name' => $device->name,
                'sensor_name' => $sensor?->name,
                'zone_name' => $zone?->name
            ],
            'triggered_at' => now(),
            'status' => 'active'
        ]);

        // Déclencher les notifications
        $this->triggerNotifications($alert);

        Log::info("Alert created: {$alert->id} - {$level} - {$title}");

        return $alert;
    }

    /**
     * Générer le titre de l'alerte
     */
    private function generateAlertTitle(string $level, ?string $sensorType): string
    {
        $levelTitles = [
            'info' => 'Information',
            'warning' => 'Avertissement',
            'critical' => 'Alerte Critique',
            'emergency' => 'URGENCE INCENDIE'
        ];

        $sensorTitles = [
            'smoke' => 'Fumée',
            'temperature' => 'Température',
            'humidity' => 'Humidité',
            'flame' => 'Flamme',
            'gas' => 'Gaz'
        ];

        $sensorTitle = $sensorTitles[$sensorType] ?? 'Capteur';
        $levelTitle = $levelTitles[$level] ?? 'Alerte';

        return "{$levelTitle} - {$sensorTitle}";
    }

    /**
     * Générer le message de l'alerte
     */
    private function generateAlertMessage(
        string $level,
        ?Sensor $sensor,
        ?float $value,
        ?Zone $zone
    ): string {
        $zoneName = $zone?->name ?? 'Zone inconnue';
        $sensorName = $sensor?->name ?? 'Capteur';
        $unit = $sensor?->unit ?? '';
        $valueText = $value !== null ? " ({$value} {$unit})" : '';

        $messages = [
            'info' => "Information du capteur {$sensorName} en {$zoneName}{$valueText}",
            'warning' => "ATTENTION: Valeur élevée détectée par {$sensorName} en {$zoneName}{$valueText}",
            'critical' => "ALERTE CRITIQUE: Danger détecté par {$sensorName} en {$zoneName}{$valueText}",
            'emergency' => "🚨 URGENCE INCENDIE: Détection de feu par {$sensorName} en {$zoneName}{$valueText} - ÉVACUATION IMMÉDIATE"
        ];

        return $messages[$level] ?? "Alerte générée par {$sensorName} en {$zoneName}";
    }

    /**
     * Déclencher les notifications
     */
    private function triggerNotifications(Alert $alert): void
    {
        // Pour l'instant, on log seulement
        // Plus tard, on pourra ajouter :
        // - Email notifications
        // - SMS via Twilio
        // - Push notifications
        // - WebSocket pour temps réel
        // - Appel automatique aux pompiers pour les urgences

        Log::info("Notification triggered for alert {$alert->id}: {$alert->title}");

        // Exemple de notification WebSocket (à implémenter plus tard)
        // broadcast(new AlertTriggered($alert))->toOthers();

        // Exemple de notification email (à implémenter plus tard)
        // if ($alert->level === 'emergency') {
        //     Notification::route('mail', 'admin@example.com')
        //         ->notify(new EmergencyAlertNotification($alert));
        // }
    }

    /**
     * Analyser les tendances pour détecter les anomalies
     */
    public function analyzeTrends(Sensor $sensor, int $hours = 24): array
    {
        $readings = $sensor->readings()
            ->where('measured_at', '>=', now()->subHours($hours))
            ->orderBy('measured_at')
            ->get();

        if ($readings->count() < 10) {
            return ['status' => 'insufficient_data'];
        }

        $values = $readings->pluck('value')->toArray();
        
        // Calculer la moyenne et l'écart-type
        $mean = array_sum($values) / count($values);
        $variance = array_sum(array_map(function($x) use ($mean) {
            return pow($x - $mean, 2);
        }, $values)) / count($values);
        $stdDev = sqrt($variance);

        // Détecter les valeurs aberrantes (plus de 2 écarts-types de la moyenne)
        $outliers = array_filter($values, function($value) use ($mean, $stdDev) {
            return abs($value - $mean) > 2 * $stdDev;
        });

        // Calculer la tendance (croissante/décroissante)
        $trend = $this->calculateTrend($values);

        return [
            'status' => 'analyzed',
            'mean' => round($mean, 2),
            'std_dev' => round($stdDev, 2),
            'outliers_count' => count($outliers),
            'trend' => $trend,
            'anomaly_risk' => count($outliers) > count($values) * 0.1 ? 'high' : 'low'
        ];
    }

    /**
     * Calculer la tendance des valeurs
     */
    private function calculateTrend(array $values): string
    {
        if (count($values) < 2) {
            return 'stable';
        }

        $firstHalf = array_slice($values, 0, count($values) / 2);
        $secondHalf = array_slice($values, count($values) / 2);

        $firstMean = array_sum($firstHalf) / count($firstHalf);
        $secondMean = array_sum($secondHalf) / count($secondHalf);

        $change = (($secondMean - $firstMean) / $firstMean) * 100;

        if ($change > 5) {
            return 'increasing';
        } elseif ($change < -5) {
            return 'decreasing';
        } else {
            return 'stable';
        }
    }

    /**
     * Résoudre automatiquement les alertes anciennes
     */
    public function autoResolveOldAlerts(int $hours = 24): int
    {
        $oldAlerts = Alert::where('status', 'active')
            ->where('triggered_at', '<', now()->subHours($hours))
            ->where('level', '!=', 'emergency') // Ne pas auto-résoudre les urgences
            ->get();

        $resolvedCount = 0;
        foreach ($oldAlerts as $alert) {
            $alert->resolve();
            $resolvedCount++;
            
            Log::info("Auto-resolved alert {$alert->id} after {$hours} hours");
        }

        return $resolvedCount;
    }

    /**
     * Obtenir les statistiques du système
     */
    public function getSystemStats(): array
    {
        $totalDevices = Device::count();
        $onlineDevices = Device::where('status', 'online')->count();
        $totalSensors = Sensor::count();
        $activeSensors = Sensor::where('status', 'active')->count();
        $activeAlerts = Alert::where('status', 'active')->count();
        $todayAlerts = Alert::whereDate('triggered_at', today())->count();

        return [
            'devices' => [
                'total' => $totalDevices,
                'online' => $onlineDevices,
                'offline' => $totalDevices - $onlineDevices
            ],
            'sensors' => [
                'total' => $totalSensors,
                'active' => $activeSensors,
                'inactive' => $totalSensors - $activeSensors
            ],
            'alerts' => [
                'active' => $activeAlerts,
                'today' => $todayAlerts
            ]
        ];
    }
}