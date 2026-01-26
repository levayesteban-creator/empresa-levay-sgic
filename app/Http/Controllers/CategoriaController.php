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
            'categorias' => Categoria::all()
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate(['nombre' => 'required|string|max:100']);
        Categoria::create($validated);

        // Registro de Auditoría
        Auditoria::registrar('Creación', 'Categorías', 'Se creó la categoría: ' . $request->nombre);

        return redirect()->route('categorias.index');
    }

    public function update(Request $request, Categoria $categoria)
    {
        $validated = $request->validate(['nombre' => 'required|string|max:100']);
        $categoria->update($validated);

        // Registro de Auditoría
        Auditoria::registrar('Edición', 'Categorías', 'Se cambió el nombre de la categoría a: ' . $categoria->nombre);

        return redirect()->route('categorias.index');
    }

    public function destroy(Categoria $categoria)
    {
        $nombre = $categoria->nombre;
        $categoria->delete();

        // Registro de Auditoría
        Auditoria::registrar('Eliminación', 'Categorías', 'Se eliminó la categoría: ' . $nombre);

        return redirect()->route('categorias.index');
    }
}
