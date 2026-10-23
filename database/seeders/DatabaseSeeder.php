<?php

namespace Database\Seeders;

use App\Models\Actividad;
use App\Models\Curso;
use App\Models\Evento;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@volta.edu'],
            [
                'name' => 'Administrador',
                'password' => Hash::make('Admin12345'),
                'is_admin' => true,
                'email_verified_at' => now(),
            ],
        );

        collect([
            ['nombre' => 'Primero de Secundaria', 'nivel' => 'Secundaria', 'turno' => 'Mañana'],
            ['nombre' => 'Segundo de Secundaria', 'nivel' => 'Secundaria', 'turno' => 'Mañana'],
            ['nombre' => 'Tercero de Secundaria', 'nivel' => 'Secundaria', 'turno' => 'Tarde'],
            ['nombre' => 'Cuarto de Secundaria', 'nivel' => 'Secundaria', 'turno' => 'Tarde'],
            ['nombre' => 'Quinto de Secundaria', 'nivel' => 'Secundaria', 'turno' => 'Tarde'],
            ['nombre' => 'Sexto de Primaria', 'nivel' => 'Primaria', 'turno' => 'Mañana'],
        ])->each(fn (array $curso) => Curso::firstOrCreate(['nombre' => $curso['nombre']], $curso));

        $hoy = Carbon::today();

        $eventos = [
            ['titulo' => 'Festival de Danzas Tradicionales', 'fecha' => $hoy->copy()->addDays(7)],
            ['titulo' => 'Dramatización: Bajo la Lluvia', 'fecha' => $hoy->copy()->addDays(14)],
            ['titulo' => 'Exposición de Ciencias', 'fecha' => $hoy->copy()->addDays(21)],
            ['titulo' => 'Encuentro Deportivo Intercursos', 'fecha' => $hoy->copy()->addDays(28)],
        ];

        foreach ($eventos as $datos) {
            Evento::firstOrCreate($datos);
        }

        $actividades = [
            ['Festival de Danzas Tradicionales', 'Danza folklórica andina', 'Primero de Secundaria', '08:00'],
            ['Festival de Danzas Tradicionales', 'Danza amazónica', 'Segundo de Secundaria', '08:40'],
            ['Festival de Danzas Tradicionales', 'Coro de apertura', 'Todos los cursos', '10:20'],
            ['Dramatización: Bajo la Lluvia', 'Primer acto', 'Tercero de Secundaria', '10:00'],
            ['Dramatización: Bajo la Lluvia', 'Segundo acto', 'Cuarto de Secundaria', '10:50'],
            ['Encuentro Deportivo Intercursos', 'Fútbol salón', 'Quinto de Secundaria', '07:30'],
            ['Encuentro Deportivo Intercursos', 'Voleibol', 'Sexto de Primaria', '09:15'],
            ['Encuentro Deportivo Intercursos', 'Atletismo', 'Todos los cursos', '11:00'],
        ];

        foreach ($actividades as [$titulo, $evento, $curso, $hora]) {
            $eventoModel = Evento::where('titulo', $titulo)->first();

            if (! $eventoModel) {
                continue;
            }

            Actividad::firstOrCreate([
                'evento_id' => $eventoModel->id,
                'evento' => $evento,
                'curso' => $curso,
                'hora' => $hora,
            ]);
        }
    }
}
