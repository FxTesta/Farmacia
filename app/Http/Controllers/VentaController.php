<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Cliente;
use App\Models\Setting;
use App\Models\Producto;
use Brick\Math\BigInteger;
use App\Models\FacturaVenta;
use Illuminate\Http\Request;

class VentaController extends Controller
{
    public function registarVenta()
    {
        $user = auth()->user();

        $configuracion = Setting::all();

        return Inertia::render('Venta/RegistrarVenta', [
            'user' => $user,
            'configuracion' => $configuracion,
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


    public function obtenerFactura()
    {

        $ultimafactura = FacturaVenta::select('nrofactura') //select de nrofactura en factura_ventas
        ->get() //obtiene los registros
        ->map(function ($item) { //recorre el objeto
            return str_replace('-', '', $item->nrofactura); //quita los "-" de la factura
        })
        ->map(function ($item) {
            return BigInteger::of($item)->toBigInteger(); //convierte a un biginteger ej: 10010000001
        })
        ->max(); //obtiene el mayor número de factura

        return response()->json($ultimafactura); //retorna el maximo o la última factura
    }
}
