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

Route::post('/sign-up', [SignUpController::class, 'store']);

//Route::get('/send-email-registered',[EmailController::class,'send']);

Route::get('/', 'EmailController@index')->name('index');
Route::post('/task', 'EmailController@store')->name('store.task');
Route::delete('/task/{task}', 'EmailController@delete')->name('delete.task');
