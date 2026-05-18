@extends('layouts.app')

@section('content')
@php
    $usuariosRanking = \App\Models\User::query()
        ->select(['id', 'nombre', 'rol', 'puntos_totales'])
        ->where('rol', 'usuario')
        ->where('esta_baneado', false)
        ->orderByDesc('puntos_totales')
        ->orderBy('nombre')
        ->get();
@endphp

<div class="screen-page">
    <div class="container">
        <div class="screen-head">
            <div>
                <span class="home-kicker">Ranking</span>
                <h1>Clasificacion local</h1>
                <p>Puntos ganados al validar retos. La racha multiplica el progreso del usuario.</p>
            </div>
        </div>

        <section class="ranking-board">
            @if ($usuariosRanking->isEmpty())
                <article class="ranking-row">
                    <strong>No hay usuarios en el ranking todavia.</strong>
                    <em>0 pts</em>
                </article>
            @endif

            @foreach ($usuariosRanking as $usuario)
                <article class="ranking-row {{ $loop->first ? 'is-top' : '' }} {{ Auth::id() === $usuario->id ? 'is-user' : '' }}">
                    <span>{{ $loop->iteration }}</span>
                    <strong>{{ $usuario->nombre }}</strong>
                    <em>{{ $usuario->puntos_totales }} pts</em>
                </article>
            @endforeach
        </section>
    </div>
</div>
@endsection
