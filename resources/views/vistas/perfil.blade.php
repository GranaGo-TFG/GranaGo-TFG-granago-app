@extends('layouts.app')

@section('content')
@php
    $user = Auth::user();
    $puntosTotales = $user->puntos_totales ?? 0;
    $multiplicador = number_format((float) $user->racha_multiplicador, 2);
    $retosCompletados = $user->validacionesRetos()
        ->where('estado', 'verificado')
        ->count();
    $logros = $user->logros()
        ->orderByPivot('fecha_desbloqueo', 'desc')
        ->get();
    $validacionesRecientes = $user->validacionesRetos()
        ->with('reto:id,nombre')
        ->orderByDesc('fecha_envio')
        ->limit(3)
        ->get();
    $siguienteObjetivo = max(50, (int) ceil(($puntosTotales + 1) / 50) * 50);
    $puntosRestantes = max(0, $siguienteObjetivo - $puntosTotales);
    $porcentajeProgreso = min(100, (int) round(($puntosTotales / $siguienteObjetivo) * 100));
    $inventario = $user->inventarioProductos()
        ->orderByPivot('ultima_compra_at', 'desc')
        ->orderBy('nombre')
        ->get();
    $unidadesInventario = $inventario->sum(fn ($producto) => (int) $producto->pivot->cantidad);
@endphp

<div class="screen-page">
    <div class="container">
        <section class="profile-hero">
            <div class="profile-cover"></div>
            <div class="profile-main">
                <div class="profile-avatar" aria-label="Foto de perfil">
                    <span>{{ strtoupper(substr($user->nombre_publico, 0, 1)) }}</span>
                </div>

                <div class="profile-info">
                    <h1 class="home-kicker">Perfil</h1>
                    <h2>{{ $user->nombre_publico }}</h2>
                    <p>Explorador urbano de Granada. Retos, puntos y logros en un mismo sitio.</p>
                    <div class="profile-tags">
                        <span>{{ '@' . $user->nickname }}</span>
                        <span>{{ $user->rol }}</span>
                        <span>Granada</span>
                        <span>{{ $user->esta_baneado ? 'Baneado' : 'Activo' }}</span>
                    </div>
                </div>

                <a href="{{ route('vistas.editar-perfil') }}" class="btn btn-outline-secondary profile-edit">Editar perfil</a>
            </div>
        </section>

        <section class="profile-stats">
            <article>
                <strong>{{ $puntosTotales }}</strong>
                <span>Puntos</span>
            </article>
            <article>
                <strong>{{ $retosCompletados }}</strong>
                <span>Retos completados</span>
            </article>
            <article>
                <strong>{{ $logros->count() }}</strong>
                <span>Logros</span>
            </article>
            <article>
                <strong>x{{ $multiplicador }}</strong>
                <span>Racha</span>
            </article>
        </section>

        <section class="profile-layout">
            <article class="home-panel profile-card">
                <span class="home-kicker">Progreso</span>
                <h2>Camino al siguiente objetivo</h2>
                <p class="muted-copy">Te faltan {{ $puntosRestantes }} puntos para llegar a el siguiente objetivo de puntos.</p>
                <div class="profile-progress">
                    <span style="width: {{ $porcentajeProgreso }}%"></span>
                </div>
                <div class="profile-progress-label">
                    <span>{{ $puntosTotales }} pts</span>
                    <span>{{ $siguienteObjetivo }} pts</span>
                </div>
            </article>

            <article class="home-panel profile-card">
                <span class="home-kicker">Logros</span>
                <div class="profile-achievement-list">
                    @if ($logros->isEmpty())
                        <div class="profile-achievement">
                            <span>Sin logros</span>
                            <strong>Aun no has desbloqueado logros.</strong>
                        </div>
                    @endif

                    @foreach ($logros as $logro)
                        <div class="profile-achievement is-unlocked">
                            <span>{{ $logro->pivot->fecha_desbloqueo ? \Illuminate\Support\Carbon::parse($logro->pivot->fecha_desbloqueo)->format('d/m/Y') : 'Desbloqueado' }}</span>
                            <strong>{{ $logro->nombre_logro }}</strong>
                        </div>
                    @endforeach
                </div>
            </article>

            <article class="home-panel profile-card" id="inventario">
                <span class="home-kicker">Inventario</span>
                <h2>Tus compras en tienda</h2>
                <p class="muted-copy">Acumulado actual: {{ $inventario->count() }} productos diferentes y {{ $unidadesInventario }} unidades.</p>

                <div class="profile-achievement-list">
                    @if ($inventario->isEmpty())
                        <div class="profile-achievement">
                            <span>Sin compras registradas</span>
                            <strong>Cuando compres en tienda, veras aqui tus productos.</strong>
                        </div>
                    @endif

                    @foreach ($inventario as $productoInventario)
                        <div class="profile-achievement is-unlocked">
                            <span>
                                @if ($productoInventario->pivot->ultima_compra_at)
                                    Ultima compra: {{ \Illuminate\Support\Carbon::parse($productoInventario->pivot->ultima_compra_at)->format('d/m/Y H:i') }}
                                @else
                                    Compra registrada
                                @endif
                            </span>
                            <strong>{{ $productoInventario->nombre }} · x{{ $productoInventario->pivot->cantidad }}</strong>
                        </div>
                    @endforeach
                </div>
            </article>

            <article class="home-panel profile-card profile-card-wide">
                <span class="home-kicker">Actividad reciente</span>
                <div class="profile-timeline">
                    @if ($validacionesRecientes->isEmpty())
                        <div>
                            <strong>Sin actividad reciente</strong>
                            <p>Cuando envies una prueba, aparecera aqui.</p>
                        </div>
                    @endif

                    @foreach ($validacionesRecientes as $validacion)
                        <div>
                            <strong>{{ $validacion->reto->nombre ?? 'Reto eliminado' }}</strong>
                            <p>Validacion {{ $validacion->estado }} enviada el {{ $validacion->fecha_envio ? $validacion->fecha_envio->format('d/m/Y') : 'sin fecha' }}.</p>
                        </div>
                    @endforeach
                </div>
            </article>

            <aside class="home-panel profile-card">
                <span class="home-kicker">Cuenta</span>
                <dl class="profile-details">
                    <div>
                        <dt>Nickname</dt>
                        <dd>{{ $user->nickname }}</dd>
                    </div>
                    <div>
                        <dt>Email</dt>
                        <dd>{{ $user->email }}</dd>
                    </div>
                    <div>
                        <dt>Rol</dt>
                        <dd>{{ $user->rol }}</dd>
                    </div>
                    <div>
                        <dt>Estado</dt>
                        <dd>{{ $user->esta_baneado ? 'Baneado' : 'Activo' }}</dd>
                    </div>
                </dl>
            </aside>
        </section>

        <section class="profile-mobile-actions">
            <a href="#inventario" class="btn btn-outline-secondary home-btn">Ver inventario</a>
            <a href="{{ route('vistas.retos') }}" class="btn btn-primary home-btn">Buscar retos</a>
            <a href="{{ route('vistas.ranking') }}" class="btn btn-outline-secondary home-btn">Ver ranking</a>
        </section>
    </div>
</div>
@endsection
