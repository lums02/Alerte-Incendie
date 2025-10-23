@extends('layouts.app')

@section('title', 'Centre d\'Alertes - FireGuard')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- En-tête -->
    <div class="mb-8">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Centre d'Alertes</h1>
                <p class="text-gray-600 mt-2">Surveillance en temps réel et historique des alertes</p>
            </div>
            <div class="flex space-x-3">
                <select class="bg-white border border-gray-200 rounded-xl px-4 py-2 text-sm font-medium text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option>Toutes les alertes</option>
                    <option>Alertes actives</option>
                    <option>Alertes résolues</option>
                    <option>Faux positifs</option>
                </select>
                <button class="bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white px-6 py-2 rounded-xl font-medium transition-all duration-200 shadow-lg hover:shadow-xl">
                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Marquer tout comme lu
                </button>
            </div>
        </div>
    </div>

    <!-- Statistiques des alertes -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <!-- Alertes actives -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 card-hover animate-on-scroll">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-red-50 rounded-xl flex items-center justify-center mr-4">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Alertes actives</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $alerts->where('status', 'active')->count() }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Alertes critiques -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 card-hover animate-on-scroll">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-orange-50 rounded-xl flex items-center justify-center mr-4">
                        <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Alertes critiques</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $alerts->where('level', 'critical')->count() }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Alertes résolues -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 card-hover animate-on-scroll">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-green-50 rounded-xl flex items-center justify-center mr-4">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Alertes résolues</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $alerts->where('status', 'resolved')->count() }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total alertes -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 card-hover animate-on-scroll">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center mr-4">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM4.828 7l2.586 2.586a2 2 0 002.828 0L12.828 7H4.828zM4.828 17h8a2 2 0 012 2v2a2 2 0 01-2 2H4.828l2.586-2.586a2 2 0 000-2.828L4.828 17z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Total alertes</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $alerts->count() }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Liste des alertes -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 animate-on-scroll">
        <div class="p-6 border-b border-gray-100">
            <h3 class="text-lg font-semibold text-gray-900">Historique des Alertes</h3>
            <p class="text-sm text-gray-500 mt-1">Dernières alertes déclenchées dans votre système</p>
        </div>
        <div class="divide-y divide-gray-100">
            @forelse($alerts as $alert)
                <div class="p-6 hover:bg-gray-50 transition-colors duration-200">
                    <div class="flex items-start justify-between">
                        <div class="flex items-start">
                            <!-- Icône de l'alerte -->
                            <div class="flex-shrink-0 mr-4">
                                <div class="w-12 h-12 rounded-xl flex items-center justify-center {{ $alert->level === 'emergency' ? 'bg-red-100' : ($alert->level === 'critical' ? 'bg-orange-100' : ($alert->level === 'warning' ? 'bg-yellow-100' : 'bg-blue-100')) }}">
                                    @if($alert->level === 'emergency')
                                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.879 16.121A3 3 0 1012.015 11L11 14H9c0 .768.293 1.536.879 2.121z"></path>
                                        </svg>
                                    @elseif($alert->level === 'critical')
                                        <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                        </svg>
                                    @elseif($alert->level === 'warning')
                                        <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    @else
                                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    @endif
                                </div>
                            </div>
                            
                            <!-- Contenu de l'alerte -->
                            <div class="flex-1">
                                <div class="flex items-center mb-2">
                                    <h4 class="text-lg font-semibold text-gray-900 mr-3">{{ $alert->title }}</h4>
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ $alert->level === 'emergency' ? 'bg-red-100 text-red-800' : ($alert->level === 'critical' ? 'bg-orange-100 text-orange-800' : ($alert->level === 'warning' ? 'bg-yellow-100 text-yellow-800' : 'bg-blue-100 text-blue-800')) }}">
                                        {{ ucfirst($alert->level) }}
                                    </span>
                                    @if($alert->status === 'active')
                                        <span class="ml-2 inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 pulse-animation">
                                            Actif
                                        </span>
                                    @endif
                                </div>
                                
                                <p class="text-gray-600 mb-3">{{ $alert->message }}</p>
                                
                                <!-- Métadonnées de l'alerte -->
                                <div class="flex items-center text-sm text-gray-500 space-x-4">
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                        {{ $alert->zone ? $alert->zone->name : 'Zone inconnue' }}
                                    </div>
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        {{ $alert->triggered_at->diffForHumans() }}
                                    </div>
                                    @if($alert->sensor)
                                        <div class="flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path>
                                            </svg>
                                            {{ $alert->sensor->name }}
                                        </div>
                                    @endif
                                </div>
                                
                                <!-- Données supplémentaires -->
                                @if($alert->data && isset($alert->data['value']))
                                    <div class="mt-3 p-3 bg-gray-50 rounded-lg">
                                        <div class="text-sm text-gray-600">
                                            <span class="font-medium">Valeur détectée:</span>
                                            <span class="ml-2 px-2 py-1 bg-white rounded text-gray-900 font-medium">
                                                {{ $alert->data['value'] }}{{ $alert->data['unit'] ?? '' }}
                                            </span>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                        
                        <!-- Actions -->
                        <div class="flex items-center space-x-2 ml-4">
                            @if($alert->status === 'active')
                                <button class="px-4 py-2 bg-green-50 text-green-600 rounded-lg text-sm font-medium hover:bg-green-100 transition-colors">
                                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Résoudre
                                </button>
                            @else
                                <span class="px-3 py-1 bg-green-100 text-green-800 rounded-lg text-sm font-medium">
                                    Résolu
                                </span>
                            @endif
                            <button class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="p-12 text-center">
                    <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM4.828 7l2.586 2.586a2 2 0 002.828 0L12.828 7H4.828zM4.828 17h8a2 2 0 012 2v2a2 2 0 01-2 2H4.828l2.586-2.586a2 2 0 000-2.828L4.828 17z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Aucune alerte</h3>
                    <p class="text-gray-500 mb-6">Aucune alerte n'a été déclenchée pour le moment. Votre système fonctionne normalement.</p>
                    <div class="flex justify-center">
                        <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center">
                            <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Graphique des alertes (si des données existent) -->
    @if($alerts->count() > 0)
        <div class="mt-8 bg-white rounded-2xl shadow-sm border border-gray-100 animate-on-scroll">
            <div class="p-6 border-b border-gray-100">
                <div class="flex justify-between items-center">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Évolution des Alertes</h3>
                        <p class="text-sm text-gray-500 mt-1">Tendances des alertes sur les 30 derniers jours</p>
                    </div>
                    <div class="flex space-x-2">
                        <button class="px-3 py-1 bg-blue-50 text-blue-600 rounded-lg text-sm font-medium hover:bg-blue-100 transition-colors" onclick="updateChart('week')">
                            7 jours
                        </button>
                        <button class="px-3 py-1 bg-gray-50 text-gray-600 rounded-lg text-sm font-medium hover:bg-gray-100 transition-colors" onclick="updateChart('month')">
                            30 jours
                        </button>
                        <button class="px-3 py-1 bg-gray-50 text-gray-600 rounded-lg text-sm font-medium hover:bg-gray-100 transition-colors" onclick="updateChart('year')">
                            1 an
                        </button>
                    </div>
                </div>
            </div>
            <div class="p-6">
                <div class="h-80">
                    <canvas id="alertsChart" style="display: none;"></canvas>
                    <div id="chartFallback" class="w-full h-full">
                        <!-- Graphique SVG de fallback -->
                        <svg class="w-full h-full" viewBox="0 0 800 300">
                            <defs>
                                <linearGradient id="emergencyGradient" x1="0%" y1="0%" x2="0%" y2="100%">
                                    <stop offset="0%" style="stop-color:#ef4444;stop-opacity:0.3" />
                                    <stop offset="100%" style="stop-color:#ef4444;stop-opacity:0" />
                                </linearGradient>
                                <linearGradient id="criticalGradient" x1="0%" y1="0%" x2="0%" y2="100%">
                                    <stop offset="0%" style="stop-color:#f97316;stop-opacity:0.3" />
                                    <stop offset="100%" style="stop-color:#f97316;stop-opacity:0" />
                                </linearGradient>
                                <linearGradient id="warningGradient" x1="0%" y1="0%" x2="0%" y2="100%">
                                    <stop offset="0%" style="stop-color:#eab308;stop-opacity:0.3" />
                                    <stop offset="100%" style="stop-color:#eab308;stop-opacity:0" />
                                </linearGradient>
                                <linearGradient id="infoGradient" x1="0%" y1="0%" x2="0%" y2="100%">
                                    <stop offset="0%" style="stop-color:#3b82f6;stop-opacity:0.3" />
                                    <stop offset="100%" style="stop-color:#3b82f6;stop-opacity:0" />
                                </linearGradient>
                            </defs>
                            
                            <!-- Grille -->
                            <g stroke="#e5e7eb" stroke-width="1" opacity="0.3">
                                <!-- Lignes verticales -->
                                <line x1="50" y1="20" x2="50" y2="280"/>
                                <line x1="150" y1="20" x2="150" y2="280"/>
                                <line x1="250" y1="20" x2="250" y2="280"/>
                                <line x1="350" y1="20" x2="350" y2="280"/>
                                <line x1="450" y1="20" x2="450" y2="280"/>
                                <line x1="550" y1="20" x2="550" y2="280"/>
                                <line x1="650" y1="20" x2="650" y2="280"/>
                                <line x1="750" y1="20" x2="750" y2="280"/>
                                
                                <!-- Lignes horizontales -->
                                <line x1="50" y1="60" x2="750" y2="60"/>
                                <line x1="50" y1="100" x2="750" y2="100"/>
                                <line x1="50" y1="140" x2="750" y2="140"/>
                                <line x1="50" y1="180" x2="750" y2="180"/>
                                <line x1="50" y1="220" x2="750" y2="220"/>
                                <line x1="50" y1="260" x2="750" y2="260"/>
                            </g>
                            
                            <!-- Axes -->
                            <line x1="50" y1="280" x2="750" y2="280" stroke="#374151" stroke-width="2"/>
                            <line x1="50" y1="20" x2="50" y2="280" stroke="#374151" stroke-width="2"/>
                            
                            <!-- Courbes de démonstration -->
                            <path d="M50,250 Q200,200 350,180 T650,120" stroke="#ef4444" stroke-width="3" fill="none"/>
                            <path d="M50,220 Q200,190 350,160 T650,100" stroke="#f97316" stroke-width="3" fill="none"/>
                            <path d="M50,200 Q200,170 350,140 T650,80" stroke="#eab308" stroke-width="3" fill="none"/>
                            <path d="M50,180 Q200,150 350,120 T650,60" stroke="#3b82f6" stroke-width="3" fill="none"/>
                            
                            <!-- Points sur les courbes -->
                            <circle cx="150" cy="200" r="4" fill="#ef4444"/>
                            <circle cx="250" cy="180" r="4" fill="#ef4444"/>
                            <circle cx="350" cy="160" r="4" fill="#ef4444"/>
                            <circle cx="450" cy="140" r="4" fill="#ef4444"/>
                            <circle cx="550" cy="120" r="4" fill="#ef4444"/>
                            <circle cx="650" cy="100" r="4" fill="#ef4444"/>
                            
                            <!-- Labels des axes -->
                            <text x="400" y="295" text-anchor="middle" class="text-sm fill-gray-600" font-family="Inter, sans-serif">Jours</text>
                            <text x="30" y="150" text-anchor="middle" class="text-sm fill-gray-600" font-family="Inter, sans-serif" transform="rotate(-90 30 150)">Nombre d'alertes</text>
                            
                            <!-- Valeurs sur l'axe Y -->
                            <text x="40" y="285" text-anchor="end" class="text-xs fill-gray-500" font-family="Inter, sans-serif">0</text>
                            <text x="40" y="245" text-anchor="end" class="text-xs fill-gray-500" font-family="Inter, sans-serif">2</text>
                            <text x="40" y="205" text-anchor="end" class="text-xs fill-gray-500" font-family="Inter, sans-serif">4</text>
                            <text x="40" y="165" text-anchor="end" class="text-xs fill-gray-500" font-family="Inter, sans-serif">6</text>
                            <text x="40" y="125" text-anchor="end" class="text-xs fill-gray-500" font-family="Inter, sans-serif">8</text>
                            <text x="40" y="85" text-anchor="end" class="text-xs fill-gray-500" font-family="Inter, sans-serif">10</text>
                        </svg>
                    </div>
                </div>
                
                <!-- Légende du graphique -->
                <div class="mt-6 flex justify-center space-x-6">
                    <div class="flex items-center">
                        <div class="w-3 h-3 bg-red-500 rounded-full mr-2"></div>
                        <span class="text-sm text-gray-600">Urgence</span>
                    </div>
                    <div class="flex items-center">
                        <div class="w-3 h-3 bg-orange-500 rounded-full mr-2"></div>
                        <span class="text-sm text-gray-600">Critique</span>
                    </div>
                    <div class="flex items-center">
                        <div class="w-3 h-3 bg-yellow-500 rounded-full mr-2"></div>
                        <span class="text-sm text-gray-600">Avertissement</span>
                    </div>
                    <div class="flex items-center">
                        <div class="w-3 h-3 bg-blue-500 rounded-full mr-2"></div>
                        <span class="text-sm text-gray-600">Information</span>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection

