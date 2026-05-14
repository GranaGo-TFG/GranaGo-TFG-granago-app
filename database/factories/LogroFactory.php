<?php

namespace Database\Factories;

use App\Models\Logro;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Logro>
 */
class LogroFactory extends Factory
{
    protected $model = Logro::class;

    public function definition(): array
    {
        return [
            'nombre_logro' => fake()->unique()->randomElement([
                'Primer paso',
                'Explorador urbano',
                'Constancia total',
                'Reto maestro',
                'Verificacion perfecta',
                'Comunidad activa',
            ]),
            'descripcion' => fake()->sentence(12),
            'icono' => 'logros/' . fake()->slug(2) . '.png',
        ];
    }
}
