<?php

function is_wx_browser(){
    return strpos($_SERVER['HTTP_USER_AGENT'],'MicroMessenger') !== false;
}

function validate_email(){}