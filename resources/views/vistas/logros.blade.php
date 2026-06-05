@extends('layouts.app')

@section('content')
@php
    $user = Auth::user();
    $todosLogros = \App\Models\Logro::query()
        ->orderBy('id')
        ->get()
        ->unique('nombre_logro')
        ->values();
    $logrosCompletados = $user->logros()
        ->orderByPivot('fecha_desbloqueo', 'desc')
        ->get()
        ->unique('nombre_logro')
        ->values();
    $logrosDesbloqueadosNombres = $logrosCompletados->pluck('nombre_logro');
    $logrosPendientes = $todosLogros
        ->whereNotIn('nombre_logro', $logrosDesbloqueadosNombres)
        ->values();
    $totalLogros = $todosLogros->count();
    $logrosDesbloqueadosCount = $logrosCompletados->count();
    $porcentajeCompletado = $totalLogros > 0
        ? (int) round(($logrosDesbloqueadosCount / $totalLogros) * 100)
        : 0;
@endphp

<div class="screen-page achievements-page">
    <div class="container">
        <section class="achievements-hero reveal-item">
            <div>
                <h1 class="home-kicker">Logros</h1>
                <h2>Insignias de tu aventura en Granada</h2>
                <p>Cada logro reconoce acciones reales en la plataforma: completar retos, crear experiencias y participar en la comunidad.</p>
            </div>

            <div class="achievements-progress-wrap">
                <span>Progreso global</span>
                <strong>{{ $logrosDesbloqueadosCount }} / {{ $totalLogros }}</strong>
                <div class="profile-progress">
                    <span style="width: {{ $porcentajeCompletado }}%"></span>
                </div>
                <small>{{ $porcentajeCompletado }}% completado</small>
            </div>
        </section>

        <section class="achievements-stats">
            <article class="reveal-item">
                <strong>{{ $logrosDesbloqueadosCount }}</strong>
                <span>Logros desbloqueados</span>
            </article>
            <article class="reveal-item">
                <strong>{{ $logrosPendientes->count() }}</strong>
                <span>Logros pendientes</span>
            </article>
            <article class="reveal-item">
                <strong>{{ $totalLogros }}</strong>
                <span>Logros totales</span>
            </article>
        </section>

        <section class="achievements-layout">
            <article class="home-panel achievement-section reveal-item">
                <div class="achievement-section-head">
                    <div>
                        <span class="home-kicker">Desbloqueados</span>
                        <h2>Tus logros conseguidos</h2>
                    </div>
                    <small>{{ $logrosDesbloqueadosCount }} activos</small>
                </div>

                <div class="achievement-grid">
                    @forelse ($logrosCompletados as $logro)
                        <article class="achievement-card is-unlocked reveal-item">
                            <div class="achievement-icon" aria-hidden="true">{{ strtoupper(substr($logro->nombre_logro, 0, 1)) }}</div>
                            <div class="achievement-card-copy">
                                <div class="achievement-card-meta">
                                    <span class="achievement-state">Desbloqueado</span>
                                    <small>{{ $logro->pivot->fecha_desbloqueo ? \Illuminate\Support\Carbon::parse($logro->pivot->fecha_desbloqueo)->format('d/m/Y') : 'Fecha no disponible' }}</small>
                                </div>
                                <h3>{{ $logro->nombre_logro }}</h3>
                                <p>{{ $logro->descripcion }}</p>
                            </div>
                        </article>
                    @empty
                        <article class="achievement-card reveal-item">
                            <div class="achievement-icon" aria-hidden="true">?</div>
                            <div class="achievement-card-copy">
                                <div class="achievement-card-meta">
                                    <span class="achievement-state">Sin desbloquear</span>
                                </div>
                                <h3>Aun no tienes logros</h3>
                                <p>Completa retos y participa en la comunidad para empezar a desbloquear insignias.</p>
                            </div>
                        </article>
                    @endforelse
                </div>
            </article>

            <article class="home-panel achievement-section reveal-item">
                <div class="achievement-section-head">
                    <div>
                        <span class="home-kicker">Pendientes</span>
                        <h2>Proximos objetivos</h2>
                    </div>
                    <small>{{ $logrosPendientes->count() }} por desbloquear</small>
                </div>

                <div class="achievement-grid">
                    @forelse ($logrosPendientes as $logro)
                        <article class="achievement-card reveal-item">
                            <div class="achievement-icon" aria-hidden="true">{{ strtoupper(substr($logro->nombre_logro, 0, 1)) }}</div>
                            <div class="achievement-card-copy">
                                <div class="achievement-card-meta">
                                    <span class="achievement-state">Bloqueado</span>
                                    <small>Pendiente</small>
                                </div>
                                <h3>{{ $logro->nombre_logro }}</h3>
                                <p>{{ $logro->descripcion }}</p>
                            </div>
                        </article>
                    @empty
                        <article class="achievement-card is-unlocked reveal-item">
                            <div class="achievement-icon" aria-hidden="true">OK</div>
                            <div class="achievement-card-copy">
                                <div class="achievement-card-meta">
                                    <span class="achievement-state">Completado</span>
                                </div>
                                <h3>Catalogo completado</h3>
                                <p>Has desbloqueado todos los logros disponibles. Sigue activo para futuros retos.</p>
                            </div>
                        </article>
                    @endforelse
                </div>
            </article>
        </section>
    </div>
</div>
@endsection
