#!/usr/bin/bash

CONF_PATH=/etc/nginx/conf.d/
sudo find $CONF_PATH -type f -name "*.conf" ! -name "default.conf" -exec rm {} \;

