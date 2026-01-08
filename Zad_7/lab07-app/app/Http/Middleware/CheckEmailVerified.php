<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckEmailVerified
{
    public function handle(Request $request, Closure $next): Response
    {
        // musi być zalogowany
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        // musi mieć zweryfikowany email
        if (auth()->user()->email_verified_at) {
            return $next($request);
        }

        // jeśli nie - przekieruj z komunikatem
        return redirect()
            ->route('dashboard')
            ->with('error', 'Zweryfikuj e-mail, aby uzyskać dostęp.');
    }
}
