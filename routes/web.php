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
    Route::group(['namespace' => 'User', 'middleware' => 'systemAdminProtection'], function () {
        Route::get('users', 'UsersController@index')->middleware('permission:module.view.user');
        Route::post('users', 'UsersController@store')->middleware('permission:user.add');
        Route::patch('users/{user}', 'UsersController@update')->middleware('permission:user.update');
        Route::delete('users/{user}', 'UsersController@destroy')->middleware('permission:user.delete');

        Route::get('users/{user}/roles', 'UserRolesController@index')->middleware('permission:role.view');
        Route::put('users/{user}/roles/{role}', 'UserRolesController@update')->middleware('permission:user.update');
        Route::delete('users/{user}/roles/{role}', 'UserRolesController@destroy')->middleware('permission:user.update');
    });

    Route::group(['namespace' => 'Role', 'middleware' => 'systemAdminProtection'], function () {
        Route::get('roles', 'RolesController@index')->middleware('permission:module.view.role');
        Route::post('roles', 'RolesController@store')->middleware('permission:role.add');
        Route::patch('roles/{role}', 'RolesController@update')->middleware('permission:role.update');
        Route::delete('roles/{role}', 'RolesController@destroy')->middleware('permission:role.delete');

        Route::get('roles/{role}/permissions', 'RolePermissionsController@index')->middleware('permission:permission.view');
        Route::put('roles/{role}/permissions', 'RolePermissionsController@update')->middleware('permission:permission.update');
    });

    Route::group(['namespace' => 'Module', 'middleware' => 'systemAdminProtection'], function () {
        Route::get('modules', 'ModulesController@index')->middleware('permission:module.view.module');
        Route::post('modules', 'ModulesController@store')->middleware('permission:role.add');
        Route::patch('modules/{module}', 'ModulesController@update')->middleware('permission:module.update');
        Route::delete('modules/{module}', 'ModulesController@destroy')->middleware('permission:module.delete');

        Route::post('modules/{module}/permissions', 'PermissionsController@store')->middleware('permission:permission.add');
        Route::patch('modules/{module}/permissions/{permission}', 'PermissionsController@update')->middleware('permission:permission.update');
        Route::delete('modules/{module}/permissions/{permission}', 'PermissionsController@destroy')->middleware('permission:permission.delete');

        Route::get('modules/tops', 'ModulesController@tops')->middleware('permission:module.view');
    });
});