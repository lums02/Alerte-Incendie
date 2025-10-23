@extends('layouts.app')

@section('title', 'Configuration des Zones - FireGuard')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- En-tête -->
    <div class="mb-8">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Configuration des Zones</h1>
                <p class="text-gray-600 mt-2">Plan interactif de votre maison avec surveillance des zones</p>
            </div>
            <button class="bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white px-6 py-3 rounded-xl font-medium transition-all duration-200 shadow-lg hover:shadow-xl">
                <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Ajouter une zone
            </button>
        </div>
    </div>

    <!-- Vue 3D de la maison -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 mb-8 animate-on-scroll">
        <div class="p-6 border-b border-gray-100">
            <h3 class="text-lg font-semibold text-gray-900">Plan de la Maison</h3>
            <p class="text-sm text-gray-500 mt-1">Cliquez sur les zones pour les configurer et voir les détails</p>
        </div>
        <div class="p-6">
            <div id="house-plan" class="relative w-full h-96 bg-gradient-to-br from-gray-50 to-gray-100 rounded-xl border-2 border-dashed border-gray-300 overflow-hidden">
                <!-- Plan de maison moderne et interactif -->
                <svg class="w-full h-full" viewBox="0 0 900 500">
                    <!-- Définition des gradients -->
                    <defs>
                        <linearGradient id="houseGradient" x1="0%" y1="0%" x2="100%" y2="100%">
                            <stop offset="0%" style="stop-color:#f8fafc;stop-opacity:1" />
                            <stop offset="100%" style="stop-color:#e2e8f0;stop-opacity:1" />
                        </linearGradient>
                        <linearGradient id="zoneGradient" x1="0%" y1="0%" x2="100%" y2="100%">
                            <stop offset="0%" style="stop-color:#ffffff;stop-opacity:0.9" />
                            <stop offset="100%" style="stop-color:#f1f5f9;stop-opacity:0.9" />
                        </linearGradient>
                        <filter id="glow">
                            <feGaussianBlur stdDeviation="3" result="coloredBlur"/>
                            <feMerge> 
                                <feMergeNode in="coloredBlur"/>
                                <feMergeNode in="SourceGraphic"/>
                            </feMerge>
                        </filter>
                    </defs>
                    
                    <!-- Fond de la maison avec ombre -->
                    <rect x="60" y="60" width="780" height="380" fill="url(#houseGradient)" stroke="#cbd5e1" stroke-width="2" rx="15"/>
                    
                    <!-- Ombre portée -->
                    <rect x="65" y="65" width="780" height="380" fill="rgba(0,0,0,0.1)" rx="15"/>
                    <rect x="60" y="60" width="780" height="380" fill="url(#houseGradient)" stroke="#cbd5e1" stroke-width="2" rx="15"/>
                    
                    <!-- Murs intérieurs avec style moderne -->
                    <line x1="450" y1="60" x2="450" y2="440" stroke="#94a3b8" stroke-width="4" stroke-linecap="round"/>
                    <line x1="60" y1="250" x2="450" y2="250" stroke="#94a3b8" stroke-width="4" stroke-linecap="round"/>
                    <line x1="450" y1="250" x2="840" y2="250" stroke="#94a3b8" stroke-width="4" stroke-linecap="round"/>
                    
                    <!-- Portes -->
                    <rect x="440" y="200" width="20" height="50" fill="#8b5cf6" rx="3"/>
                    <rect x="440" y="300" width="20" height="50" fill="#8b5cf6" rx="3"/>
                    
                    <!-- Zones avec données dynamiques -->
                    @foreach($zones as $index => $zone)
                        @php
                            $zonePositions = [
                                ['x' => 80, 'y' => 80, 'width' => 360, 'height' => 160, 'name' => 'Salon', 'color' => '#dbeafe', 'icon' => '🛋️'],
                                ['x' => 80, 'y' => 260, 'width' => 360, 'height' => 160, 'name' => 'Cuisine', 'color' => '#fef3c7', 'icon' => '🍳'],
                                ['x' => 460, 'y' => 80, 'width' => 360, 'height' => 160, 'name' => 'Chambre', 'color' => '#f3e8ff', 'icon' => '🛏️'],
                                ['x' => 460, 'y' => 260, 'width' => 360, 'height' => 160, 'name' => 'Garage', 'color' => '#fce7f3', 'icon' => '🚗']
                            ];
                            $position = $zonePositions[$index % count($zonePositions)] ?? $zonePositions[0];
                            $sensorCount = $zone->sensors->count();
                            $activeSensors = $zone->sensors->where('status', 'active')->count();
                            $hasAlerts = $zone->sensors->some(fn($s) => $s->alerts->where('status', 'active')->count() > 0);
                        @endphp
                        
                        <!-- Zone cliquable avec effet moderne -->
                        <g class="zone-group cursor-pointer animate-scale-in" data-zone-id="{{ $zone->id }}" style="animation-delay: {{ $index * 0.2 }}s;">
                            <!-- Fond de la zone avec gradient -->
                            <rect x="{{ $position['x'] }}" y="{{ $position['y'] }}" 
                                  width="{{ $position['width'] }}" height="{{ $position['height'] }}" 
                                  fill="{{ $hasAlerts ? '#fef2f2' : ($activeSensors > 0 ? '#f0fdf4' : 'url(#zoneGradient)') }}" 
                                  stroke="{{ $hasAlerts ? '#fca5a5' : ($activeSensors > 0 ? '#86efac' : '#e2e8f0') }}" 
                                  stroke-width="3" rx="12" 
                                  class="zone-rect transition-all duration-300 hover:stroke-4"
                                  filter="{{ $hasAlerts ? 'url(#glow)' : 'none' }}"/>
                            
                            <!-- Icône de la zone -->
                            <text x="{{ $position['x'] + 20 }}" y="{{ $position['y'] + 40 }}" 
                                  font-size="24" class="zone-icon">{{ $position['icon'] }}</text>
                            
                            <!-- Nom de la zone -->
                            <text x="{{ $position['x'] + 60 }}" y="{{ $position['y'] + 40 }}" 
                                  class="text-xl font-bold fill-gray-800" 
                                  font-family="Inter, sans-serif">{{ $zone->name }}</text>
                            
                            <!-- Statistiques de la zone -->
                            <text x="{{ $position['x'] + 20 }}" y="{{ $position['y'] + 65 }}" 
                                  class="text-sm fill-gray-600" 
                                  font-family="Inter, sans-serif">{{ $sensorCount }} capteurs • {{ $activeSensors }} actifs</text>
                            
                            <!-- Indicateur de statut avec animation -->
                            <circle cx="{{ $position['x'] + $position['width'] - 40 }}" cy="{{ $position['y'] + 40 }}" 
                                    r="12" fill="{{ $hasAlerts ? '#ef4444' : ($activeSensors > 0 ? '#10b981' : '#6b7280') }}" 
                                    class="status-indicator {{ $hasAlerts ? 'pulse-animation' : '' }}"
                                    filter="{{ $hasAlerts ? 'url(#glow)' : 'none' }}"/>
                            
                            <!-- Icône de statut -->
                            <text x="{{ $position['x'] + $position['width'] - 45 }}" y="{{ $position['y'] + 45 }}" 
                                  font-size="12" fill="white" text-anchor="middle" dominant-baseline="middle">
                                {{ $hasAlerts ? '🚨' : ($activeSensors > 0 ? '✅' : '⏸️') }}
                            </text>
                            
                            <!-- Capteurs avec design amélioré -->
                            @if($sensorCount > 0)
                                <g transform="translate({{ $position['x'] + 20 }}, {{ $position['y'] + 90 }})">
                                    @foreach($zone->sensors->take(6) as $sensorIndex => $sensor)
                                        @php
                                            $sensorX = ($sensorIndex % 3) * 35;
                                            $sensorY = floor($sensorIndex / 3) * 35;
                                        @endphp
                                        <g transform="translate({{ $sensorX }}, {{ $sensorY }})" class="sensor-animation" style="animation-delay: {{ $sensorIndex * 0.1 }}s;">
                                            <!-- Fond du capteur -->
                                            <circle cx="12" cy="12" r="10" fill="{{ $sensor->status === 'active' ? '#10b981' : '#6b7280' }}" opacity="0.9" class="{{ $sensor->status === 'active' ? 'pulse-animation' : '' }}"/>
                                            <circle cx="12" cy="12" r="8" fill="white" opacity="0.3"/>
                                            
                                            <!-- Icône du capteur -->
                                            @if($sensor->type === 'smoke')
                                                <text x="12" y="16" font-size="10" fill="white" text-anchor="middle">💨</text>
                                            @elseif($sensor->type === 'temperature')
                                                <text x="12" y="16" font-size="10" fill="white" text-anchor="middle">🌡️</text>
                                            @elseif($sensor->type === 'humidity')
                                                <text x="12" y="16" font-size="10" fill="white" text-anchor="middle">💧</text>
                                            @elseif($sensor->type === 'flame')
                                                <text x="12" y="16" font-size="10" fill="white" text-anchor="middle">🔥</text>
                                            @else
                                                <text x="12" y="16" font-size="10" fill="white" text-anchor="middle">📡</text>
                                            @endif
                                        </g>
                                    @endforeach
                                </g>
                            @endif
                            
                            <!-- Effet hover invisible -->
                            <rect x="{{ $position['x'] }}" y="{{ $position['y'] }}" 
                                  width="{{ $position['width'] }}" height="{{ $position['height'] }}" 
                                  fill="transparent" 
                                  class="zone-hover"/>
                        </g>
                    @endforeach
                    
                    <!-- Légende moderne -->
                    <g transform="translate(60, 460)">
                        <rect x="0" y="-10" width="780" height="30" fill="rgba(255,255,255,0.9)" rx="15" stroke="#e2e8f0"/>
                        <text x="20" y="5" class="text-sm font-bold fill-gray-700" font-family="Inter, sans-serif">Légende:</text>
                        
                        <circle cx="120" cy="0" r="6" fill="#10b981"/>
                        <text x="135" y="0" class="text-xs fill-gray-600" font-family="Inter, sans-serif">Actif</text>
                        
                        <circle cx="200" cy="0" r="6" fill="#ef4444"/>
                        <text x="215" y="0" class="text-xs fill-gray-600" font-family="Inter, sans-serif">Alerte</text>
                        
                        <circle cx="280" cy="0" r="6" fill="#6b7280"/>
                        <text x="295" y="0" class="text-xs fill-gray-600" font-family="Inter, sans-serif">Inactif</text>
                        
                        <text x="400" y="0" class="text-xs fill-gray-500" font-family="Inter, sans-serif">Cliquez sur une zone pour plus de détails</text>
                    </g>
                </svg>
                
                <!-- Overlay d'informations amélioré -->
                <div id="zone-info" class="absolute top-6 right-6 bg-white/95 backdrop-blur-md rounded-2xl p-6 shadow-2xl border border-gray-200 hidden max-w-sm">
                    <div class="flex items-center mb-4">
                        <div id="zone-icon" class="text-2xl mr-3"></div>
                        <div>
                            <h4 id="zone-name" class="text-lg font-bold text-gray-900"></h4>
                            <p id="zone-details" class="text-sm text-gray-600"></p>
                        </div>
                    </div>
                    <div id="zone-sensors" class="space-y-2"></div>
                    <div class="mt-4 pt-4 border-t border-gray-200">
                        <button class="w-full bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white px-4 py-2 rounded-xl font-medium transition-all duration-200">
                            Configurer la zone
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Liste des zones -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        @forelse($zones as $zone)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 card-hover animate-on-scroll">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center">
                            <div class="w-12 h-12 rounded-xl flex items-center justify-center mr-4 {{ $zone->sensors->where('status', 'active')->count() > 0 ? 'bg-green-50' : 'bg-gray-50' }}">
                                <svg class="w-6 h-6 {{ $zone->sensors->where('status', 'active')->count() > 0 ? 'text-green-600' : 'text-gray-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">{{ $zone->name }}</h3>
                                <p class="text-sm text-gray-500">{{ $zone->sensors->count() }} capteurs • {{ $zone->sensors->where('status', 'active')->count() }} actifs</p>
                            </div>
                        </div>
                        <div class="flex space-x-2">
                            <button class="px-3 py-2 bg-blue-50 text-blue-600 rounded-lg text-sm font-medium hover:bg-blue-100 transition-colors">
                                Modifier
                            </button>
                            <button class="px-3 py-2 bg-red-50 text-red-600 rounded-lg text-sm font-medium hover:bg-red-100 transition-colors">
                                Supprimer
                            </button>
                        </div>
                    </div>
                    
                    @if($zone->description)
                        <p class="text-sm text-gray-600 mb-4">{{ $zone->description }}</p>
                    @endif
                    
                    <!-- Position 3D -->
                    <div class="mb-4">
                        <h4 class="text-sm font-medium text-gray-700 mb-2">Position 3D</h4>
                        <div class="grid grid-cols-3 gap-2 text-sm">
                            <div>
                                <span class="text-gray-500">X:</span>
                                <span class="font-medium text-gray-900">{{ $zone->pos_x ?? '--' }}</span>
                            </div>
                            <div>
                                <span class="text-gray-500">Y:</span>
                                <span class="font-medium text-gray-900">{{ $zone->pos_y ?? '--' }}</span>
                            </div>
                            <div>
                                <span class="text-gray-500">Z:</span>
                                <span class="font-medium text-gray-900">{{ $zone->pos_z ?? '--' }}</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Dimensions -->
                    @if($zone->dimensions)
                        <div class="mb-4">
                            <h4 class="text-sm font-medium text-gray-700 mb-2">Dimensions</h4>
                            <div class="grid grid-cols-3 gap-2 text-sm">
                                <div>
                                    <span class="text-gray-500">Longueur:</span>
                                    <span class="font-medium text-gray-900">{{ $zone->dimensions['length'] ?? '--' }}m</span>
                                </div>
                                <div>
                                    <span class="text-gray-500">Largeur:</span>
                                    <span class="font-medium text-gray-900">{{ $zone->dimensions['width'] ?? '--' }}m</span>
                                </div>
                                <div>
                                    <span class="text-gray-500">Hauteur:</span>
                                    <span class="font-medium text-gray-900">{{ $zone->dimensions['height'] ?? '--' }}m</span>
                                </div>
                            </div>
                        </div>
                    @endif
                    
                    <!-- Capteurs dans cette zone -->
                    <div>
                        <h4 class="text-sm font-medium text-gray-700 mb-2">Capteurs ({{ $zone->sensors->count() }})</h4>
                        @if($zone->sensors->count() > 0)
                            <div class="space-y-2">
                                @foreach($zone->sensors as $sensor)
                                    <div class="flex items-center justify-between p-2 bg-gray-50 rounded-lg">
                                        <div class="flex items-center">
                                            <div class="status-indicator {{ $sensor->status_color === 'green' ? 'status-online' : ($sensor->status_color === 'yellow' ? 'status-warning' : 'status-offline') }} mr-2"></div>
                                            <span class="text-sm text-gray-700">{{ $sensor->name }}</span>
                                            <span class="ml-2 text-xs text-gray-500">({{ ucfirst($sensor->type) }})</span>
                                        </div>
                                        @if($sensor->latestReading)
                                            <span class="text-sm font-medium {{ $sensor->latestReading->quality === 'good' ? 'text-green-600' : ($sensor->latestReading->quality === 'warning' ? 'text-yellow-600' : 'text-red-600') }}">
                                                {{ $sensor->latestReading->formatted_value }}
                                            </span>
                                        @else
                                            <span class="text-sm text-gray-400">--</span>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-sm text-gray-500">Aucun capteur dans cette zone</p>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full bg-white rounded-2xl shadow-sm border border-gray-100 p-12 text-center animate-on-scroll">
                <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Aucune zone configurée</h3>
                <p class="text-gray-500 mb-6">Commencez par créer les zones de votre maison pour organiser vos capteurs.</p>
                <button class="bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white px-6 py-3 rounded-xl font-medium transition-all duration-200 shadow-lg hover:shadow-xl">
                    Créer la première zone
                </button>
            </div>
        @endforelse
    </div>

    <!-- Statistiques des zones -->
    @if($zones->count() > 0)
        <div class="mt-8 bg-white rounded-2xl shadow-sm border border-gray-100 animate-on-scroll">
            <div class="p-6 border-b border-gray-100">
                <h3 class="text-lg font-semibold text-gray-900">Statistiques des Zones</h3>
                <p class="text-sm text-gray-500 mt-1">Vue d'ensemble de la surveillance de votre maison</p>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="text-center">
                        <div class="text-3xl font-bold text-gray-900 mb-2">{{ $zones->count() }}</div>
                        <div class="text-sm text-gray-500">Zones totales</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-gray-900 mb-2">{{ $zones->sum(fn($z) => $z->sensors->count()) }}</div>
                        <div class="text-sm text-gray-500">Capteurs total</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-gray-900 mb-2">{{ $zones->sum(fn($z) => $z->sensors->where('status', 'active')->count()) }}</div>
                        <div class="text-sm text-gray-500">Capteurs actifs</div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection

