# Commandes pour finaliser l'installation du système d'alerte incendie

## 1. Migrations et données de test
```bash
cd alerte-incendie
php artisan migrate
php artisan db:seed --class=FireSystemSeeder
```

## 2. Compilation des assets
```bash
npm install
npm run build
```

## 3. Test du système
```bash
# Tester avec des données simulées
php artisan fire:test

# Vérifier les routes
php artisan route:list
```

## 4. Démarrage du serveur
```bash
# Serveur de développement
php artisan serve

# Ou avec Vite pour les assets
npm run dev
```

## 5. Configuration Arduino
1. Ouvrir `arduino/fire_detection_system.ino` dans Arduino IDE
2. Modifier les paramètres WiFi dans `arduino/config.h`
3. Installer les bibliothèques nécessaires
4. Uploader sur l'ESP32

## 6. URLs d'accès
- Dashboard: http://localhost:8000/fire/dashboard
- API Health: http://localhost:8000/api/health
- Capteurs: http://localhost:8000/fire/sensors
- Alertes: http://localhost:8000/fire/alerts
- Zones: http://localhost:8000/fire/zones

## 7. Configuration pour Arduino
- Remplacer `VOTRE_WIFI_SSID` et `VOTRE_WIFI_PASSWORD`
- Remplacer `http://votre-serveur-laravel` par votre IP locale
- Générer une clé API via le dashboard ou la commande artisan

## 8. Test complet
1. Démarrer le serveur Laravel
2. Uploader le code Arduino
3. Vérifier la connexion WiFi de l'ESP32
4. Tester l'envoi de données via l'API
5. Vérifier l'affichage dans le dashboard
