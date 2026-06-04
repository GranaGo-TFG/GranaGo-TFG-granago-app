<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Reto extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'creador_id',
        'nombre',
        'descripcion',
        'ubicacion_referencia',
        'archivo_multimedia',
        'fecha_inicio',
        'fecha_fin',
        'estado',
        'puntos_recompensa',
        'latitud',
        'longitud',
        'titulo_relato',
        'leyenda_relato',
        'contenido_relato',
        'cierre_relato',
    ];

    protected function casts(): array
    {
        return [
            'fecha_inicio' => 'datetime',
            'fecha_fin' => 'datetime',
            'puntos_recompensa' => 'integer',
            'latitud' => 'float',
            'longitud' => 'float',
        ];
    }

    public function creador(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creador_id');
    }

    public function validaciones(): HasMany
    {
        return $this->hasMany(ValidacionReto::class);
    }
}
