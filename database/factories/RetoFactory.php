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

        $estado = match (true) {
            $fechaFin < $ahora => 'caducado',
            $fechaInicio > $ahora => 'borrador',
            default => 'publicado',
        };

        return [
            'creador_id' => User::factory()->creador(),
            'nombre' => fake()->sentence(3),
            'descripcion' => fake()->paragraphs(2, true),
            'archivo_multimedia' => fake()->optional(0.7)->imageUrl(1280, 720, 'city', true, 'reto'),
            'fecha_inicio' => $fechaInicio,
            'fecha_fin' => $fechaFin,
            'estado' => $estado,
            'puntos_recompensa' => fake()->numberBetween(50, 500),
        ];
    }
}
