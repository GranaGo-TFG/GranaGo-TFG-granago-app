@extends('layouts.app')

@section('content')
@php
    $modoAdmin = $modoAdmin ?? false;
    $detalleRouteName = $detalleRouteName ?? 'vistas.tienda.producto';
    $crearRouteName = $crearRouteName ?? null;
@endphp

<div class="screen-page tienda-page">
    <div class="container">
        <div class="screen-head">
            <div>
                <h1 class="home-kicker">Tienda</h1>
                @if ($modoAdmin)
                    <h2>Gestion de productos de tienda</h2>
                    <p>Vista catalogo para administracion. Abre cada detalle para editar o eliminar productos.</p>
                @else
                    <h2>Recompensas y mejoras para tu aventura</h2>
                    <p>Explora el catalogo, descubre los productos mas vendidos y entra al detalle para completar tu compra con el metodo que prefieras.</p>
                @endif
            </div>

            @if ($modoAdmin && $crearRouteName)
                <a href="{{ route($crearRouteName) }}" class="btn btn-primary home-btn">Crear producto</a>
            @endif
        </div>

        @if ($masVendidos->isNotEmpty())
            <section class="store-carousel-shell home-panel p-0 overflow-hidden" aria-label="Carrusel de productos mas vendidos">
                <div class="store-carousel-head">
                    <div>
                        <span class="home-kicker mb-2">Top ventas</span>
                        <h3>Productos mas vendidos</h3>
                    </div>
                </div>

                <div id="storeTopCarousel" class="carousel slide store-carousel" data-bs-ride="carousel">
                    <div class="carousel-indicators">
                        @foreach ($masVendidos as $index => $producto)
                            <button
                                type="button"
                                data-bs-target="#storeTopCarousel"
                                data-bs-slide-to="{{ $index }}"
                                class="{{ $index === 0 ? 'active' : '' }}"
                                aria-current="{{ $index === 0 ? 'true' : 'false' }}"
                                aria-label="Producto {{ $index + 1 }}"
                            ></button>
                        @endforeach
                    </div>

                    <div class="carousel-inner">
                        @foreach ($masVendidos as $index => $producto)
                            <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                <article class="store-carousel-item">
                                    @if ($producto->imagen_url)
                                        <img src="{{ $producto->imagen_url }}" alt="Imagen del producto {{ $producto->nombre }}" class="store-carousel-image">
                                    @else
                                        <div class="store-carousel-image store-carousel-image-fallback" aria-hidden="true"></div>
                                    @endif

                                    <div class="store-carousel-overlay"></div>

                                    <div class="store-carousel-copy">
                                        <span class="store-chip">Top #{{ $index + 1 }}</span>
                                        <h3>{{ $producto->nombre }}</h3>
                                        <p>{{ $producto->descripcion_corta }}</p>
                                        <div class="store-carousel-meta">
                                            <strong>{{ $producto->precio_euros_formateado }}</strong>
                                            <span class="store-price-alt">{{ $producto->precio_puntos_formateado }}</span>
                                            <span>{{ $producto->vendidos_total }} vendidos</span>
                                        </div>
                                        <a href="{{ route($detalleRouteName, $producto) }}" class="btn btn-primary home-btn">Ver producto</a>
                                    </div>
                                </article>
                            </div>
                        @endforeach
                    </div>

                    @if ($masVendidos->count() > 1)
                        <button class="carousel-control-prev" type="button" data-bs-target="#storeTopCarousel" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Anterior</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#storeTopCarousel" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Siguiente</span>
                        </button>
                    @endif
                </div>
            </section>
        @endif

        <section class="store-grid" aria-label="Catalogo de productos">
            @forelse ($productos as $producto)
                <article class="store-card">
                    <a href="{{ route($detalleRouteName, $producto) }}" class="store-card-media">
                        @if ($producto->imagen_url)
                            <img src="{{ $producto->imagen_url }}" alt="Imagen del producto {{ $producto->nombre }}" class="store-card-image">
                        @else
                            <div class="store-card-image store-card-image-fallback" aria-hidden="true"></div>
                        @endif
                    </a>

                    <div class="store-card-body">
                        <span class="store-chip">{{ $producto->categoria }}</span>
                        @if ($modoAdmin)
                            <span class="status-pill {{ $producto->activo ? 'status-open' : 'status-rejected' }}">{{ $producto->activo ? 'Activo' : 'Retirado' }}</span>
                        @endif
                        <h3>
                            <a href="{{ route($detalleRouteName, $producto) }}">{{ $producto->nombre }}</a>
                        </h3>
                        <p>{{ \Illuminate\Support\Str::limit($producto->descripcion_corta, 118) }}</p>
                        <div class="store-card-footer">
                            <div>
                                <strong class="store-price">{{ $producto->precio_euros_formateado }}</strong>
                                <small class="store-price-alt">{{ $producto->precio_puntos_formateado }}</small>
                                <small>{{ $producto->vendidos_total }} vendidos</small>
                            </div>
                            <a href="{{ route($detalleRouteName, $producto) }}" class="btn btn-outline-secondary home-btn">Detalles</a>
                        </div>
                    </div>
                </article>
            @empty
                <article class="home-panel admin-empty">
                    No hay productos disponibles por ahora. Ejecuta el seeder para poblar el catalogo.
                </article>
            @endforelse
        </section>

        <div class="store-pagination">
            {{ $productos->onEachSide(1)->links('vendor.pagination.store-bootstrap-5') }}
        </div>
    </div>
</div>
@endsection
