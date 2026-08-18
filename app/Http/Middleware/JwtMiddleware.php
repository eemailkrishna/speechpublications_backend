<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use App\Models\User;

class JwtMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $token = $this->getTokenFromRequest($request);

        if (!$token) {
            return response()->json([
                'success' => false,
                'error' => [
                    'code' => 'INVALID_TOKEN',
                    'message' => 'Token not provided',
                ]
            ], 401);
        }

        try {
            $decoded = JWT::decode(
                $token,
                new Key(config('app.jwt_secret', 'your-secret-key'), 'HS256')
            );

            $user = User::find($decoded->user_id);

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'error' => [
                        'code' => 'USER_NOT_FOUND',
                        'message' => 'User not found',
                    ]
                ], 401);
            }

            // Manually authenticate the user
            auth('api')->setUser($user);

            return $next($request);
        } catch (\Firebase\JWT\ExpiredException $e) {
            return response()->json([
                'success' => false,
                'error' => [
                    'code' => 'INVALID_TOKEN',
                    'message' => 'Token has expired',
                ]
            ], 401);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => [
                    'code' => 'INVALID_TOKEN',
                    'message' => 'Invalid token',
                ]
            ], 401);
        }
    }

    private function getTokenFromRequest(Request $request)
    {
        $token = $request->bearerToken();

        if ($token) {
            return $token;
        }

        if ($request->has('token')) {
            return $request->input('token');
        }

        return null;
    }
}
