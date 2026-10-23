<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PinController extends Controller
{
    /**
     * Valida el PIN compartido y guarda el acceso en la sesión.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'pin' => ['required', 'string'],
        ], [], [
            'pin' => 'PIN',
        ]);

        $pin = (string) config('app.calendar_pin');

        if ($pin === '' || ! hash_equals($pin, (string) $request->input('pin'))) {
            return back()->withErrors(['pin' => 'El PIN no es correcto.'])->withInput();
        }

        if ($request->user()) {
            Auth::logout();
            $request->session()->regenerate();
        }

        $request->session()->put('pin_acceso', true);

        return redirect()->intended(route('calendario'));
    }
}
