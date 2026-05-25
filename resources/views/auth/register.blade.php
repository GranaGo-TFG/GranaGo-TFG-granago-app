<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Registro | GranaGO!</title>
    <script src="{{ asset('js/auth-theme.js') }}"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
</head>
<body class="granago-auth-body granago-register-body">
    <button type="button" class="auth-theme-toggle" id="auth-theme-toggle" aria-label="Cambiar modo oscuro" aria-pressed="false">
        <span class="auth-theme-toggle-track">
            <span class="auth-theme-toggle-icon" aria-hidden="true">☀</span>
            <span class="auth-theme-toggle-thumb"></span>
            <span class="auth-theme-toggle-icon" aria-hidden="true">☾</span>
        </span>
    </button>

    <main class="container min-vh-100 d-flex align-items-center justify-content-center py-4">
        <section class="granago-shell">
            <a href="{{ url('/') }}" class="granago-back d-inline-flex align-items-center justify-content-center text-decoration-none" aria-label="Volver al inicio">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="m15 18-6-6 6-6"></path>
                </svg>
            </a>

            <div class="granago-card mt-3 p-4 p-md-5">
                <div class="granago-pill granago-pill-gold mb-3">Nuevo explorador en Granada</div>
                <h1 class="granago-title h1 mb-3">Crea tu perfil 🗺️</h1>
                <p class="granago-copy mb-0">Prepara tu mochila digital y unete a la aventura para descubrir rincones, completar retos y ganar recompensas.</p>

                <form method="POST" action="{{ route('register') }}" class="mt-4 pt-2">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="mb-3">
                        <label for="nombre" class="granago-label form-label">Nombre</label>
                        <div class="input-group granago-input-group">
                            <input id="nombre" type="text" name="nombre" class="form-control" value="{{ old('nombre') }}" required autocomplete="name" autofocus placeholder="Como te llamas?">
                        </div>
                        <?php if ($errors->has('nombre')): ?>
                            <div class="granago-error">{{ $errors->first('nombre') }}</div>
                        <?php endif; ?>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="granago-label form-label">Correo electronico</label>
                        <div class="input-group granago-input-group">
                            <span class="input-group-text">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M4 7.5A2.5 2.5 0 0 1 6.5 5h11A2.5 2.5 0 0 1 20 7.5v9A2.5 2.5 0 0 1 17.5 19h-11A2.5 2.5 0 0 1 4 16.5v-9Z"></path>
                                    <path d="m5 7 7 5 7-5"></path>
                                </svg>
                            </span>
                            <input id="email" type="email" name="email" class="form-control" value="{{ old('email') }}" required autocomplete="email" placeholder="tu@email.com">
                        </div>
                        <?php if ($errors->has('email')): ?>
                            <div class="granago-error">{{ $errors->first('email') }}</div>
                        <?php endif; ?>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="granago-label form-label">Contrasena</label>
                        <div class="input-group granago-input-group">
                            <span class="input-group-text">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <rect x="4" y="10" width="16" height="10" rx="2"></rect>
                                    <path d="M8 10V7.75a4 4 0 1 1 8 0V10"></path>
                                </svg>
                            </span>
                            <input id="password" type="password" name="password" class="form-control" required autocomplete="new-password" placeholder="Minimo 8 caracteres">
                        </div>
                        <?php if ($errors->has('password')): ?>
                            <div class="granago-error">{{ $errors->first('password') }}</div>
                        <?php endif; ?>
                    </div>

                    <div class="mb-4">
                        <label for="password-confirm" class="granago-label form-label">Confirmar contrasena</label>
                        <div class="input-group granago-input-group">
                            <span class="input-group-text">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <rect x="4" y="10" width="16" height="10" rx="2"></rect>
                                    <path d="M8 10V7.75a4 4 0 1 1 8 0V10"></path>
                                </svg>
                            </span>
                            <input id="password-confirm" type="password" name="password_confirmation" class="form-control" required autocomplete="new-password" placeholder="Repite tu contrasena">
                        </div>
                    </div>

                    <button type="submit" class="btn granago-btn-primary w-100 text-white">Crear mi cuenta</button>
                </form>

                <div class="text-center text-secondary mt-4">
                    Ya formas parte de la aventura?
                    <a href="{{ route('login') }}" class="granago-link">Inicia sesion</a>
                </div>
            </div>
        </section>
    </main>
</body>
</html>
