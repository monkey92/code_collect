<?php

//获得0-1之间的随机数
function get_random(){
	return $random = mt_rand()/mt_getrandmax();	
}
