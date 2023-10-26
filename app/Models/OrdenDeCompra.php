<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrdenDeCompra extends Model
{
    use HasFactory;
    protected $guarded = [];
    
    protected $fillable = ['codigo','marca','laboratorio','estado','cantidad'];
}
