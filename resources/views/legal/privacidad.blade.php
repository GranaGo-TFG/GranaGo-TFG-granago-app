@extends('layouts.app')

@section('content')
<div class="legal-page">
    <div class="container">
        <section class="legal-hero home-panel">
            <span class="home-kicker">Legal</span>
            <h1>Politica de privacidad</h1>
            <p>GranaGO! utiliza datos basicos para que puedas crear una cuenta, participar en retos, subir pruebas, aparecer en el ranking y usar la comunidad.</p>
        </section>

        <section class="legal-content home-panel">
            <article>
                <h2>Datos de cuenta</h2>
                <p>Guardamos datos necesarios para identificar tu cuenta: nombre, correo electronico, contrasena cifrada, rol dentro de la app, puntos, racha, estado de baneo y datos de perfil. Estos datos permiten iniciar sesion, diferenciar entre usuario, creador y administrador, y mostrar correctamente tu actividad.</p>
            </article>

            <article>
                <h2>Retos, ranking y actividad</h2>
                <p>Cuando participas en GranaGO! se registran los retos que creas o completas, las pruebas enviadas, su estado de revision, los puntos obtenidos y tu posicion en el ranking. Esta informacion forma parte del funcionamiento principal de la aplicacion.</p>
            </article>

            <article>
                <h2>Fotos de validacion</h2>
                <p>Las fotos que subes como prueba se usan para verificar si has completado un reto. Deben estar relacionadas con el reto y evitar mostrar informacion sensible, documentos, matriculas o personas reconocibles sin permiso.</p>
            </article>

            <article>
                <h2>Comunidad y contenido</h2>
                <p>Si usas la comunidad, podemos guardar publicaciones, comentarios e interacciones. Este contenido se muestra dentro de la app y puede ser revisado si incumple las normas de uso.</p>
            </article>

            <article>
                <h2>Revision y administracion</h2>
                <p>Los administradores pueden revisar retos, validaciones, usuarios y contenido de comunidad. Esta revision permite publicar o rechazar retos, aceptar o rechazar pruebas, gestionar usuarios baneados y mantener la app ordenada.</p>
            </article>

            <article>
                <h2>Tienda y planes</h2>
                <p>La app puede mostrar productos, recompensas o planes beta. Si se activan compras reales en el futuro, se deberian incorporar condiciones especificas de pago, facturacion y devoluciones.</p>
            </article>

            <article>
                <h2>Tus derechos</h2>
                <p>Puedes solicitar la revision, correccion o eliminacion de datos asociados a tu cuenta desde la pagina de contacto. En esta beta las solicitudes se gestionan manualmente por el equipo responsable del proyecto.</p>
            </article>

            <article>
                <h2>Estado beta</h2>
                <p>GranaGO! se encuentra en fase beta. Esta politica resume el tratamiento previsto para el prototipo y deberia revisarse legalmente antes de una publicacion comercial definitiva.</p>
            </article>
        </section>
    </div>
</div>
@endsection
