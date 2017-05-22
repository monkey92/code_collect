#!/bin/bash
# 上传的单个文件
curl -F "filename=@/etc/passwd;type=application/octet-stream" -H "token: 1234" http://file-server.com/upload.do

# 模拟form表单
curl --form "filename@/etc/passwd;type=application/octet-stream" --form "name=dengdachuan" http://file-server.com/upload.do