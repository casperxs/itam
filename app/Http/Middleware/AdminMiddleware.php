<?php 
// app/Http/Middleware/AdminMiddleware.php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            abort(403, 'Acceso denegado. Solo administradores.');
        }

        return $next($request);
    }
	        public function __construct()
        {
            $this->middleware('auth:api', ['except' => ['login']]);
        }

}
