@extends('layouts.app')

@section('content')
<section class="auth-shell">
    <div class="container">
        <div class="auth-grid">
            <div class="auth-showcase">
                <span class="auth-eyebrow">Confirmación</span>
                <h1 class="auth-showcase-title">Una capa extra de seguridad, sin una pantalla gris y plana.</h1>
                <p class="auth-showcase-copy">
                    Cuando una acción es sensible, la app vuelve a pedir la contraseña dentro del mismo sistema visual renovado.
                </p>

                <div class="auth-showcase-metrics">
                    <div class="auth-metric">
                        <strong>Extra</strong>
                        <span>Verificación segura</span>
                    </div>
                    <div class="auth-metric">
                        <strong>Clara</strong>
                        <span>Explicación directa</span>
                    </div>
                    <div class="auth-metric">
                        <strong>Coherente</strong>
                        <span>Mismo estilo de acceso</span>
                    </div>
                </div>
            </div>

            <div class="auth-card">
                <span class="auth-eyebrow text-secondary">Confirmar identidad</span>
                <h2 class="auth-card-title">Verifica tu contraseña</h2>
                <p class="auth-card-copy">Por seguridad, confirma tu contraseña antes de continuar.</p>

                <form method="POST" action="{{ route('password.confirm') }}" class="auth-form">
                    @csrf

                    <div class="auth-field">
                        <label for="password">Contraseña actual</label>
                        <input id="password" type="password" class="form-control auth-input @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="Tu contraseña actual">

                        @error('password')
                            <span class="invalid-feedback d-block" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <button type="submit" class="btn auth-submit">Confirmar</button>

                    @if (Route::has('password.request'))
                        <div class="auth-links-inline">
                            <span class="auth-helper">Si no la recuerdas, puedes recuperarla.</span>
                            <a href="{{ route('password.request') }}">Restablecer contraseña</a>
                        </div>
                    @endif
                </form>
            </div>
        </div>
    </div>
</section>
@endsection
