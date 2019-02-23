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

//Route::get('/users', 'UsersController@index')->name('users.index');//用户列表
//Route::get('/users/{user}', 'UsersController@show')->name('users.show');//查看单个用户信息
//Route::get('/users/create', 'UsersController@create')->name('users.create');//显示添加表单
//Route::post('/users', 'UsersController@store')->name('users.store');//接收添加表单数据
//Route::get('/users/{user}/edit', 'UsersController@edit')->name('users.edit');//修改用户表单
//Route::patch('/users/{user}', 'UsersController@update')->name('users.update');//更新用户信息
//Route::delete('/users/{user}', 'UsersController@destroy')->name('users.destroy');//删除用户信息s


//商家分类
Route::resource('shopcategories','ShopCategoryController');
Route::post('/shopcategories/update', 'ShopCategoryController@update')->name('shopcategories.update');
Route::get('/shopcategories/ustatus/{shopcategory}', 'ShopCategoryController@ustatus')->name('shopcategories.ustatus');

//主页欢迎页面
Route::get('/welcome/index','WelcomeController@index')->name('welcome.index');


//商家信息
Route::resource('shops','ShopController');
Route::post('/shops/update', 'ShopController@update')->name('shops.update');
Route::get('/shops/ustatus/{shop}', 'ShopController@ustatus')->name('shops.ustatus');

//管理员账号
Route::resource('admins','AdminController');
Route::post('/adminss/update', 'AdminController@update')->name('adminss.update');
Route::get('/adminss/ustatus/{shop}', 'AdminController@ustatus')->name('adminss.ustatus');


//登录
Route::get('login', 'LoginController@create')->name('login');
Route::post('login', 'LoginController@store')->name('login');
Route::get('logout', 'LoginController@destroy')->name('logout');