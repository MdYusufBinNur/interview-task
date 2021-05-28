<?php

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('v1')->namespace('Api\v1')->group(function () {
    Route::post('login', 'AdminController@login');
    Route::post('register', 'AdminController@register');
    Route::get('users', 'AdminController@lists');
});

Route::middleware(['auth:api'])->prefix('v1')->namespace('Api\v1')->group(function () {
    Route::post('role', 'RoleController@create');
    Route::get('role', 'RoleController@lists');
    Route::get('userWithRole', 'RoleController@getUserWithRole');
    Route::get('profile', 'AdminController@profile');
    Route::post('assign', 'RoleController@assignRole');
    Route::post('removeRole', 'RoleController@removeRole');
});

