<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Iniciar sesion | GranaGO!</title>
    <script src="{{ asset('js/auth-theme.js') }}"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
</head>
<body class="granago-auth-body">
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
                <div class="granago-pill granago-pill-primary mb-3">Explora Granada a tu ritmo</div>
                <h1 class="granago-title h1 mb-3">¡De vuelta a las calles! 🎒</h1>
                <p class="granago-copy mb-0">Inicia sesion para seguir tus rutas, sumar puntos y desbloquear nuevos retos por la ciudad.</p>

                <form method="POST" action="{{ route('login') }}" class="mt-4 pt-2">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="mb-3">
                        <label for="email" class="granago-label form-label">Correo electronico</label>
                        <div class="input-group granago-input-group">
                            <span class="input-group-text">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M4 7.5A2.5 2.5 0 0 1 6.5 5h11A2.5 2.5 0 0 1 20 7.5v9A2.5 2.5 0 0 1 17.5 19h-11A2.5 2.5 0 0 1 4 16.5v-9Z"></path>
                                    <path d="m5 7 7 5 7-5"></path>
                                </svg>
                            </span>
                            <input id="email" type="email" name="email" class="form-control" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="tu@email.com">
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
                            <input id="password" type="password" name="password" class="form-control" required autocomplete="current-password" placeholder="Tu contrasena">
                        </div>
                        <?php if ($errors->has('password')): ?>
                            <div class="granago-error">{{ $errors->first('password') }}</div>
                        <?php endif; ?>
                    </div>

                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
                        <label class="form-check d-inline-flex align-items-center gap-2 mb-0 text-secondary" for="remember">
                            <input id="remember" type="checkbox" name="remember" class="form-check-input mt-0" {{ old('remember') ? 'checked' : '' }}>
                            <span>Recordarme</span>
                        </label>
                        <a href="{{ route('password.request') }}" class="granago-link">He olvidado mi contrasena</a>
                    </div>

                    <button type="submit" class="btn granago-btn-primary w-100 text-white">Entrar en GranaGO!</button>
                </form>

                <div class="text-center text-secondary mt-4">
                    Aun no tienes cuenta?
                    <a href="{{ route('register') }}" class="granago-link">Crea tu perfil</a>
                </div>
            </div>
        </section>
    </main>
</body>
</html>
