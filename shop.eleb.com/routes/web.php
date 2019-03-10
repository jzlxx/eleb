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
Route::post('/Register/upload','RegisterController@upload')->name('register.upload');

//登录
Route::get('login', 'LoginController@create')->name('login');
Route::post('login', 'LoginController@store')->name('login');
Route::get('logout', 'LoginController@destroy')->name('logout');


//主页欢迎
Route::get('/welcome/index','WelcomeController@index')->name('welcome.index');

//修改密码
Route::get('/users/pwd','UserController@pwd')->name('users.pwd');
Route::post('/users/pupdate', 'UserController@pupdate')->name('users.pupdate');

//菜品分类
Route::resource('menucategories','MenuCategoryController');
Route::post('/menucategories/update', 'MenuCategoryController@update')->name('menucategories.update');
Route::get('/menucategories/ustatus/{menucategory}', 'MenuCategoryController@ustatus')->name('menucategories.ustatus');

//菜品
Route::resource('menus','MenuController');
Route::post('/menus/update', 'MenuController@update')->name('menus.update');
Route::get('/menus/ustatus/{menu}', 'MenuController@ustatus')->name('menus.ustatus');
Route::post('/menus/upload','MenuController@upload')->name('menus.upload');

//活动
Route::resource('activities','ActivityController');

//订单管理
Route::resource('orders','OrderController');
Route::get('/orders/ustatus/{order}', 'OrderController@ustatus')->name('orders.ustatus');
Route::get('/orders/fstatus/{order}', 'OrderController@fstatus')->name('orders.fstatus');

//订单统计
Route::get('/count/order_week', 'CountController@order_week')->name('count.order_week');
Route::get('/count/order_month', 'CountController@order_month')->name('count.order_month');

//菜品销量统计
Route::get('/count/goods_week', 'CountController@goods_week')->name('count.goods_week');
Route::get('/count/goods_month', 'CountController@goods_month')->name('count.goods_month');

//试用活动
Route::resource('events','EventController');
Route::get('/events/sign/{event}', 'EventController@sign')->name('events.sign');
