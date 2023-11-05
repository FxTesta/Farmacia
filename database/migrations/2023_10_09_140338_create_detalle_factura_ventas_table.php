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
        Schema::create('detalle_factura_ventas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('factura_venta_id')->constrained('factura_ventas')->onDelete('cascade');
            $table->foreignId('producto_id')->constrained('productos')->onDelete('cascade');
            $table->bigInteger('codigo');
            $table->integer('iva');
            $table->string('marca');
            $table->integer('preciopublico');
            $table->integer('descuento')->nullable();
            $table->integer('preciodescuento');
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
        Schema::dropIfExists('detalle_factura_ventas');
    }
};
