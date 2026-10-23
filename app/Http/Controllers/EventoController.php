<?php

namespace App\Http\Controllers;

use App\Models\Actividad;
use App\Models\Curso;
use App\Models\Evento;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class EventoController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('eventos/index', [
            'eventos' => Evento::orderBy('fecha')->get()
                ->map(fn (Evento $e) => [
                    'id' => $e->id,
                    'titulo' => $e->titulo,
                    'fecha' => $e->fecha->toDateString(),
                    'actividades' => $e->actividades()->count(),
                ]),
        ]);
    }

    public function create(Request $request): Response
    {
        return Inertia::render('eventos/create', [
            'fechaSeleccionada' => $request->query('fecha'),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $evento = Evento::create($this->validar($request));

        Inertia::flash('toast', ['type' => 'success', 'message' => 'Evento creado.']);

        return to_route('eventos.show', $evento);
    }

    public function show(Evento $evento): Response
    {
        return Inertia::render('eventos/show', [
            'evento' => [
                'id' => $evento->id,
                'titulo' => $evento->titulo,
                'fecha' => $evento->fecha->toDateString(),
            ],
            'actividades' => $evento->actividades()->get()
                ->map(fn (Actividad $a) => [
                    'id' => $a->id,
                    'evento' => $a->evento,
                    'curso' => $a->curso,
                    'hora' => substr((string) $a->hora, 0, 5),
                ]),
            'cursos' => Curso::orderBy('nombre')->pluck('nombre')->all(),
        ]);
    }

    public function edit(Evento $evento): Response
    {
        return Inertia::render('eventos/edit', [
            'evento' => [
                'id' => $evento->id,
                'titulo' => $evento->titulo,
                'fecha' => $evento->fecha->toDateString(),
            ],
        ]);
    }

    public function update(Request $request, Evento $evento): RedirectResponse
    {
        $evento->update($this->validar($request));

        Inertia::flash('toast', ['type' => 'success', 'message' => 'Evento actualizado.']);

        return to_route('eventos.show', $evento);
    }

    public function destroy(Evento $evento): RedirectResponse
    {
        $evento->delete();

        Inertia::flash('toast', ['type' => 'success', 'message' => 'Evento eliminado.']);

        return to_route('calendario');
    }

    /** @return array<string, mixed> */
    private function validar(Request $request): array
    {
        return $request->validate([
            'titulo' => ['required', 'string', 'max:150'],
            'fecha' => ['required', 'date'],
        ], [], [
            'titulo' => 'título',
            'fecha' => 'fecha',
        ]);
    }
}
