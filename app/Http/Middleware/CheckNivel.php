<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckNivel
{
    public function handle(Request $request, Closure $next, ...$niveis)
    {
        $user = $request->user();

        if (!$user || !$user->nivel) {
            abort(403, 'Acesso negado.');
        }

        $nivelUsuario = mb_strtolower($user->nivel->nivel);

        foreach ($niveis as $nivel) {
            if (mb_strtolower($nivel) === $nivelUsuario) {
                return $next($request);
            }
        }

        abort(403, 'Acesso negado.');
    }
}
