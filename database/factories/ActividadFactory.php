<?php

namespace Database\Factories;

use App\Models\Actividad;
use App\Models\Evento;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Actividad>
 */
class ActividadFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'evento_id' => Evento::factory(),
            'evento' => fake()->randomElement([
                'Danza folklórica andina',
                'Primer acto',
                'Fútbol salón',
                'Coro de apertura',
                'Voleibol',
            ]),
            'curso' => fake()->randomElement([
                'Primero de Secundaria',
                'Tercero de Secundaria',
                'Sexto de Primaria',
            ]),
            'hora' => fake()->randomElement(['07:30', '08:00', '09:00', '10:00']),
        ];
    }
}
