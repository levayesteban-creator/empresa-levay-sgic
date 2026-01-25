<?php

namespace App\Http\Controllers;

use App\Models\Proveedor;
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

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'contacto_persona' => 'nullable|string|max:255',
            'telefono' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'direccion' => 'nullable|string',
        ]);

        Proveedor::create($validated);
        return redirect()->back();
    }

    public function show(Proveedor $proveedor)
    {
        //
    }

    public function edit(Proveedor $proveedor)
    {
        //
    }

    public function update(Request $request, Proveedor $proveedore)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'contacto_persona' => 'nullable|string|max:255',
            'telefono' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'direccion' => 'nullable|string',
        ]);

        $proveedore->update($validated);
        return redirect()->back();
    }

    public function destroy(Proveedor $proveedore)
    {
        $proveedore->delete();
        return redirect()->back();
    }
}
