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
    return view('welcome');
});
//商家列表
Route::get('/businessList','ApiController@businessList');
//获取指定商家
Route::get('/business','ApiController@business');
//注册
Route::post('/regist','ApiController@regist');
//短信验证
Route::get('/sms','ApiController@sms');
//登录
Route::post('/loginCheck','ApiController@loginCheck');
//新增地址
Route::post('/addAddress','ApiController@addAddress');
//地址列表
Route::get('/addressList','ApiController@addressList');
//指定地址
Route::get('/address','ApiController@address');
//保存修改地址
Route::post('/editAddress','ApiController@editAddress');
//保存购物车
Route::post('/addCart','ApiController@addCart');
//获取购物车
Route::get('/cart','ApiController@cart');
//添加订单
Route::post('/addorder','ApiController@addorder');
//指定订单
Route::get('/order','ApiController@order');
//订单列表
Route::get('/orderList','ApiController@orderList');
//修改密码
Route::post('/changePassword','ApiController@changePassword');
//重置密码
Route::post('/forgetPassword','ApiController@forgetPassword');