/*
 * Système d'Alerte Incendie - Code Arduino ESP32
 * 
 * Capteurs utilisés:
 * - MQ-2: Détection fumée/gaz (analogique)
 * - DHT22: Température et humidité (digital)
 * - Capteur flamme IR: Détection flamme (digital)
 * - Buzzer: Alerte sonore
 * - LED RGB: Indicateur visuel
 * 
 * Connexions:
 * MQ-2: A0 (analogique)
 * DHT22: Pin 4 (digital)
 * Capteur flamme: Pin 2 (digital)
 * Buzzer: Pin 5 (PWM)
 * LED Rouge: Pin 18
 * LED Verte: Pin 19
 * LED Bleue: Pin 21
 */

#include <WiFi.h>
#include <HTTPClient.h>
#include <ArduinoJson.h>
#include "DHT.h"

// Configuration WiFi
const char* ssid = "VOTRE_WIFI_SSID";
const char* password = "VOTRE_WIFI_PASSWORD";

// Configuration API
const char* serverUrl = "http://votre-serveur-laravel/api/fire/sensor-data";
const char* apiKey = "device_votre_cle_api_ici";

// Pins des capteurs
#define MQ2_PIN A0
#define DHT_PIN 4
#define FLAME_PIN 2
#define BUZZER_PIN 5
#define LED_RED 18
#define LED_GREEN 19
#define LED_BLUE 21

// Types de capteurs
#define DHTTYPE DHT22

// Objets
DHT dht(DHT_PIN, DHTTYPE);
HTTPClient http;

// Variables globales
unsigned long lastSensorRead = 0;
unsigned long lastApiCall = 0;
const unsigned long sensorInterval = 2000;  // Lecture capteurs toutes les 2s
const unsigned long apiInterval = 5000;     // Envoi API toutes les 5s

// Seuils d'alerte (à ajuster selon vos besoins)
const float SMOKE_THRESHOLD_WARN = 300.0;   // Seuil avertissement fumée
const float SMOKE_THRESHOLD_ALARM = 500.0;  // Seuil alarme fumée
const float TEMP_THRESHOLD_WARN = 35.0;     // Seuil avertissement température
const float TEMP_THRESHOLD_ALARM = 50.0;    // Seuil alarme température
const float HUMIDITY_THRESHOLD_WARN = 80.0; // Seuil avertissement humidité
const float HUMIDITY_THRESHOLD_ALARM = 95.0; // Seuil alarme humidité

// Variables pour les mesures
struct SensorData {
  float smoke;
  float temperature;
  float humidity;
  bool flame;
  unsigned long timestamp;
};

SensorData currentData;
bool alertActive = false;

void setup() {
  Serial.begin(115200);
  
  // Configuration des pins
  pinMode(MQ2_PIN, INPUT);
  pinMode(DHT_PIN, INPUT);
  pinMode(FLAME_PIN, INPUT);
  pinMode(BUZZER_PIN, OUTPUT);
  pinMode(LED_RED, OUTPUT);
  pinMode(LED_GREEN, OUTPUT);
  pinMode(LED_BLUE, OUTPUT);
  
  // Initialisation DHT22
  dht.begin();
  
  // Connexion WiFi
  connectToWiFi();
  
  // LED de démarrage
  startupSequence();
  
  Serial.println("Système d'alerte incendie démarré");
}

void loop() {
  unsigned long currentTime = millis();
  
  // Lecture des capteurs
  if (currentTime - lastSensorRead >= sensorInterval) {
    readSensors();
    checkAlerts();
    updateLEDs();
    lastSensorRead = currentTime;
  }
  
  // Envoi des données à l'API
  if (currentTime - lastApiCall >= apiInterval) {
    sendDataToAPI();
    lastApiCall = currentTime;
  }
  
  // Vérification de la connexion WiFi
  if (WiFi.status() != WL_CONNECTED) {
    Serial.println("WiFi déconnecté, reconnexion...");
    connectToWiFi();
  }
  
  delay(100);
}

void connectToWiFi() {
  Serial.print("Connexion au WiFi: ");
  Serial.println(ssid);
  
  WiFi.begin(ssid, password);
  
  int attempts = 0;
  while (WiFi.status() != WL_CONNECTED && attempts < 20) {
    delay(500);
    Serial.print(".");
    attempts++;
  }
  
  if (WiFi.status() == WL_CONNECTED) {
    Serial.println();
    Serial.println("WiFi connecté!");
    Serial.print("Adresse IP: ");
    Serial.println(WiFi.localIP());
  } else {
    Serial.println();
    Serial.println("Échec de connexion WiFi");
  }
}

void readSensors() {
  // Lecture MQ-2 (fumée/gaz)
  int smokeRaw = analogRead(MQ2_PIN);
  currentData.smoke = map(smokeRaw, 0, 4095, 0, 1000); // Conversion en ppm approximative
  
  // Lecture DHT22 (température et humidité)
  float temp = dht.readTemperature();
  float humidity = dht.readHumidity();
  
  if (!isnan(temp) && !isnan(humidity)) {
    currentData.temperature = temp;
    currentData.humidity = humidity;
  }
  
  // Lecture capteur flamme
  currentData.flame = digitalRead(FLAME_PIN) == LOW; // LOW = flamme détectée
  
  // Timestamp
  currentData.timestamp = millis();
  
  // Affichage des valeurs
  Serial.println("=== Données capteurs ===");
  Serial.print("Fumée: ");
  Serial.print(currentData.smoke);
  Serial.println(" ppm");
  Serial.print("Température: ");
  Serial.print(currentData.temperature);
  Serial.println(" °C");
  Serial.print("Humidité: ");
  Serial.print(currentData.humidity);
  Serial.println(" %");
  Serial.print("Flamme: ");
  Serial.println(currentData.flame ? "DÉTECTÉE" : "Non détectée");
  Serial.println("========================");
}

