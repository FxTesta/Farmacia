<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Cliente extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = 'clientes';

    public function facturaventa(): HasMany
    {
        return $this->hasMany(FacturaVenta::class);
    }

}
