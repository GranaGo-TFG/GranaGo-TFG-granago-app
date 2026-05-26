@extends('layouts.app')

@section('content')
<div class="screen-page tienda-page">
    <div class="container">
        @if ($errors->any())
            <div class="alert alert-danger" role="alert">
                <strong>Revisa los datos del pago:</strong>
                <ul class="mb-0 mt-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="screen-head">
            <div>
                <h1 class="home-kicker">Pago</h1>
                <h2>Finalizar compra</h2>
                <p>Selecciona un metodo de pago y completa los datos correspondientes.</p>
            </div>
            <a href="{{ route('vistas.tienda.producto', $producto) }}" class="btn btn-primary home-btn">Volver al producto</a>
        </div>

        @php
            $metodoSeleccionado = old('metodo_pago', array_key_first($metodosPago));
        @endphp

        <section class="store-checkout-layout">
            <article class="home-panel store-checkout-panel">
                <form method="POST" action="{{ route('vistas.tienda.pago.store', $producto) }}" class="store-payment-form" id="store-checkout-form">
                    @csrf

                    <div class="store-payment-head">
                        <h3>Metodo de pago</h3>
                        <p>Cada opcion despliega su formulario especifico.</p>
                    </div>

                    <div class="store-payment-options">
                        @foreach ($metodosPago as $codigo => $metodo)
                            <label for="metodo-{{ $codigo }}" class="store-payment-option">
                                <input
                                    id="metodo-{{ $codigo }}"
                                    type="radio"
                                    name="metodo_pago"
                                    value="{{ $codigo }}"
                                    {{ $metodoSeleccionado === $codigo ? 'checked' : '' }}
                                >
                                <span class="store-payment-logo-wrap" aria-hidden="true">
                                    <img
                                        src="{{ asset('images/payment-methods/' . $codigo . '.svg') }}"
                                        alt=""
                                        class="store-payment-logo"
                                        loading="lazy"
                                    >
                                </span>
                                <span class="store-payment-copy">
                                    <strong>{{ $metodo['nombre'] }}</strong>
                                    <small>{{ $metodo['descripcion'] }}</small>
                                </span>
                            </label>
                        @endforeach
                    </div>

                    <div class="store-payment-method-sections" data-metodo-wrap>
                        <section class="store-payment-method-card" data-metodo-seccion="tarjeta">
                            <h4>Datos de tarjeta</h4>
                            <div class="store-checkout-grid">
                                <div>
                                    <label for="tarjeta_titular" class="form-label">Titular</label>
                                    <input type="text" id="tarjeta_titular" name="tarjeta_titular" value="{{ old('tarjeta_titular') }}" class="form-control" data-required>
                                </div>
                                <div>
                                    <label for="tarjeta_numero" class="form-label">Numero de tarjeta</label>
                                    <input type="text" id="tarjeta_numero" name="tarjeta_numero" value="{{ old('tarjeta_numero') }}" class="form-control" inputmode="numeric" maxlength="19" placeholder="1234123412341234" data-required>
                                </div>
                                <div>
                                    <label for="tarjeta_caducidad" class="form-label">Caducidad</label>
                                    <input type="text" id="tarjeta_caducidad" name="tarjeta_caducidad" value="{{ old('tarjeta_caducidad') }}" class="form-control" maxlength="5" placeholder="MM/AA" data-required>
                                </div>
                                <div>
                                    <label for="tarjeta_cvv" class="form-label">CVV</label>
                                    <input type="password" id="tarjeta_cvv" name="tarjeta_cvv" value="{{ old('tarjeta_cvv') }}" class="form-control" inputmode="numeric" maxlength="4" placeholder="123" data-required>
                                </div>
                            </div>
                        </section>

                        <section class="store-payment-method-card" data-metodo-seccion="bizum">
                            <h4>Datos de Bizum</h4>
                            <div class="store-checkout-grid">
                                <div>
                                    <label for="bizum_titular" class="form-label">Titular</label>
                                    <input type="text" id="bizum_titular" name="bizum_titular" value="{{ old('bizum_titular') }}" class="form-control" data-required>
                                </div>
                                <div>
                                    <label for="bizum_telefono" class="form-label">Telefono Bizum</label>
                                    <input type="text" id="bizum_telefono" name="bizum_telefono" value="{{ old('bizum_telefono') }}" class="form-control" inputmode="numeric" maxlength="9" placeholder="6XXXXXXXX" data-required>
                                </div>
                            </div>
                        </section>

                        <section class="store-payment-method-card" data-metodo-seccion="paypal">
                            <h4>Datos de PayPal</h4>
                            <div class="store-checkout-grid">
                                <div>
                                    <label for="paypal_email" class="form-label">Correo de PayPal</label>
                                    <input type="email" id="paypal_email" name="paypal_email" value="{{ old('paypal_email') }}" class="form-control" placeholder="correo@ejemplo.com" data-required>
                                </div>
                            </div>
                        </section>

                        <section class="store-payment-method-card" data-metodo-seccion="transferencia">
                            <h4>Datos de transferencia</h4>
                            <div class="store-checkout-grid">
                                <div>
                                    <label for="transferencia_titular" class="form-label">Titular</label>
                                    <input type="text" id="transferencia_titular" name="transferencia_titular" value="{{ old('transferencia_titular') }}" class="form-control" data-required>
                                </div>
                                <div>
                                    <label for="transferencia_banco" class="form-label">Banco</label>
                                    <input type="text" id="transferencia_banco" name="transferencia_banco" value="{{ old('transferencia_banco') }}" class="form-control" data-required>
                                </div>
                                <div class="full">
                                    <label for="transferencia_iban" class="form-label">IBAN</label>
                                    <input type="text" id="transferencia_iban" name="transferencia_iban" value="{{ old('transferencia_iban') }}" class="form-control" placeholder="ES7620770024003102575766" data-required>
                                </div>
                            </div>
                        </section>

                        <section class="store-payment-method-card" data-metodo-seccion="puntos">
                            <h4>Canje con puntos</h4>
                            <p class="store-points-copy mb-0">
                                Tu saldo actual es de <strong>{{ number_format($saldoPuntos, 0, ',', '.') }} pts</strong>.
                                Este producto cuesta <strong>{{ $producto->precio_puntos_formateado }}</strong> por unidad.
                            </p>
                        </section>
                    </div>

                    <div class="store-payment-actions">
                        <label for="cantidad" class="store-quantity-label">Cantidad</label>
                        <input
                            type="number"
                            id="cantidad"
                            name="cantidad"
                            min="1"
                            max="{{ $maximoCantidad }}"
                            value="{{ old('cantidad', 1) }}"
                            class="form-control"
                            required
                        >

                        <button
                            type="submit"
                            class="btn btn-primary home-btn"
                            @if ($producto->stock < 1)
                                disabled aria-disabled="true"
                            @endif
                        >
                            Comprar ahora
                        </button>
                    </div>

                    @if ($producto->stock < 1)
                        <p class="store-out-of-stock mb-0">Producto agotado temporalmente.</p>
                    @endif
                </form>
            </article>

            <aside class="home-panel store-checkout-summary">
                <h3>Resumen de compra</h3>
                <div class="store-checkout-summary-media">
                    @if ($producto->imagen_url)
                        <img src="{{ $producto->imagen_url }}" alt="Imagen del producto {{ $producto->nombre }}">
                    @else
                        <div class="store-card-image store-card-image-fallback" aria-hidden="true"></div>
                    @endif
                </div>

                <strong class="store-checkout-summary-title">{{ $producto->nombre }}</strong>
                <p class="store-checkout-summary-copy">{{ $producto->descripcion_corta }}</p>

                <dl class="store-detail-summary">
                    <div>
                        <dt>Precio unitario en euros</dt>
                        <dd>{{ $producto->precio_euros_formateado }}</dd>
                    </div>
                    <div>
                        <dt>Precio unitario en puntos</dt>
                        <dd>{{ $producto->precio_puntos_formateado }}</dd>
                    </div>
                    <div>
                        <dt>Stock</dt>
                        <dd>{{ $producto->stock }}</dd>
                    </div>
                    <div>
                        <dt>Categoria</dt>
                        <dd>{{ $producto->categoria }}</dd>
                    </div>
                </dl>
            </aside>
        </section>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    var radios = document.querySelectorAll('input[name="metodo_pago"]');
    var secciones = document.querySelectorAll('[data-metodo-seccion]');

    if (!radios.length || !secciones.length) {
        return;
    }

    var actualizarSecciones = function () {
        var radioSeleccionado = document.querySelector('input[name="metodo_pago"]:checked');
        var metodo = radioSeleccionado ? radioSeleccionado.value : '';

        secciones.forEach(function (seccion) {
            var activa = seccion.getAttribute('data-metodo-seccion') === metodo;
            seccion.classList.toggle('is-active', activa);

            seccion.querySelectorAll('[data-required]').forEach(function (input) {
                if (activa) {
                    input.setAttribute('required', 'required');
                } else {
                    input.removeAttribute('required');
                }
            });
        });
    };

    radios.forEach(function (radio) {
        radio.addEventListener('change', actualizarSecciones);
    });

    actualizarSecciones();
});
</script>
@endpush
