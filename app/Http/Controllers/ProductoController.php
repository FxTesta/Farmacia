<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Inertia\Inertia;
use App\Models\Producto;
use App\Models\Categoria;
use App\Models\StockAudit;
use Illuminate\Http\Request;
use App\Models\OrdenDeCompra;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

class ProductoController extends Controller
{
    

    public function index(Request $request)
    {
        //$producto = Producto::all();


        /*$producto = Producto::query()
        ->when($request->search, function ($query, $search) {
            $query->where('marca', 'like', "%{$search}%");
        })
        ->
        //through sirve para traer campos en especifico de la base de datos, pero por alguna razón "through" no funciona, al parecer se cambio la sintaxis
        through(fn($producto) => [
            'marca' => $producto->marca,
        ])
        ;*/


        //hola
        $producto = Producto::when($request->search, function($query, $search){
            // filtra la busqueda por marca del producto o codigo de barras
            $query->where('marca', 'LIKE', "%{$search}%" )->orWhere('codigo', 'LIKE', "{$search}%")->orWhere('droga', 'LIKE', "%{$search}%");
        })
        ->paginate(15)
        ->withQueryString();

        $filters = $request->only('search');

        return Inertia::render('Producto/index',[
            'producto' => $producto,
            'filters' => $filters,
        ]);
    }


    public function create()
    {    

        //añadir categoría para que cargue

        /*$categoria = Categoria::all();

        return Inertia::render('Producto/create',[
            'categoria' => $categoria,
            
        ]);*/


       return Inertia::render('Producto/create');
    }

    public function store(Request $request)
    {
        $request->validate([
            //'categoria_id' => 'required',
            'categoria' => 'required',
            'descripcion' => 'required',
            'marca' => 'string|required',
            'droga' => 'string|required',
            'venta' => 'required',
            'laboratorio' => 'string|nullable',
            'vencimiento' => 'nullable',
            'alerta' => 'nullable|before_or_equal:vencimiento',
            'codigo' => 'required',
            'codigo' => 'required|unique:'.Producto::class,
            'precioventa' => 'required',
            'preciocompra' => 'nullable',
            'stock' => 'nullable',
            'stockmin' => 'nullable',
            'descuento' => 'nullable',
            'presentacion' => 'nullable',
            'estante' => 'nullable',
        ]);

        // para crear un campo con el nombre de la cateogría
        //$categorianombre = Categoria::where('id', $request->input('categoria_id'))->first();

        Producto::create([
            'categoria' => $request->categoria,
            'descripcion' => $request->descripcion,
            'marca' => $request->marca,
            'droga' => $request->droga,
            'venta' => $request->venta,
            'laboratorio' => $request->laboratorio,
            'vencimiento' => $request->vencimiento,
            'alerta' => $request->alerta,
            'codigo' => $request->codigo,
            'precioventa' => $request->precioventa,
            'preciocompra' => $request->preciocompra,
            'stock' => $request->stock,
            'stockmin' => $request->stockmin,
            'descuento' => $request->descuento,
            'presentacion' => $request->presentacion,
            'estante' => $request->estante,
            //'categorianombre' => $categorianombre->name,
        ]);
        

        return redirect()->route('producto')->with('toast', 'Producto Creado');
        
    }

   

    public function destroy(Producto $producto)
    {
        $producto->delete();
        return redirect()->back()->with('error', 'Producto Eliminado');
    }
    
    public function edit($producto_id)
    {
        $producto = Producto::find($producto_id);

        return Inertia::render('Producto/edit',[
            'producto' => $producto,
        ]);
    }

    public function updatestock(Producto $productos){
        $cantidad = request('stock');
        $user = auth()->user();
        $userName = $user->name;    
        date_default_timezone_set('America/Asuncion'); 
        $hora = date('H:i:s');
        $fecha = Carbon::now();
        if(($productos->stock >= $cantidad ) and ($cantidad > 0)){
            $productos->update([
            'stock' => $productos->stock-$cantidad,
            
            ]);
            
           // $sprint1 = Sprint::where('project_id', $project->id)->get()->pluck('id');

           StockAudit::create([
                              
                'usuario'=> $userName,
                'id_articulo'=>$productos->id,
                'codigo'=>$productos->codigo,
                'articulo'=>$productos->marca,
                'stockanterior'=>$productos->stock + $cantidad,
                'stockactual'=>$productos->stock,
                'hora'=>$hora,
                'fecha'=>$fecha,
            ]);


            return redirect()->route('producto')->with('toast', 'Stock Actualizado');
        }
     

    }
    public function update(Producto $producto)
    {
        request()->validate([
            'categoria' => ['required'],
            'descripcion' => ['required'],
            'marca' => ['required'],
            'droga' => ['required'],
            'venta' => ['required'],
            'laboratorio' => ['nullable'],
            'vencimiento' => ['nullable'],
            'alerta' => ['nullable'],
            'codigo' => ['nullable'],
            'precioventa' => ['required'],
            'preciocompra' => ['nullable'],
            'stock' => ['nullable'],
            'stockmin' => ['nullable'],
            'descuento' => ['nullable'],
            'presentacion' => ['nullable'],
            'estante' => ['nullable'],
            
            
        ]);
        $ven = request('vencimiento');
        $aler = request('alerta');
        $producto->update([
            
            'categoria' => request('categoria'),
            'descripcion' => request('descripcion'),
            'marca' => request('marca'),
            'droga' => request('droga'),
            'venta' => request('venta'),
            'laboratorio' => request('laboratorio'),
            'vencimiento' => request('vencimiento'),
            'codigo' => request('codigo'),
            'precioventa' => request('precioventa'),
            'preciocompra' => request('preciocompra'),
            'stock' => request('stock'),
            'stockmin' => request('stockmin'),
            'descuento' => request('descuento'),
            'presentacion' => request('presentacion'),
            'estante' => request('estante'),
            
        ]);
        if($ven > $aler){
            $producto->update([
             'alerta' => request('alerta'),
            ]);
            return redirect()->route('producto')->with('toast', 'Producto Editado');
         };
        
    }
//mostrar los productos con stock minimo
    public function faltantes(Request $request)
    {   
         Producto::whereNull('stock') // si hay algun producto con stock nulo actualiza a cero
        ->update(['stock' => 0]);

        $producto = Producto::when($request->search, function($query, $search){
            // filtra la busqueda por marca del producto o codigo de barras
            $query->where('marca', 'LIKE', "%{$search}%" )->orWhere('codigo', 'LIKE', "{$search}%")->orWhere('laboratorio', 'LIKE', "%{$search}%");
        })
        ->where(function($query) {
            $query->where('stock', '<=', DB::raw('COALESCE(stockmin, 0)'));
            $query->orWhereNull('stockmin');
        })
        ->paginate(15)
        ->withQueryString();

        $filters = $request->only('search');

        return Inertia::render('Producto/ProductosFaltantes',[
            'producto' => $producto,
            'filters' => $filters,
        ]);
    }
//cargar orden de productos faltantes
    public function crearorden(Producto $productos){
        $cantidad = request('stock');
          
        OrdenDeCompra::create([
                            
                'codigo'=> $productos->codigo,
                'marca'=>$productos->marca,
                'laboratorio'=>$productos->laboratorio,
                'estado'=>'Pendiente',
                'cantidad'=>$cantidad,
        ]);


        return redirect()->route('faltantes');
    }   
    
    public function indexorden(Request $request)
    {
        $productos = OrdenDeCompra::all();
        return response()->json(['productos' => $productos]);
    }
};
