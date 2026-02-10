<?php

namespace App\Http\Controllers;

use App\Models\Proveedor;
use App\Models\Auditoria;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProveedorController extends Controller
{
    /**
     * Muestra la lista de proveedores.
     * He añadido un orden descendente para que veas los nuevos registros arriba.
     */
    public function index()
    {
        return Inertia::render('Proveedores/Index', [
            'proveedores' => Proveedor::orderBy('id', 'desc')->get()
        ]);
    }

    /**
     * Registra un nuevo proveedor.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre'           => 'required|string|max:255',
            'contacto_persona' => 'nullable|string|max:255',
            'telefono'         => 'nullable|string|max:20',
            // Corregido: 'proveedores' suele ser el nombre estándar de la tabla en plural
            'email'            => 'required|email|unique:proveedores,email',
            'direccion'        => 'nullable|string',
        ]);

        Proveedor::create($validated);

        // Registro de Auditoría
        Auditoria::registrar('Creación', 'Proveedores', 'Se registró al proveedor: ' . $request->nombre);

        return redirect()->route('proveedores.index')->with('success', 'Proveedor creado con éxito.');
    }

    /**
     * Actualiza los datos de un proveedor existente.
     */
    public function update(Request $request, $id)
    {
        // 1. Buscamos primero al proveedor
        $proveedor = Proveedor::findOrFail($id);

        // 2. Validamos los datos.
        // La regla unique incluye el ID para que no de error al guardar el mismo email del proveedor.
        $validated = $request->validate([
            'nombre'           => 'required|string|max:255',
            'contacto_persona' => 'nullable|string|max:255',
            'telefono'         => 'nullable|string|max:20',
            'email'            => 'required|email|unique:proveedores,email,' . $id,
            'direccion'        => 'nullable|string',
        ]);

        // 3. Actualizamos
        $proveedor->update($validated);

        // 4. Auditoría detallada
        Auditoria::registrar(
            'Edición',
            'Proveedores',
            "Se actualizaron los datos del proveedor ID {$id}: " . $proveedor->nombre
        );

        return redirect()->route('proveedores.index')->with('success', 'Proveedor actualizado con éxito.');
    }

    /**
     * Elimina un proveedor.
     */
    public function destroy($id)
    {
        $proveedor = Proveedor::findOrFail($id);
        $nombre = $proveedor->nombre;

        $proveedor->delete();

        // Auditoría
        Auditoria::registrar('Eliminación', 'Proveedores', 'Se eliminó al proveedor: ' . $nombre);

        return redirect()->route('proveedores.index')->with('success', 'Proveedor eliminado.');
    }
}
