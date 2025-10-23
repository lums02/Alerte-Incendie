@extends('layouts.app')

@section('title', 'Gestion des Capteurs - FireGuard')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- En-tête -->
    <div class="mb-8">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Gestion des Capteurs</h1>
                <p class="text-gray-600 mt-2">Surveillez et configurez tous vos capteurs de sécurité</p>
            </div>
            <button class="bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white px-6 py-3 rounded-xl font-medium transition-all duration-200 shadow-lg hover:shadow-xl">
                <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Ajouter un capteur
            </button>
        </div>
    </div>

    <!-- Filtres -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-8 animate-on-scroll">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Device</label>
                <select class="w-full border-gray-300 rounded-xl shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                    <option value="">Tous les devices</option>
                    @foreach($devices as $device)
                        <option value="{{ $device->id }}">{{ $device->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Zone</label>
                <select class="w-full border-gray-300 rounded-xl shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                    <option value="">Toutes les zones</option>
                    @foreach($zones as $zone)
                        <option value="{{ $zone->id }}">{{ $zone->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Type</label>
                <select class="w-full border-gray-300 rounded-xl shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                    <option value="">Tous les types</option>
                    <option value="smoke">Fumée</option>
                    <option value="temperature">Température</option>
                    <option value="humidity">Humidité</option>
                    <option value="flame">Flamme</option>
                    <option value="gas">Gaz</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Statut</label>
                <select class="w-full border-gray-300 rounded-xl shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                    <option value="">Tous les statuts</option>
                    <option value="active">Actif</option>
                    <option value="inactive">Inactif</option>
                    <option value="calibrating">Calibration</option>
                    <option value="error">Erreur</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Liste des capteurs -->
    <div class="space-y-4">
        @forelse($sensors as $sensor)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 card-hover animate-on-scroll">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="w-12 h-12 rounded-xl flex items-center justify-center mr-4 {{ $sensor->status === 'active' ? 'bg-green-50' : ($sensor->status === 'error' ? 'bg-red-50' : 'bg-gray-50') }}">
                            @if($sensor->type === 'smoke')
                                <svg class="w-6 h-6 {{ $sensor->status === 'active' ? 'text-green-600' : ($sensor->status === 'error' ? 'text-red-600' : 'text-gray-600') }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12.395 2.553a1 1 0 00-1.45-.385c-.345.23-.614.558-.822.88-.214.33-.403.713-.57 1.116-.334.804-.614 1.768-.84 2.734a31.365 31.365 0 00-.613 3.58 2.64 2.64 0 01-.945-1.067c-.328-.68-.398-1.534-.398-2.654A1 1 0 005.05 6.05 6.981 6.981 0 003 11a7 7 0 1011.95-4.95c-.001-1.167-.045-2.053-.1-2.507zM10.5 3c-.28 0-.5.22-.5.5v.5h.5c.28 0 .5-.22.5-.5V3z"></path>
                                </svg>
                            @elseif($sensor->type === 'temperature')
                                <svg class="w-6 h-6 {{ $sensor->status === 'active' ? 'text-green-600' : ($sensor->status === 'error' ? 'text-red-600' : 'text-gray-600') }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                            @elseif($sensor->type === 'humidity')
                                <svg class="w-6 h-6 {{ $sensor->status === 'active' ? 'text-green-600' : ($sensor->status === 'error' ? 'text-red-600' : 'text-gray-600') }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z"></path>
                                </svg>
                            @elseif($sensor->type === 'flame')
                                <svg class="w-6 h-6 {{ $sensor->status === 'active' ? 'text-green-600' : ($sensor->status === 'error' ? 'text-red-600' : 'text-gray-600') }}" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M12.395 2.553a1 1 0 00-1.45-.385c-.345.23-.614.558-.822.88-.214.33-.403.713-.57 1.116-.334.804-.614 1.768-.84 2.734a31.365 31.365 0 00-.613 3.58 2.64 2.64 0 01-.945-1.067c-.328-.68-.398-1.534-.398-2.654A1 1 0 005.05 6.05 6.981 6.981 0 003 11a7 7 0 1011.95-4.95c-.001-1.167-.045-2.053-.1-2.507zM10.5 3c-.28 0-.5.22-.5.5v.5h.5c.28 0 .5-.22.5-.5V3z" clip-rule="evenodd"></path>
                                </svg>
                            @else
                                <svg class="w-6 h-6 {{ $sensor->status === 'active' ? 'text-green-600' : ($sensor->status === 'error' ? 'text-red-600' : 'text-gray-600') }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                            @endif
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">{{ $sensor->name }}</h3>
                            <p class="text-sm text-gray-500">{{ $sensor->device->name }} • {{ $sensor->zone?->name ?? 'Zone non définie' }}</p>
                        </div>
                    </div>
                    
                    <div class="flex items-center space-x-6">
                        <!-- Dernière valeur -->
                        <div class="text-right">
                            @if($sensor->latestReading)
                                <p class="text-lg font-bold {{ $sensor->latestReading->quality === 'good' ? 'text-green-600' : ($sensor->latestReading->quality === 'warning' ? 'text-yellow-600' : 'text-red-600') }}">
                                    {{ $sensor->latestReading->formatted_value }}
                                </p>
                                <p class="text-xs text-gray-500">
                                    {{ $sensor->latestReading->measured_at->diffForHumans() }}
                                </p>
                            @else
                                <p class="text-lg font-bold text-gray-400">--</p>
                                <p class="text-xs text-gray-400">Aucune donnée</p>
                            @endif
                        </div>
                        
                        <!-- Seuils -->
                        <div class="text-right">
                            <p class="text-xs text-gray-500 mb-1">Seuils</p>
                            <div class="flex items-center space-x-2">
                                @if($sensor->threshold_warn)
                                    <span class="px-2 py-1 bg-yellow-100 text-yellow-800 text-xs rounded-lg">{{ $sensor->threshold_warn }}</span>
                                @endif
                                @if($sensor->threshold_alarm)
                                    <span class="px-2 py-1 bg-red-100 text-red-800 text-xs rounded-lg">{{ $sensor->threshold_alarm }}</span>
                                @endif
                                <span class="text-xs text-gray-500">{{ $sensor->unit }}</span>
                            </div>
                        </div>
                        
                        <!-- Actions -->
                        <div class="flex space-x-2">
                            <button class="px-3 py-2 bg-blue-50 text-blue-600 rounded-lg text-sm font-medium hover:bg-blue-100 transition-colors">
                                Configurer
                            </button>
                            <button class="px-3 py-2 bg-gray-50 text-gray-600 rounded-lg text-sm font-medium hover:bg-gray-100 transition-colors">
                                Historique
                            </button>
                            <button class="px-3 py-2 bg-red-50 text-red-600 rounded-lg text-sm font-medium hover:bg-red-100 transition-colors">
                                Supprimer
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- Informations détaillées -->
                <div class="mt-4 pt-4 border-t border-gray-100">
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                        <div>
                            <span class="text-gray-500">Type:</span>
                            <span class="font-medium text-gray-900 ml-1">{{ ucfirst($sensor->type) }}</span>
                        </div>
                        <div>
                            <span class="text-gray-500">Statut:</span>
                            <span class="font-medium {{ $sensor->status === 'active' ? 'text-green-600' : ($sensor->status === 'error' ? 'text-red-600' : 'text-gray-600') }} ml-1">
                                {{ ucfirst($sensor->status) }}
                            </span>
                        </div>
                        <div>
                            <span class="text-gray-500">Position:</span>
                            <span class="font-medium text-gray-900 ml-1">
                                @if($sensor->pos_x && $sensor->pos_y && $sensor->pos_z)
                                    ({{ $sensor->pos_x }}, {{ $sensor->pos_y }}, {{ $sensor->pos_z }})
                                @else
                                    Non définie
                                @endif
                            </span>
                        </div>
                        <div>
                            <span class="text-gray-500">Créé:</span>
                            <span class="font-medium text-gray-900 ml-1">{{ $sensor->created_at->format('d/m/Y') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-12 text-center animate-on-scroll">
                <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Aucun capteur configuré</h3>
                <p class="text-gray-500 mb-6">Commencez par ajouter votre premier capteur pour surveiller votre maison.</p>
                <button class="bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white px-6 py-3 rounded-xl font-medium transition-all duration-200 shadow-lg hover:shadow-xl">
                    Ajouter le premier capteur
                </button>
            </div>
        @endforelse
    </div>
</div>
@endsection
