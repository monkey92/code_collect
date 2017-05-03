<?php

function is_wx_browser(){
    return strpos($_SERVER['HTTP_USER_AGENT'],'MicroMessenger') !== false;
}

function validate_email(){}

function validate_id_card(){
	var idCard = "";
	 var regIdCard=/^(^[1-9]\d{7}((0\d)|(1[0-2]))(([0|1|2]\d)|3[0-1])\d{3}$)|(^[1-9]\d{5}[1-9]\d{3}((0\d)|(1[0-2]))(([0|1|2]\d)|3[0-1])((\d{4})|\d{3}[Xx])$)$/;
	 if(!regIdCard.test(idCard)){
	 	return false;
	 }
	 return true;
}


