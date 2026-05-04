<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>GranaGO!</title>
    <link rel="stylesheet" href="{{ asset('css/welcome.css') }}">
</head>
<body class="granago-page">
    <main class="welcome-page">
        <section class="welcome-shell">
            <div class="welcome-brand">
                <span class="welcome-brand-badge" aria-hidden="true">
                    <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="#1E293B" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <circle cx="12" cy="12" r="7.5"></circle>
                        <path d="M12 2.75V5"></path>
                        <path d="M12 19v2.25"></path>
                        <path d="M2.75 12H5"></path>
                        <path d="M19 12h2.25"></path>
                        <path d="m10 14 2.6-6 3.4 3.4L10 14Z" fill="#D91C4A" stroke="none"></path>
                    </svg>
                </span>
                <div class="welcome-brand-name"><span>Grana</span><span class="welcome-brand-name-accent">GO!</span></div>
            </div>

            <h1 class="welcome-title">Tu ciudad es el tablero</h1>
            <p class="welcome-subtitle">Descubre Granada, supera retos urbanos y gana recompensas.</p>

            <div class="welcome-map-wrap">
                <img src="{{ asset('images/mapaGranaIlustracion.png') }}" alt="Mapa de Granada" class="welcome-map" />
            </div>

            <div class="welcome-actions">
                <a href="{{ route('register') }}" class="welcome-button welcome-button-primary">Empezar la aventura</a>
                <a href="{{ route('login') }}" class="welcome-button welcome-button-secondary">Ya tengo cuenta</a>
            </div>

            <div class="welcome-footer-links">
                <a href="{{ route('login') }}">Mas informacion</a>
                <span>|</span>
                <a href="{{ route('password.request') }}">Ayuda</a>
            </div>
        </section>
    </main>
</body>
</html>
