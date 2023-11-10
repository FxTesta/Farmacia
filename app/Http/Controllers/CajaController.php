<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Caja;

class CajaController extends Controller
{

    
    public function abrir(Request $request)
    {
        $caja = new Caja([
            'monto_apertura' => $request->monto_apertura,
            'fecha_apertura' => now(),
        ]);
        $caja->save();

        return response()->json($caja, 200);
    }

    public function cerrar(Request $request, $id)
    {
        $caja = Caja::find($id);
        $caja->monto_cierre = $request->monto_cierre;
        $caja->fecha_cierre = now();
        $caja->save();

        return response()->json($caja, 200);
    }
}
