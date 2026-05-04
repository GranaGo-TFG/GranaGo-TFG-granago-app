<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ValidacionReto extends Model
{
    use HasFactory;

    protected $table = 'validaciones_retos';

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'reto_id',
        'foto_prueba',
        'estado',
        'fecha_envio',
    ];

    protected function casts(): array
    {
        return [
            'fecha_envio' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function reto(): BelongsTo
    {
        return $this->belongsTo(Reto::class);
    }

    public function comentarios(): HasMany
    {
        return $this->hasMany(Comentario::class, 'validacion_id');
    }
}
