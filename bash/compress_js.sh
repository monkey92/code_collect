#!/bin/bash


if [ $# -ne 1 ];then
        echo "Usage: $0 filename";
        exit -1;
fi

if [ ! -f $1 ];then
        echo "filename must be a js file";
        exit -1;
fi

# demo.js
filename=$1
file_extend=${filename##*.}
file_name=${filename%.*}

cat $filename | tr -d '\n\t' | tr -s ' ' | sed 's:/\*.*\*/::g' | sed 's/ \?\([{}();,:=]\) \?/\1/g' > $file_name.min.$file_extend;










