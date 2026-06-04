<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nombre' => fake()->name(),
            'nickname' => fake()->unique()->userName(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'rol' => fake()->randomElement(['creador', 'usuario']),
            'puntos_totales' => fake()->numberBetween(0, 5000),
            'racha_multiplicador' => fake()->randomFloat(2, 1, 5),
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    public function admin(): static
    {
        return $this->state(fn (array $attributes) => [
            'rol' => 'admin',
            'puntos_totales' => fake()->numberBetween(1000, 5000),
            'racha_multiplicador' => fake()->randomFloat(2, 1.5, 5),
        ]);
    }

    public function creador(): static
    {
        return $this->state(fn (array $attributes) => [
            'rol' => 'creador',
        ]);
    }

    public function usuario(): static
    {
        return $this->state(fn (array $attributes) => [
            'rol' => 'usuario',
        ]);
    }
}
