<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Device;
use App\Models\Sensor;
use App\Models\SensorReading;
use App\Models\Alert;
use App\Services\FireDetectionService;

class TestFireSystem extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'fire:test {--device=1 : ID du device à tester}';

    /**
     * The console command description.
     */
    protected $description = 'Teste le système d\'alerte incendie en simulant des données de capteurs';

    protected FireDetectionService $fireDetectionService;

    public function __construct(FireDetectionService $fireDetectionService)
    {
        parent::__construct();
        $this->fireDetectionService = $fireDetectionService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $deviceId = $this->option('device');
        $device = Device::find($deviceId);

        if (!$device) {
            $this->error("Device avec l'ID {$deviceId} non trouvé.");
            return 1;
        }

        $this->info("Test du système d'alerte incendie pour le device: {$device->name}");
        $this->newLine();

        // Menu interactif
        while (true) {
            $this->displayMenu();
            $choice = $this->ask('Choisissez une option');

            switch ($choice) {
                case '1':
                    $this->simulateNormalReadings($device);
                    break;
                case '2':
                    $this->simulateSmokeAlert($device);
                    break;
                case '3':
                    $this->simulateTemperatureAlert($device);
                    break;
                case '4':
                    $this->simulateFlameAlert($device);
                    break;
                case '5':
                    $this->showDeviceStatus($device);
                    break;
                case '6':
                    $this->showRecentAlerts();
                    break;
                case '7':
                    $this->clearAlerts();
                    break;
                case '0':
                    $this->info('Test terminé.');
                    return 0;
                default:
                    $this->error('Option invalide.');
            }

            $this->newLine();
        }
    }

    private function displayMenu()
    {
        $this->info('=== MENU DE TEST ===');
        $this->line('1. Simuler des lectures normales');
        $this->line('2. Simuler une alerte fumée');
        $this->line('3. Simuler une alerte température');
        $this->line('4. Simuler une alerte flamme');
        $this->line('5. Afficher le statut du device');
        $this->line('6. Afficher les alertes récentes');
        $this->line('7. Effacer toutes les alertes');
        $this->line('0. Quitter');
        $this->newLine();
    }

    private function simulateNormalReadings(Device $device)
    {
        $this->info('Simulation de lectures normales...');

        $sensors = $device->sensors;
        
        foreach ($sensors as $sensor) {
            $value = match($sensor->type) {
                'smoke' => rand(50, 150),
                'temperature' => rand(18, 25),
                'humidity' => rand(40, 60),
                'flame' => 0,
                'gas' => rand(5, 20),
                default => rand(0, 50)
            };

            SensorReading::create([
                'sensor_id' => $sensor->id,
                'value' => $value,
                'unit' => $sensor->unit,
                'measured_at' => now(),
                'raw_data' => ['simulated' => true],
                'quality' => 'good'
            ]);

            $this->line("  {$sensor->name}: {$value} {$sensor->unit}");
        }

        $this->info('Lectures normales simulées avec succès!');
    }

    private function simulateSmokeAlert(Device $device)
    {
        $this->info('Simulation d\'une alerte fumée...');

        $smokeSensor = $device->sensors()->where('type', 'smoke')->first();
        
        if (!$smokeSensor) {
            $this->error('Aucun capteur de fumée trouvé pour ce device.');
            return;
        }

        $smokeValue = rand(400, 600); // Valeur au-dessus du seuil d'alarme

        // Créer la lecture
        SensorReading::create([
            'sensor_id' => $smokeSensor->id,
            'value' => $smokeValue,
            'unit' => $smokeSensor->unit,
            'measured_at' => now(),
            'raw_data' => ['simulated' => true, 'alert_type' => 'smoke'],
            'quality' => 'error'
        ]);

        // Créer l'alerte
        $alert = $this->fireDetectionService->createAlert(
            $device,
            $smokeSensor,
            'critical',
            $smokeValue
        );

        $this->warn("🚨 ALERTE FUMÉE DÉCLENCHÉE!");
        $this->line("Capteur: {$smokeSensor->name}");
        $this->line("Valeur: {$smokeValue} {$smokeSensor->unit}");
        $this->line("Seuil alarme: {$smokeSensor->threshold_alarm} {$smokeSensor->unit}");
        $this->line("Alerte ID: {$alert->id}");
    }

    private function simulateTemperatureAlert(Device $device)
    {
        $this->info('Simulation d\'une alerte température...');

        $tempSensor = $device->sensors()->where('type', 'temperature')->first();
        
        if (!$tempSensor) {
            $this->error('Aucun capteur de température trouvé pour ce device.');
            return;
        }

        $tempValue = rand(45, 65); // Valeur au-dessus du seuil d'alarme

        // Créer la lecture
        SensorReading::create([
            'sensor_id' => $tempSensor->id,
            'value' => $tempValue,
            'unit' => $tempSensor->unit,
            'measured_at' => now(),
            'raw_data' => ['simulated' => true, 'alert_type' => 'temperature'],
            'quality' => 'error'
        ]);

        // Créer l'alerte
        $alert = $this->fireDetectionService->createAlert(
            $device,
            $tempSensor,
            'critical',
            $tempValue
        );

        $this->warn("🌡️ ALERTE TEMPÉRATURE DÉCLENCHÉE!");
        $this->line("Capteur: {$tempSensor->name}");
        $this->line("Valeur: {$tempValue} {$tempSensor->unit}");
        $this->line("Seuil alarme: {$tempSensor->threshold_alarm} {$tempSensor->unit}");
        $this->line("Alerte ID: {$alert->id}");
    }

    private function simulateFlameAlert(Device $device)
    {
        $this->info('Simulation d\'une alerte flamme...');

        $flameSensor = $device->sensors()->where('type', 'flame')->first();
        
        if (!$flameSensor) {
            $this->error('Aucun capteur de flamme trouvé pour ce device.');
            return;
        }

        // Créer la lecture
        SensorReading::create([
            'sensor_id' => $flameSensor->id,
            'value' => 1, // Flamme détectée
            'unit' => $flameSensor->unit,
            'measured_at' => now(),
            'raw_data' => ['simulated' => true, 'alert_type' => 'flame'],
            'quality' => 'error'
        ]);

        // Créer l'alerte d'urgence
        $alert = $this->fireDetectionService->createAlert(
            $device,
            $flameSensor,
            'emergency',
            1
        );

        $this->error("🔥 URGENCE INCENDIE DÉCLENCHÉE!");
        $this->line("Capteur: {$flameSensor->name}");
        $this->line("Flamme détectée: OUI");
        $this->line("Alerte ID: {$alert->id}");
        $this->line("Niveau: URGENCE");
    }

    private function showDeviceStatus(Device $device)
    {
        $this->info("=== STATUT DU DEVICE: {$device->name} ===");
        $this->line("Localisation: {$device->location}");
        $this->line("Statut: {$device->status}");
        $lastSeen = $device->last_seen_at ? $device->last_seen_at->format('d/m/Y H:i:s') : 'Jamais';
        $this->line("Dernière vue: {$lastSeen}");
        $this->line("Clé API: {$device->api_key}");
        $this->newLine();

        $this->info("Capteurs:");
        $sensors = $device->sensors()->with('latestReading')->get();
        
        foreach ($sensors as $sensor) {
            $latestValue = $sensor->latestReading ? $sensor->latestReading->formatted_value : 'Aucune donnée';
            $this->line("  {$sensor->name} ({$sensor->type}): {$latestValue} - Statut: {$sensor->status}");
        }

        $this->newLine();
        $activeAlerts = Alert::where('device_id', $device->id)
            ->where('status', 'active')
            ->count();
        $this->line("Alertes actives: {$activeAlerts}");
    }

    private function showRecentAlerts()
    {
        $this->info('=== ALERTES RÉCENTES ===');
        
        $alerts = Alert::with(['device', 'sensor', 'zone'])
            ->latest()
            ->limit(10)
            ->get();

        if ($alerts->isEmpty()) {
            $this->line('Aucune alerte récente.');
            return;
        }

        foreach ($alerts as $alert) {
            $status = $alert->status === 'active' ? '🔴' : '✅';
            $this->line("{$status} [{$alert->level}] {$alert->title}");
            $this->line("   Device: {$alert->device->name}");
            $zoneName = $alert->zone ? $alert->zone->name : 'N/A';
            $this->line("   Zone: {$zoneName}");
            $this->line("   Déclenchée: {$alert->triggered_at->format('d/m/Y H:i:s')}");
            if ($alert->resolved_at) {
                $this->line("   Résolue: {$alert->resolved_at->format('d/m/Y H:i:s')}");
            }
            $this->newLine();
        }
    }

    private function clearAlerts()
    {
        if ($this->confirm('Êtes-vous sûr de vouloir effacer toutes les alertes?')) {
            $count = Alert::count();
            Alert::truncate();
            $this->info("{$count} alertes effacées.");
        }
    }
}
