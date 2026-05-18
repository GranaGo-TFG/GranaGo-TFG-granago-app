<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::factory()->admin()->create([
            'nombre' => 'Administrador',
            'email' => 'admin@granago.app',
            'password' => Hash::make('password'),
        ]);

        User::factory(5)->creador()->create();
        User::factory(20)->usuario()->create();
    }
}
