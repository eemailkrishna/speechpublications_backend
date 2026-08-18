<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check() || !Auth::user()->hasRole('admin')) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthorized access.'], 403);
            }
            return redirect('/dashboard1')->with('error', 'You do not have permission to access this page.');
        }

        return $next($request);
    }
}
