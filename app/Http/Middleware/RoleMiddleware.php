<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // Si el usuario no está logueado o no tiene el rol, lo mandamos al inicio
        // Nota: Esto asume que tienes una columna 'role' en tu tabla 'users'
        if (!$request->user() || $request->user()->role !== $role) {
            return redirect('/');
        }

        return $next($request);
    }
}
