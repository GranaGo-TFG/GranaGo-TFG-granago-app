@extends('layouts.app')

@section('content')
<div class="screen-page">
    <div class="container">
        <div class="detail-layout">
            <section class="home-panel detail-main">
                <h1 class="home-kicker">Validacion</h1>
                <h2>Subir prueba del reto</h2>
                <p class="detail-copy">Anade la foto del reto para que pueda revisarse y contar en tu progreso.</p>

                <div class="upload-box">
                    <span>Foto del reto</span>
                    <strong>Arrastra una imagen o selecciona un archivo</strong>
                    <p>JPG o PNG. Mejor si se ve bien el lugar del reto.</p>
                </div>

                <label class="form-label mt-4">Comentario opcional</label>
                <textarea class="form-control prototype-input" rows="4" placeholder="Ejemplo: foto hecha al atardecer desde el mirador"></textarea>
            </section>

            <aside class="home-panel">
                <span class="home-kicker">Estado</span>
                <h2>Pendiente de revisar</h2>
                <p class="muted-copy">Cuando la prueba sea aceptada, los puntos se anadiran al perfil.</p>
                <button type="button" class="btn btn-primary home-btn w-100">Guardar prueba</button>
            </aside>
        </div>
    </div>
</div>
@endsection
