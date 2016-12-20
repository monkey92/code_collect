<?php

/**
 * 微信公众号api
 */
use Monkey92\HttpUtil;

function is_wx_browser(){
    try {
        $flag = strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false;
    } catch (\Exception $e) {
        $flag = false;
    }
    return $flag;
}

function get_access_token($appid,$secret){

    $url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=' . $appid . '&secret=' . $secret;
    $httpUtil = HttpUtil::getInstance();
    $ret = json_decode($httpUtil->doGet($url), true);
    if (isset($ret['errcode'])) {
        info($ret['errmsg']);
        return [];
    }
    return $ret;
}

function menu_create($token,$menu_json)
{
    $url = 'https://api.weixin.qq.com/cgi-bin/menu/create?access_token=' . $token;
    $httpUtil = HttpUtil::getInstance();
    $ret = json_decode($httpUtil->doPost($url, $menu_json), true);
    if ($ret['errcode'] > 0) {
        info($ret['errmsg']);
    }
    return $ret['errcode'];
}
//根据oauth 的code 获取openid
function get_openid_from_oauth($appid,$secret,$code){
    $url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid='.$appid.'&secret='.$secret.'&code='.$code.'&grant_type=authorization_code';
    $ret = handle_get_method($url);
    if(isset($ret['openid'])) return $ret['openid'];
    return "";
}

function get_openids($token, $next_openid = '')
{
    $url = 'https://api.weixin.qq.com/cgi-bin/user/get?access_token=' . $token . '&next_openid=' . $next_openid;
    return handle_get_method($url);
}

function get_userinfo($token, $openid)
{
    $url = 'https://api.weixin.qq.com/cgi-bin/user/info?access_token=' . $token . '&openid=' . $openid . '&lang=zh_CN ';
    return handle_get_method($url);
}

function get_jsapi_ticket($token)
{
    $url = 'https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token=' . $token . '&type=jsapi';
    return handle_get_method($url);
}


function handle_get_method($url)
{
    $httpUtil = HttpUtil::getInstance();
    $ret = json_decode($httpUtil->doGet($url), true);
    if (isset($ret['errcode']) && $ret['errcode'] > 0) {
        info($ret['errmsg']);
        return [];
    }
    return $ret;
}

function handle_post_method($url, $data)
{
    $httpUtil = HttpUtil::getInstance();
    $ret = json_decode($httpUtil->doPost($url, $data), true);
    if (isset($ret['errcode']) && $ret['errcode'] > 0) {
        info($ret['errmsg']);
        return [];
    }
    return $ret;
}


///////////////////////////////////模板消息///////////////////////////////////
/**
 * 设置所属的行业
 * @param $token
 * @param $code1
 * @param $code2
 * @return array|mixed
 */
function api_set_industry($token, $code1, $code2)
{
    $url = 'https://api.weixin.qq.com/cgi-bin/template/api_set_industry?access_token=' . $token;
    $data = [
        "industry_id1" => "$code1",
        "industry_id2" => "$code2"
    ];
    return handle_post_method($url, json_encode($data));
}

/**
 * 添加模板
 * @param $token
 * @param $template_id_short
 * @return array|mixed
 */
function api_add_template($token, $template_id_short)
{
    $url = 'https://api.weixin.qq.com/cgi-bin/template/api_add_template?access_token=' . $token;
    $data = [
        "template_id_short" => "$template_id_short"
    ];
    return handle_post_method($url, json_encode($data));
}

/**
 * 发送消息
 * @param $token
 * @param $data
 * @return array|mixed
 */
function send_template_news($token, $data)
{
    $url = 'https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=' . $token;
    return handle_post_method($url, $data);
}

