<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'slug',
        'descripcion_corta',
        'descripcion',
        'categoria',
        'precio',
        'precio_puntos',
        'stock',
        'vendidos_total',
        'imagen_url',
        'activo',
    ];

    protected function casts(): array
    {
        return [
            'precio' => 'decimal:2',
            'precio_puntos' => 'integer',
            'stock' => 'integer',
            'vendidos_total' => 'integer',
            'activo' => 'boolean',
        ];
    }

    public function getPrecioEurosFormateadoAttribute(): string
    {
        return number_format((float) $this->precio, 2, ',', '.') . ' EUR';
    }

    public function getPrecioPuntosValorAttribute(): int
    {
        if (is_null($this->precio_puntos)) {
            return max(100, (int) round((float) $this->precio * $this->multiplicadorPuntosPorCategoria()));
        }

        return (int) $this->precio_puntos;
    }

    public function getPrecioPuntosFormateadoAttribute(): string
    {
        return number_format($this->precio_puntos_valor, 0, ',', '.') . ' pts';
    }

    public function getPrecioEtiquetaAttribute(): string
    {
        return $this->precio_euros_formateado . ' o ' . $this->precio_puntos_formateado;
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function scopeActivos(Builder $query): Builder
    {
        return $query->where('activo', true);
    }

    private function multiplicadorPuntosPorCategoria(): int
    {
        return match ($this->categoria) {
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
