<?php

namespace Database\Seeders;

use App\Models\Reto;
use App\Models\User;
use Illuminate\Database\Seeder;

class RetoSeeder extends Seeder
{
    public function run(): void
    {
        $creadores = User::query()->where('rol', 'creador')->get();

        foreach ($creadores as $creador) {
            $cantidadRetos = fake()->numberBetween(2, 4);

            Reto::factory($cantidadRetos)->create([
                'creador_id' => $creador->id,
            ]);
        }
    }
}
