@extends('layouts.app')

@section('content')
<div class="screen-page">
    <div class="container">
        <div class="screen-head">
            <div>
                <span class="home-kicker">Retos</span>
                <h1>Retos disponibles por Granada</h1>
                <p>Elige una prueba, sal a la calle y sube una foto cuando la completes.</p>
            </div>
        </div>

        <div class="screen-filters">
            <span class="home-chip">Todos</span>
            <span class="home-chip">Cerca</span>
            <span class="home-chip">Fotografia</span>
            <span class="home-chip">Cultura</span>
            <span class="home-chip">Comercio local</span>
        </div>

        <section class="challenge-grid">
            @forelse ($retos as $reto)
                <article class="challenge-card">
                    <div class="challenge-cover {{ $loop->index % 3 === 0 ? 'challenge-cover-red' : ($loop->index % 3 === 1 ? 'challenge-cover-gold' : 'challenge-cover-dark') }}">
                        <span>+{{ $reto->puntos_recompensa }} pts</span>
                    </div>
                    <div class="challenge-body">
                        <span class="status-pill {{ $reto->estado === 'publicado' ? 'status-open' : ($reto->estado === 'borrador' ? 'status-draft' : 'status-rejected') }}">
                            {{ ucfirst($reto->estado) }}
                        </span>
                        <h2>{{ $reto->nombre }}</h2>
                        <div class="challenge-meta">
                            <span>Inicio: {{ optional($reto->fecha_inicio)->format('d/m/Y') ?? 'Sin fecha' }}</span>
                            <span>Fin: {{ optional($reto->fecha_fin)->format('d/m/Y') ?? 'Sin fecha' }}</span>
                            <span>Puntuacion: {{ $reto->puntos_recompensa }} pts</span>
                        </div>
                        <a href="{{ route('vistas.reto-detalle', $reto) }}" class="btn btn-outline-secondary home-btn challenge-detail-btn">Ver detalles</a>
                    </div>
                </article>
            @empty
                <article class="challenge-card challenge-card-empty">
                    <div class="challenge-body">
                        <h2>No hay proyectos disponibles</h2>
                        <p>Cuando se publiquen retos nuevos apareceran aqui automaticamente.</p>
                    </div>
                </article>
            @endforelse
        </section>
    </div>
</div>
@endsection
