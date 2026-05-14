<?php

namespace Database\Seeders;

use App\Models\Logro;
use Illuminate\Database\Seeder;

class LogroSeeder extends Seeder
{
    public function run(): void
    {
        $logros = [
            [
                'nombre_logro' => 'Primer reto completado',
                'descripcion' => 'Has completado tu primer reto dentro de la plataforma.',
                'icono' => 'logros/primer-reto.png',
            ],
            [
                'nombre_logro' => 'Creador activo',
                'descripcion' => 'Has publicado varios retos para la comunidad.',
                'icono' => 'logros/creador-activo.png',
            ],
            [
                'nombre_logro' => 'Validador fiable',
                'descripcion' => 'Has conseguido varias validaciones correctas.',
                'icono' => 'logros/validador-fiable.png',
            ],
            [
                'nombre_logro' => 'Racha imparable',
                'descripcion' => 'Has mantenido una racha destacada durante varios dias.',
                'icono' => 'logros/racha-imparable.png',
            ],
            [
                'nombre_logro' => 'Comunidad participativa',
                'descripcion' => 'Has interactuado activamente dejando comentarios utiles.',
                'icono' => 'logros/comunidad-participativa.png',
            ],
        ];

        foreach ($logros as $logro) {
            Logro::query()->create($logro);
        }
    }
}
