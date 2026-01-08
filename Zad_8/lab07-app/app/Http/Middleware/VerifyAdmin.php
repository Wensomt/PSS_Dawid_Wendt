<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        // Sprawdź, czy użytkownik jest zalogowany i czy ma rolę admin
        if (auth()->check() && auth()->user()->is_admin) {
            return $next($request);
        }

        // Jeśli nie - przekieruj na dashboard z błędem
        return redirect()
            ->route('dashboard')
            ->withErrors('Brak uprawnień administratora.');
    }
}
