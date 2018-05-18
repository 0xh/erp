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

    /**
     * 'ToUserName' => 'gh_6e763e3a8871',
     * 'FromUserName' => 'ozBHHw7KxKgbygYLziF1vyB_hiic',
     * 'CreateTime' => '1526612237',
     * 'MsgType' => 'text',
     * 'Content' => 'gdhsh',
     * 'MsgId' => '6556749632023162650',
     * @return mixed
     */
    public function serve()
    {
        $this->wechat->server->push(function($message){
            if (isset($message['MsgType']) && $message['MsgType']=='text'){
                $result = '确认您为'.env('APP_NAME').'认证用户！<a href="'.env('APP_URL').'">点击自动登录</a>';
            }else{
                $result = '欢迎关注 '.env('APP_NAME').'！<a href="'.env('APP_URL').'">点击通过账号登录</a>
                          (已注册用户第一次登录时，需回复手机号，以绑定账号自动登录)';
            }
            return $result;
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

    public function msg2user()
    {
        $openId = Input::get('openid');
        $message = Input::get('message');
        $users = $this->wechat->customer_service->message($message)->to($openId)->send();
        return $users;
    }

    public function materials()
    {
        $materials = $this->wechat->material->lists();
        return $materials;
    }
}
