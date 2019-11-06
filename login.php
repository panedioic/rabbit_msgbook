<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>用户登录</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php
header('content-type:text/html;charset=utf-8');
include "include.php";
//检测用户是否登录
if ($isLogin) {
    echo"<script>alert('您已经登录请不要重复登录！');location.href = 'index.php';</script>";
}


//接受客户端的数据：
$name = $_POST["username"];
$pwd = $_POST["password"];
user_login($name, $pwd, $link);
?>
</body>
</html>