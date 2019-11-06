<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>信息提交</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php
header('content-type:text/html;charset=utf-8');
include "include.php";
//检测用户是否登录
if (!$isLogin) {
    echo"<script>alert('您还没有登录！');location.href = 'index.php';</script>";
    exit();
}
//检测认证信息
if(auth_check($user_auth, $user_name, $link)!=1){
    echo"登陆信息出错，信息提交失败！";
    exit();
}


//接受客户端的数据：
$text = $_POST["text"];
$name = $_COOKIE["username"];
$hdpath=$_COOKIE['headpath'];

post_msg($text, $name, $hdpath, $link);
?>
</body>
</html>