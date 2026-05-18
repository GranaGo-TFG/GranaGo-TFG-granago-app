<?php

namespace Database\Seeders;

use App\Models\Comentario;
use App\Models\User;
use App\Models\ValidacionReto;
use Illuminate\Database\Seeder;

class ComentarioSeeder extends Seeder
{
    public function run(): void
    {
        $usuarios = User::query()->get();

        if ($usuarios->isEmpty()) {
            return;
        }

        ValidacionReto::query()->each(function (ValidacionReto $validacion) use ($usuarios) {
            $autores = $usuarios->shuffle()->take(fake()->numberBetween(0, 3));

            foreach ($autores as $autor) {
                Comentario::factory()->create([
                    'user_id' => $autor->id,
                    'validacion_id' => $validacion->id,
                    'fecha' => fake()->dateTimeBetween($validacion->fecha_envio, 'now'),
                ]);
            }
        });
    }
}
