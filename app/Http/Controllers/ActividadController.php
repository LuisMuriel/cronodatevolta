<?php

namespace App\Http\Controllers;

use App\Models\Actividad;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ActividadController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $this->validar($request, conEvento: true);

        Actividad::create([
            'evento_id' => $request->integer('evento_id'),
            'evento' => $request->string('evento')->toString(),
            'curso' => $request->input('curso'),
            'hora' => $request->input('hora'),
        ]);

        Inertia::flash('toast', ['type' => 'success', 'message' => 'Actividad creada.']);

        return back();
    }

    public function update(Request $request, Actividad $actividad): RedirectResponse
    {
        $this->validar($request);

        $actividad->update([
            'evento' => $request->string('evento')->toString(),
            'curso' => $request->input('curso'),
            'hora' => $request->input('hora'),
        ]);

        Inertia::flash('toast', ['type' => 'success', 'message' => 'Actividad actualizada.']);

        return back();
    }

    public function destroy(Actividad $actividad): RedirectResponse
    {
        $actividad->delete();

        Inertia::flash('toast', ['type' => 'success', 'message' => 'Actividad eliminada.']);

        return back();
    }

    /** @return array<string, mixed> */
    private function validar(Request $request, bool $conEvento = false): array
    {
        return $request->validate([
            'evento_id' => [$conEvento ? 'required' : 'nullable', 'integer', 'exists:eventos,id'],
            'evento' => ['required', 'string', 'max:150'],
            'curso' => ['nullable', 'string', 'max:100'],
            'hora' => ['nullable', 'date_format:H:i'],
        ], [], [
            'evento_id' => 'evento',
            'evento' => 'evento',
            'curso' => 'curso',
            'hora' => 'hora',
        ]);
    }
}
