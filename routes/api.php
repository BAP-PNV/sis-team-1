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

Route::post('register', [AuthController::class, "register"]);
Route::match(['POST', 'GET'], 'login', [AuthController::class, "login"])->name('login');
Route::get("/confirm", [AuthController::class, "confirm"])->middleware('jwt.auth.middleware');

Route::middleware(['file.auth'])->group(function () {

    Route::get('files/{id}', [FileController::class, 'index']);
    Route::post('file/{id}', [FileController::class, 'create']);
    Route::delete('file/{id}', [FileController::class, 'destroy']);

    Route::post('folder/{id}', [FileController::class, 'createFolder']);
    Route::delete('folders', [FileController::class, 'destroyFolder']);
    Route::get('folders', [FileController::class, 'showFolder']);
});

Route::group(
    [
        'middleware' => 'auth',
        'prefix' => 'dashboard'
    ],
    function () {

        Route::get("/me", [AuthController::class, "me"]);
        Route::get('files/{id}', [FileController::class, 'index']);
        Route::post('file', [FileController::class, 'create']);
        Route::delete('file/{id}', [FileController::class, 'destroy']);

        Route::post('folders', [FileController::class, 'createFolder']);
        Route::delete('folders', [FileController::class, 'destroyFolder']);
        Route::get('folders', [FileController::class, 'showFolder']);
    }
);

// Route::get('/registers', [MailController::class, 'index']);
