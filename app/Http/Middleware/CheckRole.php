<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // Pastikan user sudah login dan memiliki role yang sesuai
        if ($request->user() && in_array($request->user()->role->name, $roles)) {
            return $next($request);
        }

        // Jika tidak punya akses, lempar ke halaman POS/Dashboard dengan pesan error
        return redirect()->route('pos.index')->with('error', 'Anda tidak memiliki hak akses untuk membuka halaman tersebut.');
    }
}
