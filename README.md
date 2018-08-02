## 安装 🔩

先git clone本项目到指定文件夹，然后用composer运行以下代码

    composer install

然后配置**build/config.php**，cd到项目文件夹下，运行下行代码创建表单

     php migration/update.php

## 结构 💥

-   build: 路由
-   migration: 数据库定义
-   service: 主逻辑
-   view: 前端
-   ui: 纯HTML前端模板

## 代码规范 💥

1、service代码文件头部请加上注释

          /*
          |--------------------------------------------------------------------------
          | 本段代码的主要功能
          |--------------------------------------------------------------------------
          |
          | 代码的input/output注释
          |
          |
          */

例如

          /*
          |--------------------------------------------------------------------------
          | 储存openID到session，并跳转至相关链接
          |--------------------------------------------------------------------------
          |
          | 输入
          | - $openid：用户openID
          | - $url：特殊格式化的跳转网址（a+b+c!d=1 => https://xy.zuggr.com/a/b/c?d=1）
          |
          |
          */

2、请使用[PSR-2](https://www.php-fig.org/psr/psr-2/)代码规范

3、请统一使用UTF-8编码

## Road Map 🔭

-   ~~7月21日：UI设计~~
-   ~~7月27日：完成**阶段一**~~
-   ~~8月01日：测试完成，可以上线~~

## 提示 ⚠️

1.  当想更新数据库结构时，请先清空**migration/log.json**中的记录，删除所有数据库结构。
2.  在运行前请记得将**file/json**中的所有文件权限设置为可读可写
