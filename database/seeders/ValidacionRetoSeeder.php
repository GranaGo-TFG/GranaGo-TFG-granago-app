<?php

namespace Database\Seeders;

use App\Models\Reto;
use App\Models\User;
use App\Models\ValidacionReto;
use Illuminate\Database\Seeder;

class ValidacionRetoSeeder extends Seeder
{
    public function run(): void
    {
        $usuarios = User::query()->where('rol', 'usuario')->get();
        $retos = Reto::query()
            ->where('fecha_inicio', '<=', now())
            ->get();

        if ($usuarios->isEmpty() || $retos->isEmpty()) {
            return;
        }

        foreach ($retos as $reto) {
            $maxParticipantes = min(6, $usuarios->count());
            $minParticipantes = min(2, $maxParticipantes);
            $fechaMaximaEnvio = $reto->fecha_fin->lte(now()) ? $reto->fecha_fin : now();

            if ($maxParticipantes === 0) {
                continue;
            }

            $participantes = $usuarios
                ->where('id', '!=', $reto->creador_id)
                ->shuffle()
                ->take(fake()->numberBetween($minParticipantes, $maxParticipantes));

            foreach ($participantes as $usuario) {
                ValidacionReto::factory()->create([
                    'user_id' => $usuario->id,
                    'reto_id' => $reto->id,
                    'fecha_envio' => fake()->dateTimeBetween($reto->fecha_inicio, $fechaMaximaEnvio),
                ]);
            }
        }
    }
}
