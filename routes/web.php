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

Route::any('wechat', 'WeChatController@serve')->name('wechat');
Route::any('wechat/user', 'WechatController@user')->name('wechat.user');
Route::any('wechat/users', 'WechatController@users')->name('wechat.users');
Route::any('wechat/materials', 'WechatController@materials')->name('wechat.materials');