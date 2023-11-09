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
    {
        Schema::create('nota_credito_compras', function (Blueprint $table) {
            $table->id();
            $table->foreignId('factura_id')->constrained('factura_compras')->onDelete('cascade');
            $table->foreignId('proveedor_id')->constrained('proveedores')->onDelete('cascade');
            $table->foreignId('users_id')->constrained('users')->onDelete('cascade');
            $table->string('nro_nota_credito')->unique();
            $table->string('nrofactura');
            $table->string('username');
            $table->string('proveedor_nombre');
            $table->date('fecha');
            $table->integer('preciototal');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nota_credito_compras');
    }
};
