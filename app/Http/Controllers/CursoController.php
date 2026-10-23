<?php

namespace App\Http\Controllers;

use App\Models\Curso;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class CursoController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('cursos/index', [
            'cursos' => Curso::orderBy('nombre')->get()
                ->map(fn (Curso $c) => [
                    'id' => $c->id,
                    'nombre' => $c->nombre,
                    'nivel' => $c->nivel,
                    'turno' => $c->turno,
                ]),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        Curso::create($this->validar($request));

        Inertia::flash('toast', ['type' => 'success', 'message' => 'Curso creado.']);

        return to_route('cursos.index');
    }

    public function update(Request $request, Curso $curso): RedirectResponse
    {
        $curso->update($this->validar($request));

        Inertia::flash('toast', ['type' => 'success', 'message' => 'Curso actualizado.']);

        return to_route('cursos.index');
    }

    public function destroy(Curso $curso): RedirectResponse
    {
        $curso->delete();

        Inertia::flash('toast', ['type' => 'success', 'message' => 'Curso eliminado.']);

        return to_route('cursos.index');
    }

    /** @return array<string, mixed> */
    private function validar(Request $request): array
    {
        return $request->validate([
            'nombre' => ['required', 'string', 'max:100'],
            'nivel' => ['nullable', 'string', 'max:50'],
            'turno' => ['nullable', 'string', 'max:50'],
        ], [], [
            'nombre' => 'nombre',
            'nivel' => 'nivel',
            'turno' => 'turno',
        ]);
    }
}
