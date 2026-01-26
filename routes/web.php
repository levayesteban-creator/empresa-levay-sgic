<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\ProveedorController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\MovimientoController;
use App\Http\Controllers\AuditoriaController; // Importado
use App\Models\Producto;   // Importado para el Dashboard
use App\Models\Proveedor;  // Importado para el Dashboard
use App\Models\Movimiento; // Importado para el Dashboard
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// Ruta de Bienvenida
Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

// Ruta del Dashboard con Estadísticas
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

// --- Rutas de Gestión de Inventario (Recursos) ---
Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('categorias', CategoriaController::class);
    Route::resource('proveedores', ProveedorController::class);
    Route::resource('productos', ProductoController::class);
    Route::resource('movimientos', MovimientoController::class);

    // Ruta de Auditoría dentro del grupo autenticado
    Route::get('/auditoria', [AuditoriaController::class, 'index'])->name('auditoria.index');
});

// Rutas de Perfil de Usuario
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
