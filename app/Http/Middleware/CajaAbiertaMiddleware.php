<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Caja;

class CajaAbiertaMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Lógica para verificar si la caja está abierta
        $cajaAbierta = Caja::where('estado', 'abierta')->first();

        if (!$cajaAbierta) {
            // Si la caja no está abierta, redirecciona a caja
            return redirect()->route('caja'); // Ajusta esto con la ruta de tu página de apertura de caja
        }

        return $next($request);
    }
}
