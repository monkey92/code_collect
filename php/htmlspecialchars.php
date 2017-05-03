<?php

function _htmlspecialchars($arr){
	$newArr = [];
	foreach ($arr as $key => $value) {
		$newArr[$key] = is_array($value)? _htmlspecialchars($value) : htmlspecialchars($value,ENT_QUOTES);
	}
	return $newArr;
}
