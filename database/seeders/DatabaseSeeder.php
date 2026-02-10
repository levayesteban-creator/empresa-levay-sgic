<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Categoria;
use App\Models\Proveedor;
use App\Models\Producto;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Usuario Admin con ROL (Corregido para Auditoría)
        User::factory()->create([
            'name' => 'Gerente Levay',
            'email' => 'admin@levay.com',
            'password' => bcrypt('admin123'),
            'role' => 'admin', // Esto es lo que pide el middleware
        ]);

        // 2. Crear Categorías
        $c1 = Categoria::create(['nombre' => 'Materia Prima']);
        $c2 = Categoria::create(['nombre' => 'Producto Terminado']);
        $c3 = Categoria::create(['nombre' => 'Repuestos']);

        // 3. Crear Proveedores
        $p1 = Proveedor::create([
            'nombre' => 'Distribuidora Global',
            'email' => 'ventas@global.com'
        ]);
        $p2 = Proveedor::create([
            'nombre' => 'Industrial C.A.',
            'email' => 'contacto@industrial.ca'
        ]);

        // 4. Crear Productos
        Producto::create([
            'nombre' => 'Cojinete de Acero',
            'precio' => 15.50,
            'stock' => 50,
            'categoria_id' => $c3->id,
            'proveedor_id' => $p2->id
        ]);

        Producto::create([
            'nombre' => 'Panel de Control V2',
            'precio' => 450.00,
            'stock' => 3,
            'categoria_id' => $c2->id,
            'proveedor_id' => $p1->id
        ]);

        Producto::create([
            'nombre' => 'Lubricante Industrial',
            'precio' => 85.00,
            'stock' => 12,
            'categoria_id' => $c1->id,
            'proveedor_id' => $p2->id
        ]);
    }
}
