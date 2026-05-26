@extends('layouts.app')

@section('content')
@php
    $modoAdmin = $modoAdmin ?? false;
    $detalleRouteName = $detalleRouteName ?? 'vistas.tienda.producto';
    $backRouteName = $backRouteName ?? 'vistas.tienda';
@endphp

<div class="screen-page tienda-page">
    <div class="container">
        @if (session('status'))
            <div class="alert alert-success home-alert" role="alert">
                {{ session('status') }}
            </div>
        @endif

        <div class="screen-head">
            <div>
                <h1 class="home-kicker">Detalle de producto</h1>
                <h2>{{ $producto->nombre }}</h2>
                <p>{{ $producto->descripcion_corta }}</p>
            </div>
            <a href="{{ route($backRouteName) }}" class="btn btn-primary home-btn">Volver a tienda</a>
        </div>

        <section class="store-detail-layout">
            <article class="store-detail-media">
                @if ($producto->imagen_url)
                    <img src="{{ $producto->imagen_url }}" alt="Imagen del producto {{ $producto->nombre }}">
                @else
                    <div class="store-card-image store-card-image-fallback" aria-hidden="true"></div>
                @endif
            </article>

            <article class="home-panel store-detail-panel">
                <span class="store-chip">{{ $producto->categoria }}</span>
                <p class="store-detail-description">{{ $producto->descripcion }}</p>

                <dl class="store-detail-summary">
                    <div>
                        <dt>Precio en euros</dt>
                        <dd>{{ $producto->precio_euros_formateado }}</dd>
                    </div>
                    <div>
                        <dt>Precio en puntos</dt>
                        <dd>{{ $producto->precio_puntos_formateado }}</dd>
                    </div>
                    <div>
                        <dt>Stock disponible</dt>
                        <dd>{{ $producto->stock }} unidades</dd>
                    </div>
                    <div>
                        <dt>Ventas acumuladas</dt>
                        <dd>{{ $producto->vendidos_total }}</dd>
                    </div>
                </dl>

                <div class="store-purchase-cta">
                    @if ($modoAdmin)
                        <p class="store-purchase-note">Opciones de administracion del producto.</p>

                        <form method="POST" action="{{ route('admin.productos.update', $producto) }}" class="store-admin-edit-form">
                            @csrf
                            @method('PATCH')

                            <div class="store-checkout-grid">
                                <div>
                                    <label for="nombre-{{ $producto->id }}" class="form-label">Nombre</label>
                                    <input type="text" id="nombre-{{ $producto->id }}" name="nombre" class="form-control" value="{{ old('nombre', $producto->nombre) }}" required>
                                </div>
                                <div>
                                    <label for="categoria-{{ $producto->id }}" class="form-label">Categoria</label>
                                    <input type="text" id="categoria-{{ $producto->id }}" name="categoria" class="form-control" value="{{ old('categoria', $producto->categoria) }}" required>
                                </div>
                                <div>
                                    <label for="precio-{{ $producto->id }}" class="form-label">Precio EUR</label>
                                    <input type="number" id="precio-{{ $producto->id }}" name="precio" class="form-control" min="0" max="25" step="0.01" value="{{ old('precio', number_format((float) $producto->precio, 2, '.', '')) }}" required>
                                </div>
                                <div>
                                    <label for="precio-puntos-{{ $producto->id }}" class="form-label">Precio puntos</label>
                                    <input type="number" id="precio-puntos-{{ $producto->id }}" name="precio_puntos" class="form-control" min="100" value="{{ old('precio_puntos', $producto->precio_puntos_valor) }}" required>
                                </div>
                                <div>
                                    <label for="stock-{{ $producto->id }}" class="form-label">Stock</label>
                                    <input type="number" id="stock-{{ $producto->id }}" name="stock" class="form-control" min="0" value="{{ old('stock', $producto->stock) }}" required>
                                </div>
                                <div>
                                    <label for="activo-{{ $producto->id }}" class="form-label">Estado</label>
                                    <select id="activo-{{ $producto->id }}" name="activo" class="form-select" required>
                                        <option value="1" @selected((int) old('activo', $producto->activo ? 1 : 0) === 1)>Activo</option>
                                        <option value="0" @selected((int) old('activo', $producto->activo ? 1 : 0) === 0)>Retirado</option>
                                    </select>
                                </div>
                                <div class="full">
                                    <label for="descripcion-corta-{{ $producto->id }}" class="form-label">Descripcion corta</label>
                                    <input type="text" id="descripcion-corta-{{ $producto->id }}" name="descripcion_corta" class="form-control" maxlength="180" value="{{ old('descripcion_corta', $producto->descripcion_corta) }}" required>
                                </div>
                                <div class="full">
                                    <label for="descripcion-{{ $producto->id }}" class="form-label">Descripcion</label>
                                    <textarea id="descripcion-{{ $producto->id }}" name="descripcion" class="form-control" rows="4" required>{{ old('descripcion', $producto->descripcion) }}</textarea>
                                </div>
                                <div class="full">
                                    <label for="imagen-{{ $producto->id }}" class="form-label">URL imagen</label>
                                    <input type="url" id="imagen-{{ $producto->id }}" name="imagen_url" class="form-control" value="{{ old('imagen_url', $producto->imagen_url) }}">
                                </div>
                            </div>

                            <div class="d-flex justify-content-end mt-2">
                                <button type="submit" class="btn btn-primary home-btn">Guardar cambios</button>
                            </div>
                        </form>

                        <form method="POST" action="{{ route('admin.productos.destroy', $producto) }}" class="d-flex justify-content-end mt-2" onsubmit="return confirm('Se eliminara este producto. ¿Continuar?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-secondary home-btn">Eliminar producto</button>
                        </form>
                    @else
                        <p class="store-purchase-note">Pulsa comprar para completar los datos de pago en el siguiente paso.</p>

                        @if ($producto->stock > 0)
                            <a href="{{ route('vistas.tienda.pago', $producto) }}" class="btn btn-primary home-btn w-100">Comprar</a>
                        @else
                            <button type="button" class="btn btn-primary home-btn w-100" disabled aria-disabled="true">Comprar</button>
                            <p class="store-out-of-stock mb-0">Producto agotado temporalmente.</p>
                        @endif
                    @endif
                </div>
            </article>
        </section>

        @if ($relacionados->isNotEmpty())
            <section class="store-related-section">
                <div class="screen-head">
                    <div>
                        <h1 class="home-kicker">Tambien te puede interesar</h1>
                        <h2>Productos relacionados</h2>
                    </div>
                </div>

                <div class="store-related-grid">
                    @foreach ($relacionados as $relacionado)
                        <article class="store-related-card">
                            <a href="{{ route($detalleRouteName, $relacionado) }}" class="store-card-media">
                                @if ($relacionado->imagen_url)
                                    <img src="{{ $relacionado->imagen_url }}" alt="Imagen del producto {{ $relacionado->nombre }}" class="store-card-image">
                                @else
                                    <div class="store-card-image store-card-image-fallback" aria-hidden="true"></div>
                                @endif
                            </a>
                            <div class="store-related-card-body">
                                <span class="store-chip">{{ $relacionado->categoria }}</span>
                                <h3>{{ $relacionado->nombre }}</h3>
                                <p>{{ \Illuminate\Support\Str::limit($relacionado->descripcion_corta, 95) }}</p>
                                <a href="{{ route($detalleRouteName, $relacionado) }}" class="btn btn-outline-secondary home-btn">Ver detalle</a>
                            </div>
                        </article>
                    @endforeach
                </div>
            </section>
        @endif
    </div>
</div>
@endsection
