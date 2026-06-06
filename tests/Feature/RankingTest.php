<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RankingTest extends TestCase
{
    use RefreshDatabase;

    public function test_muestra_el_ranking_ordenado_por_puntos_de_mayor_a_menor(): void
    {
        $usuarioActual = User::factory()->usuario()->create([
            'nombre' => 'Andre',
            'nickname' => 'andre',
            'puntos_totales' => 150,
        ]);
        $primero = User::factory()->usuario()->create([
            'nombre' => 'Lider',
            'nickname' => 'lider',
            'puntos_totales' => 900,
        ]);
        $segundo = User::factory()->usuario()->create([
            'nombre' => 'Segundo',
            'nickname' => 'segundo',
            'puntos_totales' => 500,
        ]);

        $response = $this->actingAs($usuarioActual)->get(route('vistas.ranking'));

        $response->assertOk();
        $response->assertSeeInOrder([
            $primero->nombre_publico,
            $segundo->nombre_publico,
            $usuarioActual->nombre_publico,
        ]);
    }

    public function test_no_muestra_admins_creadores_ni_usuarios_baneados_en_el_ranking(): void
    {
        $usuario = User::factory()->usuario()->create([
            'nombre' => 'Usuario visible',
            'nickname' => 'visible',
            'puntos_totales' => 300,
        ]);
        $admin = User::factory()->admin()->create([
            'nombre' => 'Admin oculto',
            'nickname' => 'adminoculto',
            'puntos_totales' => 9999,
        ]);
        $creador = User::factory()->creador()->create([
            'nombre' => 'Creador oculto',
            'nickname' => 'creadoroculto',
            'puntos_totales' => 9999,
        ]);
        $baneado = User::factory()->usuario()->create([
            'nombre' => 'Baneado oculto',
            'nickname' => 'baneadooculto',
            'esta_baneado' => true,
            'puntos_totales' => 9999,
        ]);

        $response = $this->actingAs($usuario)->get(route('vistas.ranking'));

        $response->assertOk();
        $response->assertSee($usuario->nombre_publico);
        $response->assertDontSee($admin->nombre_publico);
        $response->assertDontSee($creador->nombre_publico);
        $response->assertDontSee($baneado->nombre_publico);
    }
}
