<?php

namespace Database\Seeders;

use App\Models\Producto;
use Illuminate\Database\Seeder;

class ProductoSeeder extends Seeder
{
    public function run(): void
    {
        $topVentas = [
            [
                'nombre' => 'Pack Aventura Albaicin',
                'slug' => 'pack-aventura-albaicin',
                'descripcion_corta' => 'Incluye mapa premium, insignias y rutas recomendadas para completar retos.',
                'descripcion' => 'Pack pensado para quienes quieren exprimir cada reto de la ciudad. Incluye acceso a rutas destacadas, insignias exclusivas y recomendaciones para completar desafios en menos tiempo.',
                'categoria' => 'Packs',
                'precio' => 22.90,
                'precio_puntos' => 3100,
                'stock' => 24,
                'vendidos_total' => 980,
                'imagen_url' => 'https://picsum.photos/seed/pack-aventura-albaicin/1200/800',
                'activo' => true,
            ],
            [
                'nombre' => 'Sudadera Oficial GranaGO',
                'slug' => 'sudadera-oficial-granago',
                'descripcion_corta' => 'Sudadera comoda con diseno exclusivo para la comunidad mas activa.',
                'descripcion' => 'Tejido suave, interior afelpado y acabado premium. Ideal para salidas de tarde y retos nocturnos, con un estilo reconocible para la comunidad de GranaGO.',
                'categoria' => 'Merchandising',
                'precio' => 24.95,
                'precio_puntos' => 3450,
                'stock' => 31,
                'vendidos_total' => 860,
                'imagen_url' => 'https://picsum.photos/seed/sudadera-oficial-granago/1200/800',
                'activo' => true,
            ],
            [
                'nombre' => 'Pulsera Smart GO',
                'slug' => 'pulsera-smart-go',
                'descripcion_corta' => 'Controla avances y desbloquea recompensas de forma rapida.',
                'descripcion' => 'Pulsera conectada para registrar progreso en eventos especiales y canjear beneficios dentro de la plataforma. Resistente al agua y con bateria de larga duracion.',
                'categoria' => 'Tecnologia',
                'precio' => 25.00,
                'precio_puntos' => 3600,
                'stock' => 17,
                'vendidos_total' => 790,
                'imagen_url' => 'https://picsum.photos/seed/pulsera-smart-go/1200/800',
                'activo' => true,
            ],
            [
                'nombre' => 'Mochila Explorer GO',
                'slug' => 'mochila-explorer-go',
                'descripcion_corta' => 'Mochila ligera con compartimentos para rutas urbanas y outdoor.',
                'descripcion' => 'Perfecta para jornadas largas de retos. Tiene varios compartimentos internos, zona acolchada para accesorios y diseno ergonomico para caminar comodo todo el dia.',
                'categoria' => 'Equipamiento',
                'precio' => 19.50,
                'precio_puntos' => 2800,
                'stock' => 27,
                'vendidos_total' => 740,
                'imagen_url' => 'https://picsum.photos/seed/mochila-explorer-go/1200/800',
                'activo' => true,
            ],
            [
                'nombre' => 'Kit Fotografia de Retos',
                'slug' => 'kit-fotografia-de-retos',
                'descripcion_corta' => 'Mini kit para capturar pruebas con mejor calidad en cualquier entorno.',
                'descripcion' => 'Incluye mini tripode, luz compacta y soporte universal para movil. Mejora tus capturas de validacion en interior o exterior con un set ligero y facil de transportar.',
                'categoria' => 'Accesorios',
                'precio' => 16.90,
                'precio_puntos' => 2350,
                'stock' => 22,
                'vendidos_total' => 705,
                'imagen_url' => 'https://picsum.photos/seed/kit-fotografia-de-retos/1200/800',
                'activo' => true,
            ],
        ];

        foreach ($topVentas as $producto) {
            Producto::query()->updateOrCreate(
                ['slug' => $producto['slug']],
                $producto
            );
        }

        Producto::factory(20)->create();

        // Garantiza que no haya ningun producto por encima del maximo acordado.
        Producto::query()
            ->where('precio', '>', 25)
            ->get()
            ->each(fn (Producto $producto) => $producto->update(['precio' => 25]));

        // Garantiza que todos los productos tengan precio en puntos.
        Producto::query()
            ->whereNull('precio_puntos')
            ->orWhere('precio_puntos', '<=', 0)
            ->get()
            ->each(function (Producto $producto): void {
                $multiplicador = $this->multiplicadorPuntosPorCategoria($producto->categoria);
                $precioPuntos = (int) round((float) $producto->precio * $multiplicador);

                $producto->update([
                    'precio_puntos' => max(100, $precioPuntos),
                ]);
            });
    }

    private function multiplicadorPuntosPorCategoria(?string $categoria): int
    {
        return match ($categoria) {
            'Tecnologia' => 145,
            'Packs' => 140,
            'Merchandising' => 130,
            'Equipamiento' => 120,
            'Guias' => 110,
            'Accesorios' => 100,
            'Coleccionables' => 95,
            default => 115,
        };
    }
}
