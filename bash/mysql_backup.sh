#!/bin/bash


[[ ! -d $1 ]] && echo -e  "please specify the legal directory \nUsage: bash $0 dir_name" && exit 0;

/usr/bin/mysqldump -uroot -proot db_name [table1 table2...] > $1/db_name.sql


