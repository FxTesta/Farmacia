<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Compra;
use App\Models\DetalleFacturaCompra;
use App\Models\FacturaCompra;
use App\Models\OrdenCompraCabecera;
use App\Models\Producto;
use App\Models\ProductosFaltantes;
use App\Models\Proveedor;
use App\Models\Caja;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CompraController extends Controller
{
    //vista para registrar compra
    public function registarCompra(Request $request)
    {
        $user = auth()->user();

        return Inertia::render('Compra/RegistrarCompra', [
            'user' => $user,
        ]);
    }

    public function buscarProveedor(Request $request)
    {

        

        $query = $request->input('query');

        if (!preg_match('/[a-zA-Z0-9]/', $query)) {
            // Si no contiene ningún carácter alfabético o numérico, devolver respuesta vacía o un mensaje indicando que la búsqueda es inválida
            return response()->json([]);
        }

        // Realiza la lógica de búsqueda utilizando el valor de $query
        $proveedores = Proveedor::where('empresa', 'LIKE', "$query%")->get();

        return response()->json($proveedores);
    }

    public function buscarProducto(Request $request)
    {
        $query = $request->input('query');

        if (!preg_match('/[a-zA-Z0-9]/', $query)) {
            // Si no contiene ningún carácter alfabético o numérico, devolver respuesta vacía o un mensaje indicando que la búsqueda es inválida
            return response()->json([]);
        }

        // Realiza la lógica de búsqueda utilizando el valor de $query
        $producto = Producto::where('marca', 'LIKE', "$query%")->orWhere('codigo', 'LIKE', "{$query}%")->get();

        return response()->json($producto);
    }


    public function store(Request $request)
    {

        // se obtiene el array donde se cargo la lista de productos comprados
        $data = $request->input('arrayProductos');

        //en caso de que "arrayProductos" sea nulo o vacio no se guarda nada en la BD
        if (empty($data)) {
            // El array está vacío o nulo o contiene un valor considerado vacío por empty()
        } else {

            //validación
            $request->validate([
                'proveedorid' => 'required',
                'proveedornombre' => 'string|required',
                'codigo' => 'required',
                'usuario' => 'string|required',
                'nrofactura' => 'required|unique:' . FacturaCompra::class,
                'fechafactura' => 'required',
                'total' => 'required',
            ]);

            //Se realiza carga de cabecera factura
            FacturaCompra::create([

                'proveedor_id' => $request->proveedorid,
                'proveedor_nombre' => $request->proveedornombre,
                'users_id' => $request->codigo,
                'username' => $request->usuario,
                'nrofactura' => $request->nrofactura,
                'fechafactura' => $request->fechafactura,
                'preciototal' => $request->total,
            ]);

            //OBTENER EL ID DE FACTURA COMPRA CREADO ARRIBA, PARA EL CREATE DE ABAJO
            $factura = FacturaCompra::where('nrofactura', $request->input('nrofactura'))->first();

            // Itera a través de los datos del array y crea un nuevo registro para cada producto
            foreach ($data as $producto) {
                DetalleFacturaCompra::create([
                    'factura_compra_id' => $factura->id, //envia el id de cabecera factura para asociar con detallefacturacomrpa
                    'producto_id' => $producto['productoid'],
                    'codigo' => $producto['codigobarra'],
                    'marca' => $producto['marca'],
                    'preciocompra' => $producto['precio'],
                    'cantidad' => $producto['cantidad'],
                    'total' => $producto['total'],
                ]);


                $productos = Producto::where('id', $producto['productoid'])->first();
                $productos->update([
                    'stock' => $productos->stock + $producto['cantidad'],
                ]);

                $caja = Caja::latest()->first(); // Obtener la última caja abierta
                $caja->update([
                    'montoCierre' => $caja->montoCierre -  $request->total,
                ]);
            }

            return redirect('/compra')->with('toast', 'Compra Registrada');
        }
    }


    //LISTAR COMPRAS
    public function listarCompras(Request $request)
    {

        $lista = FacturaCompra::when($request->search, function ($query, $search) {
            //filtra la busqueda por nombre proveedor o nrofactura
            $query->where('proveedor_nombre', 'LIKE', "%{$search}%" )->orWhere('nrofactura', 'LIKE', "{$search}%")->orWhere('fechafactura', 'LIKE', "{$search}%");
        })
            ->paginate(15)
            ->withQueryString();

        $filters = $request->only('search');


        return Inertia::render('Compra/ListarCompra', [
            'lista' => $lista,
            'filters' => $filters,
        ]);
    }

    public function detalles(FacturaCompra $detallefact)
    {
        $detallefact->load('detallefactura');
        return Inertia::render('Compra/detalle', [
            'detallefact' => $detallefact,
        ]);
    }


    //Nota de credito
    public function notaCredito()
    {
        $user = auth()->user();

        return Inertia::render('Compra/NotaCredito/NotaCredito', [
            'user' => $user,
        ]);
    }

    //ORDEN DE COMPRA
    public function listarOrdenCompra(Request $request)
    {

        $ordencompra = OrdenCompraCabecera::when($request->search, function ($query, $search) {

            $query->where('proveedornombre', 'LIKE', "%{$search}%")->orWhere('id', 'LIKE', "{$search}%")->orWhere('estado', 'LIKE', "{$search}%");
        })
            ->with('detalleOrdenCompra')
            ->paginate(15)
            ->withQueryString();

        $filters = $request->only('search');

        return Inertia::render('Compra/OrdenCompra/ListarOrdenCompra', [
            'ordencompra' => $ordencompra,
            'filters' => $filters,
        ]);
    }

    //ORDEN DE COMPRA CAMBIAR ESTADO
    public function cambiarEstado(OrdenCompraCabecera $ordencompra, Request $request)
    {
        request()->validate([
            'estado' => ['required'],
            'producto' => ['required'],
        ]);

        $ordencompra->update([
            'estado' => request('estado'),
        ]);

        $data = $request->input('producto');

        foreach ($data as $producto) {

            $productos = ProductosFaltantes::where('producto_id', $producto['producto_id'])->first();

            $productos->update([
                'estado' => 'Finalizado',
            ]); 
            
        }

        return redirect('/compra/ordencompra')->with('toast', 'Orden compra Finalizada');


        
    }

    public function listarComprasProveedor(Request $request)
    {
        
        $lista = FacturaCompra::when($request->search, function($query, $search){
            //filtra la busqueda por nombre proveedor
            $query->where('proveedor_nombre', 'LIKE', "%{$search}%" );
        })
        ->paginate(15)
        ->withQueryString();

        $filters = $request->only('search');
        

        return Inertia::render('Compra/ListarCompraProveedor',[
            'lista' => $lista,
            'filters' => $filters,
        ]);
    }


}
