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
            @if (Auth::user()->rol === 'creador')
                <a href="{{ route('vistas.crear-reto') }}" class="btn btn-primary home-btn">Crear reto</a>
            @else
                <a href="{{ route('vistas.subir-prueba') }}" class="btn btn-primary home-btn">Subir prueba</a>
            @endif
        </div>

        @if (session('status'))
            <div class="alert alert-success home-alert" role="alert">
                {{ session('status') }}
            </div>
        @endif

        <div class="screen-filters">
            <span class="home-chip">Todos</span>
            <span class="home-chip">Cerca</span>
            <span class="home-chip">Fotografia</span>
            <span class="home-chip">Cultura</span>
            <span class="home-chip">Comercio local</span>
        </div>

        <section class="challenge-grid">
            @forelse ($retos as $reto)
                @php
                    $coverClass = match ($reto->estado) {
                        'publicado' => 'challenge-cover-red',
                        'borrador' => 'challenge-cover-gold',
                        default => 'challenge-cover-dark',
                    };

                    $statusClass = match ($reto->estado) {
                        'publicado' => 'status-open',
                        'borrador' => 'status-draft',
                        default => 'status-rejected',
                    };
                @endphp

                <article class="challenge-card">
                    <div class="challenge-cover {{ $coverClass }}">
                        <span>+{{ $reto->puntos_recompensa }} pts</span>
                    </div>
                    <div class="challenge-body">
                        <span class="status-pill {{ $statusClass }}">{{ ucfirst($reto->estado) }}</span>
                        <h2>{{ $reto->nombre }}</h2>
                        <p>{{ \Illuminate\Support\Str::limit($reto->descripcion, 125) }}</p>
                        <div class="challenge-card-meta">
                            <span>{{ $reto->ubicacion_referencia ?? 'Ubicacion pendiente' }}</span>
                            <span>{{ optional($reto->fecha_fin)->format('d/m/Y') ?? 'Sin fecha fin' }}</span>
                            <span>{{ $reto->validaciones_verificadas_count }} validadas</span>
                        </div>
                        <span class="home-small-link">Disponible en el mapa</span>
                    </div>
                </article>
            @empty
                <div class="home-panel admin-empty">
                    No hay retos todavia. @if (Auth::user()->rol === 'creador') Puedes crear el primero desde "Crear reto". @endif
                </div>
            @endforelse
        </section>
    </div>
</div>
@endsection
