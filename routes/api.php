<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PostController;
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

Route::group(["prefix" => "user"], function () {

    Route::post('register', [RegisterController::class, "register"]);
    Route::post('completeregistration', [RegisterController::class, "completeRegistration"]);

    Route::post('resetpassword', [ResetPasswordController::class, "resetPassword"]);
    Route::post('completeresetpassword', [ResetPasswordController::class, "completeResetPassword"]);

    Route::post('login', [LoginController::class, "loginWithApi"]);

});


Route::group(["middleware" => "auth:api"], function () {

    Route::group(["prefix" => "category"], function () {
        Route::get("list", [CategoryController::class, "list"]);
        Route::post("create", [CategoryController::class, "create"]);
        Route::post("update/{id}", [CategoryController::class, "update"]);
    });

    Route::group(["prefix" => "post"], function () {
        Route::get("list", [PostController::class, "list"]);
        Route::post("create", [PostController::class, "create"]);
        Route::post("update/{id}", [PostController::class, "update"]);
    });

});

Route::get('categories', [CategoryController::class, "index"]);
Route::get('post', [PostController::class, "index"]);

Route::get('post/search', [PostController::class, "searchPost"]);


