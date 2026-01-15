<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
     public function handle(Request $request, Closure $next): Response
    {
        if(!$request->user() || !$request->user()->is_admin){
          return response()->json([
            'message' => 'Acceso denegado. Se requieren privilegios de administrador para realizar esta acción'
          ], 403);
        }

        return $next($request);
    }
}
