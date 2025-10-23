@extends('layouts.app')

@section('title', 'Dashboard - FireGuard')

@section('content')
<!-- Hero Section -->
<div class="gradient-bg">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="text-center">
            <h1 class="text-4xl font-bold text-white mb-4">Surveillance en Temps Réel</h1>
            <p class="text-xl text-white/90 mb-8">Système d'alerte incendie intelligent et moderne</p>
            <div class="flex justify-center space-x-4">
                <div class="bg-white/20 backdrop-blur-sm rounded-lg px-4 py-2">
                    <span class="text-white font-medium">{{ $devices->where('status', 'online')->count() }} Devices Actifs</span>
                </div>
                <div class="bg-white/20 backdrop-blur-sm rounded-lg px-4 py-2">
                    <span class="text-white font-medium">{{ $recentAlerts->where('status', 'active')->count() }} Alertes Actives</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Contenu principal -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-8">
    <!-- Cartes de statistiques -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
        <!-- Device Status -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 card-hover animate-on-scroll ">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Devices</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $devices->where('status', 'online')->count() }}/{{ $devices->count() }}</p>
                    <p class="text-sm text-gray-500 mt-1">En ligne</p>
                </div>
                <div class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Capteurs Actifs -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 card-hover animate-on-scroll ">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Capteurs</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $devices->sum(fn($d) => $d->sensors->where('status', 'active')->count()) }}</p>
                    <p class="text-sm text-gray-500 mt-1">Actifs</p>
                </div>
                <div class="w-12 h-12 bg-green-50 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Alertes Actives -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 card-hover animate-on-scroll">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Alertes</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $recentAlerts->where('status', 'active')->count() }}</p>
                    <p class="text-sm text-gray-500 mt-1">Actives</p>
                </div>
                <div class="w-12 h-12 bg-red-50 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Zones Surveillées -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 card-hover animate-on-scroll">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Zones</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $zones->count() }}</p>
                    <p class="text-sm text-gray-500 mt-1">Surveillées</p>
                </div>
                <div class="w-12 h-12 bg-purple-50 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-12">
        <!-- État des Devices -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 animate-on-scroll">
            <div class="p-6 border-b border-gray-100">
                <h3 class="text-lg font-semibold text-gray-900">État des Devices</h3>
                <p class="text-sm text-gray-500 mt-1">Statut en temps réel de tous les devices</p>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    @forelse($devices as $device)
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl ">
                            <div class="flex items-center">
                                <div class="status-indicator {{ $device->isOnline() ? 'status-online' : 'status-offline' }} {{ $device->isOnline() ? 'pulse-animation' : '' }} mr-3"></div>
                                <div>
                                    <h4 class="font-medium text-gray-900">{{ $device->name }}</h4>
                                    <p class="text-sm text-gray-500">{{ $device->location ?? 'Localisation non définie' }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-medium {{ $device->isOnline() ? 'text-green-600' : 'text-gray-500' }}">
                                    {{ $device->isOnline() ? 'En ligne' : 'Hors ligne' }}
                                </p>
                                @if($device->last_seen_at)
                                    <p class="text-xs text-gray-400">
                                        {{ $device->last_seen_at->diffForHumans() }}
                                    </p>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8">
                            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <p class="text-gray-500">Aucun device configuré</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Alertes Récentes -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 animate-on-scroll">
            <div class="p-6 border-b border-gray-100">
                <h3 class="text-lg font-semibold text-gray-900">Alertes Récentes</h3>
                <p class="text-sm text-gray-500 mt-1">Dernières alertes déclenchées</p>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    @forelse($recentAlerts->take(5) as $alert)
                        <div class="flex items-start p-4 rounded-xl {{ $alert->level === 'emergency' ? 'bg-red-50 border border-red-100' : ($alert->level === 'critical' ? 'bg-orange-50 border border-orange-100' : ($alert->level === 'warning' ? 'bg-yellow-50 border border-yellow-100' : 'bg-blue-50 border border-blue-100')) }}">
                            <div class="flex-shrink-0 mr-3">
                                <div class="w-8 h-8 rounded-lg flex items-center justify-center {{ $alert->level === 'emergency' ? 'bg-red-100' : ($alert->level === 'critical' ? 'bg-orange-100' : ($alert->level === 'warning' ? 'bg-yellow-100' : 'bg-blue-100')) }}">
                                    @if($alert->level === 'emergency')
                                        <svg class="w-4 h-4 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M12.395 2.553a1 1 0 00-1.45-.385c-.345.23-.614.558-.822.88-.214.33-.403.713-.57 1.116-.334.804-.614 1.768-.84 2.734a31.365 31.365 0 00-.613 3.58 2.64 2.64 0 01-.945-1.067c-.328-.68-.398-1.534-.398-2.654A1 1 0 005.05 6.05 6.981 6.981 0 003 11a7 7 0 1011.95-4.95c-.001-1.167-.045-2.053-.1-2.507zM10.5 3c-.28 0-.5.22-.5.5v.5h.5c.28 0 .5-.22.5-.5V3z" clip-rule="evenodd"></path>
                                        </svg>
                                    @elseif($alert->level === 'critical')
                                        <svg class="w-4 h-4 text-orange-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                        </svg>
                                    @elseif($alert->level === 'warning')
                                        <svg class="w-4 h-4 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                        </svg>
                                    @else
                                        <svg class="w-4 h-4 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                        </svg>
                                    @endif
                                </div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <h4 class="font-medium text-gray-900">{{ $alert->title }}</h4>
                                <p class="text-sm text-gray-600 mt-1">{{ $alert->message }}</p>
                                <div class="flex items-center mt-2 space-x-4 text-xs text-gray-500">
                                    <span>{{ $alert->zone?->name ?? 'Zone inconnue' }}</span>
                                    <span>•</span>
                                    <span>{{ $alert->triggered_at->diffForHumans() }}</span>
                                </div>
                            </div>
                            <div class="flex-shrink-0">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    {{ $alert->level === 'emergency' ? 'bg-red-100 text-red-800' : 
                                       ($alert->level === 'critical' ? 'bg-orange-100 text-orange-800' : 
                                        ($alert->level === 'warning' ? 'bg-yellow-100 text-yellow-800' : 'bg-blue-100 text-blue-800')) }}">
                                    {{ ucfirst($alert->level) }}
                                </span>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8">
                            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <p class="text-gray-500">Aucune alerte récente</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Vue des capteurs par zone -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 animate-on-scroll">
        <div class="p-6 border-b border-gray-100">
            <h3 class="text-lg font-semibold text-gray-900">Capteurs par Zone</h3>
            <p class="text-sm text-gray-500 mt-1">État des capteurs dans chaque zone surveillée</p>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($zones as $zone)
                    <div class="border border-gray-200 rounded-xl p-4 hover:border-gray-300 transition-colors">
                        <div class="flex items-center justify-between mb-3">
                            <h4 class="font-medium text-gray-900">{{ $zone->name }}</h4>
                            <span class="text-xs text-gray-500">{{ $zone->sensors->count() }} capteurs</span>
                        </div>
                        <div class="space-y-2">
                            @forelse($zone->sensors as $sensor)
                                <div class="flex items-center justify-between text-sm">
                                    <div class="flex items-center">
                                        <div class="status-indicator {{ $sensor->status_color === 'green' ? 'status-online' : ($sensor->status_color === 'yellow' ? 'status-warning' : 'status-offline') }} mr-2"></div>
                                        <span class="text-gray-700">{{ ucfirst($sensor->type) }}</span>
                                    </div>
                                    @if($sensor->latestReading)
                                        <span class="font-medium {{ $sensor->latestReading->quality === 'good' ? 'text-green-600' : ($sensor->latestReading->quality === 'warning' ? 'text-yellow-600' : 'text-red-600') }}">
                                            {{ $sensor->latestReading->formatted_value }}
                                        </span>
                                    @else
                                        <span class="text-gray-400">--</span>
                                    @endif
                                </div>
                            @empty
                                <p class="text-sm text-gray-500">Aucun capteur dans cette zone</p>
                            @endforelse
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-8">
                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                        </div>
                        <p class="text-gray-500">Aucune zone configurée</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
