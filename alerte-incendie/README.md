# 🔥 Système d'Alerte Incendie IoT

Un système complet d'alerte incendie combinant **IoT**, **programmation web** et **modélisation 3D** pour la surveillance en temps réel d'une maison.

## 🎯 Objectifs du Projet

- **Détection automatique** : Fumée, température, humidité, flamme
- **Alertes temps réel** : Notifications instantanées et alertes sonores
- **Dashboard web** : Interface de monitoring avec visualisation 3D
- **Historique complet** : Logs et analyses des incidents
- **Architecture modulaire** : Facilement extensible

## 🛠️ Technologies Utilisées

### Backend
- **Laravel 12** : Framework PHP moderne
- **MySQL** : Base de données relationnelle
- **Tailwind CSS 4.0** : Framework CSS utilitaire
- **WebSockets** : Communication temps réel

### IoT
- **ESP32** : Microcontrôleur WiFi
- **Capteurs** : MQ-2 (fumée), DHT22 (température/humidité), capteur flamme IR
- **Actionneurs** : Buzzer, LED RGB

### 3D
- **Modélisation** : Boîtiers et supports pour impression 3D
- **Visualisation** : Three.js pour la vue 3D interactive

## 📁 Structure du Projet

```
alerte-incendie/
├── app/
│   ├── Http/Controllers/FireController.php
│   ├── Models/ (Device, Sensor, Alert, Zone, SensorReading)
│   └── Services/FireDetectionService.php
├── database/
│   ├── migrations/ (5 tables principales)
│   └── seeders/FireSystemSeeder.php
├── resources/views/fire/
│   ├── dashboard.blade.php
│   ├── sensors.blade.php
│   ├── alerts.blade.php
│   └── zones.blade.php
├── routes/
│   ├── api.php (endpoints Arduino)
│   └── web.php (routes dashboard)
├── arduino/
│   ├── fire_detection_system.ino
│   ├── config.h
│   └── README_MONTAGE.md
└── 3d_models/ (à créer)
    ├── sensor_case.stl
    ├── buzzer_case.stl
    └── device_case.stl
```

## 🚀 Installation et Configuration

### 1. Prérequis
- PHP 8.2+
- Composer
- Node.js & NPM
- MySQL/MariaDB
- Arduino IDE
- ESP32 DevKit

### 2. Installation Laravel

```bash
# Cloner le projet
git clone <votre-repo>
cd alerte-incendie

# Installer les dépendances
composer install
npm install

# Configuration
cp .env.example .env
php artisan key:generate

# Base de données
php artisan migrate
php artisan db:seed --class=FireSystemSeeder

# Compiler les assets
npm run build
```

### 3. Configuration Arduino

1. **Installer les bibliothèques** :
   - WiFi (ESP32)
   - HTTPClient (ESP32)
   - ArduinoJson
   - DHT sensor library

2. **Modifier la configuration** dans `arduino/config.h` :
   ```cpp
   const char* WIFI_SSID = "VOTRE_WIFI";
   const char* WIFI_PASSWORD = "VOTRE_MOT_DE_PASSE";
   const char* API_SERVER_URL = "http://VOTRE_IP/api/fire/sensor-data";
   ```

3. **Uploader le code** sur l'ESP32

### 4. Montage Électronique

Voir le guide détaillé dans `arduino/README_MONTAGE.md`

## 🔧 Utilisation

### Dashboard Web
Accédez à `http://localhost/fire/dashboard` pour :
- Surveiller les capteurs en temps réel
- Consulter l'historique des alertes
- Configurer les zones et seuils
- Visualiser la maison en 3D

### API Arduino
Endpoints disponibles :
- `POST /api/fire/sensor-data` : Envoi des données capteurs
- `POST /api/fire/alert` : Déclenchement d'alerte manuelle
- `GET /api/fire/system-status` : Statut du système

### Tests
```bash
# Tester le système avec des données simulées
php artisan fire:test

# Générer des données de test
php artisan db:seed --class=FireSystemSeeder
```

## 📊 Fonctionnalités

### Détection Automatique
- **Fumée/Gaz** : Capteur MQ-2 avec seuils configurables
- **Température** : DHT22 avec alertes de surchauffe
- **Humidité** : Surveillance de l'humidité ambiante
- **Flamme** : Détection infrarouge de flamme

### Alertes Intelligentes
- **4 niveaux** : Info, Warning, Critical, Emergency
- **Notifications multiples** : Sonores, visuelles, web
- **Résolution automatique** : Auto-résolution des anciennes alertes
- **Historique complet** : Logs détaillés avec filtres

### Dashboard Temps Réel
- **Vue globale** : Statut de tous les devices et capteurs
- **Cartes de statistiques** : Métriques en temps réel
- **Alertes récentes** : Liste des incidents actifs
- **Vue 3D** : Plan interactif de la maison

### Gestion Avancée
- **Zones configurables** : Définition des espaces surveillés
- **Seuils personnalisés** : Ajustement selon l'environnement
- **Calibration** : Ajustement automatique des capteurs
- **Maintenance** : Planning des vérifications

## 🔒 Sécurité

- **Authentification API** : Clés API uniques par device
- **Validation stricte** : Vérification de toutes les données
- **HTTPS obligatoire** : Communication chiffrée
- **Rate limiting** : Protection contre le spam

## 📈 Monitoring et Analytics

### Métriques Disponibles
- Nombre de devices en ligne/offline
- Capteurs actifs/inactifs
- Alertes par niveau et zone
- Tendances temporelles

### Rapports
- Export PDF des incidents
- Graphiques de tendances
- Statistiques de maintenance
- Analyse des faux positifs

## 🛠️ Développement

### Structure MVC
- **Models** : Relations Eloquent entre entités
- **Controllers** : Logique métier et API
- **Services** : Services métier (FireDetectionService)
- **Views** : Interface utilisateur responsive

### API RESTful
- Endpoints standardisés
- Codes de retour HTTP appropriés
- Validation des données
- Gestion d'erreurs

### Tests
```bash
# Tests unitaires
php artisan test

# Tests de l'API
php artisan fire:test --device=1
```

## 🔮 Améliorations Futures

### Fonctionnalités Avancées
- **IA prédictive** : Détection d'anomalies avant alerte
- **Notifications externes** : SMS, Email, Push
- **Intégration pompiers** : Appel automatique
- **Caméras IP** : Visualisation en temps réel

### Optimisations
- **Deep sleep** : Économie d'énergie ESP32
- **OTA updates** : Mise à jour sans fil
- **SD card logging** : Stockage local
- **Multi-zones** : Surveillance étendue

### 3D Printing
- **Boîtiers étanches** : Protection des capteurs
- **Supports muraux** : Installation discrète
- **Central de contrôle** : Boîtier principal
- **Sirène d'alerte** : Boîtier avec LED et buzzer

## 📞 Support

### Documentation
- Guide de montage : `arduino/README_MONTAGE.md`
- Configuration : `arduino/config.h`
- API : Routes dans `routes/api.php`

### Dépannage
- Vérifiez les connexions WiFi
- Testez les capteurs individuellement
- Consultez les logs Laravel
- Utilisez le moniteur série Arduino

## 📄 Licence

Ce projet est développé dans le cadre d'un stage de formation.

## 👥 Auteur

Projet réalisé par [Votre Nom] - Stage IoT/Programmation/3D

---

**🔥 Système d'Alerte Incendie IoT - Sécurité et Innovation**