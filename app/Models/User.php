<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['nombre', 'email', 'password', 'rol', 'esta_baneado', 'puntos_totales', 'racha_multiplicador'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'esta_baneado' => 'boolean',
            'puntos_totales' => 'integer',
            'racha_multiplicador' => 'decimal:2',
        ];
    }

    public function retosCreados(): HasMany
    {
        return $this->hasMany(Reto::class, 'creador_id');
    }

    public function validacionesRetos(): HasMany
    {
        return $this->hasMany(ValidacionReto::class);
    }

    public function comentarios(): HasMany
    {
        return $this->hasMany(Comentario::class);
    }

    public function logros(): BelongsToMany
    {
        return $this->belongsToMany(Logro::class, 'logro_user')
            ->withPivot('fecha_desbloqueo');
    }
}
