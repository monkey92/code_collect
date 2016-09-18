#!/bin/bash

export DISPLAY=:0
export LANG=zh_CN.utf8

count_down=30
count=0

( while [ $count -lt $count_down ];do

	echo $(( 100*count/count_down ));
	sleep 1;
	(( count++ ))
done ) | zenity  --progress \
		 --title="关机吗～" \
		 --text="30s后将会执行关机脚本\n $0" \
		 --auto-close \
		 --time-remaining \
		 --width=400 \
		 --height=150

zenity_result=$?

if [ $zenity_result -ne 0 ];then
	notify-send  "已取消关机"
	exit 0;
fi

# kill application  process
# notify-send  "kill application process";
# shutdown machine

echo "123456" | sudo -S /sbin/shutdown -h 22:30

notify-send  "22:30将会执行关机命令 取消sudo shutdown -c"


