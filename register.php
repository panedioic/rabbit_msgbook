<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>某Final Mission</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="content container pt-5">
        
    <h1>某Final Mission</h1>
    <p>本站提供注册，登录，留言板功能！</p>
    <?php
        header('content-type:text/html;charset=utf-8');
        include "include.php";
        //检测用户是否登录
        if ($isLogin) {
            echo"<script>alert('您已经登录请不要重复注册！');location.href = 'index.php';</script>";
        }


        if(isset($_POST['username'])){
            $name = $_POST["username"];
            $pwd = $_POST["password"];
            user_register($name, $pwd, $link);
        }
    ?>
    <form method="post" action="register.php" <?php if ($_SESSION['islogin'])echo "hidden"?>>
        <div class="form-group">
            <label>
                用户名：<input type="text" class="form-control" name="username">
            </label>
        </div>
        <div class="form-group">
            <label>
                密码：<input type="password" class="form-control" name="password">
            </label>
        </div>
        <button type="submit" class="btn btn-primary">注册</button>
        <a class="btn btn-success" href="index.php">已有id？点击登录</a>
    </form>
    <br>
    <p>本站仅供学习使用，请勿用于任何非法用途！</p>

    </div>
</body>
</html>