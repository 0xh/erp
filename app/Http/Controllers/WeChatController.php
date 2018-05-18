<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Log;
//use Encore\Admin\Controllers\Dashboard;
//use Encore\Admin\Facades\Admin;
//use Encore\Admin\Layout\Column;
//use Encore\Admin\Layout\Content;
//use Encore\Admin\Layout\Row;

class WeChatController extends Controller
{
    public $wechat;

    /**
     * WechatController constructor.
     * @param $wechat
     */
    public function __construct()
    {
        $this->wechat = app('wechat.official_account');
    }

    public function serve()
    {
        $this->wechat->server->push(function($message){
            return '欢迎关注 '.env('APP_NAME').'！<a href="'.env('APP_URL').'">系统地址</a>';
        });

        return $this->wechat->server->serve();
    }

    public function users()
    {
        $users = $this->wechat->user->list($nextOpenId = null);
        return $users;
    }

    public function user()
    {
        $openId = Input::get('openid');
        $users = $this->wechat->user->get($openId);
        return $users;
    }

    public function materials()
    {
        $materials = $this->wechat->material->lists();
        return $materials;
    }
}
