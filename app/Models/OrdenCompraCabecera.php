<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrdenCompraCabecera extends Model
{
    use HasFactory;
    
    protected $guarded = [];

    public function detalleOrdenCompra(): HasMany
    {
        return $this->hasMany(OrdenCompraDetalle::class);
    }
}
