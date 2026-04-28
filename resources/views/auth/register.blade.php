@extends('layouts.app')

@section('content')
<section class="auth-shell">
    <div class="container">
        <div class="auth-grid">
            <div class="auth-showcase">
                <span class="auth-eyebrow">Nueva cuenta</span>
                <h1 class="auth-showcase-title">Empieza con una entrada mucho mejor presentada.</h1>
                <p class="auth-showcase-copy">
                    El registro ahora comparte el mismo tono visual que el login para que toda la experiencia de acceso se sienta coherente y bastante más profesional.
                </p>

                <div class="auth-showcase-metrics">
                    <div class="auth-metric">
                        <strong>Perfil</strong>
                        <span>Alta sencilla</span>
                    </div>
                    <div class="auth-metric">
                        <strong>Segura</strong>
                        <span>Campos claros</span>
                    </div>
                    <div class="auth-metric">
                        <strong>Fluida</strong>
                        <span>Desde móvil y desktop</span>
                    </div>
                </div>
            </div>

            <div class="auth-card">
                <span class="auth-eyebrow text-secondary">Crear cuenta</span>
                <h2 class="auth-card-title">Regístrate en Granago</h2>
                <p class="auth-card-copy">Completa los datos básicos para empezar a usar la aplicación.</p>

                <form method="POST" action="{{ route('register') }}" class="auth-form">
                    @csrf

                    <div class="auth-field">
                        <label for="name">Nombre</label>
                        <input id="name" type="text" class="form-control auth-input @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus placeholder="Tu nombre">

                        @error('name')
                            <span class="invalid-feedback d-block" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="auth-field">
                        <label for="email">Correo electrónico</label>
                        <input id="email" type="email" class="form-control auth-input @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="nombre@correo.com">

                        @error('email')
                            <span class="invalid-feedback d-block" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="auth-field">
                        <label for="password">Contraseña</label>
                        <input id="password" type="password" class="form-control auth-input @error('password') is-invalid @enderror" name="password" required autocomplete="new-password" placeholder="Crea una contraseña">

                        @error('password')
                            <span class="invalid-feedback d-block" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="auth-field">
                        <label for="password-confirm">Confirmar contraseña</label>
                        <input id="password-confirm" type="password" class="form-control auth-input" name="password_confirmation" required autocomplete="new-password" placeholder="Repite la contraseña">
                    </div>

                    <button type="submit" class="btn auth-submit">Crear cuenta</button>

                    @if (Route::has('login'))
                        <p class="auth-note mb-0">
                            ¿Ya tienes una cuenta?
                            <a class="auth-note-strong" href="{{ route('login') }}">Inicia sesión</a>
                        </p>
                    @endif
                </form>
            </div>
        </div>
    </div>
</section>
@endsection
