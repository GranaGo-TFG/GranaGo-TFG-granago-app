@extends('layouts.app')

@section('content')
<div class="screen-page">
    <div class="container">
        <div class="screen-head">
            <div>
                <h1 class="home-kicker">Revision</h1>
                <h2>Validaciones pendientes</h2>
                <p>Revisa las pruebas enviadas y decide si suman puntos al usuario.</p>
            </div>
        </div>

        <section class="validation-list">
            <article class="validation-row">
                <div>
                    <strong>Foto en el Mirador de San Nicolas</strong>
                    <p>{{ Auth::user()->nombre }} envio una prueba hace unos minutos.</p>
                </div>
                <span class="status-pill status-pending">Pendiente</span>
            </article>
            <article class="validation-row">
                <div>
                    <strong>Ruta de arte urbano</strong>
                    <p>La imagen se ve clara y coincide con el reto.</p>
                </div>
                <span class="status-pill status-open">Verificado</span>
            </article>
            <article class="validation-row">
                <div>
                    <strong>Comercio de barrio</strong>
                    <p>No se reconoce el comercio en la foto subida.</p>
                </div>
                <span class="status-pill status-rejected">Rechazado</span>
            </article>
        </section>
    </div>
</div>
@endsection
