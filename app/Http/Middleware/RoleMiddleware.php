<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     * Usage: ->middleware('role:admin,staff')
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (! auth()->check()) {
            return redirect()->route('login');
        }

        $userRole = auth()->user()->peran;

        if (! in_array($userRole, $roles)) {
            abort(403, 'Akses tidak diizinkan. Anda tidak memiliki hak akses ke halaman ini.');
        }

        return $next($request);
    }
}
