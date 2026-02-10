<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Auditoria;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CategoriaController extends Controller
{
    public function index()
    {
        return Inertia::render('Categorias/Index', [
            'categorias' => Categoria::orderBy('id', 'desc')->get()
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:100',
            'descripcion' => 'nullable|string|max:255'
        ]);

        Categoria::create($validated);

        Auditoria::registrar('Creación', 'Categorías', 'Se creó la categoría: ' . $request->nombre);

        return redirect()->route('categorias.index')->with('success', 'Categoría creada.');
    }

    public function update(Request $request, $id)
    {
        $categoria = Categoria::findOrFail($id);

        $validated = $request->validate([
            'nombre' => 'required|string|max:100',
            'descripcion' => 'nullable|string|max:255'
        ]);

        $categoria->update($validated);

        // Auditoría detallada
        Auditoria::registrar(
            'Edición',
            'Categorías',
            "Actualizada ID {$id}: {$categoria->nombre} | Desc: " . ($categoria->descripcion ?? 'N/A')
        );

        return redirect()->route('categorias.index')->with('success', 'Categoría actualizada.');
    }

    public function destroy($id)
    {
        $categoria = Categoria::findOrFail($id);
        $nombre = $categoria->nombre;
        $categoria->delete();

        Auditoria::registrar('Eliminación', 'Categorías', 'Se eliminó la categoría: ' . $nombre);

        return redirect()->route('categorias.index')->with('success', 'Categoría eliminada.');
    }
}
