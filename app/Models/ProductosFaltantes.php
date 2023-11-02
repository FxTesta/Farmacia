<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductosFaltantes extends Model
{
    use HasFactory;
    protected $guarded = [];
    
    protected $fillable = ['codigo','marca','laboratorio','estado','stock','stockmin'];
}
