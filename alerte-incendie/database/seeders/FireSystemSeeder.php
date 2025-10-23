<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Device;
use App\Models\Zone;
use App\Models\Sensor;
use App\Models\Alert;
use App\Models\SensorReading;

class FireSystemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Créer des zones de test
        $zones = [
            [
                'name' => 'Cuisine',
                'description' => 'Zone cuisine avec four et plaques de cuisson',
                'pos_x' => 2.5,
                'pos_y' => 1.0,
                'pos_z' => 0.0,
                'dimensions' => ['length' => 4.0, 'width' => 3.0, 'height' => 2.5]
            ],
            [
                'name' => 'Salon',
                'description' => 'Zone salon avec cheminée et TV',
                'pos_x' => 0.0,
                'pos_y' => 0.0,
                'pos_z' => 0.0,
                'dimensions' => ['length' => 5.0, 'width' => 4.0, 'height' => 2.5]
            ],
            [
                'name' => 'Chambre',
                'description' => 'Chambre principale',
                'pos_x' => -2.0,
                'pos_y' => 3.0,
                'pos_z' => 0.0,
                'dimensions' => ['length' => 3.5, 'width' => 3.0, 'height' => 2.5]
            ],
            [
                'name' => 'Garage',
                'description' => 'Garage avec outils et produits chimiques',
                'pos_x' => 5.0,
                'pos_y' => 0.0,
                'pos_z' => 0.0,
                'dimensions' => ['length' => 6.0, 'width' => 3.0, 'height' => 2.5]
            ]
        ];

        foreach ($zones as $zoneData) {
            Zone::create($zoneData);
        }

        // Créer des devices de test
        $devices = [
            [
                'name' => 'Arduino Cuisine',
                'location' => 'Cuisine - Placard haut',
                'api_key' => Device::generateApiKey(),
                'status' => 'online',
                'last_seen_at' => now(),
                'settings' => ['firmware_version' => '1.0.0', 'calibration_date' => now()->toDateString()]
            ],
            [
                'name' => 'Arduino Salon',
                'location' => 'Salon - Prise murale',
                'api_key' => Device::generateApiKey(),
                'status' => 'online',
                'last_seen_at' => now()->subMinutes(2),
                'settings' => ['firmware_version' => '1.0.0', 'calibration_date' => now()->toDateString()]
            ],
            [
                'name' => 'Arduino Chambre',
                'location' => 'Chambre - Table de chevet',
                'api_key' => Device::generateApiKey(),
                'status' => 'offline',
                'last_seen_at' => now()->subHours(1),
                'settings' => ['firmware_version' => '0.9.5', 'calibration_date' => now()->subDays(7)->toDateString()]
            ]
        ];

        foreach ($devices as $deviceData) {
            Device::create($deviceData);
        }

        // Créer des capteurs pour chaque device
        $devices = Device::all();
        $zones = Zone::all();

        foreach ($devices as $device) {
            $zone = $zones->random();
            
            // Capteur fumée
            Sensor::create([
                'device_id' => $device->id,
                'zone_id' => $zone->id,
                'name' => "Capteur Fumée {$zone->name}",
                'type' => 'smoke',
                'unit' => 'ppm',
                'threshold_warn' => 300,
                'threshold_alarm' => 500,
                'status' => 'active',
                'pos_x' => $zone->pos_x + (rand(-50, 50) / 100),
                'pos_y' => $zone->pos_y + (rand(-50, 50) / 100),
                'pos_z' => 2.0,
                'calibration_data' => ['last_calibration' => now()->subDays(30)->toDateString()]
            ]);

            // Capteur température
            Sensor::create([
                'device_id' => $device->id,
                'zone_id' => $zone->id,
                'name' => "Capteur Température {$zone->name}",
                'type' => 'temperature',
                'unit' => '°C',
                'threshold_warn' => 35,
                'threshold_alarm' => 50,
                'status' => 'active',
                'pos_x' => $zone->pos_x + (rand(-50, 50) / 100),
                'pos_y' => $zone->pos_y + (rand(-50, 50) / 100),
                'pos_z' => 1.5,
                'calibration_data' => ['last_calibration' => now()->subDays(30)->toDateString()]
            ]);

            // Capteur humidité
            Sensor::create([
                'device_id' => $device->id,
                'zone_id' => $zone->id,
                'name' => "Capteur Humidité {$zone->name}",
                'type' => 'humidity',
                'unit' => '%',
                'threshold_warn' => 80,
                'threshold_alarm' => 95,
                'status' => 'active',
                'pos_x' => $zone->pos_x + (rand(-50, 50) / 100),
                'pos_y' => $zone->pos_y + (rand(-50, 50) / 100),
                'pos_z' => 1.5,
                'calibration_data' => ['last_calibration' => now()->subDays(30)->toDateString()]
            ]);

            // Capteur flamme
            Sensor::create([
                'device_id' => $device->id,
                'zone_id' => $zone->id,
                'name' => "Capteur Flamme {$zone->name}",
                'type' => 'flame',
                'unit' => 'bool',
                'threshold_warn' => 1,
                'threshold_alarm' => 1,
                'status' => 'active',
                'pos_x' => $zone->pos_x + (rand(-50, 50) / 100),
                'pos_y' => $zone->pos_y + (rand(-50, 50) / 100),
                'pos_z' => 1.0,
                'calibration_data' => ['last_calibration' => now()->subDays(30)->toDateString()]
            ]);
        }

        // Créer des lectures de capteurs récentes
        $sensors = Sensor::all();
        
        foreach ($sensors as $sensor) {
            // Créer des lectures pour les dernières 24h
            for ($i = 0; $i < 24; $i++) {
                $timestamp = now()->subHours($i);
                
                // Valeurs réalistes selon le type de capteur
                $value = match($sensor->type) {
                    'smoke' => rand(50, 200) + (rand(0, 100) / 100),
                    'temperature' => 20 + rand(0, 15) + (rand(0, 100) / 100),
                    'humidity' => 40 + rand(0, 40) + (rand(0, 100) / 100),
                    'flame' => rand(0, 1),
                    'gas' => rand(10, 50) + (rand(0, 100) / 100),
                    default => rand(0, 100)
                };

                // Qualité des données
                $quality = match(true) {
                    $value >= $sensor->threshold_alarm => 'error',
                    $value >= $sensor->threshold_warn => 'warning',
                    default => 'good'
                };

                SensorReading::create([
                    'sensor_id' => $sensor->id,
                    'value' => $value,
                    'unit' => $sensor->unit,
                    'measured_at' => $timestamp,
                    'raw_data' => ['raw_value' => $value, 'sensor_type' => $sensor->type],
                    'quality' => $quality
                ]);
            }
        }

        // Créer quelques alertes de test
        $alerts = [
            [
                'device_id' => $devices[0]->id,
                'sensor_id' => $devices[0]->sensors()->where('type', 'smoke')->first()->id,
                'zone_id' => $zones[0]->id,
                'level' => 'warning',
                'title' => 'Avertissement - Fumée',
                'message' => 'ATTENTION: Valeur élevée détectée par Capteur Fumée Cuisine en Cuisine (350 ppm)',
                'data' => ['value' => 350, 'threshold_warn' => 300, 'threshold_alarm' => 500],
                'triggered_at' => now()->subHours(2),
                'status' => 'resolved',
                'resolved_at' => now()->subHours(1)
            ],
            [
                'device_id' => $devices[1]->id,
                'sensor_id' => $devices[1]->sensors()->where('type', 'temperature')->first()->id,
                'zone_id' => $zones[1]->id,
                'level' => 'critical',
                'title' => 'Alerte Critique - Température',
                'message' => 'ALERTE CRITIQUE: Danger détecté par Capteur Température Salon en Salon (52 °C)',
                'data' => ['value' => 52, 'threshold_warn' => 35, 'threshold_alarm' => 50],
                'triggered_at' => now()->subMinutes(30),
                'status' => 'active'
            ],
            [
                'device_id' => $devices[0]->id,
                'sensor_id' => $devices[0]->sensors()->where('type', 'flame')->first()->id,
                'zone_id' => $zones[0]->id,
                'level' => 'emergency',
                'title' => 'URGENCE INCENDIE - Flamme',
                'message' => '🚨 URGENCE INCENDIE: Détection de feu par Capteur Flamme Cuisine en Cuisine (1) - ÉVACUATION IMMÉDIATE',
                'data' => ['value' => 1, 'threshold_warn' => 1, 'threshold_alarm' => 1],
                'triggered_at' => now()->subMinutes(5),
                'status' => 'active'
            ]
        ];

        foreach ($alerts as $alertData) {
            Alert::create($alertData);
        }

        $this->command->info('Données de test créées avec succès!');
        $this->command->info('Devices créés: ' . Device::count());
        $this->command->info('Zones créées: ' . Zone::count());
        $this->command->info('Capteurs créés: ' . Sensor::count());
        $this->command->info('Alertes créées: ' . Alert::count());
        $this->command->info('Lectures créées: ' . SensorReading::count());
    }
}
