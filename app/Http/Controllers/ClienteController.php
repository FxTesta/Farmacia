<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Inertia\Inertia;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    

    public function index(Request $request)
    {
        $cliente = Cliente::when($request->search, function($query, $search){
            // filtra la busqueda por nombre o cedula o nombre del cliente
            $query->where('name', 'LIKE', "%{$search}%" )->orWhere('cedula', 'LIKE', "%{$search}%");
        })
        ->paginate(15)
        ->withQueryString();

        $filters = $request->only('search');

        return Inertia::render('Cliente/index',[
            'cliente' => $cliente,
            'filters' => $filters,
        ]);
    }

    public function create()
    {
        return Inertia::render('Cliente/create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'cedula' => 'required',
            'ruc' => 'string|max:255|nullable',
            'dv',
            'direccion' => 'required|string|max:255',
            'barrio' => 'string|max:255|nullable',
            'callelateral' => 'string|max:255|nullable',
            'referencia' => 'string|max:255|nullable',
            'telefono' => 'required|max:255',
            'estado' => 'max:255',
            'email' => 'nullable|string|email|max:255|unique:'.Cliente::class,
            'descuento' => 'max:255|nullable',
        ]);

        Cliente::create([
            'name' => $request->name,
            'cedula' => $request->cedula,
            'ruc' => $request->ruc,
            'dv' => $request->dv,
            'direccion' => $request->direccion,
            'barrio' => $request->barrio,
            'callelateral' => $request->callelateral,
            'referencia' => $request->referencia,
            'telefono' => $request->telefono,
            'estado' => $request->estado,
            'email' => $request->email,
            'descuento' => $request->descuento,
        ]);

        return redirect()->route('cliente')->with('toast', 'Cliente Creado');
        
    }

    public function destroy(Cliente $cliente)
    {
        $cliente->delete();
        return redirect()->back()->with('error', 'Cliente Eliminado');
    }

    public function edit($cliente_id)
    {
        $cliente = Cliente::find($cliente_id);

        return Inertia::render('Cliente/edit',[
            'cliente' => $cliente,
        ]);
    }

    public function update(Cliente $cliente)
    {
        request()->validate([
            'name' => ['required'],
            'cedula' =>['required'],
            'ruc',
            'dv',
            'direccion' =>['required'],
            'referencia',
            'telefono' =>['required'],
            'estado' ,
            'email' ,
            'descuento',
            
        ]);

        $cliente->update([
            'name' => request('name'),
            'cedula' => request('cedula'),
            'ruc' => request('ruc'),
            'dv' => request('dv'),
            'direccion' => request('direccion'),
            'referencia' => request('referencia'),
            'telefono' => request('telefono'),
            'estado' => request('estado'),
            'email' => request('email'),
            'descuento' => request('descuento'),
            
        ]);
        return redirect()->route('cliente')->with('toast', 'Cliente Editado');
    }

    public function facturaCliente(Cliente $cliente, Request $request)
    {
        $cliente->load('facturaventa'); //carga la relación, sirve para obtener el id de cliente en la url (router.get)

        $facturaVenta = $cliente->facturaventa(); // obtiene la relación entre cliente y factura

        $facturaVenta->when($request->search, function($query, $search){
            //buscar por numero de factura o fecha
            $query->where('nrofactura', 'LIKE', "{$search}%")->orWhere('fechafactura', 'LIKE', "{$search}%");;
        }); //query de busqueda según la relación obtenida
        
        $facturaVenta = $facturaVenta->paginate(15)->withQueryString(); //se hace de esta forma por que $facturaCompra es una colección y no un objeto


        $filters = $request->only('search');

        return Inertia::render('Cliente/listarFactura',[
          'cliente' => $cliente,    
          'filters' => $filters,
          'facturaVenta' => $facturaVenta,
        ]);       
    }  
    
}
