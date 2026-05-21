<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>GranaGO!</title>
    <script src="{{ asset('js/auth-theme.js') }}"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('css/welcome.css') }}">
</head>
<body class="granago-welcome-body">
    <button type="button" class="auth-theme-toggle" id="auth-theme-toggle" aria-label="Cambiar modo oscuro" aria-pressed="false">
        <span class="auth-theme-toggle-track">
            <span class="auth-theme-toggle-icon" aria-hidden="true">☀</span>
            <span class="auth-theme-toggle-thumb"></span>
            <span class="auth-theme-toggle-icon" aria-hidden="true">☾</span>
        </span>
    </button>

    <main class="container min-vh-100 d-flex align-items-center justify-content-center py-4">
        <section class="w-100 text-center" style="max-width: 560px;">
            <div class="d-inline-flex align-items-center gap-3 mb-4">
                <span class="granago-brand-badge d-inline-flex align-items-center justify-content-center" aria-hidden="true">
                    <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="#1E293B" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <circle cx="12" cy="12" r="7.5"></circle>
                        <path d="M12 2.75V5"></path>
                        <path d="M12 19v2.25"></path>
                        <path d="M2.75 12H5"></path>
                        <path d="M19 12h2.25"></path>
                        <path d="m10 14 2.6-6 3.4 3.4L10 14Z" fill="#D91C4A" stroke="none"></path>
                    </svg>
                </span>
                <div class="granago-brand-name"><span>Grana</span><span>GO!</span></div>
            </div>

            <h1 class="granago-title granago-welcome-title mb-3">Tu ciudad es el tablero</h1>
            <p class="granago-copy granago-welcome-copy mx-auto mb-0">Descubre Granada, supera retos urbanos y gana recompensas.</p>

            <div class="my-4 my-md-5">
                <img src="{{ asset('images/mapaGranaIlustracion.png') }}" alt="Mapa de Granada" class="granago-map mx-auto" />
            </div>

            <div class="d-grid gap-3 mx-auto" style="max-width: 480px;">
                <a href="{{ route('register') }}" class="btn granago-btn-primary text-white">Empezar la aventura</a>
                <a href="{{ route('login') }}" class="btn granago-btn-outline">Ya tengo cuenta</a>
            </div>

            <div class="text-secondary mt-4">
                <a href="{{ route('login') }}" class="text-decoration-none text-secondary">Mas informacion</a>
                <span class="mx-2 text-secondary-subtle">|</span>
                <a href="{{ route('password.request') }}" class="text-decoration-none text-secondary">Ayuda</a>
            </div>
        </section>
    </main>
</body>
</html>
