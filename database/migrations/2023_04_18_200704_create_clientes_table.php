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
        Schema::create('clientes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->bigInteger('cedula');
            $table->string('ruc')->nullable();
            $table->bigInteger('dv')->nullable();
            $table->string('direccion');
            $table->string('barrio')->nullable();
            $table->string('callelateral')->nullable();
            $table->string('referencia')->nullable();
            $table->bigInteger('telefono');
            $table->string('estado');
            $table->string('email')->unique()->nullable();
            $table->integer('descuento')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clientes');
    }
};
