<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // For now, we'll allow access without authentication
        // In a real application, you would check auth()->check() and auth()->user()->role === 'admin'
        // Since we don't have a full auth system yet, we'll just allow access
        
        // Uncomment this when you have authentication:
        // if (!auth()->check() || auth()->user()->role !== 'admin') {
        //     abort(403, 'Unauthorized access. Admin only.');
        // }
        
        return $next($request);
    }
}
