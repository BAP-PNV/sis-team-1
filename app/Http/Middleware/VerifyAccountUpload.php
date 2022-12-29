<?php

namespace App\Http\Middleware;

use app\Helpers\SecretKeyHelper;
use App\Traits\ApiResponse;
use Closure;
use Illuminate\Http\Request;

class VerifyAccountUpload
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
        if (SecretKeyHelper::checkKey($request->get('secret_access'))) {
            return $next($request);
        }
        return $this->responseErrorUnauthorized();
    }
}