@section('scripts')
<style>
    /* Animations spéciales pour les capteurs */
    .sensor-animation {
        animation: sensorAppear 0.6s ease-out forwards;
        opacity: 0;
        transform: scale(0);
    }
    
    @keyframes sensorAppear {
        0% {
            opacity: 0;
            transform: scale(0) rotate(180deg);
        }
        50% {
            opacity: 0.8;
            transform: scale(1.2) rotate(90deg);
        }
        100% {
            opacity: 1;
            transform: scale(1) rotate(0deg);
        }
    }
    
    /* Animation des zones */
    .zone-group {
        transition: all 0.3s ease;
    }
    
    .zone-group:hover {
        transform: scale(1.05);
    }
    
    /* Effet de glow pour les alertes */
    .alert-glow {
        filter: drop-shadow(0 0 10px rgba(239, 68, 68, 0.5));
        animation: alertPulse 1s ease-in-out infinite alternate;
    }
    
    @keyframes alertPulse {
        0% { filter: drop-shadow(0 0 5px rgba(239, 68, 68, 0.3)); }
        100% { filter: drop-shadow(0 0 15px rgba(239, 68, 68, 0.8)); }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const zones = @json($zones);
        const zoneInfo = document.getElementById('zone-info');
        const zoneName = document.getElementById('zone-name');
        const zoneDetails = document.getElementById('zone-details');
        const zoneSensors = document.getElementById('zone-sensors');
        
        // Gestion des clics sur les zones
        document.querySelectorAll('.zone-group').forEach(group => {
            const zoneId = parseInt(group.dataset.zoneId);
            const zone = zones.find(z => z.id === zoneId);
            
            group.addEventListener('click', function(e) {
                e.preventDefault();
                
                // Obtenir l'icône de la zone
                const zoneIcon = group.querySelector('.zone-icon');
                const iconText = zoneIcon ? zoneIcon.textContent : '🏠';
                
                // Afficher les informations de la zone
                zoneName.textContent = zone.name;
                zoneDetails.textContent = zone.description || 'Zone de surveillance';
                document.getElementById('zone-icon').textContent = iconText;
                
                // Afficher les capteurs avec design amélioré
                zoneSensors.innerHTML = '';
                if (zone.sensors && zone.sensors.length > 0) {
                    zone.sensors.forEach(sensor => {
                        const sensorDiv = document.createElement('div');
                        sensorDiv.className = 'flex items-center justify-between p-2 bg-gray-50 rounded-lg';
                        
                        // Icône du capteur
                        const sensorIcon = getSensorIcon(sensor.type);
                        
                        sensorDiv.innerHTML = `
                            <div class="flex items-center">
                                <span class="text-sm mr-2">${sensorIcon}</span>
                                <span class="text-sm text-gray-700 font-medium">${sensor.name}</span>
                                <span class="ml-2 text-xs text-gray-500">(${sensor.type})</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <span class="px-2 py-1 rounded-full text-xs font-medium ${
                                    sensor.status === 'active' ? 'bg-green-100 text-green-800' : 
                                    sensor.status === 'error' ? 'bg-red-100 text-red-800' : 
                                    'bg-gray-100 text-gray-800'
                                }">${sensor.status}</span>
                                ${sensor.latestReading ? `<span class="text-xs text-gray-600">${sensor.latestReading.formatted_value}</span>` : ''}
                            </div>
                        `;
                        zoneSensors.appendChild(sensorDiv);
                    });
                } else {
                    zoneSensors.innerHTML = '<div class="text-center py-4"><p class="text-sm text-gray-500">Aucun capteur configuré</p><p class="text-xs text-gray-400 mt-1">Cliquez sur "Configurer la zone" pour en ajouter</p></div>';
                }
                
                // Afficher l'overlay avec animation améliorée
                zoneInfo.classList.remove('hidden');
                
                // Animation d'apparition plus fluide
                zoneInfo.style.opacity = '0';
                zoneInfo.style.transform = 'translateY(-20px) scale(0.95)';
                zoneInfo.style.transition = 'all 0.4s cubic-bezier(0.4, 0, 0.2, 1)';
                
                setTimeout(() => {
                    zoneInfo.style.opacity = '1';
                    zoneInfo.style.transform = 'translateY(0) scale(1)';
                }, 50);
            });
            
            // Effet hover
            group.addEventListener('mouseenter', function() {
                const rect = group.querySelector('.zone-rect');
                rect.style.strokeWidth = '4';
                rect.style.stroke = '#3b82f6';
            });
            
            group.addEventListener('mouseleave', function() {
                const rect = group.querySelector('.zone-rect');
                rect.style.strokeWidth = '2';
                rect.style.stroke = rect.style.stroke.includes('#fca5a5') ? '#fca5a5' : 
                                   rect.style.stroke.includes('#86efac') ? '#86efac' : '#e2e8f0';
            });
        });
        
        // Masquer l'overlay en cliquant ailleurs
        document.addEventListener('click', function(e) {
            if (!e.target.closest('.zone-group') && !e.target.closest('#zone-info')) {
                zoneInfo.classList.add('hidden');
            }
        });
        
        // Fonction pour obtenir l'icône du capteur
        function getSensorIcon(type) {
            const icons = {
                'smoke': '💨',
                'temperature': '🌡️',
                'humidity': '💧',
                'flame': '🔥',
                'gas': '⛽'
            };
            return icons[type] || '📡';
        }
        
        // Animation des indicateurs de statut
        setInterval(() => {
            document.querySelectorAll('.pulse-animation').forEach(indicator => {
                indicator.style.opacity = indicator.style.opacity === '0.5' ? '1' : '0.5';
            });
        }, 1000);
    });
</script>
@endsection
