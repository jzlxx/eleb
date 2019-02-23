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

Route::get('/', function () {
    return view('Login.login');
});

Route::get('/Register/register','RegisterController@register')->name('register.register');
Route::post('/Register/store','RegisterController@store')->name('register.store');

//登录
Route::get('login', 'LoginController@create')->name('login');
Route::post('login', 'LoginController@store')->name('login');
Route::get('logout', 'LoginController@destroy')->name('logout');


//主页欢迎
Route::get('/welcome/index','WelcomeController@index')->name('welcome.index');

Route::get('/users/pwd','UserController@pwd')->name('users.pwd');
Route::post('/users/pupdate', 'UserController@pupdate')->name('users.pupdate');