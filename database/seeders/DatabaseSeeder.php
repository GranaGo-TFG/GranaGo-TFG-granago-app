<?php

namespace Database\Seeders;

use App\Models\Logro;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            LogroSeeder::class,
            RetoSeeder::class,
            ValidacionRetoSeeder::class,
            ComentarioSeeder::class,
            ProductoSeeder::class,
        ]);

        $logroIds = Logro::query()->pluck('id');

        User::query()
            ->whereIn('rol', ['creador', 'usuario'])
            ->each(function (User $user) use ($logroIds) {
                $idsAsignados = $logroIds->shuffle()->take(fake()->numberBetween(0, min(3, $logroIds->count())));

                foreach ($idsAsignados as $logroId) {
                    $user->logros()->syncWithoutDetaching([
                        $logroId => [
                            'fecha_desbloqueo' => fake()->dateTimeBetween('-30 days', 'now'),
                        ],
                    ]);
                }
            });
    }
}