void checkAlerts() {
  bool newAlert = false;
  String alertMessage = "";
  
  // Vérification fumée
  if (currentData.smoke >= SMOKE_THRESHOLD_ALARM) {
    newAlert = true;
    alertMessage = "ALERTE CRITIQUE: Fumée détectée (" + String(currentData.smoke) + " ppm)";
    activateBuzzer(2000); // Buzzer 2 secondes
  } else if (currentData.smoke >= SMOKE_THRESHOLD_WARN) {
    newAlert = true;
    alertMessage = "Avertissement: Fumée élevée (" + String(currentData.smoke) + " ppm)";
    activateBuzzer(500); // Buzzer court
  }
  
  // Vérification température
  if (currentData.temperature >= TEMP_THRESHOLD_ALARM) {
    newAlert = true;
    alertMessage = "ALERTE CRITIQUE: Température élevée (" + String(currentData.temperature) + " °C)";
    activateBuzzer(2000);
  } else if (currentData.temperature >= TEMP_THRESHOLD_WARN) {
    newAlert = true;
    alertMessage = "Avertissement: Température élevée (" + String(currentData.temperature) + " °C)";
    activateBuzzer(500);
  }
  
  // Vérification humidité
  if (currentData.humidity >= HUMIDITY_THRESHOLD_ALARM) {
    newAlert = true;
    alertMessage = "Avertissement: Humidité très élevée (" + String(currentData.humidity) + " %)";
    activateBuzzer(500);
  }
  
  // Vérification flamme
  if (currentData.flame) {
    newAlert = true;
    alertMessage = "URGENCE INCENDIE: Flamme détectée!";
    activateBuzzer(3000); // Buzzer long pour urgence
  }
  
  if (newAlert) {
    Serial.println("🚨 ALERTE: " + alertMessage);
    alertActive = true;
  } else {
    alertActive = false;
  }
}

void activateBuzzer(int duration) {
  digitalWrite(BUZZER_PIN, HIGH);
  delay(duration);
  digitalWrite(BUZZER_PIN, LOW);
}

void updateLEDs() {
  // Éteindre toutes les LEDs
  digitalWrite(LED_RED, LOW);
  digitalWrite(LED_GREEN, LOW);
  digitalWrite(LED_BLUE, LOW);
  
  if (alertActive) {
    // LED rouge clignotante en cas d'alerte
    digitalWrite(LED_RED, HIGH);
    delay(200);
    digitalWrite(LED_RED, LOW);
  } else if (currentData.smoke >= SMOKE_THRESHOLD_WARN || 
             currentData.temperature >= TEMP_THRESHOLD_WARN) {
    // LED orange (rouge + verte) pour avertissement
    digitalWrite(LED_RED, HIGH);
    digitalWrite(LED_GREEN, HIGH);
  } else {
    // LED verte pour état normal
    digitalWrite(LED_GREEN, HIGH);
  }
}

void sendDataToAPI() {
  if (WiFi.status() != WL_CONNECTED) {
    Serial.println("WiFi non connecté, impossible d'envoyer les données");
    return;
  }
  
  // Création du JSON
  DynamicJsonDocument doc(1024);
  doc["device_status"] = "online";
  
  JsonArray readings = doc.createNestedArray("readings");
  
  // Ajout des données fumée
  JsonObject smokeReading = readings.createNestedObject();
  smokeReading["sensor_type"] = "smoke";
  smokeReading["value"] = currentData.smoke;
  smokeReading["timestamp"] = currentData.timestamp;
  
  // Ajout des données température
  JsonObject tempReading = readings.createNestedObject();
  tempReading["sensor_type"] = "temperature";
  tempReading["value"] = currentData.temperature;
  tempReading["timestamp"] = currentData.timestamp;
  
  // Ajout des données humidité
  JsonObject humidityReading = readings.createNestedObject();
  humidityReading["sensor_type"] = "humidity";
  humidityReading["value"] = currentData.humidity;
  humidityReading["timestamp"] = currentData.timestamp;
  
  // Ajout des données flamme
  JsonObject flameReading = readings.createNestedObject();
  flameReading["sensor_type"] = "flame";
  flameReading["value"] = currentData.flame ? 1 : 0;
  flameReading["timestamp"] = currentData.timestamp;
  
  // Envoi HTTP
  http.begin(serverUrl);
  http.addHeader("Content-Type", "application/json");
  http.addHeader("X-API-KEY", apiKey);
  
  String jsonString;
  serializeJson(doc, jsonString);
  
  Serial.println("Envoi des données à l'API...");
  Serial.println(jsonString);
  
  int httpResponseCode = http.POST(jsonString);
  
  if (httpResponseCode > 0) {
    String response = http.getString();
    Serial.println("Réponse API (" + String(httpResponseCode) + "): " + response);
  } else {
    Serial.println("Erreur API: " + String(httpResponseCode));
  }
  
  http.end();
}

void startupSequence() {
  // Séquence de démarrage avec les LEDs
  for (int i = 0; i < 3; i++) {
    digitalWrite(LED_RED, HIGH);
    delay(200);
    digitalWrite(LED_RED, LOW);
    digitalWrite(LED_GREEN, HIGH);
    delay(200);
    digitalWrite(LED_GREEN, LOW);
    digitalWrite(LED_BLUE, HIGH);
    delay(200);
    digitalWrite(LED_BLUE, LOW);
  }
  
  // LED verte finale pour indiquer le démarrage réussi
  digitalWrite(LED_GREEN, HIGH);
  delay(1000);
  digitalWrite(LED_GREEN, LOW);
}
