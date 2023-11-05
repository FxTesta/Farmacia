<?php

namespace App\Observers;

use App\Models\Producto;
use App\Models\ProductosFaltantes;

class ProductObserver
{
    /**
     * Handle the Producto "created" event.
     */
    public function created(Producto $producto): void
    {
        //
    }

    /**
     * Handle the Producto "updated" event.
     */
    public function updated(Producto $producto): void
    {
        if ($producto->isDirty('stock') && $producto->stock === 0 || ($producto->stockmin >= $producto->stock)) {

            $productoFaltante = ProductosFaltantes::where('producto_id', $producto->id)->first();

            if (!$productoFaltante) 
            {
                ProductosFaltantes::create([
                    'producto_id' => $producto->id,
                    'codigo' => $producto->codigo,
                    'marca' => $producto->marca,
                    'laboratorio' => $producto->laboratorio,
                    'estado' => 'Faltante',
                    'stock' => $producto->stock,
                    'stockmin' => $producto->stockmin,
                ]);
            }else{
                $productoFaltante->update([
                    'stock' => $producto->stock,
                    'stockmin' => $producto->stockmin,
                ]);
            }
        }

    }

    /**
     * Handle the Producto "deleted" event.
     */
    public function deleted(Producto $producto): void
    {
        //
    }

    /**
     * Handle the Producto "restored" event.
     */
    public function restored(Producto $producto): void
    {
        //
    }

    /**
     * Handle the Producto "force deleted" event.
     */
    public function forceDeleted(Producto $producto): void
    {
        //
    }
}
