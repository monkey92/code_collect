<?php

//获得当前月的最后一天
function get_last_day_of_month(){
	return date('Y-m-d 23:59:59',strtotime(date('Y-m-01',time()).' +1 month -1 day'));	
}

//获得当前毫秒值
function msectime() {
    list($msec, $sec) = explode(' ', microtime());
    $msectime =  sprintf('%.0f', (floatval($msec) + floatval($sec)) * 1000);
    return $msectime;
}

//时间显示处理
public static function timeStr($time_str){

    $time = strtotime($time_str);
    $cur_time = time();
    $m_10 = 10 * 60;
    $h_1 =  1 * 60 * 60;
    $h_12 = 12 * 60 * 60;
    $h_24 = 24 * 60 * 60;
    $h_48 = 48 * 60 * 60;
    $h_72 = 72 * 60 * 60;

    $expend = $cur_time-$time;

    switch($expend){
        case $expend < $m_10 :
            $str = '刚刚';
            break;
        case $expend >= $m_10 && $expend < $h_1 :
            $str = floor($expend/60).'分钟前';
            break;
        case $expend >= $h_1 && $expend < $h_24 :
            $str = floor($expend/3600).'小时前';
            break;
        case $expend >= $h_24 && $expend < $h_72:
            $str = floor($expend / (24*3600)).'天前';
            break;
        default:
            $str = date("m/d",$time);
    }
    return $str;
}




