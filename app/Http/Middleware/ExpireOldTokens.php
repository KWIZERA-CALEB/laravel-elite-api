<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;
use Symfony\Component\HttpFoundation\Response;

class ExpireOldTokens
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->bearerToken();
        if($token) {
            $personAccessToken = PersonalAccessToken::findToken($token);
            
            
            if($personAccessToken) {
                $lastUsed = $personAccessToken->last_used_at;

                if($lastUsed && now()->diffInMonths($lastUsed) >= 5) {
                    $personAccessToken->delete();

                    return response()->json(['message'=>'Token expired'], 401);
                }
            }
        }else {
            $data = [
                'status'=>401,
                'course'=>'Logged Out',
            ];
            return response()->json($data, 401);
        }

        return $next($request);
    }
}
