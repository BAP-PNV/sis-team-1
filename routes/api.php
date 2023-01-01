<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\FileController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::get("/", [AuthController::class, "getLogin"]);
Route::post('register', [AuthController::class, "register"]);
Route::post('login', [AuthController::class, "login"]);
Route::get('/user-info', [AuthController::class, "getUserInfo"]);
Route::get("/get-info", [AuthController::class, "confirm"]);
Route::get("/confirm", [AuthController::class, "confirm"])->middleware('jwt.auth.middleware');

Route::middleware(['file.auth'])->group(function () {
    Route::get('files/{id}', [FileController::class, 'index']);
    Route::post('file', [FileController::class, 'create']);
    Route::delete('file/{id}', [FileController::class, 'destroy']);
});



// folders
Route::post('folders',[FileController::class,'createFolder']);
Route::delete('folders',[FileController::class,'destroyFolder']);
Route::get('folders',[FileController::class,'showFolder']);
// Send email when registered
Route::get('/registers', [MailController::class, 'index']);
// Route::post('/registers', [MailController::class, 'store'])->name('send.store');
