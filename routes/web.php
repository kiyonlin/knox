<?php

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

Route::view('/', 'welcome');

Auth::routes();

Route::get('/home', 'Home\HomeController@index')->name('home');

Route::group(['middleware' => 'auth'], function () {
    Route::group(['namespace' => 'User'], function () {
        Route::resource('users', 'UsersController')->only(['index', 'destroy', 'store', 'update']);
    });

    Route::group(['namespace' => 'Role'], function () {
        Route::resource('roles', 'RolesController')->only(['index', 'destroy', 'store', 'update']);
    });

    Route::group(['namespace' => 'Module'], function () {
        Route::resource('modules', 'ModulesController')->only(['index', 'destroy', 'store', 'update']);

        Route::post('modules/{module}/permissions', 'PermissionsController@store')->middleware('permission:add_permission');
        Route::patch('modules/{module}/permissions/{permission}', 'PermissionsController@update')->middleware('permission:update_permission');
        Route::delete('modules/{module}/permissions/{permission}', 'PermissionsController@destroy')->middleware('permission:delete_permission');
    });
});