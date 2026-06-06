<?php

namespace Tests\Feature;

use App\Models\Reto;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RetoVistaTest extends TestCase
{
    use RefreshDatabase;

    public function test_la_pagina_de_retos_solo_muestra_retos_publicados_o_caducados(): void
    {
        $creador = User::factory()->creador()->create();
        $publicado = Reto::factory()->for($creador, 'creador')->create([
            'nombre' => 'Reto publicado visible',
            'estado' => 'publicado',
        ]);
        $caducado = Reto::factory()->for($creador, 'creador')->create([
            'nombre' => 'Reto caducado visible',
            'estado' => 'caducado',
        ]);
        $borrador = Reto::factory()->for($creador, 'creador')->create([
            'nombre' => 'Reto borrador oculto',
            'estado' => 'borrador',
        ]);

        $response = $this->get(route('vistas.retos'));

        $response->assertOk();
        $response->assertSee($publicado->nombre);
        $response->assertSee($caducado->nombre);
        $response->assertDontSee($borrador->nombre);
    }

    public function test_permite_filtrar_retos_por_estado_y_busqueda(): void
    {
        $creador = User::factory()->creador()->create();
        $retoBuscado = Reto::factory()->for($creador, 'creador')->create([
            'nombre' => 'Mirador de San Nicolas',
            'ubicacion_referencia' => 'Albaicin',
            'estado' => 'publicado',
        ]);
        $otroPublicado = Reto::factory()->for($creador, 'creador')->create([
            'nombre' => 'Paseo de los Tristes',
            'ubicacion_referencia' => 'Centro',
            'estado' => 'publicado',
        ]);
        $caducado = Reto::factory()->for($creador, 'creador')->create([
            'nombre' => 'Reto antiguo del Albaicin',
            'ubicacion_referencia' => 'Albaicin',
            'estado' => 'caducado',
        ]);

        $response = $this->get(route('vistas.retos', [
            'estado' => 'publicado',
            'buscar' => 'Albaicin',
        ]));

        $response->assertOk();
        $response->assertSee($retoBuscado->nombre);
        $response->assertDontSee($otroPublicado->nombre);
        $response->assertDontSee($caducado->nombre);
    }

    public function test_un_creador_puede_crear_un_reto_y_se_guarda_como_borrador(): void
    {
        $creador = User::factory()->creador()->create();

        $response = $this->actingAs($creador)->post(route('vistas.retos.store'), [
            'nombre' => 'Encuentra la mano de la Puerta de la Justicia',
            'descripcion' => 'Busca el simbolo sobre la entrada y sube una foto clara.',
            'archivo_multimedia' => 'https://example.com/reto.jpg',
            'fecha_inicio' => now()->addDay()->format('Y-m-d H:i:s'),
            'fecha_fin' => now()->addDays(3)->format('Y-m-d H:i:s'),
            'puntos_recompensa' => 250,
            'ubicacion_referencia' => 'Alhambra',
            'latitud' => 37.176078,
            'longitud' => -3.588141,
            'titulo_relato' => 'La llave y la mano',
            'leyenda_relato' => 'Una vieja senal de la ciudad nazari.',
            'contenido_relato' => 'Cuenta la tradicion que nadie conquistaria la fortaleza hasta que la mano alcanzase la llave.',
            'cierre_relato' => 'Observa la puerta y deja constancia de lo que encuentres.',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('retos', [
            'creador_id' => $creador->id,
            'nombre' => 'Encuentra la mano de la Puerta de la Justicia',
            'estado' => 'borrador',
            'puntos_recompensa' => 250,
        ]);
    }

    public function test_un_usuario_normal_no_puede_crear_retos(): void
    {
        $usuario = User::factory()->usuario()->create();

        $response = $this->actingAs($usuario)->post(route('vistas.retos.store'), [
            'nombre' => 'Reto no permitido',
            'descripcion' => 'Este reto no deberia crearse.',
            'fecha_inicio' => now()->addDay()->format('Y-m-d H:i:s'),
            'fecha_fin' => now()->addDays(2)->format('Y-m-d H:i:s'),
            'puntos_recompensa' => 100,
            'latitud' => 37.176078,
            'longitud' => -3.588141,
        ]);

        $response->assertForbidden();
        $this->assertDatabaseMissing('retos', [
            'nombre' => 'Reto no permitido',
        ]);
    }
}
