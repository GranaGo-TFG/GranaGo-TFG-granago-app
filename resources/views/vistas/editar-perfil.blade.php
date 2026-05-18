@extends('layouts.app')

@section('content')
<div class="screen-page">
    <div class="container">
        <div class="edit-profile-hero">
            <div class="edit-profile-hero-copy">
                <span class="home-kicker">Ajustes de cuenta</span>
                <h1>Editar perfil</h1>
                <p>Actualiza tu nombre, tu correo y la contrasena de acceso sin salir del panel de GranaGO!.</p>
            </div>
            <a href="{{ route('vistas.perfil') }}" class="btn btn-outline-secondary profile-edit-back">
                <span class="profile-edit-back-icon" aria-hidden="true">&larr;</span>
                <span>Volver al perfil</span>
            </a>
        </div>

        @if (session('status'))
            <div class="alert alert-success home-alert" role="alert">
                {{ session('status') }}
            </div>
        @endif

        <div class="edit-profile-layout">
            <div class="home-panel edit-profile-card">
                <div class="edit-profile-card-head">
                    <div class="profile-avatar edit-profile-avatar" aria-label="Inicial del usuario">
                        <span>{{ strtoupper(substr(Auth::user()->nombre, 0, 1)) }}</span>
                    </div>

                    <div class="edit-profile-card-copy">
                        <span class="home-kicker">Tu cuenta</span>
                        <h2>{{ Auth::user()->nombre }}</h2>
                        <p class="muted-copy">Rol actual: {{ Auth::user()->rol }}</p>
                    </div>
                </div>

                <div class="edit-profile-summary">
                    <div class="edit-profile-summary-item">
                        <div class="edit-profile-summary-label">Puntos acumulados</div>
                        <div class="edit-profile-summary-value">{{ Auth::user()->puntos_totales }}</div>
                    </div>
                    <div class="edit-profile-summary-item">
                        <div class="edit-profile-summary-label">Multiplicador actual</div>
                        <div class="edit-profile-summary-value">x{{ number_format((float) Auth::user()->racha_multiplicador, 2) }}</div>
                    </div>
                    <div class="edit-profile-summary-item">
                        <div class="edit-profile-summary-label">Estado</div>
                        <div class="edit-profile-summary-value">Perfil activo</div>
                    </div>
                </div>
            </div>

            <div class="home-panel edit-profile-card edit-profile-form-card">
                <div class="edit-profile-section-head">
                    <span class="home-kicker">Datos principales</span>
                    <h2>Informacion publica y acceso</h2>
                    <p class="muted-copy">Los cambios se guardan en tu cuenta actual. La contrasena es opcional.</p>
                </div>

                <form method="POST" action="{{ route('vistas.perfil.update') }}" class="edit-profile-form">
                    @csrf
                    @method('PATCH')

                    <div class="edit-profile-grid">
                        <div class="edit-profile-field">
                            <label for="nombre" class="form-label">Nombre</label>
                            <input id="nombre" type="text" class="form-control @error('nombre') is-invalid @enderror" name="nombre" 
                            value="{{ old('nombre', Auth::user()->nombre) }}" required autocomplete="name">
                            @error('nombre')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="edit-profile-field">
                            <label for="email" class="form-label">Correo electronico</label>
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email"
                            value="{{ old('email', Auth::user()->email) }}" required autocomplete="email">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="edit-profile-field">
                            <label for="password" class="form-label">Nueva contrasena</label>
                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password"
                            autocomplete="new-password" placeholder="Dejala vacia si no quieres cambiarla">
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="edit-profile-field">
                            <label for="password-confirm" class="form-label">Confirmar contrasena</label>
                            <input id="password-confirm" type="password" class="form-control" name="password_confirmation"
                            autocomplete="new-password" placeholder="Repite la nueva contrasena">
                        </div>
                    </div>

                    <div class="edit-profile-actions">
                        <a href="{{ route('vistas.perfil') }}" class="btn btn-outline-secondary home-btn">Cancelar</a>
                        <button type="submit" class="btn btn-primary home-btn">Guardar cambios</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
