<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Categoria;
use App\Models\Proveedor;
use App\Models\Auditoria;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProductoController extends Controller
{
    public function index()
    {
        return Inertia::render('Productos/Index', [
            // Cargamos las relaciones para ver nombres de categoría y proveedor en la tabla
            'productos' => Producto::with(['categoria', 'proveedor'])->orderBy('id', 'desc')->get(),
            'categorias' => Categoria::all(),
            'proveedores' => Proveedor::all()
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'precio' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'id_categoria' => 'required|exists:categorias,id',
            // CORREGIDO: Se cambió 'proveedors' por 'proveedores'
            'id_proveedor' => 'required|exists:proveedores,id',
        ]);

        $producto = Producto::create($validated);

        Auditoria::registrar('Creación', 'Productos', 'Se registró el producto: ' . $producto->nombre);

        return redirect()->route('productos.index')->with('success', 'Producto creado.');
    }

    public function update(Request $request, $id)
    {
        $producto = Producto::findOrFail($id);

        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'precio' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'id_categoria' => 'required|exists:categorias,id',
            // CORREGIDO: Se cambió 'proveedors' por 'proveedores'
            'id_proveedor' => 'required|exists:proveedores,id',
        ]);

        $producto->update($validated);

        Auditoria::registrar('Edición', 'Productos', 'Se actualizó el producto: ' . $producto->nombre);

        return redirect()->route('productos.index')->with('success', 'Producto actualizado.');
    }

    public function destroy($id)
    {
        $producto = Producto::findOrFail($id);
        $nombre = $producto->nombre;
        $producto->delete();

        Auditoria::registrar('Eliminación', 'Productos', 'Se eliminó el producto: ' . $nombre);

        return redirect()->route('productos.index')->with('success', 'Producto eliminado.');
    }
}
