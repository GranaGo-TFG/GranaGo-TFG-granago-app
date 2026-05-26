<?php

namespace Database\Factories;

use App\Models\Producto;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Producto>
 */
class ProductoFactory extends Factory
{
    protected $model = Producto::class;

    public function definition(): array
    {
        $catalogoBase = [
            ['nombre' => 'Mochila Explorador', 'categoria' => 'Equipamiento'],
            ['nombre' => 'Botella Termica', 'categoria' => 'Accesorios'],
            ['nombre' => 'Pack de Insignias', 'categoria' => 'Coleccionables'],
            ['nombre' => 'Pulsera Smart GO', 'categoria' => 'Tecnologia'],
            ['nombre' => 'Sudadera Oficial', 'categoria' => 'Merchandising'],
            ['nombre' => 'Guia de Rutas Secretas', 'categoria' => 'Guias'],
            ['nombre' => 'Linterna Mini Trek', 'categoria' => 'Equipamiento'],
            ['nombre' => 'Monedero GranaGO', 'categoria' => 'Accesorios'],
            ['nombre' => 'Mapa Vintage de Granada', 'categoria' => 'Coleccionables'],
            ['nombre' => 'Kit Aventura Urbana', 'categoria' => 'Packs'],
        ];

        $ediciones = [
            'Edicion Albaicin',
            'Edicion Sierra',
            'Edicion Nazarie',
            'Edicion Centro',
            'Edicion Catedral',
            'Edicion Sacromonte',
        ];

        $base = fake()->randomElement($catalogoBase);
        $nombre = $base['nombre'] . ' ' . fake()->randomElement($ediciones);
        $slug = Str::slug($nombre) . '-' . fake()->unique()->numerify('###');
        $precio = fake()->randomFloat(2, 4, 25);

        $multiplicadoresPuntos = [
            'Tecnologia' => 145,
            'Packs' => 140,
            'Merchandising' => 130,
            'Equipamiento' => 120,
            'Guias' => 110,
            'Accesorios' => 100,
            'Coleccionables' => 95,
        ];

        $precioPuntos = (int) round($precio * ($multiplicadoresPuntos[$base['categoria']] ?? 115));

        return [
            'nombre' => $nombre,
            'slug' => $slug,
            'descripcion_corta' => fake()->sentence(12),
            'descripcion' => fake()->paragraphs(2, true),
            'categoria' => $base['categoria'],
            'precio' => $precio,
            'precio_puntos' => $precioPuntos,
            'stock' => fake()->numberBetween(0, 60),
            'vendidos_total' => fake()->numberBetween(0, 520),
            'imagen_url' => 'https://picsum.photos/seed/' . $slug . '/1200/800',
            'activo' => fake()->boolean(92),
        ];
    }
}
