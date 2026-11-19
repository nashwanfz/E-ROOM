<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsMahasiswa
{
    public function handle(Request $request, Closure $next): Response
    {
        abort_unless($request->user()?->role === 'mahasiswa', 403, 'Halaman ini khusus mahasiswa (peminjam).');

        return $next($request);
    }
}
