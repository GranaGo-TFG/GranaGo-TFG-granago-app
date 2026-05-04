<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Recuperar contrasena | GranaGO!</title>
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
                <div class="auth-eyebrow auth-eyebrow-neutral">Recupera tu ruta</div>
                <h1 class="auth-title">¿Contrasena perdida? 🕵️‍♂️</h1>
                <p class="auth-copy">No te preocupes. Dinos tu correo y te mandaremos un mapa para recuperarla.</p>

                <?php if (session('status')): ?>
                    <div class="auth-status">{{ session('status') }}</div>
                <?php endif; ?>

                <form method="POST" action="{{ route('password.email') }}" class="auth-form auth-form-tight">
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

                    <button type="submit" class="auth-submit">Enviarme el enlace</button>
                </form>

                <div class="auth-bottom">
                    Te has acordado?
                    <a href="{{ route('login') }}" class="auth-link">Volver al login</a>
                </div>
            </div>
        </section>
    </main>
</body>
</html>
