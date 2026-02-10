<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\ProveedorController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\MovimientoController;
use App\Http\Controllers\AuditoriaController;
use App\Models\Producto;
use App\Models\Proveedor;
use App\Models\Movimiento;
use App\Models\Categoria;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// Ruta de bienvenida
Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

// Dashboard con estadísticas
Route::get('/dashboard', function () {
    return Inertia::render('Dashboard', [
        'stats' => [
            'total_productos' => Producto::count(),
            'total_proveedores' => Proveedor::count(),
            'stock_bajo' => Producto::where('stock', '<', 5)->count(),
            'ultimos_movimientos' => Movimiento::with('producto')->latest()->take(5)->get()
        ]
    ]);
})->middleware(['auth', 'verified'])->name('dashboard');

// Rutas protegidas del sistema
Route::middleware(['auth', 'verified'])->group(function () {

    Route::resource('categorias', CategoriaController::class);

    // Corregimos el recurso de proveedores para que use el parámetro singular 'proveedor'
    Route::resource('proveedores', ProveedorController::class)->parameters([
        'proveedores' => 'proveedor'
    ]);

    Route::resource('productos', ProductoController::class);

    Route::resource('movimientos', MovimientoController::class);

    // Módulo de Reportes
    Route::get('/reportes', function () {
        $productos = Producto::all();
        $categorias = Categoria::withCount('productos')->get();
        // Aseguramos que el cálculo trate los valores como números
        $valorTotalUsd = $productos->sum(fn($p) => (float)$p->precio * (int)$p->stock);

        return Inertia::render('Reportes/Index', [
            'valorTotalUsd' => (float)$valorTotalUsd,
            'conteoCategorias' => $categorias,
            'productos' => $productos
        ]);
    })->name('reportes.index');

    // Módulo de Auditoría
    Route::get('/auditoria', [AuditoriaController::class, 'index'])->name('auditoria.index');
});

// Perfil de usuario
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
