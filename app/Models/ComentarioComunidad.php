<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ComentarioComunidad extends Model
{
    use HasFactory;

    protected $table = 'comentarios_comunidad';

    public $timestamps = false;

    protected $fillable = [
        'publicacion_id',
        'user_id',
        'contenido',
        'fecha_comentario',
    ];

    protected function casts(): array
    {
        return [
            'fecha_comentario' => 'datetime',
        ];
    }

    public function publicacion(): BelongsTo
    {
        return $this->belongsTo(PublicacionComunidad::class, 'publicacion_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
