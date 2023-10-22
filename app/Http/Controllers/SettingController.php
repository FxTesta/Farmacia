<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Inertia\Inertia;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {

        $configuracion = Setting::all();

        return Inertia::render('Configuracion/index', [
            'configuracion' => $configuracion
        ]);
    }

    public function create()
    {
        return Inertia::render('Configuracion/create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nrofactura' => 'required|regex:/^[0-9]{3}-[0-9]{3}$/',
            'timbrado' => 'required',
        ]);

        Setting::create([
            'nrofactura' => $request->nrofactura,
            'timbrado' => $request->timbrado,
        ]);

        return redirect()->route('configuracion')->with('toast', 'Configuración Establecida');
        
    }

    public function edit($configuracion_id)
    {
        $configuracion = Setting::find($configuracion_id);

        return Inertia::render('Configuracion/edit',[
            'configuracion' => $configuracion,
        ]);
    }

    public function update(Setting $configuracion)
    {
        request()->validate([
            'nrofactura' => ['required', 'regex:/^[0-9]{3}-[0-9]{3}$/'],
            'timbrado' =>['required'],
        ]);

        $configuracion->update([
            'nrofactura' => request('nrofactura'),
            'timbrado' => request('timbrado'),
            
        ]);
        return redirect()->route('configuracion')->with('toast', 'Configuracion Editada');
    }

}
