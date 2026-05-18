@extends('layouts.app')

@section('content')
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
            <article class="ranking-row is-top"><span>1</span><strong>GranadaRunner</strong><em>420 pts</em></article>
            <article class="ranking-row"><span>2</span><strong>AlbaicinGo</strong><em>360 pts</em></article>
            <article class="ranking-row is-user"><span>3</span><strong>{{ Auth::user()->nombre }}</strong><em>120 pts</em></article>
            <article class="ranking-row"><span>4</span><strong>RutaCentro</strong><em>90 pts</em></article>
            <article class="ranking-row"><span>5</span><strong>FotoGranada</strong><em>70 pts</em></article>
        </section>
    </div>
</div>
@endsection
