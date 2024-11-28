# Laravel11 Api Starter

基于Laravel 11构建的一个**基础功能**完备，**规范统一**，能够**快速**应用于实际的 API 项目开发启动模板。同时，也希望通过**合理的**架构设计使其适用于中大型项目。

可以简单的API版本，目录形式，在api.php里面可以看到

开箱即用，加速 Api 开发。


## 概览

### 说明

项目就平时自己使用的，分享出来，不一定很好，勿喷。
会laravel基本都会使用，好记性不如烂笔头，比较习惯记录下来：
- 修改.env数据库连接信息
- composer install
- php artisan key:generate
- php artisan migrate
- 创建测试的用户：php artisan db:seed --class=UsersTableSeeder
- php artisan storage:link

API登录获取access token：
https://xxx.com/api/v1/auth/login

拿到access token后
在头部Header里面增加：
Accept:application/json
Authorization: Bearer (这里填写上面获取到的access_token)

https://xxx.com/api/v1/auth/me


### 支持情况

- RESTful 规范的HTTP 响应结构：成功、失败、异常场景统一结构响应；
- Sanctum 方式授权


### 目录结构

```
├── app
│   ├── Contracts                     // 定义 interface
│   ├── Api                        // 事件处理
│   │   ├── V1
│           └──Controllers         //Api都写在这里面
                 └──  AuthController.php   // 包含 用户登录和获取用户信息的使用示例
│   ├── Http
│   │   ├── Controllers               // Controller 层根据 Request 将任务分发给不同 Service 处理，返回响应给客户端
│   │   │   └── Controller.php
│   ├── Jobs                          // 异步任务
│   │   ├── ExampleJob.php
│   │   └── Job.php
│   ├── Listeners                     // 监听事件处理
│   │   └── ExampleListener.php
│   ├── Models                        // Laravel 原始的 Eloquent\Model：定义数据表特性、数据表之间的关联关系等；不处理业务
│   │   └── User.php
│   ├── Providers                     // 各种服务容器
│   │   └── AppServiceProvider.php

```

## License

The Laravel11 Api Starter is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
