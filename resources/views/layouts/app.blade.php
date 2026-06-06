<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>
    <link rel="icon" type="image/png" href="{{ asset('images/Logo.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/Logo.png') }}">

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=manrope:400,500,600,700,800|fraunces:600,700" rel="stylesheet">

    <script>
        (function () {
            var savedTheme = localStorage.getItem('granago-theme');
            var prefersDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
            var theme = savedTheme || (prefersDark ? 'dark' : 'light');

            document.documentElement.setAttribute('data-theme', theme);
            document.documentElement.style.colorScheme = theme;
        }());
    </script>

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    @stack('styles')
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-dark bg-dark shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ auth()->check() ? route('home') : url('/') }}">
                    <img src="{{ asset('images/Logo_fondo_blanco.png') }}" alt="Logo de GranaGO!" class="navbar-brand-logo">
                    <span>GranaGO<span class="navbar-brand-bang">!</span></span>
                </a>
                <div class="navbar-mobile-actions">
                    <button
                        type="button"
                        class="theme-toggle"
                        aria-label="Cambiar modo oscuro"
                        aria-pressed="false"
                    >
                        <span class="theme-toggle-track">
                            <span class="theme-toggle-icon theme-toggle-icon-sun" aria-hidden="true">☀</span>
                            <span class="theme-toggle-thumb"></span>
                            <span class="theme-toggle-icon theme-toggle-icon-moon" aria-hidden="true">☾</span>
                        </span>
                    </button>
                </div>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">
                        @auth
                            @if (Auth::user()->rol === 'admin')
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('admin.retos.index') }}">Retos</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('admin.validaciones.index') }}">Validaciones</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('admin.usuarios.index') }}">Usuarios</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('admin.productos.index') }}">Tienda</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('admin.logros.index') }}">Logros</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('vistas.comunidad') }}">Comunidad</a>
                                </li>
                            @else
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('home') }}">Inicio</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('vistas.retos') }}">Retos</a>
                                </li>
                                @if (Auth::user()->rol === 'creador')
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('vistas.mis-retos') }}">Mis retos</a>
                                    </li>
                                @elseif (Auth::user()->rol === 'usuario')
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('vistas.validaciones') }}">Validaciones</a>
                                    </li>
                                @endif
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('vistas.ranking') }}">Ranking</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('vistas.logros') }}">Logros</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('vistas.comunidad') }}">Comunidad</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('vistas.tienda') }}">Tienda</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('vistas.planes') }}">Planes</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('home') }}">Inicio</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('vistas.retos') }}">Retos</a>
                            </li>
                        @endauth
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <!-- Authentication Links -->
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->nombre_publico }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    @if (Auth::user()->rol !== 'admin')
                                        <a class="dropdown-item" href="{{ route('vistas.perfil') }}">
                                            Perfil
                                        </a>
                                    @endif

                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest

                        <li class="nav-item d-flex align-items-center theme-toggle-nav">
                            <button
                                type="button"
                                class="theme-toggle"
                                id="theme-toggle"
                                aria-label="Cambiar modo oscuro"
                                aria-pressed="false"
                            >
                                <span class="theme-toggle-track">
                                    <span class="theme-toggle-icon theme-toggle-icon-sun" aria-hidden="true">☀</span>
                                    <span class="theme-toggle-thumb"></span>
                                    <span class="theme-toggle-icon theme-toggle-icon-moon" aria-hidden="true">☾</span>
                                </span>
                            </button>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <main class="site-main py-4">
            @yield('content')
        </main>

        @include('partials.site-footer')

        <div class="floating-guide" data-floating-guide>
            <div class="floating-guide-card" id="floating-guide-card" aria-hidden="true">
                <div class="floating-guide-head">
                    <div class="floating-guide-title">
                        <span class="floating-guide-icon" aria-hidden="true">
                            <img src="{{ asset('images/Logo_fondo_blanco.png') }}" alt="">
                        </span>
                        <div>
                            <span class="floating-guide-label">GranaGO</span>
                            <h2>Ayuda rapida</h2>
                        </div>
                    </div>
                    <button type="button" class="floating-guide-close" data-floating-guide-close aria-label="Cerrar asistente">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="floating-guide-body">
                    <div class="floating-guide-status">
                        <span aria-hidden="true"></span>
                        Preguntas frecuentes
                    </div>

                    <div class="floating-guide-chat" aria-live="polite">
                        <p class="floating-guide-message is-bot" data-floating-guide-answer>
                            Elige una pregunta y te explico lo basico de la app sin salir de esta pagina.
                        </p>
                    </div>

                    <div class="floating-guide-questions" data-floating-guide-questions>
                        <button type="button" data-question="retos">Retos</button>
                        <button type="button" data-question="pruebas">Subir prueba</button>
                        <button type="button" data-question="puntos">Puntos</button>
                        <button type="button" data-question="planes">Planes</button>
                    </div>
                </div>
            </div>

            <button
                type="button"
                class="floating-guide-toggle"
                aria-controls="floating-guide-card"
                aria-expanded="false"
                aria-label="Abrir asistente"
            >
                <span class="floating-guide-toggle-mark" aria-hidden="true">?</span>
                <span>Ayuda</span>
            </button>
        </div>
    </div>

    @stack('scripts')
</body>
</html>
