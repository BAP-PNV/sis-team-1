<?php

namespace App\Http\Middleware;

use App\Helpers\SecretKeyHelper;
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
        if ($request->has('access_key')) {

            $idUser = SecretKeyHelper::checkKey($request->get('access_key'));

            if ($idUser != -1) {
                return $next($request->merge(["user_id" => $idUser]));
            }

            return $this->responseErrorUnauthorized();
        }

        return $this->responseErrorWithData([
            "detail" => "Not found key"
        ]);
        
    }

}
