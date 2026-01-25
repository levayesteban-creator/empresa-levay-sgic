<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Categoria;
use App\Models\Proveedor;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProductoController extends Controller
{
    public function index()
    {
        return Inertia::render('Productos/Index', [
            'productos' => Producto::with(['categoria', 'proveedor'])->get(),
            'categorias' => Categoria::all(),
            'proveedores' => Proveedor::all()
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre'       => 'required|string|max:255',
            'descripcion'  => 'nullable|string',
            'precio'       => 'required|numeric|min:0',
            'stock'        => 'required|integer|min:0',
            'id_categoria' => 'required|exists:categorias,id',
            'id_proveedor' => 'required|exists:proveedors,id',
        ]);

        Producto::create($validated);

        return redirect()->back();
    }

    public function update(Request $request, Producto $producto)
    {
        $validated = $request->validate([
            'nombre'       => 'required|string|max:255',
            'descripcion'  => 'nullable|string',
            'precio'       => 'required|numeric|min:0',
            'stock'        => 'required|integer|min:0',
            'id_categoria' => 'required|exists:categorias,id',
            'id_proveedor' => 'required|exists:proveedors,id',
        ]);

        $producto->update($validated);

        return redirect()->back();
    }

    public function destroy(Producto $producto)
    {
        $producto->delete();

        return redirect()->back();
    }
}
