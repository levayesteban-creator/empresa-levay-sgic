<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
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

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $request->validate(['nombre' => 'required|string|max:255']);
        Categoria::create($request->all());
        return redirect()->back();
    }

    public function show(Categoria $categoria)
    {
        //
    }

    public function edit(Categoria $categoria)
    {
        //
    }

    public function update(Request $request, Categoria $categoria)
    {
        $request->validate(['nombre' => 'required|string|max:255']);
        $categoria->update($request->all());
        return redirect()->back();
    }

    public function destroy(Categoria $categoria)
    {
        $categoria->delete();
        return redirect()->back();
    }
}
