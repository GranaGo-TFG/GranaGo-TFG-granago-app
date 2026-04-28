@extends('layouts.app')

@section('content')
<section class="auth-shell">
    <div class="container">
        <div class="auth-grid">
            <div class="auth-showcase">
                <span class="auth-eyebrow">Nueva contraseña</span>
                <h1 class="auth-showcase-title">Renueva tu acceso con una vista más cuidada.</h1>
                <p class="auth-showcase-copy">
                    La pantalla de cambio de contraseña sigue la misma línea visual para mantener continuidad en todo el flujo de autenticación.
                </p>

                <div class="auth-showcase-metrics">
                    <div class="auth-metric">
                        <strong>Seguro</strong>
                        <span>Token incluido</span>
                    </div>
                    <div class="auth-metric">
                        <strong>Simple</strong>
                        <span>Formulario directo</span>
                    </div>
                    <div class="auth-metric">
                        <strong>Consistente</strong>
                        <span>Mismo lenguaje visual</span>
                    </div>
                </div>
            </div>

            <div class="auth-card">
                <span class="auth-eyebrow text-secondary">Actualizar contraseña</span>
                <h2 class="auth-card-title">Crea una nueva clave</h2>
                <p class="auth-card-copy">Introduce tu correo y la nueva contraseña para recuperar la cuenta.</p>

                <form method="POST" action="{{ route('password.update') }}" class="auth-form">
                    @csrf

                    <input type="hidden" name="token" value="{{ $token }}">

                    <div class="auth-field">
                        <label for="email">Correo electrónico</label>
                        <input id="email" type="email" class="form-control auth-input @error('email') is-invalid @enderror" name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus placeholder="nombre@correo.com">

                        @error('email')
                            <span class="invalid-feedback d-block" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="auth-field">
                        <label for="password">Nueva contraseña</label>
                        <input id="password" type="password" class="form-control auth-input @error('password') is-invalid @enderror" name="password" required autocomplete="new-password" placeholder="Escribe una nueva contraseña">

                        @error('password')
                            <span class="invalid-feedback d-block" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="auth-field">
                        <label for="password-confirm">Confirmar contraseña</label>
                        <input id="password-confirm" type="password" class="form-control auth-input" name="password_confirmation" required autocomplete="new-password" placeholder="Repite la nueva contraseña">
                    </div>

                    <button type="submit" class="btn auth-submit">Guardar nueva contraseña</button>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection
