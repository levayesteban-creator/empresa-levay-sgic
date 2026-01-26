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
        // 1. Usuario Admin (Nueva Identidad del Sistema)
        User::factory()->create([
            'name' => 'Gerente Levay',
            'email' => 'admin@levay.com',
            'password' => bcrypt('admin123'),
        ]);

        // 2. Crear Categorías para el rubro Industrial
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

        // 4. Crear Productos (Variados para demostrar el funcionamiento)
        Producto::create([
            'nombre' => 'Cojinete de Acero',
            'precio' => 15.50,
            'stock' => 50,
            'id_categoria' => $c3->id,
            'id_proveedor' => $p2->id
        ]);

        Producto::create([
            'nombre' => 'Panel de Control V2',
            'precio' => 450.00,
            'stock' => 3,
            'id_categoria' => $c2->id,
            'id_proveedor' => $p1->id
        ]);

        Producto::create([
            'nombre' => 'Lubricante Industrial',
            'precio' => 85.00,
            'stock' => 12,
            'id_categoria' => $c1->id,
            'id_proveedor' => $p2->id
        ]);
    }
}
