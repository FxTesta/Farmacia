<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
//use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
class FacturaCompra extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function detallefactura(): HasMany
    {
        return $this->hasMany(DetalleFacturaCompra::class);
    }

    public function notaCreditoCompra(): HasMany
    {
        return $this->hasMany(NotaCreditoCompra::class);
    }


}
