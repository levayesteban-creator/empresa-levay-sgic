<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Categoria;
use App\Models\Proveedor;
use App\Models\Auditoria; // Importamos el modelo de Auditoría
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProductoController extends Controller
{
    /**
     * Mostrar la lista de productos
     */
    public function index()
    {
        $productos = Producto::with(['categoria', 'proveedor'])->get();
        return Inertia::render('Productos/Index', [
            'productos' => $productos
        ]);
    }

    /**
     * Guardar un nuevo producto
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'precio' => 'required|numeric',
            'stock' => 'required|integer',
            'id_categoria' => 'required|exists:categorias,id',
            'id_proveedor' => 'required|exists:proveedores,id',
        ]);

        Producto::create($validated);

        // --- REGISTRO DE AUDITORÍA ---
        Auditoria::registrar(
            'Creación',
            'Productos',
            'Se añadió el producto: ' . $request->nombre . ' con un stock inicial de ' . $request->stock
        );

        return redirect()->route('productos.index')->with('message', 'Producto creado con éxito');
    }

    /**
     * Actualizar un producto existente
     */
    public function update(Request $request, Producto $producto)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'precio' => 'required|numeric',
            'stock' => 'required|integer',
            'id_categoria' => 'required|exists:categorias,id',
            'id_proveedor' => 'required|exists:proveedores,id',
        ]);

        $producto->update($validated);

        // --- REGISTRO DE AUDITORÍA ---
        Auditoria::registrar(
            'Edición',
            'Productos',
            'Se actualizó la información de: ' . $producto->nombre
        );

        return redirect()->route('productos.index')->with('message', 'Producto actualizado');
    }

    /**
     * Eliminar un producto
     */
    public function destroy(Producto $producto)
    {
        $nombreEliminado = $producto->nombre; // Guardamos el nombre antes de borrar

        $producto->delete();

        // --- REGISTRO DE AUDITORÍA ---
        Auditoria::registrar(
            'Eliminación',
            'Productos',
            'Se eliminó permanentemente el producto: ' . $nombreEliminado
        );

        return redirect()->route('productos.index')->with('message', 'Producto eliminado');
    }
}
