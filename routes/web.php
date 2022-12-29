<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\MailController;


/*

|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::get('/user', [UsersController::class, 'index']);

Route::get('/registers', [MailController::class, 'index']);
Route::post('/registers', [MailController::class, 'store'])->name('send.store');
Route::get('/confirm',[AuthController::class,'confirm'])->name('confirm');
