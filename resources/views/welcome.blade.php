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
            <nav class="welcome-nav">
                <div class="welcome-nav-inner">
                    <a class="app-brand text-decoration-none" href="{{ url('/') }}">
                        <span class="app-brand-mark">G</span>
                        <span>{{ config('app.name', 'Granago') }}</span>
                    </a>

                    <div class="welcome-nav-links">
                        @auth
                            <a class="welcome-link" href="{{ url('/home') }}">Ir al panel</a>
                        @else
                            @if (Route::has('login'))
                                <a class="welcome-link" href="{{ route('login') }}">Iniciar sesión</a>
                            @endif

                            @if (Route::has('register'))
                                <a class="welcome-link welcome-link-pill" href="{{ route('register') }}">Crear cuenta</a>
                            @endif
                        @endauth
                    </div>
                </div>
            </nav>
        </div>

        <section class="welcome-hero">
            <div class="container">
                <div class="welcome-grid">
                    <div class="welcome-copy">
                        <span class="welcome-kicker">TFG • Plataforma renovada</span>
                        <h1 class="welcome-title">
                            Una portada con más
                            <span class="welcome-title-accent">personalidad</span>
                            para tu proyecto.
                        </h1>
                        <p class="welcome-lead">
                            He rehecho la bienvenida para que ya no parezca la landing genérica de Laravel: ahora tiene una identidad visual más cálida, un mensaje más claro y una entrada mucho más presentable.
                        </p>

                        <div class="welcome-actions">
                            @auth
                                <a class="welcome-btn welcome-btn-primary" href="{{ url('/home') }}">Entrar al panel</a>
                            @else
                                @if (Route::has('register'))
                                    <a class="welcome-btn welcome-btn-primary" href="{{ route('register') }}">Empezar ahora</a>
                                @endif

                                @if (Route::has('login'))
                                    <a class="welcome-btn welcome-btn-secondary" href="{{ route('login') }}">Ya tengo cuenta</a>
                                @endif
                            @endauth
                        </div>

                        <div class="welcome-stats">
                            <div class="welcome-stat">
                                <strong>Visual</strong>
                                <span>Portada más cuidada y menos genérica.</span>
                            </div>
                            <div class="welcome-stat">
                                <strong>Coherente</strong>
                                <span>Mismo lenguaje que login y registro.</span>
                            </div>
                            <div class="welcome-stat">
                                <strong>Responsive</strong>
                                <span>Preparada para móvil y escritorio.</span>
                            </div>
                        </div>
                    </div>

                    <aside class="welcome-panel">
                        <div class="welcome-panel-head">
                            <div>
                                <span class="welcome-panel-chip">Vista principal</span>
                            </div>
                            <span class="welcome-panel-chip">Nuevo look</span>
                        </div>

                        <div class="welcome-panel-grid">
                            <div class="welcome-card">
                                <h3>Primera impresión mejorada</h3>
                                <p>La home ahora presenta el proyecto con más intención, jerarquía visual y una composición bastante más elegante.</p>
                            </div>

                            <div class="welcome-card">
                                <h3>Qué transmite ahora</h3>
                                <ul class="welcome-list">
                                    <li>Proyecto más serio y más propio.</li>
                                    <li>Acceso destacado sin saturar la vista.</li>
                                    <li>Información más ordenada y legible.</li>
                                </ul>
                            </div>

                            <div class="welcome-card">
                                <h3>Siguiente paso</h3>
                                <p>Si quieres, después podemos llevar este mismo estilo al `home`, al menú interno y a los formularios restantes.</p>
                            </div>
                        </div>
                    </aside>
                </div>
            </div>
        </section>

        <section class="welcome-section">
            <div class="container">
                <div class="welcome-section-head">
                    <h2>Una bienvenida más fuerte, clara y útil</h2>
                    <p>
                        En lugar de un bloque estándar, ahora la portada explica mejor el proyecto, guía al usuario hacia la acción principal y mantiene un estilo visual consistente con el resto de la aplicación.
                    </p>
                </div>

                <div class="welcome-features">
                    <article class="welcome-feature">
                        <span class="welcome-feature-badge">01</span>
                        <h3>Hero con identidad</h3>
                        <p>Tipografía más expresiva, paleta más cálida y una composición con más presencia para que la página no se vea vacía.</p>
                    </article>

                    <article class="welcome-feature">
                        <span class="welcome-feature-badge">02</span>
                        <h3>Accesos bien visibles</h3>
                        <p>Login, registro y panel quedan colocados como acciones claras para que el usuario no tenga que buscar dónde entrar.</p>
                    </article>

                    <article class="welcome-feature">
                        <span class="welcome-feature-badge">03</span>
                        <h3>Continuidad visual</h3>
                        <p>La portada ya no va por un lado y el login por otro: todo sigue la misma dirección estética.</p>
                    </article>
                </div>
            </div>
        </section>

        <section class="welcome-section pt-0">
            <div class="container">
                <div class="welcome-banner">
                    <h2>Tu app ya empieza a tener cara de producto</h2>
                    <p>
                        La base ya está bastante mejor. Si seguimos con esta línea, el siguiente salto bueno sería rediseñar la pantalla de `home` y la navegación una vez el usuario inicia sesión.
                    </p>

                    @auth
                        <a class="welcome-btn welcome-btn-secondary" href="{{ url('/home') }}">Abrir mi panel</a>
                    @else
                        @if (Route::has('register'))
                            <a class="welcome-btn welcome-btn-secondary" href="{{ route('register') }}">Crear una cuenta</a>
                        @elseif (Route::has('login'))
                            <a class="welcome-btn welcome-btn-secondary" href="{{ route('login') }}">Acceder</a>
                        @endif
                    @endauth
                </div>
            </div>
        </section>
    </div>
</body>
</html>
