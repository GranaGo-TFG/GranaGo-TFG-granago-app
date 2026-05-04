<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Iniciar sesion | GranaGO!</title>
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
</head>
<body class="granago-page">
    <main class="auth-page">
        <section class="auth-shell">
            <a href="{{ url('/') }}" class="auth-back" aria-label="Volver al inicio">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="m15 18-6-6 6-6"></path>
                </svg>
            </a>

            <div class="auth-card">
                <div class="auth-eyebrow auth-eyebrow-primary">Explora Granada a tu ritmo</div>
                <h1 class="auth-title">¡De vuelta a las calles! 🎒</h1>
                <p class="auth-copy">Inicia sesion para seguir tus rutas, sumar puntos y desbloquear nuevos retos por la ciudad.</p>

                <form method="POST" action="{{ route('login') }}" class="auth-form">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="auth-field">
                        <label for="email">Correo electronico</label>
                        <div class="auth-input-wrap">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M4 7.5A2.5 2.5 0 0 1 6.5 5h11A2.5 2.5 0 0 1 20 7.5v9A2.5 2.5 0 0 1 17.5 19h-11A2.5 2.5 0 0 1 4 16.5v-9Z"></path>
                                <path d="m5 7 7 5 7-5"></path>
                            </svg>
                            <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="tu@email.com">
                        </div>
                        <?php if ($errors->has('email')): ?>
                            <div class="auth-error">{{ $errors->first('email') }}</div>
                        <?php endif; ?>
                    </div>

                    <div class="auth-field">
                        <label for="password">Contrasena</label>
                        <div class="auth-input-wrap">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <rect x="4" y="10" width="16" height="10" rx="2"></rect>
                                <path d="M8 10V7.75a4 4 0 1 1 8 0V10"></path>
                            </svg>
                            <input id="password" type="password" name="password" required autocomplete="current-password" placeholder="Tu contrasena">
                        </div>
                        <?php if ($errors->has('password')): ?>
                            <div class="auth-error">{{ $errors->first('password') }}</div>
                        <?php endif; ?>
                    </div>

                    <div class="auth-row">
                        <label class="auth-remember" for="remember">
                            <input id="remember" type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                            <span>Recordarme</span>
                        </label>
                        <a href="{{ route('password.request') }}" class="auth-link">He olvidado mi contrasena</a>
                    </div>

                    <button type="submit" class="auth-submit">Entrar en GranaGO!</button>
                </form>

                <div class="auth-bottom">
                    Aun no tienes cuenta?
                    <a href="{{ route('register') }}" class="auth-link">Crea tu perfil</a>
                </div>
            </div>
        </section>
    </main>
</body>
</html>
