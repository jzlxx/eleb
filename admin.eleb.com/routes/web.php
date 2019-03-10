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
Route::resource('/shopcategories','ShopCategoryController');
Route::post('/shopcategories/update', 'ShopCategoryController@update')->name('shopcategories.update');
Route::get('/shopcategories/ustatus/{shopcategory}', 'ShopCategoryController@ustatus')->name('shopcategories.ustatus');
Route::post('/shopcategories/upload','ShopCategoryController@upload')->name('shopcategories.upload');

//主页欢迎页面
Route::get('/welcome/index','WelcomeController@index')->name('welcome.index');


//商家信息
Route::resource('/shops','ShopController');
Route::post('/shops/update', 'ShopController@update')->name('shops.update');
Route::get('/shops/ustatus/{shop}', 'ShopController@ustatus')->name('shops.ustatus');
Route::post('/shops/upload','ShopController@upload')->name('shops.upload');

//管理员账号
Route::get('/admins/pwd','AdminController@pwd')->name('admins.pwd');
Route::post('/admins/pupdate', 'AdminController@pupdate')->name('admins.pupdate');
Route::resource('/admins','AdminController');
Route::post('/admins/rupdate', 'AdminController@rupdate')->name('admins.rupdate');
//Route::get('/admins/ustatus/{admin}', 'AdminController@ustatus')->name('admins.ustatus');


//商家账号
Route::resource('/users','UserController');
Route::post('/users/update', 'UserController@update')->name('users.update');
Route::get('/users/ustatus/{user}', 'UserController@ustatus')->name('users.ustatus');


//登录
Route::get('/login', 'LoginController@create')->name('login');
Route::post('/login', 'LoginController@store')->name('login');
Route::get('/logout', 'LoginController@destroy')->name('logout');


//活动
Route::resource('/activities','ActivityController');
Route::post('/activities/update', 'ActivityController@update')->name('activities.update');

//会员管理
Route::resource('/members','MemberController');
Route::get('/members/ustatus/{member}', 'MemberController@ustatus')->name('members.ustatus');

//权限管理
Route::resource('/permissions','PermissionController');

//角色管理
Route::resource('/roles','RoleController');
Route::post('/roles/update', 'RoleController@update')->name('roles.update');

//导航菜单
Route::resource('/navs','NavController');
Route::post('/navs/update', 'NavController@update')->name('navs.update');
Route::post('/navs/rupdate', 'NavController@rupdate')->name('navs.rupdate');

//试用活动
Route::resource('/events','EventController');
Route::post('/events/update', 'EventController@update')->name('events.update');
Route::get('/events/luck/{event}', 'EventController@luck')->name('events.luck');


//活动奖品
Route::resource('/eventprizes','EventPrizeController');
Route::post('/events/destory', 'EventPrizeController@destory')->name('eventprizes.destory');