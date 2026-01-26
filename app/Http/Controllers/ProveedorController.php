<?php

namespace App\Http\Controllers;

use App\Models\Proveedor;
use App\Models\Auditoria;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProveedorController extends Controller
{
    public function index()
    {
        return Inertia::render('Proveedores/Index', [
            'proveedores' => Proveedor::all()
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'email' => 'required|email|unique:proveedores,email',
        ]);

        Proveedor::create($validated);

        // Registro de Auditoría
        Auditoria::registrar('Creación', 'Proveedores', 'Se registró al proveedor: ' . $request->nombre);

        return redirect()->route('proveedores.index');
    }

    public function update(Request $request, Proveedor $proveedor)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'email' => 'required|email|unique:proveedores,email,' . $proveedor->id,
        ]);

        $proveedor->update($validated);

        // Registro de Auditoría
        Auditoria::registrar('Edición', 'Proveedores', 'Se actualizaron los datos del proveedor: ' . $proveedor->nombre);

        return redirect()->route('proveedores.index');
    }

    public function destroy(Proveedor $proveedor)
    {
        $nombre = $proveedor->nombre;
        $proveedor->delete();

        // Registro de Auditoría
        Auditoria::registrar('Eliminación', 'Proveedores', 'Se eliminó al proveedor: ' . $nombre);

        return redirect()->route('proveedores.index');
    }
}
