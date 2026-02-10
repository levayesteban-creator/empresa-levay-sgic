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
        Schema::table('productos', function (Blueprint $table) {
            // Cambiamos los nombres de las columnas para que coincidan con tu código
            $table->renameColumn('categoria_id', 'id_categoria');
            $table->renameColumn('proveedor_id', 'id_proveedor');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('productos', function (Blueprint $table) {
            // Revertimos los cambios si es necesario
            $table->renameColumn('id_categoria', 'categoria_id');
            $table->renameColumn('id_proveedor', 'proveedor_id');
        });
    }
};
