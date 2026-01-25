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
        Schema::create('movimientos', function (Blueprint $table) {
            $table->id();
            // Relación con el producto
            $table->foreignId('id_producto')->constrained('productos')->onDelete('cascade');
            
            // Tipo de movimiento (Entrada/Salida)
            $table->enum('tipo', ['Entrada', 'Salida']);
            
            $table->integer('cantidad');
            $table->string('motivo')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('movimientos');
    }
};