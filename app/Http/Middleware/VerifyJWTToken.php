<?php

namespace App\Http\Middleware;

use App\Traits\ApiResponse;
use Closure;
use Exception;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Facades\JWTFactory;
use Illuminate\Http\Request;

class VerifyJWTToken
{
    use ApiResponse;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (!$request->has('token')) {
            return $this->responseErrorWithData(['token' => 'Not found']);
        }
        try {
            $token = JWTAuth::getToken();
            $apy = JWTAuth::getPayload($token)->toArray();
            return $next($request->merge(['apy' => $apy]));
        } catch (TokenInvalidException $e) {
            return $this->responseErrorWithData(['token' => 'Invalid token']);;
        } catch (Exception) {
            return $this->responseErrorWithData(['token' => 'Somethings is wrong']);
        }
    }
}
