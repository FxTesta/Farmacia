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
        Schema::create('nota_credito_compra_detalles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('nota_credito_compra_id')->constrained('nota_credito_compras')->onDelete('cascade');
            $table->foreignId('producto_id')->constrained('productos')->onDelete('cascade');
            $table->bigInteger('codigo');
            $table->string('marca');
            $table->integer('preciocompra');
            $table->integer('cantidad');
            $table->integer('total');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nota_credito_compra_detalles');
    }
};
