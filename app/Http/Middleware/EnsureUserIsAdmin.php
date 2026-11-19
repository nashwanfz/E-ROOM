<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        $role = $request->user()?->role;
        abort_unless(in_array($role, ['admin', 'super_admin'], true), 403, 'Halaman ini khusus pengelola (admin / super_admin).');

        return $next($request);
    }
}
