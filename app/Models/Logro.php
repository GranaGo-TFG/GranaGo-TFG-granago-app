<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Logro extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'nombre_logro',
        'descripcion',
        'icono',
    ];

    public function usuarios(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'logro_user')
            ->withPivot('fecha_desbloqueo');
    }
}
