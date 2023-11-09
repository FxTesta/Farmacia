<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Proveedor;
use Illuminate\Http\Request;

class ProveedorController extends Controller
{
    

    public function index(Request $request)
    {
        $proveedor = Proveedor::when($request->search, function($query, $search){
            // filtra la busqueda por nombre de la empresa ,ruc o nombre del proveedor
            $query->where('empresa', 'LIKE', "%{$search}%" )->orWhere('ruc', 'LIKE', "%{$search}%");
        })
        ->paginate(15)
        ->withQueryString();

        $filters = $request->only('search');

        return Inertia::render('Proveedor/index',[
            'proveedor' => $proveedor,
            'filters' => $filters,
        ]);
    }

    public function create()
    {
        return Inertia::render('Proveedor/create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'empresa' => 'required',
            'name' => 'required|string|max:255',
            'ruc' => 'required',
            'dv',
            'direccion' => 'required|string|max:255',
            'barrio' => 'string|max:255|nullable',
            'callelateral' => 'string|max:255|nullable',
            'referencia' => 'string|max:255|nullable',
            'telefono' => 'required|max:255',
            'email' => 'nullable|string|email|max:255|unique:'.Proveedor::class,
        ]);

        Proveedor::create([
            'empresa' => $request->empresa,
            'name' => $request->name,
            'ruc' => $request->ruc,
            'dv' => $request->dv,
            'direccion' => $request->direccion,
            'barrio' => $request->barrio,
            'callelateral' => $request->callelateral,
            'referencia' => $request->referencia,
            'telefono' => $request->telefono,
            'email' => $request->email,
        ]);

        return redirect()->route('proveedor')->with('toast', 'Proveedor Creado');
        
    }
//prueba aron
    
    public function destroy(Proveedor $proveedor)
    {
        $proveedor->delete();
        return redirect()->back()->with('error', 'Proveedor Eliminado');
    }

    public function edit($proveedor_id)
    {
        $proveedor = Proveedor::find($proveedor_id);

        return Inertia::render('Proveedor/edit',[
            'proveedor' => $proveedor,
        ]);
    }

    public function update(Proveedor $proveedor)
    {
        request()->validate([
            'empresa' => ['required'],
            'name' => ['required'],
            'ruc' =>['required'],
            'dv' =>['required'],
            'direccion' =>['required'],
            'referencia',
            'telefono' =>['required'],
            'email',
            
        ]);

        $proveedor->update([
            'empresa' => request('empresa'),
            'name' => request('name'),
            'ruc' => request('ruc'),
            'dv' => request('dv'),
            'direccion' => request('direccion'),
            'referencia' => request('referencia'),
            'telefono' => request('telefono'),
            'email' => request('email'),
            
        ]);
        return redirect()->route('proveedor')->with('toast', 'Proveedor Editado');
    }

    public function facturaProveedor(Proveedor $proveedor, Request $request)
    {
        $proveedor->load('facturacompra'); //carga la relación, sirve para obtener el id de proveedor en la url (router.get)

        $facturaCompra = $proveedor->facturacompra(); // obtiene la relación entre proveedor y factura

        $facturaCompra->when($request->search, function($query, $search){
            //buscar por numero de factura o fecha
            $query->where('nrofactura', 'LIKE', "{$search}%")->orWhere('fechafactura', 'LIKE', "{$search}%");;
        }); //query de busqueda según la relación obtenida
        
        $facturaCompra = $facturaCompra->paginate(15)->withQueryString(); //se hace de esta forma por que $facturaCompra es una colección y no un objeto


        $filters = $request->only('search');

        return Inertia::render('Proveedor/listarFactura',[
          'proveedor' => $proveedor,    
          'filters' => $filters,
          'facturaCompra' => $facturaCompra,
        ]);       
    }
}
