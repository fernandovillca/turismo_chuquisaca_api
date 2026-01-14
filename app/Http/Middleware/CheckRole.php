<?php

namespace App\Http\Middleware;

use App\Http\Helpers\ApiResponse;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (!$request->user()) {
            return ApiResponse::unauthorized('Debes iniciar sesión para acceder a este recurso');
        }

        if (!in_array($request->user()->role, $roles)) {
            return ApiResponse::forbidden('No tienes permisos para realizar esta acción');
        }

        return $next($request);
    }
}
