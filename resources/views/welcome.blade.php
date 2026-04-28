<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Granago') }}</title>

    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=manrope:400,500,600,700,800|fraunces:600,700" rel="stylesheet">

    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>
<body class="welcome-page">
    <div class="welcome-shell">
        <div class="container">
            <nav class="welcome-topbar">
                @auth
                    <a class="welcome-topbar-link is-primary" href="{{ url('/home') }}">Dashboard</a>
                @else
                    @if (Route::has('login'))
                        <a class="welcome-topbar-link" href="{{ route('login') }}">Log in</a>
                    @endif

                    @if (Route::has('register'))
                        <a class="welcome-topbar-link is-primary" href="{{ route('register') }}">Register</a>
                    @endif
                @endauth
            </nav>

            <main class="welcome-layout">
                <section class="welcome-main">
                    <div class="welcome-mark">
                        <div class="welcome-mark-badge">G</div>

                        <div class="welcome-mark-text">
                            <span class="welcome-mark-title">{{ config('app.name', 'Granago') }}</span>
                            <span class="welcome-mark-subtitle">TFG DAW · prototipo inicial</span>
                        </div>
                    </div>

                    <span class="welcome-kicker">Exploracion urbana en Granada</span>

                    <h1 class="welcome-title">
                        <span class="welcome-title-accent">GranaGO</span>
                    </h1>

                    <p class="welcome-copy">
                        Esta es solo una primera bienvenida para el proyecto. La idea es mantener
                        una portada limpia, cercana a la que trae Laravel al empezar, pero con una
                        paleta y un tono mas ligados al TFG: ciudad, retos, fotos, recorridos y vida urbana.
                    </p>

                    <div class="welcome-notes">
                        <span class="welcome-note">Paleta inicial del proyecto</span>
                        <span class="welcome-note">Portada todavia sin cerrar</span>
                        <span class="welcome-note">El resto de pantallas, por definir</span>
                    </div>
                </section>

                <aside class="welcome-side">
                    <div class="welcome-panel">
                        <h2 class="welcome-panel-title">Ahora mismo</h2>

                        <ul class="welcome-panel-list">
                            <li>
                                <span class="welcome-panel-label">Enfoque</span>
                                <span class="welcome-panel-text">Una app pensada para descubrir lugares y moverse por la ciudad de otra forma.</span>
                            </li>
                            <li>
                                <span class="welcome-panel-label">Estilo</span>
                                <span class="welcome-panel-text">Rojo granada, azul noche y acentos oro como punto de partida visual.</span>
                            </li>
                            <li>
                                <span class="welcome-panel-label">Estado</span>
                                <span class="welcome-panel-text">Interfaz inicial, todavia abierta a cambios y sin desarrollar el resto del sistema visual.</span>
                            </li>
                        </ul>
                    </div>
                </aside>
            </main>
        </div>
    </div>
</body>
</html>
