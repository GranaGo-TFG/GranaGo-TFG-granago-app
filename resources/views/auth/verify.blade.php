@extends('layouts.app')

@section('content')
<section class="auth-shell">
    <div class="container">
        <div class="auth-grid">
            <div class="auth-showcase">
                <span class="auth-eyebrow">Verificación</span>
                <h1 class="auth-showcase-title">Solo queda un paso para activar tu cuenta.</h1>
                <p class="auth-showcase-copy">
                    La pantalla de verificación también entra en la nueva línea visual para que todo el acceso tenga la misma identidad.
                </p>

                <div class="auth-showcase-metrics">
                    <div class="auth-metric">
                        <strong>Inbox</strong>
                        <span>Revisa tu correo</span>
                    </div>
                    <div class="auth-metric">
                        <strong>Un paso</strong>
                        <span>Activa tu cuenta</span>
                    </div>
                    <div class="auth-metric">
                        <strong>Listo</strong>
                        <span>Entra en segundos</span>
                    </div>
                </div>
            </div>

            <div class="auth-card">
                <span class="auth-eyebrow text-secondary">Validar correo</span>
                <h2 class="auth-card-title">Verifica tu dirección de email</h2>
                <p class="auth-card-copy">Antes de continuar, revisa tu bandeja y pulsa en el enlace de verificación.</p>

                @if (session('resent'))
                    <div class="alert alert-success auth-alert" role="alert">
                        Se ha enviado un nuevo enlace de verificación a tu correo.
                    </div>
                @endif

                <div class="auth-form">
                    <p class="auth-note mb-0">
                        Si no lo encuentras, mira también en spam o promociones. Si sigue sin aparecer, puedes solicitar otro envío.
                    </p>

                    <form method="POST" action="{{ route('verification.resend') }}">
                        @csrf
                        <button type="submit" class="btn auth-submit w-100">Reenviar email de verificación</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
