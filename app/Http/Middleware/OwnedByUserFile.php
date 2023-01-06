<?php

namespace App\Http\Middleware;

use App\Constants\AppConstant;
use App\Traits\ApiResponse;
use Closure;
use Illuminate\Http\Request;
use App\Models\Image;

class OwnedByUserFile
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
        $file = new Image();
        $fileExist = $file->find($request->id);

        if ($fileExist == null) {
            return $this->responseErrorWithData(['file' => AppConstant::FILE_NOT_EXIST]);
        } else if ($fileExist->folder->user_id == $request->user_id) {
            return $next($request);
        }

        return  $this->responseErrorWithData(['file' => 'You do not have permission for this file']);
    }
}
