<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Proveedor;

class ProveedorSeeder extends Seeder
{
    public function run(): void
    {
        Proveedor::create([
            'nombre' => 'Suministros Globales S.A.',
            'contacto_persona' => 'Juan Pérez',
            'telefono' => '555-0123',
            'email' => 'ventas@global.com',
            'direccion' => 'Calle Industrial 456'
        ]);

        Proveedor::create([
            'nombre' => 'Distribuidora Levay Mayorista',
            'contacto_persona' => 'Elena Levay',
            'telefono' => '555-9876',
            'email' => 'contacto@levay.com',
            'direccion' => 'Avenida Principal 123'
        ]);
    }
}