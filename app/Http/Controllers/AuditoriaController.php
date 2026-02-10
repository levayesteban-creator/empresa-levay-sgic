<?php

namespace App\Http\Controllers;

use App\Models\Auditoria;
use Inertia\Inertia;

class AuditoriaController extends Controller
{
    public function index()
    {

        $auditorias = Auditoria::latest()->get();

        return Inertia::render('Auditoria/Index', [
            'auditorias' => $auditorias
        ]);
    }
}
