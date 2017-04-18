<?php

//获得当前月的最后一天
function get_last_day_of_month(){
	return date('Y-m-d 24:59:59',strtotime(date('Y-m-01',time()).' +1 month -1 day'));	
}

//获得当前毫秒值
function msectime() {
    list($msec, $sec) = explode(' ', microtime());
    $msectime =  sprintf('%.0f', (floatval($msec) + floatval($sec)) * 1000);
    return $msectime;
}