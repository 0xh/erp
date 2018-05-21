<?php

namespace App\Http\Controllers;

use EasyWeChat\Kernel\Messages\Text;
use App\Http\Controllers\Controller;
use Encore\Admin\Auth\Database\Administrator;
use Illuminate\Support\Facades\Auth;
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
     * 'MsgType' => 'text','Content' => 'gdhsh','MsgId' => '6556749632023162650',
     * 'MsgType' => 'event','Event' => 'CLICK','EventKey' => 'V1001_TODAY_MUSIC',
     *
     * @return mixed
     */
    public function serve()
    {
        $this->wechat->server->push(function($message){
            $user = null;
            if(isset($message['FromUserName'])){
                $user = Administrator::where('wechat_id',$message['FromUserName'])->first();
            }
            if (isset($message['Content']) && !$user){
                $user = Administrator::where('username',$message['Content'])->first();
                if ($user && !$user->wechat_id){
                    $user->wechat_id = $message['FromUserName'];
                    $user->save();
                }
            }
            if (isset($message['MsgType']) && $user){
                $result = '确认您为'.env('APP_NAME').'认证用户！<a href="'.env('APP_URL').'/wechat/login">点击自动登录</a>';
            }else{
                $result = '欢迎关注 '.env('APP_NAME').'！<a href="'.env('APP_URL').'">点击通过账号登录</a>'
                          .'(已注册用户第一次登录时，需回复手机号，以绑定账号自动登录)';
            }
            return $result;
        });

        return $this->wechat->server->serve();
    }

    public function getOpenId()
    {
        $oauth = $this->wechat->oauth->user();
        dd($oauth);
//        return $oauth;
//        $openPlatform = EasyWeChat::openPlatform();
//        app('wechat.open_platform')->getPreAuthorizationUrl(env('APP_URL').'/wechat/login');
    }

    public function loginUsingId()
    {
        $url = Input::get('url');
        $url = $url ? $url : '/admin/tasks';
        $wxUser = session('wechat.oauth_user.default');
        $openId = $wxUser ? $wxUser->attributes['id'] : null;
        $user = Administrator::where('wechat_id',$openId)->first();
        if (empty($user)) {
            return redirect($url);
        }

        Auth::guard('admin')->loginUsingId($user->id);
        return redirect($url);
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
        $fromId = Input::get('fromid');
        $toId = Input::get('toid');
        $message = Input::get('message');

        $msgSend = $this->wechat->customer_service->message($message)->to($toId)->send();
        return $msgSend;
    }

    public function menuList()
    {
        $this->wechat->menu->delete();
        $buttons = [
                        [
                            "type" => "click",
                            "name" => env('APP_NAME'),
                            "key"  => "V1001_TODAY_MUSIC"
                        ],
                    ];
//        $this->wechat->menu->create($buttons);
        $menuList = $this->wechat->menu->list();
        return $menuList;
    }

    public function materials()
    {
        $materials = $this->wechat->material->lists();
        return $materials;
    }
}



//$this->wechat->server->push(function($message){
//    $fromId = Input::get('fromid');
//    $toId = Input::get('toid');
//    $message = Input::get('message');
//
//    $msgSend=new Text($message);
//    $msgSend->setAttribute('from',$fromId);
//    $msgSend->setAttribute('to',$toId);
//    $msgSend = $this->wechat->customer_service->message($message)->to($toId)->send();
//    return $msgSend;
//});
//
//return $this->wechat->server->serve();