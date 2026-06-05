<?php

namespace Database\Seeders;

use App\Models\Logro;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LogroSeeder extends Seeder
{
    public function run(): void
    {
        $logros = [
            [
                'nombre_logro' => 'Primer reto completado',
                'descripcion' => 'Has completado tu primer reto dentro de la plataforma.',
                'icono' => 'logros/primer-reto-completado.png',
            ],
            [
                'nombre_logro' => 'Ruta de barrio',
                'descripcion' => 'Has completado 5 retos por diferentes barrios de Granada.',
                'icono' => 'logros/ruta-de-barrio.png',
            ],
            [
                'nombre_logro' => 'Explorador del Albaicin',
                'descripcion' => 'Has superado un reto ambientado en el Albaicin.',
                'icono' => 'logros/explorador-del-albaicin.png',
            ],
            [
                'nombre_logro' => 'Mirador conquistado',
                'descripcion' => 'Has completado 3 retos en puntos panoramicos de la ciudad.',
                'icono' => 'logros/mirador-conquistado.png',
            ],
            [
                'nombre_logro' => 'Guardian del patrimonio',
                'descripcion' => 'Has finalizado una ruta historica con todos sus retos activos.',
                'icono' => 'logros/guardian-del-patrimonio.png',
            ],
            [
                'nombre_logro' => 'Cronista urbano',
                'descripcion' => 'Has compartido publicaciones utiles en la comunidad de GranaGO.',
                'icono' => 'logros/cronista-urbano.png',
            ],
            [
                'nombre_logro' => 'Maestro de pistas',
                'descripcion' => 'Has completado 10 retos resolviendo pistas de forma consecutiva.',
                'icono' => 'logros/maestro-de-pistas.png',
            ],
            [
                'nombre_logro' => 'Creador en marcha',
                'descripcion' => 'Has publicado tu primer reto como creador.',
                'icono' => 'logros/creador-en-marcha.png',
            ],
            [
                'nombre_logro' => 'Arquitecto de retos',
                'descripcion' => 'Has publicado 5 retos con ubicacion y relato completo.',
                'icono' => 'logros/arquitecto-de-retos.png',
            ],
            [
                'nombre_logro' => 'Curador de historias',
                'descripcion' => 'Tus retos han recibido validaciones positivas de otros usuarios.',
                'icono' => 'logros/curador-de-historias.png',
            ],
            [
                'nombre_logro' => 'Mentor de aventureros',
                'descripcion' => 'Has ayudado a la comunidad con comentarios valorados.',
                'icono' => 'logros/mentor-de-aventureros.png',
            ],
            [
                'nombre_logro' => 'Racha granadina',
                'descripcion' => 'Has mantenido actividad durante 7 dias seguidos.',
                'icono' => 'logros/racha-granadina.png',
            ],
            [
                'nombre_logro' => 'Leyenda nazari',
                'descripcion' => 'Has alcanzado un gran volumen de puntos en la plataforma.',
                'icono' => 'logros/leyenda-nazari.png',
            ],
            [
                'nombre_logro' => 'Corazon comunitario',
                'descripcion' => 'Has participado en debates y comentarios de la comunidad.',
                'icono' => 'logros/corazon-comunitario.png',
            ],
            [
                'nombre_logro' => 'Voz de la comunidad',
                'descripcion' => 'Has recibido me gusta en publicaciones y comentarios propios.',
                'icono' => 'logros/voz-de-la-comunidad.png',
            ],
            [
                'nombre_logro' => 'Cazatesoros local',
                'descripcion' => 'Has completado retos de distintas categorias en Granada.',
                'icono' => 'logros/cazatesoros-local.png',
            ],
            [
                'nombre_logro' => 'Comerciante callejero',
                'descripcion' => 'Has hecho tus primeras compras en la tienda con puntos.',
                'icono' => 'logros/comerciante-callejero.png',
            ],
            [
                'nombre_logro' => 'Conquistador de puntos',
                'descripcion' => 'Has superado los 1000 puntos acumulados en tus aventuras.',
                'icono' => 'logros/conquistador-de-puntos.png',
            ],
            [
                'nombre_logro' => 'Embajador de GranaGO',
                'descripcion' => 'Has contribuido como usuario y como creador dentro de la comunidad.',
                'icono' => 'logros/embajador-de-granago.png',
            ],
            [
                'nombre_logro' => 'Horizonte sin fin',
                'descripcion' => 'Has desbloqueado una gran parte del catalogo de logros.',
                'icono' => 'logros/horizonte-sin-fin.png',
            ],
        ];

        if (count($logros) !== 20) {
            throw new \RuntimeException('El catalogo base de logros debe tener exactamente 20 elementos.');
        }

        DB::transaction(function () use ($logros): void {
            $this->consolidarLogrosDuplicados();

            foreach ($logros as $logro) {
                Logro::query()->updateOrCreate(
                    ['nombre_logro' => $logro['nombre_logro']],
                    $logro
                );
            }

            $nombresCatalogo = collect($logros)
                ->pluck('nombre_logro')
                ->all();

            Logro::query()
                ->whereNotIn('nombre_logro', $nombresCatalogo)
                ->delete();

            $this->consolidarPivotDuplicado();
        });
    }

    private function consolidarLogrosDuplicados(): void
    {
        $grupos = Logro::query()
            ->orderBy('id')
            ->get()
            ->groupBy('nombre_logro');

        foreach ($grupos as $registros) {
            if ($registros->count() <= 1) {
                continue;
            }

            $principal = $registros->first();

            foreach ($registros->slice(1) as $duplicado) {
                $this->migrarUsuariosLogro($duplicado->id, $principal->id);
                $duplicado->delete();
            }
        }
    }

    private function migrarUsuariosLogro(int $logroOrigenId, int $logroDestinoId): void
    {
        $usersAfectados = DB::table('logro_user')
            ->where('logro_id', $logroOrigenId)
            ->pluck('user_id')
            ->unique();

        foreach ($usersAfectados as $userId) {
            $fechaMinima = DB::table('logro_user')
                ->where('user_id', $userId)
                ->whereIn('logro_id', [$logroOrigenId, $logroDestinoId])
                ->min('fecha_desbloqueo');

            $pivotDestino = DB::table('logro_user')
                ->where('user_id', $userId)
                ->where('logro_id', $logroDestinoId)
                ->first(['id']);

            if ($pivotDestino) {
                if ($fechaMinima) {
                    DB::table('logro_user')
                        ->where('id', $pivotDestino->id)
                        ->update(['fecha_desbloqueo' => $fechaMinima]);
                }
            } else {
                DB::table('logro_user')->insert([
                    'user_id' => $userId,
                    'logro_id' => $logroDestinoId,
                    'fecha_desbloqueo' => $fechaMinima ?: now(),
                ]);
            }
        }

        DB::table('logro_user')
            ->where('logro_id', $logroOrigenId)
            ->delete();
    }

    private function consolidarPivotDuplicado(): void
    {
        $duplicados = DB::table('logro_user')
            ->select(
                'user_id',
                'logro_id',
                DB::raw('MIN(id) as id_conservado'),
                DB::raw('MIN(fecha_desbloqueo) as fecha_minima'),
                DB::raw('COUNT(*) as total')
            )
            ->groupBy('user_id', 'logro_id')
            ->havingRaw('COUNT(*) > 1')
            ->get();

        foreach ($duplicados as $duplicado) {
            DB::table('logro_user')
                ->where('id', $duplicado->id_conservado)
                ->update(['fecha_desbloqueo' => $duplicado->fecha_minima]);

            DB::table('logro_user')
                ->where('user_id', $duplicado->user_id)
                ->where('logro_id', $duplicado->logro_id)
                ->where('id', '!=', $duplicado->id_conservado)
                ->delete();
        }
    }
}
