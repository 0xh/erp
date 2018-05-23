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
    return redirect('/admin');
});

Route::any('test', 'TestController@test')->name('test');
Route::any('wechat', 'WeChatController@serve')->name('wechat');
Route::any('wechat/user', 'WeChatController@user')->name('wechat.user');
Route::any('wechat/msg2user', 'WeChatController@msg2user')->name('wechat.msg2user');
Route::any('wechat/users', 'WeChatController@users')->name('wechat.users');
Route::any('wechat/materials', 'WeChatController@materials')->name('wechat.materials');
Route::any('wechat/menulist', 'WeChatController@menuList')->name('wechat.menuList');

Route::group(['middleware' => ['web', 'wechat.oauth']], function () {
    Route::any('wechat/login', 'WeChatController@loginUsingId')->name('wechat.login');
    Route::any('wechat/getopenid', 'WeChatController@getOpenId')->name('wechat.getOpenId');
});