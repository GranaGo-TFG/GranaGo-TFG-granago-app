<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Recuperar contrasena | GranaGO!</title>
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
                <div class="granago-pill granago-pill-neutral mb-3">Recupera tu ruta</div>
                <h1 class="granago-title h1 mb-3">¿Contrasena perdida? 🕵️‍♂️</h1>
                <p class="granago-copy mb-0">No te preocupes. Dinos tu correo y te mandaremos un mapa para recuperarla.</p>

                <?php if (session('status')): ?>
                    <div class="granago-status mt-4 p-3">{{ session('status') }}</div>
                <?php endif; ?>

                <form method="POST" action="{{ route('password.email') }}" class="mt-4 pt-1">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="mb-4">
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

                    <button type="submit" class="btn granago-btn-primary w-100 text-white">Enviarme el enlace</button>
                </form>

                <div class="text-center text-secondary mt-4">
                    Te has acordado?
                    <a href="{{ route('login') }}" class="granago-link">Volver al login</a>
                </div>
            </div>
        </section>
    </main>
</body>
</html>
