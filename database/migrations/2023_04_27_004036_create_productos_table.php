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
        Schema::create('productos', function (Blueprint $table) {
            $table->id();
            //$table->foreignId('categoria_id')->constrained()->OnDelete('cascade');
            $table->string('categoria');
            $table->string('descripcion');
            $table->string('marca');
            $table->string('droga');
            $table->string('venta');
            $table->string('laboratorio')->nullable();
            $table->date('vencimiento');
            $table->date('alerta');
            $table->bigInteger('codigo')->unique();
            $table->integer('precioventa');            
            $table->integer('preciocompra');
            $table->integer('stock')->nullable();
            $table->integer('stockmin')->nullable();
            $table->integer('descuento')->nullable();
            $table->string('presentacion')->nullable();
            $table->string('estante')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('productos');
    }
};
