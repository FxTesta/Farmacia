<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Producto;
use Illuminate\Http\Request;

class PruebaController extends Controller
{
/*     public function index(Request $request)
    {

        $producto = Producto::when($request->search, function($query, $search){
            // filtra la busqueda por marca del producto o codigo de barras o drogas
            $query->where('marca', 'LIKE', "%{$search}%" )->orWhere('codigo', 'LIKE', "{$search}%")->orWhere('droga', 'LIKE', "%{$search}%");
        })
        ->paginate(15)//SACAR ESTA PARTE PARA QUE NO REQUIERA PAGINACIÓN
        ->withQueryString();

        $filters = $request->only('search');

        return Inertia::render('Pruebas/prueba',[
            'producto' => $producto,
            'filters' => $filters,
        ]);
    } */

    public function index(Request $request)
    {


        return Inertia::render('Pruebas/prueba copy',[]);
    }

    public function buscarProducto(Request $request)
    {
        $query = $request->input('query');

        // Realiza la lógica de búsqueda utilizando el valor de $query
        $producto = Producto::where('marca', 'LIKE', "%$query%")->orWhere('codigo', 'LIKE', "{$query}%")->orWhere('droga', 'LIKE', "%{$query}%")->get();

        return response()->json($producto);
    }
}