//推送任务消息通知
function send_task_news($token, $template_id, $touser, $link, $title, $status, $remark = '', $first = '')
{
    $url = 'https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=' . $token;
    $data = [
        "touser" => $touser,
        "template_id" => $template_id,
        "url" => $link,
        "data" => [
            "first" => [
                "value" => $first,
                "color" => "#000000"
            ],
            "keyword1" => [
                "value" => $title,
                "color" => "#000000"
            ],
            "keyword2" => [
                "value" => date("Y-m-d", time()),
                "color" => "#000000"
            ],
//            "keyword3" => [
//                "value" => $status,
//                "color" => "#000000"
//            ],
            "remark" => [
                "value" => $remark,
                "color" => "#000000"
            ]
        ]
    ];
    $data = json_encode($data, JSON_UNESCAPED_UNICODE);
    return handle_post_method($url, $data);
}
//推送设备警告消息通知
function send_warning_news($token,$template_id, $touser, $link, $title,  $remark = '', $first = '')
{
    $url = 'https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=' . $token;
    $data = [
        "touser" => $touser,
        "template_id" => $template_id,
        "url" => $link,
        "data" => [
            "first" => [
                "value" => $first,
                "color" => "#000000"
            ],
            "keyword1" => [
                "value" => date("Y-m-d", time()),
                "color" => "#000000"
            ],
            "keyword2" => [
                "value" => $title,
                "color" => "#000000"
            ],
            "remark" => [
                "value" => $remark,
                "color" => "#000000"
            ]
        ]
    ];
    $data = json_encode($data, JSON_UNESCAPED_UNICODE);
    return handle_post_method($url, $data);
}


/**
 * 下载微信图片
 * @param $token
 * @param $media_id
 * @param $file_path
 * @return string
 */
function wx_download_img($token, $media_id, $file_path)
{
    $url = 'https://api.weixin.qq.com/cgi-bin/media/get?access_token=' . $token . '&media_id=' . $media_id;
    if (!file_exists($file_path)) {
        @mkdir($file_path, 0777, true);
    }
    $file_name = mt_rand() . '.jpg';
    $httpUtil = HttpUtil::getInstance();
    $ret = $httpUtil->doGet($url);
    $f = file_put_contents(public_path($file_path) . '/' . $file_name, $ret);
    if ($f) {
        return $file_path . '/' . $file_name;
    }
    return "";
}


/**
 * 更新或者添加用户
 * 参照微信字段为准
 * @param $token
 * @param $openid
 */
function insert_or_update_user($token, $openid,$appid)
{
    $userinfo_ret = get_userinfo($token, $openid);
    if (empty($userinfo_ret)) return false;
    $wx_user = \App\User::where(['openid' => $openid])->first();
    if ($wx_user == null) $wx_user = new \App\User();

    //设置默认帐号和密码
    if (empty($wx_user->account)) {
//        $wx_user->account = str_random(10);
        $wx_user->account = substr(mt_rand() . strrev(time()) . "", 0, 10);
        $wx_user->password = bcrypt(\App\Consts\SystemConst::DEFAULT_PWD);
    }
    //设置默认角色
    if (empty($wx_user->roles)) {
        $wx_user->roles = json_encode([\App\Consts\SystemConst::PUBLIC_USER . ""], JSON_UNESCAPED_UNICODE);
    }
    $wx_user->openid = $openid;
    $wx_user->appid = $appid;
    $wx_user->subscribe = $userinfo_ret['subscribe'];
    $wx_user->nickname = $userinfo_ret['nickname'];
    $wx_user->sex = $userinfo_ret['sex'];
    $wx_user->language = $userinfo_ret['language'];
    $wx_user->city = $userinfo_ret['city'];
    $wx_user->province = $userinfo_ret['province'];
    $wx_user->country = $userinfo_ret['country'];
    $wx_user->headimgurl = $userinfo_ret['headimgurl'];
    $wx_user->subscribe_time = $userinfo_ret['subscribe_time'];
    $wx_user->remark = $userinfo_ret['remark'];
    $wx_user->groupid = $userinfo_ret['groupid'];
    $wx_user->tagid_list = json_encode($userinfo_ret['tagid_list'], JSON_UNESCAPED_UNICODE);
    if ($wx_user->save()) {
        return $wx_user;
    }
    return false;
}






