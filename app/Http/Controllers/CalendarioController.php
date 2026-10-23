<?php

namespace App\Http\Controllers;

use App\Models\Actividad;
use App\Models\Curso;
use App\Models\Evento;
use Inertia\Inertia;
use Inertia\Response;

class CalendarioController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('calendario', [
            'eventos' => Evento::orderBy('fecha')->get()
                ->map(fn (Evento $e) => [
                    'id' => $e->id,
                    'title' => $e->titulo,
                    'date' => $e->fecha->toDateString(),
                ]),
        ]);
    }

    public function cronograma(): Response
    {
        return Inertia::render('cronograma', [
            'actividades' => Actividad::with('evento')->orderBy('hora')->get()
                ->map(fn (Actividad $a) => [
                    'id' => $a->id,
                    'evento_id' => $a->evento_id,
                    'evento' => $a->evento,
                    'curso' => $a->curso,
                    'hora' => substr((string) $a->hora, 0, 5),
                    'fecha' => $a->fecha,
                    'titulo_evento' => $a->titulo_evento,
                ]),
            'cursos' => Curso::orderBy('nombre')->pluck('nombre')->all(),
        ]);
    }
}
