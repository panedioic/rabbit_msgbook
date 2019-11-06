<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Rabbit 留言本 ^ - ^</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="content container pt-5">
        
    <h1>Rabbit 留言本 ^ - ^</h1>
    <p>本站提供注册，登录，留言板功能！</p>
    <?php
    include "include.php";
    if ($isLogin) {
        // 若已经登录
        //输出用户信息
        echo "你好! ".$user_name.' ,欢迎来到个人中心!<br>';
        echo "<img src=\"./upload/$head_path\" width=50 height=50> ";
        echo "<a class=\"btn btn-warning\" href='logout.php'>注销</a> ";
        echo "<a class=\"btn btn-warning\" href='upimg.html'>上传/修改头像</a>";
	} else {
        // 若没有登录
		echo "您还没有登录,请先登录!</a>";
	}
 ?>
    <form method="post" action="login.php" <?php if ($isLogin)echo "hidden"?>>
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
        <button type="submit" class="btn btn-primary">登录</button>
        <a class="btn btn-success" href="register.php">没有id？点击注册</a>
    </form>
    <div id="loged"  <?php if (!$isLogin)echo "hidden"?>>
        <p>test233</p>
        <table border="1" width=80%>
            <tr>
                <td>Test</td>
                <td width=80%>test message</td>
            </tr>
            <?php //输出留言信息
                $query='select * from msg_book';
                $result=mysqli_query($link, $query);
                
                //echo '<br> <br>';
                
                while ($data=mysqli_fetch_assoc($result)){
                echo '<tr>';
                echo '<td>';
                echo "<img src=\"./upload/".$data[post_head]."\" width=50 height=50> <br> $data[username] <br> ";
                echo $data[post_time];
                echo '</td>';
                echo '<td>'.$data[text].'</td>';
                //echo '</tr>\n'
                }
            
            ?>
            <tr>
                <td>Admin<br>2019-11-3 22:45</td>
                <td width=80%>Welcome!</td>
            </tr>
            <tr>
                <td colspan="2">
                <form name="input" action="postmsg.php" method="post">
                    回复内容: <input type="text" name="text">
                    <input type="submit" value="Submit">
                    </form>
                </td>
            </tr>
        </table>
    </div>
    <br>
    <p>本站仅供学习使用，请勿用于任何非法用途！<del>更不要日站！！！</del></p>

    </div>
</body>
</html>