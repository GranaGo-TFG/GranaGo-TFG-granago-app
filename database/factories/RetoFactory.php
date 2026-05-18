<?php

namespace Database\Factories;

use App\Models\Reto;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Reto>
 */
class RetoFactory extends Factory
{
    protected $model = Reto::class;

    public function definition(): array
    {
        $fechaInicio = fake()->dateTimeBetween('-20 days', '+15 days');
        $fechaFin = (clone $fechaInicio)->modify('+' . fake()->numberBetween(3, 15) . ' days');
        $ahora = now();
        $latitud = fake()->randomFloat(7, 37.1200000, 37.2400000);
        $longitud = fake()->randomFloat(7, -3.7000000, -3.5200000);

        $estado = match (true) {
            $fechaFin < $ahora => 'caducado',
            $fechaInicio > $ahora => 'borrador',
            default => 'publicado',
        };

        return [
            'creador_id' => User::factory()->creador(),
            'nombre' => fake()->sentence(3),
            'descripcion' => fake()->paragraphs(2, true),
            'ubicacion_referencia' => fake()->randomElement([
                'Albaicin',
                'Centro Historico',
                'Realejo',
                'Sacromonte',
                'Zaidin',
            ]),
            'archivo_multimedia' => fake()->optional(0.7)->imageUrl(1280, 720, 'city', true, 'reto'),
            'fecha_inicio' => $fechaInicio,
            'fecha_fin' => $fechaFin,
            'estado' => $estado,
            'puntos_recompensa' => fake()->numberBetween(50, 500),
            'latitud' => $latitud,
            'longitud' => $longitud,
        ];
    }
}
