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
        Schema::create('factura_ventas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cliente_id')->constrained('clientes')->onDelete('cascade');
            $table->string('cliente_nombre');
            $table->foreignId('users_id')->constrained('users')->onDelete('cascade');
            $table->string('username');
            $table->string('nrofactura')->unique();
            $table->bigInteger('timbrado')->unique(); // ? 
            $table->date('fechafactura');
            $table->string('comprobante');
            $table->string('formadepago');
            $table->integer('exenta');
            $table->integer('gravadascinco');
            $table->integer('gravadasdiez');
            $table->integer('ivacinco');
            $table->integer('ivadiez');
            $table->integer('ivatotal');
            $table->integer('pagacon');
            $table->integer('cambio');
            $table->integer('preciototal');
            $table->timestamp('hora_venta')->useCurrent();//campo se crea automaticamente con la fecha y hora al momento de que se guardan los datos 'timestamp'
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('factura_ventas');
    }
};
