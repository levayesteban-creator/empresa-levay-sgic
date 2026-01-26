<?php

namespace App\Http\Controllers;

use App\Models\Auditoria;
use Inertia\Inertia;

class AuditoriaController extends Controller
{
    public function index()
    {
        return Inertia::render('Auditorias/Index', [
            'logs' => Auditoria::latest()->paginate(20)
        ]);
    }
}
