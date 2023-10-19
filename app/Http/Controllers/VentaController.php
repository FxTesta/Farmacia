<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Inertia\Inertia;
use App\Models\Producto;
use Illuminate\Http\Request;

class VentaController extends Controller
{
    public function registarVenta()
    {
        $user = auth()->user();

        return Inertia::render('Venta/RegistrarVenta', [
            'user' => $user,
        ]);
    }

    public function buscarProducto(Request $request)
    {
        $query = $request->input('query');

        // Realiza la lógica de búsqueda utilizando el valor de $query
        $producto = Producto::where('marca', 'LIKE', "%$query%")->orWhere('codigo', 'LIKE', "{$query}%")->orWhere('droga', 'LIKE', "%{$query}%")->get();

        return response()->json($producto);
    }

    public function buscarCliente(Request $request)
    {
        $query = $request->input('query');

        // Realiza la lógica de búsqueda utilizando el valor de $query
        $cliente = Cliente::where('name', 'LIKE', "%$query%")->orWhere('cedula', 'LIKE', "{$query}%")->get();

        return response()->json($cliente);
    }
}
