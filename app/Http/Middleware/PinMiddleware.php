<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PinMiddleware
{
    /**
     * Permite el acceso a quienes hayan ingresado con el PIN compartido
     * o a los administradores autenticados.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user()?->is_admin || $request->session()->get('pin_acceso')) {
            return $next($request);
        }

        return redirect()->route('home');
    }
}
