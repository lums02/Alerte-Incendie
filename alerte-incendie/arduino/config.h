/*
 * Configuration du Système d'Alerte Incendie
 * 
 * Modifiez ces valeurs selon votre configuration
 */

// Configuration WiFi
const char* WIFI_SSID = "VOTRE_WIFI_SSID";
const char* WIFI_PASSWORD = "VOTRE_WIFI_PASSWORD";

// Configuration API Laravel
const char* API_SERVER_URL = "http://votre-serveur-laravel/api/fire/sensor-data";
const char* API_KEY = "device_votre_cle_api_ici";

// Configuration des pins (ESP32)
#define PIN_MQ2_SMOKE A0        // Capteur fumée MQ-2 (analogique)
#define PIN_DHT22 4             // Capteur température/humidité DHT22
#define PIN_FLAME_SENSOR 2      // Capteur flamme IR
#define PIN_BUZZER 5            // Buzzer d'alerte
#define PIN_LED_RED 18          // LED rouge
#define PIN_LED_GREEN 19        // LED verte
#define PIN_LED_BLUE 21         // LED bleue

// Intervalles de temps (en millisecondes)
const unsigned long SENSOR_READ_INTERVAL = 2000;  // Lecture capteurs toutes les 2s
const unsigned long API_SEND_INTERVAL = 5000;     // Envoi API toutes les 5s
const unsigned long WIFI_CHECK_INTERVAL = 30000;  // Vérification WiFi toutes les 30s

// Seuils d'alerte - À AJUSTER SELON VOS BESOINS
const float SMOKE_THRESHOLD_WARN = 300.0;    // Seuil avertissement fumée (ppm)
const float SMOKE_THRESHOLD_ALARM = 500.0;   // Seuil alarme fumée (ppm)
const float TEMP_THRESHOLD_WARN = 35.0;      // Seuil avertissement température (°C)
const float TEMP_THRESHOLD_ALARM = 50.0;     // Seuil alarme température (°C)
const float HUMIDITY_THRESHOLD_WARN = 80.0;  // Seuil avertissement humidité (%)
const float HUMIDITY_THRESHOLD_ALARM = 95.0; // Seuil alarme humidité (%)

// Durées des alertes sonores (en millisecondes)
const int BUZZER_SHORT_BEEP = 500;    // Bip court pour avertissement
const int BUZZER_LONG_BEEP = 2000;    // Bip long pour alerte critique
const int BUZZER_EMERGENCY_BEEP = 3000; // Bip très long pour urgence

// Configuration du capteur MQ-2
const int MQ2_RAW_MIN = 0;           // Valeur minimale brute
const int MQ2_RAW_MAX = 4095;        // Valeur maximale brute (ESP32)
const int MQ2_PPM_MIN = 0;           // Valeur minimale en ppm
const int MQ2_PPM_MAX = 1000;        // Valeur maximale en ppm

// Configuration du capteur de flamme
const bool FLAME_SENSOR_INVERTED = true; // true si le capteur est inversé (LOW = flamme détectée)

// Messages d'alerte personnalisés
const char* ALERT_SMOKE_WARN = "Avertissement: Fumée détectée";
const char* ALERT_SMOKE_ALARM = "ALERTE CRITIQUE: Fumée détectée";
const char* ALERT_TEMP_WARN = "Avertissement: Température élevée";
const char* ALERT_TEMP_ALARM = "ALERTE CRITIQUE: Température élevée";
const char* ALERT_HUMIDITY_WARN = "Avertissement: Humidité élevée";
const char* ALERT_HUMIDITY_ALARM = "ALERTE CRITIQUE: Humidité très élevée";
const char* ALERT_FLAME_DETECTED = "URGENCE INCENDIE: Flamme détectée!";

// Configuration du debug
const bool DEBUG_MODE = true;        // true pour activer les messages de debug
const int SERIAL_BAUD_RATE = 115200; // Vitesse de communication série
