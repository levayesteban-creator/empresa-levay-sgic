<?php

namespace App\Http\Controllers;

use App\Models\Movimiento;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class MovimientoController extends Controller
{
    public function index()
    {
        return Inertia::render('Movimientos/Index', [
            'movimientos' => Movimiento::with('producto')->latest()->get(),
            'productos' => Producto::all()
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_producto' => 'required|exists:productos,id',
            'tipo' => 'required|in:Entrada,Salida',
            'cantidad' => 'required|integer|min:1',
            'motivo' => 'nullable|string|max:255'
        ]);

        try {
            DB::transaction(function () use ($request) {
                $producto = Producto::findOrFail($request->id_producto);

                if ($request->tipo === 'Entrada') {
                    $producto->increment('stock', $request->cantidad);
                } else {
                    if ($producto->stock < $request->cantidad) {
                        throw new \Exception('Stock insuficiente.');
                    }
                    $producto->decrement('stock', $request->cantidad);
                }

                Movimiento::create($request->all());
            });

            return redirect()->back();

        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['cantidad' => $e->getMessage()]);
        }
    }
}