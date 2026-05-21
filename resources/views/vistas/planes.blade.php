@extends('layouts.app')

@section('content')
@php
    $planes = [
        [
            'nombre' => 'Explora',
            'precio' => '0',
            'periodo' => '/siempre',
            'descripcion' => 'Perfecto para descubrir la ciudad a tu ritmo y empezar a sumar puntos.',
            'etiqueta' => 'Acceso libre',
            'destacado' => false,
            'cta' => 'Empezar gratis',
            'caracteristicas' => [
                'Acceso a retos publicos semanales',
                'Perfil, ranking y logros basicos',
                '1 subida de prueba activa por reto',
                'Notificaciones esenciales',
            ],
        ],
        [
            'nombre' => 'Aventura',
            'precio' => '6,99',
            'periodo' => '/mes',
            'descripcion' => 'Para quienes quieren vivir Granada como un juego continuo con ventajas reales.',
            'etiqueta' => 'Mas popular',
            'destacado' => true,
            'cta' => 'Elegir Aventura',
            'caracteristicas' => [
                'Retos premium y rutas exclusivas',
                'Multiplicador extra en eventos especiales',
                'Pistas avanzadas y contenido sorpresa',
                'Canjes y recompensas prioritarias',
                'Insignia premium en comunidad',
            ],
        ],
        [
            'nombre' => 'Crew',
            'precio' => '14,99',
            'periodo' => '/mes',
            'descripcion' => 'Pensado para grupos, creadores y equipos que quieren competir juntos.',
            'etiqueta' => 'Equipos',
            'destacado' => false,
            'cta' => 'Crear tu crew',
            'caracteristicas' => [
                'Hasta 5 miembros en un mismo plan',
                'Clasificaciones privadas por equipo',
                'Retos colaborativos y ligas internas',
                'Panel con metricas compartidas',
                'Soporte prioritario para organizadores',
            ],
        ],
    ];

    $comparativa = [
        ['label' => 'Retos semanales', 'explora' => 'Incluidos', 'aventura' => 'Incluidos', 'crew' => 'Incluidos'],
        ['label' => 'Retos premium', 'explora' => 'No', 'aventura' => 'Si', 'crew' => 'Si'],
        ['label' => 'Multiplicadores especiales', 'explora' => 'No', 'aventura' => 'Si', 'crew' => 'Si'],
        ['label' => 'Equipos privados', 'explora' => 'No', 'aventura' => 'No', 'crew' => 'Si'],
        ['label' => 'Recompensas prioritarias', 'explora' => 'No', 'aventura' => 'Si', 'crew' => 'Si'],
    ];

    $faq = [
        [
            'pregunta' => '¿Puedo cambiar de plan cuando quiera?',
            'respuesta' => 'Si. Puedes subir, bajar o cancelar tu plan desde tu perfil sin perder tu progreso ni tus logros.',
        ],
        [
            'pregunta' => '¿El plan gratuito tiene limite de tiempo?',
            'respuesta' => 'No. Explora es permanente y sirve para empezar a jugar, descubrir retos y probar la experiencia.',
        ],
        [
            'pregunta' => '¿Crew sirve para asociaciones o clases?',
            'respuesta' => 'Si. Esta pensado para grupos pequenos que quieren competir juntos y revisar su actividad compartida.',
        ],
    ];
@endphp

<div class="screen-page pricing-page">
    <div class="container">
        <section class="pricing-hero">
            <div class="pricing-hero-copy">
                <h1 class="home-kicker">Planes GranaGO!</h1>
                <h2>Elige como quieres explorar Granada</h2>

                <div class="pricing-hero-badges">
                    <span>Sin permanencia</span>
                    <span>Cancelacion flexible</span>
                    <span>Beneficios desde el primer dia</span>
                </div>
            </div>

            <aside class="pricing-hero-panel">
                <span class="pricing-panel-tag">Recomendado</span>
                <strong>Aventura</strong>
                <p>El equilibrio ideal entre exploracion, recompensas y acceso a retos especiales.</p>
                <div>
                    <span class="pricing-panel-price">6,99€</span>
                    <small>por mes</small>
                </div>
                <a href="#" class="btn btn-primary home-btn">Probar 7 dias</a>
            </aside>
        </section>
        <br>
        <section class="pricing-card-grid" aria-label="Planes disponibles">
            @foreach ($planes as $plan)
                <article class="pricing-card {{ $plan['destacado'] ? 'is-featured' : '' }}">
                    <div class="pricing-card-top">
                        <span class="pricing-badge">{{ $plan['etiqueta'] }}</span>
                        <h2>{{ $plan['nombre'] }}</h2>
                        <p>{{ $plan['descripcion'] }}</p>
                    </div>

                    <div class="pricing-value">
                        <strong>{{ $plan['precio'] }}€</strong>
                        <span>{{ $plan['periodo'] }}</span>
                    </div>

                    <ul class="pricing-feature-list">
                        @foreach ($plan['caracteristicas'] as $caracteristica)
                            <li>{{ $caracteristica }}</li>
                        @endforeach
                    </ul>

                    <a href="#" class="btn {{ $plan['destacado'] ? 'btn-primary' : 'btn-outline-secondary' }} home-btn">
                        {{ $plan['cta'] }}
                    </a>
                </article>
            @endforeach
        </section>
        <br>
        <section class="pricing-layout">
            <article class="home-panel pricing-compare">
                <div class="pricing-section-head">
                    
                    <div>
                        <span class="home-kicker">Comparativa</span>
                        <h2>Que incluye cada plan</h2>
                    </div>
                    <p class="muted-copy">Una vista rapida para elegir segun tu forma de jugar.</p>
                </div>
                <div class="pricing-compare-table">
                    <div class="pricing-compare-row pricing-compare-head">
                        <span>Caracteristica</span>
                        <span>Explora</span>
                        <span>Aventura</span>
                        <span>Crew</span>
                    </div>

                    @foreach ($comparativa as $fila)
                        <div class="pricing-compare-row">
                            <span>{{ $fila['label'] }}</span>
                            <span>{{ $fila['explora'] }}</span>
                            <span>{{ $fila['aventura'] }}</span>
                            <span>{{ $fila['crew'] }}</span>
                        </div>
                    @endforeach
                </div>
            </article>

            <aside class="pricing-side-stack">
                <article class="home-panel pricing-mini-card">
                    <span class="home-kicker">Ideal para</span>
                    <h2>Usuarios activos</h2>
                    <p class="muted-copy">Si participas varias veces por semana, Aventura suele amortizarse antes por sus ventajas y recompensas.</p>
                </article>
                <aside class="pricing-side-stack">
                    <article class="home-panel pricing-mini-card pricing-mini-card-dark">
                        <span class="home-kicker">Equipos</span>
                        <h2>Crew para competir juntos</h2>
                        <p>Perfecto para clases, grupos de amigos o asociaciones que quieran tener ranking privado y retos colaborativos.</p>
                    </article>
                </aside>
            </aside>
        </section>

        <section class="pricing-faq">
            <div class="pricing-section-head">
                <div>
                    <span class="home-kicker">Preguntas frecuentes</span>
                    <h2>Lo importante, sin letra pequena</h2>
                </div>
            </div>

            <div class="pricing-faq-grid">
                @foreach ($faq as $item)
                    <article class="home-panel pricing-faq-card">
                        <h3>{{ $item['pregunta'] }}</h3>
                        <p>{{ $item['respuesta'] }}</p>
                    </article>
                @endforeach
            </div>
        </section>
    </div>
</div>
@endsection
