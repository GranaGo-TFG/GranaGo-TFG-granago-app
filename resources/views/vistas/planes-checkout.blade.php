@extends('layouts.app')

@section('content')
<div class="screen-page tienda-page">
    <div class="container">
        @if (session('status'))
            <div class="alert alert-success" role="alert">
                {{ session('status') }}
            </div>
        @endif

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
                <h1 class="home-kicker">Pago de plan</h1>
                <h2>Activar {{ $plan['nombre'] }}</h2>
                <p>Selecciona un metodo de pago y completa los datos para activar tu suscripcion.</p>
            </div>
            <a href="{{ route('vistas.planes') }}" class="btn btn-primary home-btn">Volver a planes</a>
        </div>

        @php
            $metodoSeleccionado = old('metodo_pago', array_key_first($metodosPago));
        @endphp

        <section class="store-checkout-layout">
            <article class="home-panel store-checkout-panel">
                <form method="POST" action="{{ route('vistas.planes.pago.store', $plan['slug']) }}" class="store-payment-form" id="plan-checkout-form">
                    @csrf

                    <div class="store-payment-head">
                        <h3>Metodo de pago</h3>
                        <p>Este flujo no permite canje con puntos, solo pagos monetarios.</p>
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
                                        src="{{ asset('images/metodos-pago/' . $codigo . '.svg') }}"
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
                    </div>

                    <div class="store-payment-actions">
                        <button type="submit" class="btn btn-primary home-btn">Confirmar {{ $plan['nombre'] }}</button>
                    </div>
                </form>
            </article>

            <aside class="home-panel store-checkout-summary">
                <h3>Resumen del plan</h3>

                <strong class="store-checkout-summary-title">{{ $plan['nombre'] }}</strong>
                <p class="store-checkout-summary-copy">{{ $plan['descripcion'] }}</p>

                <dl class="store-detail-summary">
                    <div>
                        <dt>Precio</dt>
                        <dd>{{ $plan['precio'] }}€ {{ $plan['periodo'] }}</dd>
                    </div>
                    <div>
                        <dt>Tipo de cobro</dt>
                        <dd>Suscripcion mensual</dd>
                    </div>
                    <div>
                        <dt>Metodo por puntos</dt>
                        <dd>No disponible en planes</dd>
                    </div>
                </dl>

                <h4 class="mt-4">Incluye</h4>
                <ul class="pricing-feature-list mb-0">
                    @foreach ($plan['beneficios'] as $beneficio)
                        <li>{{ $beneficio }}</li>
                    @endforeach
                </ul>
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
