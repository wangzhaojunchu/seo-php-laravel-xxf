
#! /bin/bash

if [ -d "web" ]; then
  echo "目录已存在，安装依赖..."
  cd web
  composer install
else
  echo "目录不存在，创建 Laravel 项目..."
  composer create-project --prefer-dist laravel/laravel:9 web
fi
# composer create-project topthink/think=5.1.* web
echo 当前版本 `php -v`
#启动服务器


