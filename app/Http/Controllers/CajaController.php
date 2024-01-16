<?php

namespace App\Http\Controllers;

use App\Models\Caja;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class CajaController extends Controller
{

    public function index(Request $request)
    {
        $caja = Caja::all();
        return Inertia::render('Caja/index',[
            'caja' => $caja
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'monto' => 'required|numeric|min:0',
        ]);

        Caja::create([
            'monto' => $request->monto,
            'users_id' => Auth::id(), 
            'username' => Auth::user()->name, 
            'fecha_apertura' => now(),
            'estado' => 'Abierta', 
            
        ]);

        return redirect()->route('caja')->with('toast', 'Caja abierta con éxito');
      
    }

    public function close(Request $request, $id)
{
    $caja = Caja::find($id);
    if ($caja && $caja->estado == 'Abierta') {
        $caja->update([
            'fecha_cierre' => now(),
            'estado' => 'Cerrada',
        ]);

        return redirect()->route('caja')->with('toast', 'Caja cerrada con éxito');
    }

    return redirect()->route('caja')->with('error', 'La caja ya está cerrada o no fue encontrada');
}

}