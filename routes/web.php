<?php

use App\Http\Validations\Validation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Auth::routes();
Route::impersonate();

Route::group(['namespace' => 'App\Http\Controllers', 'middleware' => 'auth'], function () {
    Route::get('/', 'HomeController@index')->name('home2');
    Route::get('/home',  'HomeController@index')->name('home');
});

Route::group(['namespace' => 'App\Http\Controllers\ProjectControllers', 'middleware' => 'auth'], function () {
    Route::get('/audit/{table}/{id}', 'AuditController@changeLog')->name('audit.change_log');

    Route::group(['middleware' => ['role_or_permission:Administrador']], function () {
        // Utilidades
        Route::get('/cache', 'App\Http\Controllers\ToolController@cache')->name('cache');
        Route::get('/phpinfo', 'App\Http\Controllers\ToolController@phpinfo')->name('phpinfo');

        //Usuarios
        Route::get('/users/{user}/password/update', 'UserController@passwordEdit')->name('users.password.edit');
        Route::put('/users/{user}/password/update', 'UserController@passwordUpdate')->name('users.password.update');
        Route::resource('users', 'UserController');
        Route::post('/users_/export', 'UserController@export')->name('users.export');

        //Roles
        Route::get('/roles/{role}/permissions', 'RolesController@editPermissions')->name('roles.permissions.edit');
        Route::post('/roles/{role}/permissions', 'RolesController@assignPermissions')->name('roles.permissions.update');
        Route::resource('roles', 'RolesController')->only(['index', 'store', 'destroy', 'update']);

        // Listas
        Route::group(['middleware' => [Validation::permissionsRoute('lists')]], function () {
            Route::resource('lists', 'ListController');
        });
    });
});
