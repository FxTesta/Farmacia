<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class NotaCreditoCompra extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function detalleNotaCreditoCompra(): HasMany
    {
        return $this->hasMany(NotaCreditoCompraDetalle::class);
    }
}
