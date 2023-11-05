<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Producto extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function productoFaltante(): HasOne
    {
        return $this->hasOne(ProductosFaltantes::class);
    }

}