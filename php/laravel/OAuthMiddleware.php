<?php

namespace App\Http\Middleware;

use App\Models\WxUser;
use Closure;
use Monkey92\HttpUtil;

/**
 * 微信公众号oauth认证
 * Class OAuthMiddleware
 * @package App\Http\Middleware
 *
 */
class OAuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */

    public function handle($request, Closure $next){

        //检测是否是微信浏览器的访问
        if(!is_wx_browser()){
            return "需要在微信浏览器打开此链接";
        }

        //检查session里是否有openid
        if(!empty(session('openid'))){
            return $next($request);
        }
        $openid_from = '';
        $openid = '';
        if($request->has('code')){
            $openid = $this->getOpenIdFromCode($request->input('code'));
            $openid_from = 'code';
        }else if($request->hasCookie('openid')){
            $openid = $request->cookie('openid');
            $openid_from = 'cookie';
        }else{
            return $this->redirect2getcode($request->fullUrl());
        }
        //验证openid
        $wx_user = WxUser::where(['openid'=>$openid])->first();
        if($wx_user == null){
            return "非法的openid,这可能是你还没有关注此公众号,或者服务器未同步";
        }
        //把openid放到session里面
        session(["openid"=>$openid,"openid_from"=>$openid_from]);
        //把openid放到cookie里面
//        app('cookie')->queue('openid',$openid,7*24*60);
        app('cookie')->queue('openid',$openid,60);
        //向下请求
        return $next($request);
    }

    private function redirect2getcode($redirect_uri){
        $appid = config('wxconf.appid');
        $url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid='.$appid.'&redirect_uri='.urlencode($redirect_uri).'&response_type=code&scope=snsapi_base&state=STATE#wechat_redirect';
        return redirect($url);
    }

    private function getOpenIdFromCode($code){
        $appid = config('wxconf.appid');
        $secret = config('wxconf.secret');
        $url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid='.$appid.'&secret='.$secret.'&code='.$code.'&grant_type=authorization_code';
        $httpUtil = HttpUtil::getInstance();
        $ret = json_decode($httpUtil->doGet($url),true);
        if(isset($ret['errcode']) && $ret['errcode'] > 0 ){
            info(__METHOD__.'==>'.$ret['errmsg']);
            return "";
        }
        return $ret['openid'];
    }

    private function getAccessTokenFromCode($code){
        $appid = config('wxconf.appid');
        $secret = config('wxconf.secret');
        $url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid='.$appid.'&secret='.$secret.'&code='.$code.'&grant_type=authorization_code';
        $httpUtil = HttpUtil::getInstance();
        $ret = json_decode($httpUtil->doGet($url),true);
        if(isset($ret['errcode']) && $ret['errcode'] > 0 ){
            info(__METHOD__.'==>'.$ret['errmsg']);
            return [];
        }
        return $ret;
    }

    private function getUserInfoFromAccessToken($ret){
        $url = 'https://api.weixin.qq.com/sns/userinfo?access_token='.$ret['access_token'].'&openid='.$ret['openid'].'&lang=zh_CN';
        $httpUtil = HttpUtil::getInstance();
        $ret = json_decode($httpUtil->doGet($url),true);
        if(isset($ret['errcode']) && $ret['errcode'] > 0 ){
            info(__METHOD__.'==>'.$ret['errmsg']);
            return [];
        }
        return $ret;
    }


}
