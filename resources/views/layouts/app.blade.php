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
                    <span>GranaGO!</span>
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
                                    <a class="nav-link" href="{{ route('admin.retos.index') }}">Proyectos</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('admin.validaciones.index') }}">Validaciones</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('admin.usuarios.index') }}">Usuarios</a>
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
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('vistas.ranking') }}">Ranking</a>
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
                                    {{ Auth::user()->nombre }}
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

        <main class="py-4">
            @yield('content')
        </main>
    </div>

    @stack('scripts')
</body>
</html>
