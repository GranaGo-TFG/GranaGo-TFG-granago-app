@extends('layouts.app')

@section('content')
<div class="screen-page">
    <div class="container">
        <div class="screen-head">
            <div>
                <h1 class="home-kicker">Gestion de tienda</h1>
                <p>Solo administradores pueden crear, modificar o retirar productos del catalogo.</p>
            </div>
        </div>

        @if (session('status'))
            <div class="alert alert-success home-alert" role="alert">
                {{ session('status') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger" role="alert">
                {{ $errors->first() }}
            </div>
        @endif

        <section class="home-panel mb-3">
            <div class="admin-row-top mb-2">
                <span class="status-pill status-open">Nuevo producto</span>
            </div>

            <form method="POST" action="{{ route('admin.productos.store') }}" class="row g-3">
                @csrf

                <div class="col-md-6">
                    <label for="crear-nombre" class="form-label">Nombre</label>
                    <input type="text" id="crear-nombre" name="nombre" class="form-control" value="{{ old('nombre') }}" required>
                </div>

                <div class="col-md-3">
                    <label for="crear-categoria" class="form-label">Categoria</label>
                    <input type="text" id="crear-categoria" name="categoria" class="form-control" value="{{ old('categoria') }}" required>
                </div>

                <div class="col-md-3">
                    <label for="crear-stock" class="form-label">Stock</label>
                    <input type="number" id="crear-stock" name="stock" class="form-control" value="{{ old('stock', 0) }}" min="0" required>
                </div>

                <div class="col-md-3">
                    <label for="crear-precio" class="form-label">Precio EUR (max 25)</label>
                    <input type="number" id="crear-precio" name="precio" class="form-control" value="{{ old('precio', 1) }}" min="0" max="25" step="0.01" required>
                </div>

                <div class="col-md-3">
                    <label for="crear-precio-puntos" class="form-label">Precio puntos</label>
                    <input type="number" id="crear-precio-puntos" name="precio_puntos" class="form-control" value="{{ old('precio_puntos', 100) }}" min="100" required>
                </div>

                <div class="col-md-6">
                    <label for="crear-imagen" class="form-label">URL imagen (opcional)</label>
                    <input type="url" id="crear-imagen" name="imagen_url" class="form-control" value="{{ old('imagen_url') }}">
                </div>

                <div class="col-md-3">
                    <label for="crear-activo" class="form-label">Estado</label>
                    <select id="crear-activo" name="activo" class="form-select" required>
                        <option value="1" @selected(old('activo', '1') === '1')>Activo</option>
                        <option value="0" @selected(old('activo') === '0')>Retirado</option>
                    </select>
                </div>

                <div class="col-12">
                    <label for="crear-descripcion-corta" class="form-label">Descripcion corta</label>
                    <input type="text" id="crear-descripcion-corta" name="descripcion_corta" class="form-control" maxlength="180" value="{{ old('descripcion_corta') }}" required>
                </div>

                <div class="col-12">
                    <label for="crear-descripcion" class="form-label">Descripcion completa</label>
                    <textarea id="crear-descripcion" name="descripcion" class="form-control" rows="3" required>{{ old('descripcion') }}</textarea>
                </div>

                <div class="col-12 d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary home-btn">Crear producto</button>
                </div>
            </form>
        </section>

        <div class="admin-list">
            @forelse ($productos as $producto)
                <article class="home-panel admin-row">
                    <div class="admin-row-main">
                        <div class="admin-row-top">
                            <span class="status-pill status-{{ $producto->activo ? 'open' : 'rejected' }}">
                                {{ $producto->activo ? 'Activo' : 'Retirado' }}
                            </span>
                            <span class="admin-row-meta">{{ $producto->precio_euros_formateado }}</span>
                            <span class="admin-row-meta">{{ $producto->precio_puntos_formateado }}</span>
                        </div>
                        <h2>{{ $producto->nombre }}</h2>
                        <p>{{ $producto->descripcion_corta }}</p>
                        <div class="admin-row-meta-wrap">
                            <span>Slug: {{ $producto->slug }}</span>
                            <span>Categoria: {{ $producto->categoria }}</span>
                            <span>Stock: {{ $producto->stock }}</span>
                            <span>Vendidos: {{ $producto->vendidos_total }}</span>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('admin.productos.update', $producto) }}" class="row g-2 mt-2">
                        @csrf
                        @method('PATCH')

                        <div class="col-md-6">
                            <label for="nombre-{{ $producto->id }}" class="form-label">Nombre</label>
                            <input type="text" id="nombre-{{ $producto->id }}" name="nombre" class="form-control" value="{{ $producto->nombre }}" required>
                        </div>

                        <div class="col-md-3">
                            <label for="categoria-{{ $producto->id }}" class="form-label">Categoria</label>
                            <input type="text" id="categoria-{{ $producto->id }}" name="categoria" class="form-control" value="{{ $producto->categoria }}" required>
                        </div>

                        <div class="col-md-3">
                            <label for="stock-{{ $producto->id }}" class="form-label">Stock</label>
                            <input type="number" id="stock-{{ $producto->id }}" name="stock" class="form-control" min="0" value="{{ $producto->stock }}" required>
                        </div>

                        <div class="col-md-3">
                            <label for="precio-{{ $producto->id }}" class="form-label">Precio EUR</label>
                            <input type="number" id="precio-{{ $producto->id }}" name="precio" class="form-control" min="0" max="25" step="0.01" value="{{ number_format((float) $producto->precio, 2, '.', '') }}" required>
                        </div>

                        <div class="col-md-3">
                            <label for="precio-puntos-{{ $producto->id }}" class="form-label">Precio puntos</label>
                            <input type="number" id="precio-puntos-{{ $producto->id }}" name="precio_puntos" class="form-control" min="100" value="{{ $producto->precio_puntos_valor }}" required>
                        </div>

                        <div class="col-md-6">
                            <label for="imagen-{{ $producto->id }}" class="form-label">URL imagen</label>
                            <input type="url" id="imagen-{{ $producto->id }}" name="imagen_url" class="form-control" value="{{ $producto->imagen_url }}">
                        </div>

                        <div class="col-md-3">
                            <label for="activo-{{ $producto->id }}" class="form-label">Estado</label>
                            <select id="activo-{{ $producto->id }}" name="activo" class="form-select" required>
                                <option value="1" @selected($producto->activo)>Activo</option>
                                <option value="0" @selected(! $producto->activo)>Retirado</option>
                            </select>
                        </div>

                        <div class="col-12">
                            <label for="descripcion-corta-{{ $producto->id }}" class="form-label">Descripcion corta</label>
                            <input type="text" id="descripcion-corta-{{ $producto->id }}" name="descripcion_corta" class="form-control" maxlength="180" value="{{ $producto->descripcion_corta }}" required>
                        </div>

                        <div class="col-12">
                            <label for="descripcion-{{ $producto->id }}" class="form-label">Descripcion</label>
                            <textarea id="descripcion-{{ $producto->id }}" name="descripcion" class="form-control" rows="3" required>{{ $producto->descripcion }}</textarea>
                        </div>

                        <div class="col-12 d-flex flex-wrap justify-content-end gap-2">
                            <button type="submit" class="btn btn-primary home-btn">Guardar cambios</button>
                        </div>
                    </form>

                    <form method="POST" action="{{ route('admin.productos.disponibilidad', $producto) }}" class="d-flex justify-content-end mt-2">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="activo" value="{{ $producto->activo ? 0 : 1 }}">
                        <button type="submit" class="btn btn-outline-secondary home-btn">
                            {{ $producto->activo ? 'Retirar de tienda' : 'Reactivar producto' }}
                        </button>
                    </form>
                </article>
            @empty
                <div class="home-panel admin-empty">No hay productos registrados todavia.</div>
            @endforelse
        </div>
    </div>
</div>
@endsection
