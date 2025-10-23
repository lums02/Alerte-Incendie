<!DOCTYPE html>
<html lang="fr" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'FireGuard - Système d\'Alerte Incendie')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
            <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/three@0.158.0/build/three.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body{
            font-family: 'Inter', sans-serif;
        }
        .gradient-bg{ background: linear-gradient(135deg, #667eea 0%, #764ba2 100%  ); }
        .glass-effect{ background: rgba(255, 255, 255, 0.1); backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.2); }
        .card-hover{ transition: all 0.3s ease; }
        .card-hover:hover{ transform: translateY(-2px) ; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04); }
        .status-online{ background-color: #10b981; }
        .status-indicator{ width: 8px; height: 8px; border-radius: 50%; display: inline-block; margin-right: 4px; }
        .status-offline{ background-color: #ef4444; }
        .status-warning{ background-color: #f59e0b; }
        .pulse-animation{ animation: pulse 2s infinite; }
        @keyframes pulse{
            0%{ transform: scale(1); }
            50%{ transform: scale(1.1); }
            100%{ transform: scale(1); }
        }
    </style>
</head>
<body class="h-full bg-gray-50">
    
    <!-- Navigation moderne -->
    <nav class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <div class="flex-shrink-0 flex items-center">
                        <div class="w-8 h-8 gradient-bg rounded-lg flex items-center justify-center mr-3">
                            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M12.395 2.553a1 1 0 00-1.45-.385c-.345.23-.614.558-.822.88-.214.33-.403.713-.57 1.116-.334.804-.614 1.768-.84 2.734a31.365 31.365 0 00-.613 3.58 2.64 2.64 0 01-.945-1.067c-.328-.68-.398-1.534-.398-2.654A1 1 0 005.05 6.05 6.981 6.981 0 003 11a7 7 0 1011.95-4.95c-.001-1.167-.045-2.053-.1-2.507zM10.5 3c-.28 0-.5.22-.5.5v.5h.5c.28 0 .5-.22.5-.5V3z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <h1 class="text-xl font-semibold text-gray-900">FireGuard</h1>
                    </div>
                    <div class="ml-10 flex items-baseline space-x-1">
                        <a href="{{ route('fire.dashboard') }}" 
                           class="px-3 py-2 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('fire.dashboard') ? 'bg-gray-100 text-gray-900' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }}">
                            Dashboard
                        </a>
                        <a href="{{ route('fire.sensors') }}" 
                           class="px-3 py-2 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('fire.sensors') ? 'bg-gray-100 text-gray-900' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }}">
                            Capteurs
                        </a>
                        <a href="{{ route('fire.alerts') }}" 
                           class="px-3 py-2 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('fire.alerts') ? 'bg-gray-100 text-gray-900' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }}">
                            Alertes
                        </a>
                        <a href="{{ route('fire.zones') }}" 
                           class="px-3 py-2 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('fire.zones') ? 'bg-gray-100 text-gray-900' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }}">
                            Zones
                        </a>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="text-sm text-gray-500">
                        {{ now()->format('d/m/Y') }}
                    </div>
                    <div class="w-8 h-8 bg-gray-200 rounded-full flex items-center justify-center">
                        <svg class="w-4 h-4 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Contenu principal -->
    <main class="min-h-screen">
        @yield('content')
    </main>

            <!-- Scripts dynamiques -->
            <script>
                //Auto-refresh toutes les 30 secondes 
                setInterval(function() {
                    location.reload();
                }, 30000);

                // Fonctions pour formater les timestamps 
                function formatTimestamp(timestamp) {
                    const date = new Date(timestamp);
                    return date.toLocaleString('fr-FR', {
                        year: 'numeric',
                        month: 'long',
                        day: 'numeric',
                        hour: '2-digit',
                        minute: '2-digit',
                        second: '2-digit'
                    });

                // Fonction pour obtenir la couleur selon le niveau de l'alerte
                function getAlertColor(level) {
                    const colors = {
                        'info': 'bg-blue-100 text-blue-800',
                        'warning': 'bg-yellow-100 text-yellow-800',
                        'critical': 'bg-orange-100 text-orange-800',
                        'emergency': 'bg-red-100 text-red-800'
                    };
                    return colors[level] || 'bg-gray-100 text-gray-800';
                }

                function getAlertIcon(level) {
                    const icons = {
                        'info': 'ℹ️',
                        'warning': '⚠️',
                        'critical': '🚨',
                        'emergency': '🔥'
                    };
                    return icons[level] || '❓';
                }

                // Animation des cartes au sroll 
                const observerOptions = {
                    threshold: 0.1,
                    rootMargin: '0px 0px -50px 0px'
                };
                const observer = new IntersectionObserver(entries => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            entry.target.style.opacity = '1';
                            entry.target.style.transform = 'translateY(0)';
                            
                        }
                    });
                }, observerOptions);
                document.addEventListener('DOMContentLoaded', function() {
                    const cards = document.querySelectorAll('.animate-on-scroll');
                    cards.forEach(card => {
                        card.style.opacity = '0';
                        card.style.transform = 'translateY(20px)';
                        card.style.transition = 'opacity 0.6s ease, transform 0.6sease';
                        observer.observe(card);
                    });
                });
            </script>

    @yield('scripts')
</body>
</html>