@section('scripts')
<script>
    let alertsChart = null;
    
    document.addEventListener('DOMContentLoaded', function() {
        // Animation des indicateurs de statut
        setInterval(() => {
            document.querySelectorAll('.pulse-animation').forEach(indicator => {
                indicator.style.opacity = indicator.style.opacity === '0.5' ? '1' : '0.5';
            });
        }, 1000);
        
        // Auto-refresh des alertes actives
        setInterval(() => {
            const activeAlerts = document.querySelectorAll('.pulse-animation');
            if (activeAlerts.length > 0) {
                // Recharger la page si des alertes actives sont présentes
                location.reload();
            }
        }, 30000); // Refresh toutes les 30 secondes
        
        // Filtrage des alertes
        const filterSelect = document.querySelector('select');
        if (filterSelect) {
            filterSelect.addEventListener('change', function() {
                const filter = this.value;
                // TODO: Implémenter le filtrage côté client ou recharger avec paramètres
                console.log('Filtre sélectionné:', filter);
            });
        }
        
        // Initialiser le graphique des alertes après un délai pour s'assurer que Chart.js est chargé
        setTimeout(() => {
            if (typeof Chart !== 'undefined') {
                console.log('Chart.js loaded, initializing interactive chart');
                initAlertsChart();
            } else {
                console.log('Chart.js not loaded, using SVG fallback');
                // Le graphique SVG est déjà affiché par défaut
                document.getElementById('chartFallback').style.display = 'block';
            }
        }, 1000);
    });
    
    function initAlertsChart() {
        const ctx = document.getElementById('alertsChart');
        if (!ctx) {
            console.log('Canvas alertsChart not found');
            return;
        }
        
        console.log('Initializing alerts chart...');
        
        // Données simulées pour le graphique (en attendant les vraies données)
        const alertsData = @json($alerts);
        console.log('Alerts data:', alertsData);
        
        // Générer des données pour les 30 derniers jours
        const labels = [];
        const emergencyData = [];
        const criticalData = [];
        const warningData = [];
        const infoData = [];
        
        // Générer des données de démonstration si pas d'alertes
        for (let i = 29; i >= 0; i--) {
            const date = new Date();
            date.setDate(date.getDate() - i);
            labels.push(date.toLocaleDateString('fr-FR', { month: 'short', day: 'numeric' }));
            
            // Si on a des vraies alertes, les utiliser
            if (alertsData && alertsData.length > 0) {
                const dayAlerts = alertsData.filter(alert => {
                    const alertDate = new Date(alert.triggered_at);
                    return alertDate.toDateString() === date.toDateString();
                });
                
                emergencyData.push(dayAlerts.filter(a => a.level === 'emergency').length);
                criticalData.push(dayAlerts.filter(a => a.level === 'critical').length);
                warningData.push(dayAlerts.filter(a => a.level === 'warning').length);
                infoData.push(dayAlerts.filter(a => a.level === 'info').length);
            } else {
                // Données de démonstration
                emergencyData.push(Math.floor(Math.random() * 3));
                criticalData.push(Math.floor(Math.random() * 5));
                warningData.push(Math.floor(Math.random() * 8));
                infoData.push(Math.floor(Math.random() * 10));
            }
        }
        
        try {
            // Masquer le fallback SVG et afficher le canvas Chart.js
            document.getElementById('chartFallback').style.display = 'none';
            ctx.style.display = 'block';
            
            alertsChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [
                        {
                            label: 'Urgence',
                            data: emergencyData,
                            borderColor: '#ef4444',
                            backgroundColor: 'rgba(239, 68, 68, 0.1)',
                            borderWidth: 3,
                            fill: true,
                            tension: 0.4,
                            pointBackgroundColor: '#ef4444',
                            pointBorderColor: '#ffffff',
                            pointBorderWidth: 2,
                            pointRadius: 6,
                            pointHoverRadius: 8
                        },
                        {
                            label: 'Critique',
                            data: criticalData,
                            borderColor: '#f97316',
                            backgroundColor: 'rgba(249, 115, 22, 0.1)',
                            borderWidth: 3,
                            fill: true,
                            tension: 0.4,
                            pointBackgroundColor: '#f97316',
                            pointBorderColor: '#ffffff',
                            pointBorderWidth: 2,
                            pointRadius: 6,
                            pointHoverRadius: 8
                        },
                        {
                            label: 'Avertissement',
                            data: warningData,
                            borderColor: '#eab308',
                            backgroundColor: 'rgba(234, 179, 8, 0.1)',
                            borderWidth: 3,
                            fill: true,
                            tension: 0.4,
                            pointBackgroundColor: '#eab308',
                            pointBorderColor: '#ffffff',
                            pointBorderWidth: 2,
                            pointRadius: 6,
                            pointHoverRadius: 8
                        },
                        {
                            label: 'Information',
                            data: infoData,
                            borderColor: '#3b82f6',
                            backgroundColor: 'rgba(59, 130, 246, 0.1)',
                            borderWidth: 3,
                            fill: true,
                            tension: 0.4,
                            pointBackgroundColor: '#3b82f6',
                            pointBorderColor: '#ffffff',
                            pointBorderWidth: 2,
                            pointRadius: 6,
                            pointHoverRadius: 8
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false // On utilise notre propre légende
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            titleColor: '#ffffff',
                            bodyColor: '#ffffff',
                            borderColor: '#e5e7eb',
                            borderWidth: 1,
                            cornerRadius: 8,
                            displayColors: true,
                            intersect: false,
                            mode: 'index'
                        }
                    },
                    scales: {
                        x: {
                            grid: {
                                color: 'rgba(229, 231, 235, 0.3)',
                                drawBorder: false
                            },
                            ticks: {
                                color: '#6b7280',
                                font: {
                                    family: 'Inter, sans-serif',
                                    size: 12
                                }
                            }
                        },
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(229, 231, 235, 0.3)',
                                drawBorder: false
                            },
                            ticks: {
                                color: '#6b7280',
                                font: {
                                    family: 'Inter, sans-serif',
                                    size: 12
                                },
                                stepSize: 1
                            }
                        }
                    },
                    interaction: {
                        intersect: false,
                        mode: 'index'
                    },
                    elements: {
                        point: {
                            hoverBackgroundColor: '#ffffff'
                        }
                    }
                }
            });
            console.log('Chart created successfully');
        } catch (error) {
            console.error('Error creating chart:', error);
            // Fallback: afficher un message d'erreur
            ctx.parentElement.innerHTML = `
                <div class="flex items-center justify-center h-full bg-gray-50 rounded-xl">
                    <div class="text-center">
                        <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p class="text-gray-500">Erreur lors du chargement du graphique</p>
                        <p class="text-sm text-gray-400 mt-2">Chart.js n'est pas disponible</p>
                    </div>
                </div>
            `;
        }
    }
    
    function updateChart(period) {
        // Mettre à jour les boutons
        document.querySelectorAll('[onclick^="updateChart"]').forEach(btn => {
            btn.className = 'px-3 py-1 bg-gray-50 text-gray-600 rounded-lg text-sm font-medium hover:bg-gray-100 transition-colors';
        });
        event.target.className = 'px-3 py-1 bg-blue-50 text-blue-600 rounded-lg text-sm font-medium hover:bg-blue-100 transition-colors';
        
        // Générer de nouvelles données selon la période
        let labels = [];
        let days = 0;
        
        switch(period) {
            case 'week':
                days = 7;
                break;
            case 'month':
                days = 30;
                break;
            case 'year':
                days = 365;
                break;
        }
        
        for (let i = days - 1; i >= 0; i--) {
            const date = new Date();
            date.setDate(date.getDate() - i);
            
            if (period === 'year') {
                labels.push(date.toLocaleDateString('fr-FR', { month: 'short' }));
            } else {
                labels.push(date.toLocaleDateString('fr-FR', { month: 'short', day: 'numeric' }));
            }
        }
        
        // Mettre à jour le graphique avec de nouvelles données
        if (alertsChart) {
            alertsChart.data.labels = labels;
            alertsChart.update('active');
        }
    }
</script>
@endsection