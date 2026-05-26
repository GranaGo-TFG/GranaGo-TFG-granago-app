@extends('layouts.app')

@section('content')
<div class="screen-page tienda-page">
    <div class="container">
        <div class="screen-head">
            <div>
                <h1 class="home-kicker">Tienda admin</h1>
                <h2>Crear nuevo producto</h2>
                <p>Alta de producto visible solo para administradores.</p>
            </div>
            <a href="{{ route('admin.productos.index') }}" class="btn btn-primary home-btn">Volver a productos</a>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger" role="alert">
                {{ $errors->first() }}
            </div>
        @endif

        <section class="home-panel store-detail-panel">
            <form method="POST" action="{{ route('admin.productos.store') }}" class="store-admin-edit-form">
                @csrf

                <div class="store-checkout-grid">
                    <div>
                        <label for="nombre" class="form-label">Nombre</label>
                        <input type="text" id="nombre" name="nombre" class="form-control" value="{{ old('nombre') }}" required>
                    </div>
                    <div>
                        <label for="categoria" class="form-label">Categoria</label>
                        <input type="text" id="categoria" name="categoria" class="form-control" value="{{ old('categoria') }}" required>
                    </div>
                    <div>
                        <label for="precio" class="form-label">Precio EUR</label>
                        <input type="number" id="precio" name="precio" class="form-control" min="0" max="25" step="0.01" value="{{ old('precio', 1) }}" required>
                    </div>
                    <div>
                        <label for="precio_puntos" class="form-label">Precio puntos</label>
                        <input type="number" id="precio_puntos" name="precio_puntos" class="form-control" min="100" value="{{ old('precio_puntos', 100) }}" required>
                    </div>
                    <div>
                        <label for="stock" class="form-label">Stock</label>
                        <input type="number" id="stock" name="stock" class="form-control" min="0" value="{{ old('stock', 0) }}" required>
                    </div>
                    <div>
                        <label for="activo" class="form-label">Estado</label>
                        <select id="activo" name="activo" class="form-select" required>
                            <option value="1" @selected((int) old('activo', 1) === 1)>Activo</option>
                            <option value="0" @selected((int) old('activo', 1) === 0)>Retirado</option>
                        </select>
                    </div>
                    <div class="full">
                        <label for="descripcion_corta" class="form-label">Descripcion corta</label>
                        <input type="text" id="descripcion_corta" name="descripcion_corta" class="form-control" maxlength="180" value="{{ old('descripcion_corta') }}" required>
                    </div>
                    <div class="full">
                        <label for="descripcion" class="form-label">Descripcion</label>
                        <textarea id="descripcion" name="descripcion" class="form-control" rows="4" required>{{ old('descripcion') }}</textarea>
                    </div>
                    <div class="full">
                        <label for="imagen_url" class="form-label">URL imagen (opcional)</label>
                        <input type="url" id="imagen_url" name="imagen_url" class="form-control" value="{{ old('imagen_url') }}">
                    </div>
                </div>

                <div class="d-flex justify-content-end mt-2">
                    <button type="submit" class="btn btn-primary home-btn">Crear producto</button>
                </div>
            </form>
        </section>
    </div>
</div>
@endsection
