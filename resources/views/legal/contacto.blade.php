@extends('layouts.app')

@section('content')
<div class="legal-page">
    <div class="container">
        <section class="legal-hero home-panel">
            <span class="home-kicker">Contacto</span>
            <h1>Metodos de contacto</h1>
            <p>Canales previstos para soporte, privacidad, revision de contenido y dudas sobre la beta de GranaGO!.</p>
        </section>

        <section class="legal-contact-grid">
            <article class="home-panel legal-contact-card">
                <span>Soporte</span>
                <h2>Incidencias de cuenta</h2>
                <p>Problemas para entrar, editar el perfil, ver puntos, aparecer en el ranking o usar funciones generales.</p>
                <strong>soporte@granago.app</strong>
            </article>

            <article class="home-panel legal-contact-card">
                <span>Contenido</span>
                <h2>Retos y validaciones</h2>
                <p>Revision de retos, pruebas enviadas, imagenes subidas o publicaciones de comunidad.</p>
                <strong>contenido@granago.app</strong>
            </article>

            <article class="home-panel legal-contact-card">
                <span>Privacidad</span>
                <h2>Datos personales</h2>
                <p>Solicitudes relacionadas con datos de cuenta, imagenes, correcciones o eliminacion de informacion.</p>
                <strong>privacidad@granago.app</strong>
            </article>

            <article class="home-panel legal-contact-card">
                <span>Proyecto</span>
                <h2>Informacion general</h2>
                <p>Consultas sobre la idea de producto, colaboraciones, planes beta o futuras mejoras.</p>
                <strong>info@granago.app</strong>
            </article>
        </section>

        <section class="legal-content home-panel mt-3">
            <article>
                <h2>Tiempo de respuesta</h2>
                <p>Durante la beta, las solicitudes se gestionan manualmente por el equipo del proyecto. En una version comercial se añadiria un sistema de soporte con tiempos de respuesta definidos.</p>
            </article>

            <article>
                <h2>Informacion util al contactar</h2>
                <p>Para resolver una incidencia conviene indicar el correo de la cuenta, la pantalla afectada, el reto o validacion relacionada y una descripcion breve. Nunca se debe enviar la contrasena.</p>
            </article>
        </section>
    </div>
</div>
@endsection
