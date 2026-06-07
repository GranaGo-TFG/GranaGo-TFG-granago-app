<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $adminData = User::factory()->admin()->raw([
            'nombre' => 'Administrador',
            'nickname' => 'admin_granago',
            'email' => 'admin@granago.app',
            'password' => Hash::make('password'),
        ]);

        User::query()->updateOrCreate(
            ['email' => 'admin@granago.app'],
            [
                'nombre' => $adminData['nombre'],
                'nickname' => $adminData['nickname'],
                'password' => $adminData['password'],
                'rol' => $adminData['rol'],
                'puntos_totales' => $adminData['puntos_totales'],
                'racha_multiplicador' => $adminData['racha_multiplicador'],
                'racha_ultimo_reto' => $adminData['racha_ultimo_reto'] ?? null,
                'esta_baneado' => false,
            ]
        );

        User::factory(5)->creador()->create();
        User::factory(20)->usuario()->create();
    }
}
