@extends('layouts.app')

@section('content')
<div class="legal-page">
    <div class="container">
        <section class="legal-hero home-panel">
            <span class="home-kicker">Legal</span>
            <h1>Aviso legal</h1>
            <p>Condiciones basicas para usar GranaGO! durante su fase beta.</p>
        </section>

        <section class="legal-content home-panel">
            <article>
                <h2>Que es GranaGO!</h2>
                <p>GranaGO! es una aplicacion para descubrir Granada mediante retos urbanos. Los usuarios pueden explorar retos, subir pruebas fotograficas, ganar puntos, consultar el ranking, participar en comunidad y desbloquear contenido asociado a los lugares.</p>
            </article>

            <article>
                <h2>Uso responsable</h2>
                <p>Los retos deben realizarse respetando la ciudad, las normas de circulacion, los espacios publicos y la privacidad de otras personas. La app no obliga a entrar en zonas privadas, asumir riesgos ni molestar a terceros para completar un reto.</p>
            </article>

            <article>
                <h2>Retos creados por usuarios</h2>
                <p>Los usuarios con rol creador pueden proponer retos. Estos retos empiezan en borrador y deben ser revisados por un administrador antes de publicarse. Un reto puede ser rechazado si no encaja con la app, es inseguro o contiene informacion incorrecta.</p>
            </article>

            <article>
                <h2>Validaciones y puntos</h2>
                <p>Las pruebas enviadas por los usuarios empiezan en estado pendiente. Un administrador puede verificarlas o rechazarlas. Los puntos se suman cuando la prueba queda verificada, usando la recompensa del reto y las reglas de puntuacion configuradas.</p>
            </article>

            <article>
                <h2>Comunidad</h2>
                <p>La comunidad esta pensada para compartir contenido relacionado con la experiencia en la app. No se permite publicar contenido ofensivo, ilegal, engañoso, spam o informacion personal de terceros.</p>
            </article>

            <article>
                <h2>Tienda, recompensas y planes</h2>
                <p>La tienda, los productos y los planes forman parte de la experiencia prevista para la beta. Si en una version futura se activan pagos reales, se añadiran condiciones especificas sobre precios, compras, cancelaciones y soporte.</p>
            </article>

            <article>
                <h2>Contenido propio o autorizado</h2>
                <p>Las fotos, textos, retos y publicaciones deben ser propios o contar con permiso para usarse. Si se detecta contenido no autorizado, el equipo puede retirarlo o limitar el acceso del usuario responsable.</p>
            </article>

            <article>
                <h2>Beta y limitaciones</h2>
                <p>GranaGO! esta en fase beta. Algunas funciones pueden cambiar, estar en pruebas o no estar disponibles de forma definitiva. La version final requerira una revision tecnica, legal y de seguridad completa.</p>
            </article>
        </section>
    </div>
</div>
@endsection
