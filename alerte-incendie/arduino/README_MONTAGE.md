# Système d'Alerte Incendie - Guide de Montage Arduino

## 📋 Matériel nécessaire

### Composants électroniques
- **ESP32 DevKit** (recommandé) ou Arduino Uno + module WiFi
- **Capteur MQ-2** (détection fumée/gaz)
- **Capteur DHT22** (température et humidité)
- **Capteur flamme IR** (détection flamme)
- **Buzzer piezo** (alerte sonore)
- **LED RGB** ou 3 LEDs (rouge, verte, bleue)
- **Résistances** : 10kΩ (pull-up DHT22), 220Ω (LEDs)
- **Breadboard** et fils de connexion
- **Alimentation** : 5V pour ESP32, 3.3V pour capteurs

### Outils
- Fer à souder et étain
- Multimètre
- Pince à dénuder
- Tournevis

## 🔌 Schéma de connexions

### ESP32 DevKit
```
Pin ESP32    →    Composant
─────────────────────────────
A0          →    MQ-2 (signal analogique)
GPIO 4      →    DHT22 (data)
GPIO 2      →    Capteur flamme (signal)
GPIO 5      →    Buzzer (+)
GPIO 18     →    LED Rouge (+)
GPIO 19     →    LED Verte (+)
GPIO 21     →    LED Bleue (+)
3.3V        →    Alimentation capteurs
GND         →    Masse commune
```

### Détail des connexions

#### Capteur MQ-2 (Fumée/Gaz)
- **VCC** → 5V (ou 3.3V selon le modèle)
- **GND** → GND
- **A0** → Pin A0 ESP32
- **D0** → Non utilisé (on utilise la sortie analogique)

#### Capteur DHT22 (Température/Humidité)
- **VCC** → 3.3V
- **GND** → GND
- **Data** → GPIO 4 + résistance pull-up 10kΩ vers 3.3V

#### Capteur Flamme IR
- **VCC** → 3.3V
- **GND** → GND
- **Signal** → GPIO 2

#### Buzzer Piezo
- **+** → GPIO 5
- **-** → GND

#### LEDs
- **LED Rouge** : Anode → GPIO 18, Cathode → GND (via résistance 220Ω)
- **LED Verte** : Anode → GPIO 19, Cathode → GND (via résistance 220Ω)
- **LED Bleue** : Anode → GPIO 21, Cathode → GND (via résistance 220Ω)

## ⚙️ Configuration du code

### 1. Installation des bibliothèques Arduino
Ouvrez l'Arduino IDE et installez ces bibliothèques :
- **WiFi** (incluse avec ESP32)
- **HTTPClient** (incluse avec ESP32)
- **ArduinoJson** (via Library Manager)
- **DHT sensor library** (via Library Manager)

### 2. Configuration WiFi
Modifiez dans `config.h` :
```cpp
const char* WIFI_SSID = "VOTRE_WIFI_SSID";
const char* WIFI_PASSWORD = "VOTRE_WIFI_PASSWORD";
```

### 3. Configuration API
Modifiez l'URL de votre serveur Laravel :
```cpp
const char* API_SERVER_URL = "http://votre-ip-laravel/api/fire/sensor-data";
const char* API_KEY = "device_votre_cle_api_generee";
```

### 4. Ajustement des seuils
Modifiez les seuils selon votre environnement :
```cpp
const float SMOKE_THRESHOLD_WARN = 300.0;    // ppm
const float SMOKE_THRESHOLD_ALARM = 500.0;   // ppm
const float TEMP_THRESHOLD_WARN = 35.0;      // °C
const float TEMP_THRESHOLD_ALARM = 50.0;     // °C
```

## 🔧 Calibration des capteurs

### Capteur MQ-2
1. **Test en air pur** : Notez la valeur en air pur (environ 100-200 ppm)
2. **Test avec fumée** : Approchez une source de fumée et notez la valeur
3. **Ajustez les seuils** selon vos tests

### Capteur DHT22
1. **Vérification température** : Comparez avec un thermomètre de référence
2. **Vérification humidité** : Comparez avec un hygromètre de référence

### Capteur Flamme IR
1. **Test de détection** : Approchez une flamme (briquet, bougie)
2. **Ajustez la sensibilité** si nécessaire

## 🚀 Démarrage

### 1. Upload du code
1. Connectez l'ESP32 via USB
2. Sélectionnez le bon port dans l'Arduino IDE
3. Sélectionnez "ESP32 Dev Module" comme carte
4. Uploadez le code

### 2. Test de fonctionnement
1. **LEDs de démarrage** : Les LEDs doivent clignoter en séquence
2. **Connexion WiFi** : Vérifiez dans le moniteur série
3. **Lecture capteurs** : Vérifiez les valeurs dans le moniteur série
4. **Envoi API** : Vérifiez les requêtes HTTP dans le moniteur série

### 3. Test des alertes
1. **Test fumée** : Approchez une source de fumée
2. **Test température** : Réchauffez le capteur DHT22
3. **Test flamme** : Approchez une flamme du capteur IR

## 🛠️ Dépannage

### Problèmes courants

#### WiFi ne se connecte pas
- Vérifiez le SSID et mot de passe
- Vérifiez la portée du WiFi
- Redémarrez l'ESP32

#### Capteurs ne fonctionnent pas
- Vérifiez les connexions
- Vérifiez l'alimentation (3.3V ou 5V)
- Vérifiez les pins dans le code

#### API ne répond pas
- Vérifiez l'URL du serveur Laravel
- Vérifiez la clé API
- Vérifiez que le serveur Laravel fonctionne

#### Buzzer ne sonne pas
- Vérifiez la connexion GPIO 5
- Testez avec un multimètre
- Vérifiez que le buzzer fonctionne (test direct 3.3V)

### Messages d'erreur courants

```
WiFi déconnecté, reconnexion...
→ Problème de connexion WiFi

Erreur API: -1
→ Serveur Laravel inaccessible

Erreur API: 401
→ Clé API invalide

Erreur API: 400
→ Données mal formatées
```

## 📊 Monitoring

### Moniteur série Arduino IDE
- Ouvrez le moniteur série (Ctrl+Shift+M)
- Vitesse : 115200 baud
- Surveillez les messages de debug

### Dashboard Laravel
- Accédez à `http://votre-serveur/fire/dashboard`
- Surveillez les données en temps réel
- Consultez l'historique des alertes

## 🔒 Sécurité

### Recommandations
- Changez la clé API régulièrement
- Utilisez HTTPS en production
- Placez les capteurs hors de portée des enfants
- Testez régulièrement le système

### Maintenance
- Nettoyez les capteurs régulièrement
- Vérifiez les connexions
- Mettez à jour le firmware ESP32
- Sauvegardez la configuration

## 📈 Améliorations possibles

### Fonctionnalités avancées
- **Capteur CO2** : Détection de monoxyde de carbone
- **Caméra IP** : Visualisation en temps réel
- **SMS/Email** : Notifications externes
- **Batterie de secours** : Fonctionnement autonome
- **Enclosure étanche** : Protection contre l'eau

### Optimisations
- **Deep sleep** : Économie d'énergie
- **OTA updates** : Mise à jour sans fil
- **SD card logging** : Stockage local des données
- **Multiple zones** : Surveillance de plusieurs pièces
