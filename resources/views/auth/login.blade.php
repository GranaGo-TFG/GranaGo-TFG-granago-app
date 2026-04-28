@extends('layouts.app')

@section('content')
<section class="auth-shell">
    <div class="container">
        <div class="auth-grid">
            <div class="auth-showcase">
                <span class="auth-eyebrow">Granago access</span>
                <h1 class="auth-showcase-title">Vuelve a entrar y sigue donde lo dejaste.</h1>
                <p class="auth-showcase-copy">
                    Un acceso más limpio, más claro y con una presencia visual mucho más cuidada para que la primera impresión de la app acompañe al proyecto.
                </p>

                <div class="auth-showcase-metrics">
                    <div class="auth-metric">
                        <strong>01</strong>
                        <span>Acceso rápido</span>
                    </div>
                    <div class="auth-metric">
                        <strong>02</strong>
                        <span>Diseño más cálido</span>
                    </div>
                    <div class="auth-metric">
                        <strong>03</strong>
                        <span>Mejor legibilidad</span>
                    </div>
                </div>
            </div>

            <div class="auth-card">
                <span class="auth-eyebrow text-secondary">Iniciar sesión</span>
                <h2 class="auth-card-title">Bienvenido de vuelta</h2>
                <p class="auth-card-copy">Introduce tus credenciales para acceder a tu panel.</p>

                <form method="POST" action="{{ route('login') }}" class="auth-form">
                    @csrf

                    <div class="auth-field">
                        <label for="email">Correo electrónico</label>
                        <input id="email" type="email" class="form-control auth-input @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="nombre@correo.com">

                        @error('email')
                            <span class="invalid-feedback d-block" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="auth-field">
                        <label for="password">Contraseña</label>
                        <input id="password" type="password" class="form-control auth-input @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="Tu contraseña">

                        @error('password')
                            <span class="invalid-feedback d-block" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="auth-links">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                            <label class="form-check-label" for="remember">Recordarme</label>
                        </div>

                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}">¿Has olvidado tu contraseña?</a>
                        @endif
                    </div>

                    <button type="submit" class="btn auth-submit">Entrar</button>

                    @if (Route::has('register'))
                        <p class="auth-note mb-0">
                            ¿Aún no tienes cuenta?
                            <a class="auth-note-strong" href="{{ route('register') }}">Crear una ahora</a>
                        </p>
                    @endif
                </form>
            </div>
        </div>
    </div>
</section>
@endsection
