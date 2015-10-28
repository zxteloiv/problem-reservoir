# problem-reservoir
A CRUD system designed to help teachers compile a test.

## How to Use

This system needs PHP and CodeIgniter. It is deployed using Nginx but it will run under any PHP server theoretically.

The CodeIgniter version 3.0.2 code is also distributed here. You must copy prs and system folders to your own web root.

Further configuration can be modified in the `prs/index.php` file, which may allow you to put these two folders somewhere else.

Access the home page by something like `http://localhost/prs/`.

## Others

Here're some Chinese intro notes to my advisor.

> 这个题库系统初版支持题目录入和按需查询功能。包括服务端和客户端两个部分。
> 服务端系统以 API 为核心，以 PHP 和 Nginx 实现了类似 RESTful 风格的 HTTP 接口，用于与客户端交互。
> 服务端使用了流行的 MVC 架构，代码之间实现了低耦合，便于扩充新的功能。后台存储使用 MySQL 关系数据库，保存了题库的各项属性和题目内容，必要时部分内容可改用更高效的 KV 系统。
> 客户端使用了 HTML5 和 JavaScript 做用户交互，方便跨平台使用。由于均为静态内容，可以使用 CDN 加速访问，或与服务端独立部署。
>
