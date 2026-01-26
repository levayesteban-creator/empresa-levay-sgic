<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Auditoria extends Model
{

    protected $fillable = ['user_id', 'accion', 'modulo', 'detalles', 'ip_address'];


    public static function registrar($accion, $modulo, $detalles)
    {
        self::create([
            'user_id'    => Auth::id(),
            'accion'     => $accion,
            'modulo'     => $modulo,
            'detalles'   => $detalles,
            'ip_address' => request()->ip(),
        ]);
    }
}
