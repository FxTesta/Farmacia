<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {   //a
        Schema::create('factura_compras', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proveedor_id')->constrained('proveedores')->onDelete('cascade');
            $table->string('proveedor_nombre');
            $table->foreignId('users_id')->constrained('users')->onDelete('cascade');
            $table->string('username');
            $table->bigInteger('nrofactura')->unique();
            $table->date('fechafactura');
            $table->integer('preciototal'); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('factura_compras');
    }
};
