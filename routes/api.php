<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\FileController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SignUpController;
use App\Http\Controllers\Api\EmailController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get("/",[AuthController::class,"getLogin"]);
Route::post('register', [AuthController::class,"register"]);
Route::post('login', [AuthController::class,"login"]);
Route::get('/user-info',[AuthController::class,"getUserInfo"]);
Route::get("/get-info",[AuthController::class,"confirm"]);
Route::get("/",[AuthController::class,"confirm"]);
Route::post('files',[FileController::class,'create'])->middleware('upload.auth');;
Route::get('file/{id}',[FileController::class,'show']);
Route::delete('file/{id}',[FileController::class,'destroy']);
Route::get("/confirm",[AuthController::class,"confirm"]);

// Send email when registered
Route::get('/registers', [MailController::class, 'index']);
Route::post('/registers', [MailController::class, 'store'])->name('send.store');
Route::post('test',[AuthController::class,'allow'])->middleware('upload.auth');