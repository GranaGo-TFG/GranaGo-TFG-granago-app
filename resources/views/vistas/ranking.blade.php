@extends('layouts.app')

@section('content')
@php
    $usuariosRanking = \App\Models\User::query()
        ->select(['id', 'nombre', 'rol', 'puntos_totales'])
        ->where('rol', '!=', 'admin')
        ->where('esta_baneado', false)
        ->orderByDesc('puntos_totales')
        ->orderBy('nombre')
        ->get();
    $liderRanking = $usuariosRanking->first();
    $topRanking = $usuariosRanking->take(3);
    $usuarioActual = $usuariosRanking->firstWhere('id', Auth::id());
    $posicionActual = $usuariosRanking->search(fn ($usuario) => $usuario->id === Auth::id());
    $puntosMaximos = max((int) ($liderRanking->puntos_totales ?? 0), 1);
@endphp

<div class="screen-page ranking-page">
    <div class="container">
        <div class="screen-head ranking-head">
            <div>
                <h1 class="home-kicker">Ranking</h1>
                <h2>Clasificacion local</h2>
                <p>Usuarios ordenados por los puntos conseguidos al validar retos.</p>
            </div>
        </div>
        <br>
        <section class="ranking-spotlights">
            
        </section>
        <section class="ranking-spotlights">
            <article class="ranking-spotlight ranking-spotlight-user">
                <div>
                    <span class="ranking-label">Tu puesto</span>
                    <h2>{{ $posicionActual === false ? $usuariosRanking->count() : $posicionActual + 1 }}</h2>
                    <p>Posicion actual dentro de la clasificacion general.</p>
                    <em>Tienes {{ $usuarioActual->puntos_totales ?? 0 }} pts</em>
                </div>
            </article>
        </section>
        <br>
        <section class="ranking-top">
            @forelse ($topRanking as $usuario)
                <article class="ranking-top-card ranking-top-card-{{ $loop->iteration }}">
                    <span>{{ $loop->iteration }}</span>
                    <div>
                        <strong>{{ $usuario->nombre }}</strong>
                        <em>{{ $usuario->puntos_totales }} pts</em>
                    </div>
                </article>
            @empty
                <article class="ranking-top-card">
                    <span>-</span>
                    <div>
                        <strong>Sin ranking</strong>
                        <em>0 pts</em>
                    </div>
                </article>
            @endforelse
        </section>
        <br>
        <section class="ranking-board">
            <div class="ranking-board-title">
                <div>
                    <span class="ranking-label">Listado</span>
                    <h2>Clasificacion completa</h2>
                </div>
                <small>{{ $usuariosRanking->count() }} usuarios</small>
            </div>

            @if ($usuariosRanking->isEmpty())
                <article class="ranking-row">
                    <strong>No hay usuarios en el ranking todavia.</strong>
                    <em>0 pts</em>
                </article>
            @endif

            @foreach ($usuariosRanking as $usuario)
                <article class="ranking-row {{ $loop->first ? 'is-top' : '' }} {{ Auth::id() === $usuario->id ? 'is-user' : '' }}">
                    <span title="Posicion {{ $loop->iteration }}" aria-label="Posicion {{ $loop->iteration }}">
                        {{ $loop->iteration }}
                    </span>
                    <div>
                        <strong>Nombre: {{ $usuario->nombre }}</strong>
                        <div class="ranking-progress">
                            <i style="width: {{ max(6, ((int) $usuario->puntos_totales / $puntosMaximos) * 100) }}%"></i>
                        </div>
                    </div>
                    <em>Puntos totales: {{ $usuario->puntos_totales }} pts</em>
                </article>
            @endforeach
        </section>
    </div>
</div>
@endsection
