@extends('layouts.app')

@section('content')
<div class="screen-page">
    <div class="container">
        <div class="screen-head">
            <div>
                <h1 class="home-kicker">Retos</h1>
                <h2>Retos disponibles por Granada</h2>
                <p>Elige una prueba, sal a la calle y sube una foto cuando la completes.</p>
            </div>
            @if (Auth::check() && Auth::user()->rol === 'creador')
                <a href="{{ route('vistas.crear-reto') }}" class="btn btn-primary home-btn">Crear reto</a>
            @endif
        </div>

        @if (session('status'))
            <div class="alert alert-success home-alert" role="alert">
                {{ session('status') }}
            </div>
        @endif

        <section class="challenge-grid reveal-list">
                @forelse ($retos as $reto)
                @php
                    $statusClass = match ($reto->estado) {
                        'publicado' => 'status-open',
                        'borrador' => 'status-draft',
                        default => 'status-rejected',
                    };
                @endphp

                <article class="challenge-card reveal-item">
                    <div class="challenge-card-media">
                        @if ($reto->archivo_multimedia)
                            <img
                                src="{{ $reto->archivo_multimedia }}"
                                alt="Imagen del reto {{ $reto->nombre }}"
                                class="challenge-card-media-image"
                            >
                        @else
                            <div class="challenge-card-media-fallback" aria-hidden="true"></div>
                        @endif
                    </div>
                    <div class="challenge-body">
                        <span class="home-kicker">+{{ $reto->puntos_recompensa }} pts</span>
                        <span class="status-pill {{ $statusClass }}">{{ ucfirst($reto->estado) }}</span>
                        <h2>{{ $reto->nombre }}</h2>
                        <p>{{ \Illuminate\Support\Str::limit($reto->descripcion, 125) }}</p>
                        <div class="challenge-card-meta">
                            <span class="d-block">{{ $reto->ubicacion_referencia ?? 'Ubicacion pendiente' }}</span>
                            <span class="d-block">{{ optional($reto->fecha_fin)->format('d/m/Y') ?? 'Sin fecha fin' }}</span>
                            <span class="d-block">{{ $reto->validaciones_verificadas_count }} validadas</span>
                        </div>
                        <a href="{{ Auth::check() ? route('vistas.reto-detalle', $reto) : route('login') }}" class="status-pill home-small-link">Ver detalle</a>
                    </div>
                </article>
            @empty
                <div class="home-panel admin-empty">
                    No hay retos todavia. @if (Auth::check() && Auth::user()->rol === 'creador') Puedes crear el primero desde "Crear reto". @endif
                </div>
            @endforelse
        </section>
    </div>
</div>
@endsection
