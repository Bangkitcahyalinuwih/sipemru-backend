<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, string $role): mixed
    {
        // Pastikan user sudah login
        if (!$request->user()) {
            return response()->json([
                'message' => 'Unauthenticated.'
            ], 401);
        }

        // Pastikan role sesuai
        if ($request->user()->role !== $role) {
            return response()->json([
                'message' => 'Akses ditolak. Anda tidak memiliki izin untuk fitur ini.'
            ], 403);
        }

        return $next($request);
    }
}

// app/Http/Middleware/CheckRole.php

