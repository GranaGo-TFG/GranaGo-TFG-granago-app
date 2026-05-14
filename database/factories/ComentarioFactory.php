<?php

namespace Database\Factories;

use App\Models\Comentario;
use App\Models\User;
use App\Models\ValidacionReto;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Comentario>
 */
class ComentarioFactory extends Factory
{
    protected $model = Comentario::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'validacion_id' => ValidacionReto::factory(),
            'texto' => fake()->sentence(fake()->numberBetween(8, 18)),
            'fecha' => fake()->dateTimeBetween('-10 days', 'now'),
        ];
    }
}
