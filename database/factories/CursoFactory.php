<?php

namespace Database\Factories;

use App\Models\Curso;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Curso>
 */
class CursoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nombre' => fake()->unique()->randomElement([
                'Primero de Secundaria',
                'Segundo de Secundaria',
                'Tercero de Secundaria',
                'Cuarto de Secundaria',
                'Quinto de Secundaria',
                'Sexto de Primaria',
                'Séptimo de Primaria',
            ]),
            'nivel' => fake()->randomElement(['Primaria', 'Secundaria']),
            'turno' => fake()->randomElement(['Mañana', 'Tarde']),
            'activo' => true,
        ];
    }
}
