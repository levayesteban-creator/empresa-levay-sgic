<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Producto;
use App\Models\Categoria;
use App\Models\Proveedor;

class ProductoSeeder extends Seeder
{
    public function run(): void
    {
        // Buscamos el primer registro de cada uno para usarlos como referencia
        $categoria = Categoria::first();
        $proveedor = Proveedor::first();

        // Verificamos que existan para evitar errores si las tablas están vacías
        if ($categoria && $proveedor) {
            Producto::create([
                'nombre' => 'Laptop Gamer Pro',
                'descripcion' => 'Procesador de última generación y 16GB RAM',
                'precio' => 1200.50,
                'stock' => 10,
                'categoria_id' => $categoria->id, // Corregido: antes id_categoria
                'proveedor_id' => $proveedor->id, // Corregido: antes id_proveedor
            ]);

            Producto::create([
                'nombre' => 'Mouse Inalámbrico',
                'descripcion' => 'Ergonómico con batería recargable',
                'precio' => 25.00,
                'stock' => 50,
                'categoria_id' => $categoria->id, // Corregido: antes id_categoria
                'proveedor_id' => $proveedor->id, // Corregido: antes id_proveedor
            ]);
        }
    }
}
