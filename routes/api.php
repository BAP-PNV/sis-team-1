<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
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
Route::post('login', [AuthController::class, "login"])->name('login');
Route::match(['POST', 'GET'], "confirm", [AuthController::class, "confirm"])->middleware('jwt.auth.middleware')->name('confirm');

Route::middleware(['file.auth'])->group(function () {

    Route::middleware('ownedByUser.folder')->group(function () {
        Route::post('folder/{id}', [FileController::class, 'createFolder']);
        Route::delete('folder/{id}', [FileController::class, 'deleteFolder']);
        Route::get('folder/{id}', [FileController::class, 'indexFolder']);
    });

    Route::get('folders', [FileController::class, 'indexFolder']);
    Route::post('folder', [FileController::class, 'createFolder']);

    Route::get('files', [FileController::class, 'index']);
    Route::post('file', [FileController::class, 'create']);

    Route::middleware('ownedByUser.file')->group(
        function () {
            Route::post('folder/{id}/file', [FileController::class, 'create']);
            Route::get('folder/{id}/file', [FileController::class, 'show']);
            Route::delete('file/{id}', [FileController::class, 'destroy']);
        }
    );
});

Route::group(
    [
        'middleware' => 'auth',
        'prefix' => 'dashboard'
    ],
    function () {
        Route::get('', [DashboardController::class, 'index']);

        Route::get("me", [DashboardController::class, "me"]);

        Route::post('refresh-token', [AuthController::class, 'refresh']);

        Route::middleware(['file.auth'])->group(function () {

            Route::middleware('ownedByUser.folder')->group(function () {
                Route::post('folder/{id}', [FileController::class, 'createFolder']);
                Route::delete('folder/{id}', [FileController::class, 'deleteFolder']);
                Route::get('folder/{id}', [FileController::class, 'indexFolder']);
            });

            Route::get('folders', [FileController::class, 'indexFolder']);
            Route::post('folder', [FileController::class, 'createFolder']);

            Route::get('files', [FileController::class, 'index']);
            Route::post('file', [FileController::class, 'create']);

            Route::middleware('ownedByUser.file')->group(
                function () {
                    Route::post('folder/{id}/file', [FileController::class, 'create']);
                    Route::get('folder/{id}/file', [FileController::class, 'show']);
                    Route::delete('file/{id}', [FileController::class, 'destroy']);
                }
            );
        });
    }
);

Route::fallback(function () {
    return response()->json(
        [
            'message' => 'Not found',
            'data' => 'Somethings is wrong with the url'
        ],
        404
    );
});
