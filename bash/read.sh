#!/bin/bash

while : 
do 
	read -p "Please input a number:(Default 2)" PHP_VERSION
	[ -z "$PHP_VERSION" ] && PHP_VERSION=2
	if [[ ! $PHP_VERSION =~ ^[1-5]$ ]]; then
		echo "input error!"
	else
		break
	fi
done