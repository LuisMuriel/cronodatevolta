<?php

namespace Database\Factories;

use App\Models\Evento;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Evento>
 */
class EventoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'titulo' => fake()->randomElement([
                'Festival de Danzas Tradicionales',
                'Dramatización',
                'Exposición de Ciencias',
                'Encuentro Deportivo',
                'Ceremonia de Graduación',
            ]),
            'fecha' => fake()->dateTimeBetween('+1 week', '+3 months')->format('Y-m-d'),
        ];
    }
}
