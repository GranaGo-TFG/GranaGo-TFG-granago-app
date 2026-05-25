<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PublicacionComunidad extends Model
{
    use HasFactory;

    protected $table = 'publicaciones_comunidad';

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'contenido',
        'imagen',
        'fecha_publicacion',
    ];

    protected function casts(): array
    {
        return [
            'fecha_publicacion' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function comentarios(): HasMany
    {
        return $this->hasMany(ComentarioComunidad::class, 'publicacion_id');
    }
}
