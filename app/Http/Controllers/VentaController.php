<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Cliente;
use App\Models\DetalleFacturaVenta;
use App\Models\Setting;
use App\Models\Producto;
use Brick\Math\BigInteger;
use App\Models\FacturaVenta;
use Illuminate\Http\Request;

class VentaController extends Controller
{

    public function __construct()
    {
        $this->middleware('caja.abierta');
    }
    
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

        //Solución para que no obtenga resultados al presionar "Espacio"
        if (!preg_match('/[a-zA-Z0-9]/', $query)) {
            // Si no contiene ningún carácter alfabético o numérico, devolver respuesta vacía o un mensaje indicando que la búsqueda es inválida
            return response()->json([]);
        }

        // Realiza la lógica de búsqueda utilizando el valor de $query
        $producto = Producto::where('marca', 'LIKE', "%$query%")->orWhere('codigo', 'LIKE', "{$query}%")->orWhere('droga', 'LIKE', "%{$query}%")->get();

        return response()->json($producto);
    }

    public function buscarCliente(Request $request)
    {
        $query = $request->input('query');

        if (!preg_match('/[a-zA-Z0-9]/', $query)) {
            // Si no contiene ningún carácter alfabético o numérico, devolver respuesta vacía o un mensaje indicando que la búsqueda es inválida
            return response()->json([]);
        }

        // Realiza la lógica de búsqueda utilizando el valor de $query
        $cliente = Cliente::where('name', 'LIKE', "%$query%")->orWhere('cedula', 'LIKE', "{$query}%")->get();

        return response()->json($cliente);
    }


    public function obtenerFactura()
    {
        // Obtiene el valor de "nrofactura" de la tabla "settings" que sería el prefijo de la factura
        $nrofactura = Setting::first()->nrofactura;

        // Filtra solo que obtenga las facturas con el prefijo de factura configurado
        $filteredData = FacturaVenta::where('nrofactura', 'LIKE', "{$nrofactura}%")
            ->orderBy('fechafactura', 'desc') // Ordena por fecha de forma descendente para obtener el más reciente
            ->get();

        // Obtiene el valor máximo del numero de factura
        $ultimafactura = $filteredData
            ->map(function ($item) { //recorre el objeto
                return str_replace('-', '', $item->nrofactura); //quita los "-" de la factura
            })
            ->map(function ($item) {
                return BigInteger::of($item)->toBigInteger(); //convierte a un biginteger ej: 10010000001
            })
            ->max(); //obtiene el mayor número de factura 

        return response()->json($ultimafactura); //retorna el maximo o la última factura
    }

    public function store(Request $request)
    {

        // se obtiene el array donde se cargo la lista de productos vendidos
        $data = $request->input('arrayProductos');

        //en caso de que "arrayProductos" sea nulo o vacio no se guarda nada en la BD
        if (empty($data)) {
            // El array está vacío o nulo o contiene un valor considerado vacío por empty()
        } else {

            //validación
            $request->validate([
                'usuario' => 'string|required',
                'codigo' => 'required',
                'formadepago' => 'required',
                'fechafactura' => 'required',
                'clienteid' => 'required',
                'clientenombre' => 'string|required',
                'comprobante' => 'required',
                'nrofactura' => 'required|unique:' . FacturaVenta::class,
                'timbrado' => 'required',
                'exenta' => 'required',
                'gravadascinco' => 'required',
                'gravadasdiez' => 'required',
                'ivacinco' => 'required',
                'ivadiez' => 'required',
                'ivatotal' => 'required',
                'pagacon' => 'nullable',
                'cambio' => 'nullable',
                'preciototal' => 'required',
                'montoahorrado' => 'required',
                'nrotransaccion' => 'nullable',
                'importemixto' => 'nullable',
                'tarjetamixto' => 'nullable',
            ]);

            //Se realiza carga de cabecera factura
            FacturaVenta::create([

                'cliente_id' => $request->clienteid,
                'cliente_nombre' => $request->clientenombre,
                'users_id' => $request->codigo,
                'username' => $request->usuario,
                'nrofactura' => $request->nrofactura,
                'timbrado' => $request->timbrado,
                'fechafactura' => $request->fechafactura,
                'comprobante' => $request->comprobante,
                'formadepago' => $request->formadepago,
                'exenta' => $request->exenta,
                'gravadascinco' => $request->gravadascinco,
                'gravadasdiez' => $request->gravadasdiez,
                'ivacinco' => $request->ivacinco,
                'ivadiez' => $request->ivadiez,
                'ivatotal' => $request->ivatotal,
                'pagacon' => $request->pagacon,
                'cambio' => $request->cambio,
                'preciototal' => $request->preciototal,
                'montoahorrado' => $request->montoahorrado,
                'nrotransaccion' => $request->nrotransaccion,
                'importemixto' => $request->importemixto,
                'tarjetamixto' => $request->tarjetamixto,
            ]);

            //OBTENER EL ID DE FACTURA VENTA CREADO ARRIBA, PARA EL CREATE DE ABAJO
            $factura = FacturaVenta::where('nrofactura', $request->input('nrofactura'))->first();

            // Itera a través de los datos del array y crea un nuevo registro para cada producto
            foreach ($data as $producto) {
                DetalleFacturaVenta::create([
                    'factura_venta_id' => $factura->id, //envia el id de cabecera factura para asociar con detallefacturaventa
                    'producto_id' => $producto['productoid'],
                    'codigo' => $producto['codigobarra'],
                    'iva' => $producto['iva'],
                    'marca' => $producto['marca'],
                    'preciopublico' => $producto['preciopublico'],
                    'descuento' => $producto['descuento'],
                    'preciodescuento' => $producto['preciodescuento'],
                    'cantidad' => $producto['cantidad'],
                    'total' => $producto['total'],
                ]);


                $productos = Producto::where('id', $producto['productoid'])->first();
                $productos->update([
                    'stock' => $productos->stock - $producto['cantidad'],
                ]);
            }

            return redirect('/venta')->with('toast', 'Venta Realizada');
        }
    }


    public function listarVentas(Request $request)
    {
        
        $lista = FacturaVenta::when($request->search, function($query, $search){
            //filtra la busqueda por nombre proveedor o nrofactura
            $query->where('cliente_nombre', 'LIKE', "%{$search}%" )->orWhere('nrofactura', 'LIKE', "{$search}%")->orWhere('fechafactura', 'LIKE', "{$search}%");
        })
        ->paginate(15)
        ->withQueryString();

        $filters = $request->only('search');
        

        return Inertia::render('Venta/ListarVenta',[
            'lista' => $lista,
            'filters' => $filters,
        ]);
    }

    public function detalleVenta(FacturaVenta $detallefact)
    {
        $detallefact->load('detallefactura');
        $config = Setting::all();
        return Inertia::render('Venta/DetalleFactura',[
          'detallefact' => $detallefact,         
          'config' => $config,         
        ]);
       
       
    }

    public function obtenerFacturaId()
    {
        $ultimoID = FacturaVenta::latest()->first()->id;

        return response()->json($ultimoID); //retorna el último id factura venta
    }
}