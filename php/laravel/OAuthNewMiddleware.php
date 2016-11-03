<?php

namespace App\Http\Middleware;

use App\Models\Corp;
use App\Models\TokenCache;
use App\User;
use Closure;
use Monkey92\HttpUtil;

/**
 * 新的oauth认证过滤器
 * 实现手机端的登录
 * @author ddc
 * Class OAuthMiddleware
 * @package App\Http\Middleware
 *
 */
class OAuthNewMiddleware
{
    /**
     * Handle an incoming request.
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next){

        info("========= url=".$request->fullUrl()."  =====");

        //检测是否是微信浏览器的访问
        if(!is_wx_browser()){
            if(auth()->check()){
                return $next($request);
            }
            //添加pc端测试入口
            if(session()->has('openid') || $request->has('openid')){

                $openid = session('openid') ? session('openid') : $request->input('openid');
                $user = User::where('openid',$openid)->where('account','!=','')->first();
                if($user != null){
                    auth()->login($user);
                    session(['openid'=>$openid]);
                    return $next($request);
                }
            }
            return "需要在微信浏览器打开此链接";
        }

        /*
         * 多公众号存在的问题,如果一个人同时关注当前平台下的两个公众号,用户在两个公众号下切换浏览时使用是同一个session
         * 从入口链接(微信的菜单，以及模板消息 )进入的话是必须携带appid 参数的,用于切换当前的session
         * 在网页内的链接 使用当前session保存的appid，
         * 如果没有从request中获取到appid 但是用户还是登录状态,就认为用户没有切换公众号
         */
        $appid = $request->input('appid','');
        if(auth()->check() && $appid == ''){
            info("=============== 微信已登录  ============================");
            return $next($request);
        }
        if(empty($appid)){
            $route_param = $request->route('_missing');
            $appid = empty($route_param) ? '' : $route_param[0];
        }
        $corp = Corp::where(['appid'=>$appid])->first();
        if($corp == null){
            return "非法的appid";
        }
        info("====当前的appid=".$appid."=====");
        //获得openid
        $cooke_openid_key = $appid."_openid";

        if($request->has('code')){
            $openid = $corp->getOpenIdFromOAuth($request->input('code'));
            $openid_from = 'code';
        }else if($request->hasCookie($cooke_openid_key)){
            $openid = $request->cookie($cooke_openid_key);
            info("====cookie_openid_key=".$cooke_openid_key."=====");
            $openid_from = 'cookie';
        }else{
            return $corp->redirect2getcode($request->fullUrl());
        }
        info('====openid='.$openid.'====');
        info('====openid_from='.$openid_from.'====');
        //用户登录
        $user = User::where('openid',$openid)->where('appid',$appid)->first();

        //如果cookie的来源找不到用户,调用oauth再获取一次
        if($user == null && $openid_from=='cookie'){
            return $corp->redirect2getcode($request->fullUrl());
        }
        //如果code的来源找不到用户,可能是没有抓取当前人
        if($user == null && $openid_from=='code'){
            //可以再拉取一下微信用户
            $token = $corp->getAccessToken();
            insert_or_update_user($token,$openid,$appid);
            $user = User::where('openid',$openid)->where('appid',$appid)->first();
            if($user == null){
                return "您暂不可以访问当前公众号的连接";
            }
        }

        auth()->login($user);
        //把openid放到cookie里面
       app('cookie')->queue($cooke_openid_key,$openid,env('WX_BROWSER_COOKIE_TIME',10080));
//       app('cookie')->queue('appid',$appid,env('WX_BROWSER_COOKIE_TIME',10080));
        return $next($request);
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
