<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use App\Models\User;

class JwtBroadcastAuth
{
    public function handle(Request $request, Closure $next)
    {
        $token = $request->bearerToken() ?? $request->input('token');

        if (! $token) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        try {
            $decoded = JWT::decode(
                $token,
                new Key(config('app.jwt_secret', 'your-secret-key'), 'HS256')
            );

            $user = User::find($decoded->user_id);

            if (! $user) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }

            auth()->setUser($user);
            auth('api')->setUser($user);
            $request->setUserResolver(fn () => $user);

            return $next($request);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    }
}
