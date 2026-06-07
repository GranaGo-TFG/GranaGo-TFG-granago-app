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

#[Fillable(['nombre', 'nickname', 'email', 'password', 'rol', 'esta_baneado', 'puntos_totales', 'racha_multiplicador', 'racha_ultimo_reto'])]
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
            'racha_ultimo_reto' => 'datetime',
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

    public function publicacionesComunidad(): HasMany
    {
        return $this->hasMany(PublicacionComunidad::class, 'user_id');
    }

    public function comentariosComunidad(): HasMany
    {
        return $this->hasMany(ComentarioComunidad::class, 'user_id');
    }

    public function publicacionesComunidadConMeGusta(): BelongsToMany
    {
        return $this->belongsToMany(PublicacionComunidad::class, 'publicacion_me_gusta', 'user_id', 'publicacion_id')
            ->withPivot('fecha_reaccion');
    }

    public function logros(): BelongsToMany
    {
        return $this->belongsToMany(Logro::class, 'logro_user')
            ->withPivot('fecha_desbloqueo');
    }

    public function inventarioProductos(): BelongsToMany
    {
        return $this->belongsToMany(Producto::class, 'inventario_producto')
            ->withPivot(['cantidad', 'ultima_compra_at'])
            ->withTimestamps();
    }

    public function getNombrePublicoAttribute(): string
    {
        $nickname = trim((string) $this->nickname);

        return $nickname !== '' ? $nickname : (string) $this->nombre;
    }

    public function getRachaMultiplicadorAttribute($value): float
    {
        return $this->multiplicadorRachaVigente();
    }

    public function multiplicadorRachaVigente(): float
    {
        $ultimoReto = $this->racha_ultimo_reto;

        if (! $ultimoReto) {
            return 1.00;
        }

        if (now()->greaterThan($ultimoReto->copy()->addDays(3))) {
            return 1.00;
        }

        return max(1.00, round((float) $this->getRawOriginal('racha_multiplicador'), 2));
    }

    public function registrarRetoCompletado(): float
    {
        $multiplicadorActual = $this->multiplicadorRachaVigente();
        $nuevoMultiplicador = round($multiplicadorActual + 0.25, 2);

        $this->forceFill([
            'racha_multiplicador' => $nuevoMultiplicador,
            'racha_ultimo_reto' => now(),
        ]);

        return $nuevoMultiplicador;
    }
}
