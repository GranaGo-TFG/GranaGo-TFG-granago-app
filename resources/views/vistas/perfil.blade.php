@extends('layouts.app')

@section('content')
<div class="screen-page">
    <div class="container">
        <section class="profile-hero">
            <div class="profile-cover"></div>
            <div class="profile-main">
                <div class="profile-avatar" aria-label="Foto de perfil">
                    <span>{{ strtoupper(substr(Auth::user()->nombre, 0, 1)) }}</span>
                </div>

                <div class="profile-info">
                    <span class="home-kicker">Perfil</span>
                    <h1>{{ Auth::user()->nombre }}</h1>
                    <p>Explorador urbano de Granada. Retos, puntos y logros en un mismo sitio.</p>
                    <div class="profile-tags">
                        <span>{{ Auth::user()->rol }}</span>
                        <span>Granada</span>
                        <span>Racha activa</span>
                    </div>
                </div>

                <a href="#" class="btn btn-outline-secondary profile-edit">Editar perfil</a>
            </div>
        </section>

        <section class="profile-stats">
            <article>
                <strong>120</strong>
                <span>Puntos</span>
            </article>
            <article>
                <strong>3</strong>
                <span>Retos completados</span>
            </article>
            <article>
                <strong>2</strong>
                <span>Logros</span>
            </article>
            <article>
                <strong>x1.25</strong>
                <span>Racha</span>
            </article>
        </section>

        <section class="profile-layout">
            <article class="home-panel profile-card">
                <span class="home-kicker">Progreso</span>
                <h2>Camino al siguiente nivel</h2>
                <p class="muted-copy">Te faltan 30 puntos para subir al nivel de Explorador del Albaicin.</p>
                <div class="profile-progress">
                    <span style="width: 68%"></span>
                </div>
                <div class="profile-progress-label">
                    <span>120 pts</span>
                    <span>150 pts</span>
                </div>
            </article>

            <article class="home-panel profile-card">
                <span class="home-kicker">Logros</span>
                <div class="profile-achievement-list">
                    <div class="profile-achievement is-unlocked">
                        <span>Inicio</span>
                        <strong>Primer reto</strong>
                    </div>
                    <div class="profile-achievement is-unlocked">
                        <span>Ruta</span>
                        <strong>Explorador urbano</strong>
                    </div>
                    <div class="profile-achievement">
                        <span>Foto</span>
                        <strong>Cazador de miradores</strong>
                    </div>
                </div>
            </article>

            <article class="home-panel profile-card profile-card-wide">
                <span class="home-kicker">Actividad reciente</span>
                <div class="profile-timeline">
                    <div>
                        <strong>Prueba enviada</strong>
                        <p>Foto en el Mirador de San Nicolas pendiente de revisar.</p>
                    </div>
                    <div>
                        <strong>Logro desbloqueado</strong>
                        <p>Explorador urbano se anadio a tu perfil.</p>
                    </div>
                    <div>
                        <strong>Puntos actualizados</strong>
                        <p>Sumaste 35 puntos en la ruta de arte urbano.</p>
                    </div>
                </div>
            </article>

            <aside class="home-panel profile-card">
                <span class="home-kicker">Cuenta</span>
                <dl class="profile-details">
                    <div>
                        <dt>Email</dt>
                        <dd>{{ Auth::user()->email }}</dd>
                    </div>
                    <div>
                        <dt>Rol</dt>
                        <dd>{{ Auth::user()->rol }}</dd>
                    </div>
                    <div>
                        <dt>Estado</dt>
                        <dd>Activo</dd>
                    </div>
                </dl>
            </aside>
        </section>

        <section class="profile-mobile-actions">
            <a href="{{ route('vistas.retos') }}" class="btn btn-primary home-btn">Buscar retos</a>
            <a href="{{ route('vistas.ranking') }}" class="btn btn-outline-secondary home-btn">Ver ranking</a>
        </section>
    </div>
</div>
@endsection
