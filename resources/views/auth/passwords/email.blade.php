@extends('layouts.app')

@section('content')
<section class="auth-shell">
    <div class="container">
        <div class="auth-grid">
            <div class="auth-showcase">
                <span class="auth-eyebrow">Recuperación</span>
                <h1 class="auth-showcase-title">Recupera el acceso sin perder claridad.</h1>
                <p class="auth-showcase-copy">
                    También he unificado la parte de recuperación para que no parezca una pantalla aparte del resto del sistema.
                </p>

                <div class="auth-showcase-metrics">
                    <div class="auth-metric">
                        <strong>Email</strong>
                        <span>Enlace de recuperación</span>
                    </div>
                    <div class="auth-metric">
                        <strong>Rápido</strong>
                        <span>Un solo paso</span>
                    </div>
                    <div class="auth-metric">
                        <strong>Claro</strong>
                        <span>Mensajes visibles</span>
                    </div>
                </div>
            </div>

            <div class="auth-card">
                <span class="auth-eyebrow text-secondary">Restablecer acceso</span>
                <h2 class="auth-card-title">¿Olvidaste tu contraseña?</h2>
                <p class="auth-card-copy">Te enviaremos un enlace para crear una nueva.</p>

                @if (session('status'))
                    <div class="alert alert-success auth-alert" role="alert">
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('password.email') }}" class="auth-form">
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

                    <button type="submit" class="btn auth-submit">Enviar enlace de recuperación</button>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection
