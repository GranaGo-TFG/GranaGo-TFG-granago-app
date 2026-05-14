<?php

namespace Database\Factories;

use App\Models\Reto;
use App\Models\User;
use App\Models\ValidacionReto;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ValidacionReto>
 */
class ValidacionRetoFactory extends Factory
{
    protected $model = ValidacionReto::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory()->usuario(),
            'reto_id' => Reto::factory(),
            'foto_prueba' => fake()->imageUrl(1080, 1080, 'people', true, 'prueba'),
            'estado' => fake()->randomElement(['pendiente', 'verificado', 'rechazado']),
            'fecha_envio' => fake()->dateTimeBetween('-15 days', 'now'),
        ];
    }
}
