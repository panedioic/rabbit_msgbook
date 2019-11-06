<?php
/*
*   Welcome to Rabbit Msgbook system!
*
*    This is a main file in include files.
*/

//Contest definations.
define("MYSQL_HOST", "********");
define("MYSQL_USERNAME", "********");
define("MYSQL_PASSWORD", "********");
define("MYSQL_DATABASE", "final_database");
//No privacy information!!!

define("TABLE_USERLIST", "final_user");
define("TABLE_MSGBOOK", "msg_book");
define("TABLE_USERAUTH", "user_auth");

//variables
$isLogin = false;



//Cookie control
header('Content-type:text/html; charset=utf-8');
// 开启Session
session_start();
if(isset($_COOKIE['username'])){
    $isLogin=true;
    //Inf from cookie use x_x
    $user_name = $_COOKIE['username'];
    $head_path = $_COOKIE['headpath'];
    $user_auth = $_COOKIE['userauth'];
}
else{
    $isLogin=false;
}

//MySql Control
$link = mysqli_connect(MYSQL_HOST, MYSQL_USERNAME, MYSQL_PASSWORD, MYSQL_DATABASE);


//function definations.
function anti_xss($text){
    $text_anti_xss = trim($text);  //清理空格  
    $text_anti_xss = strip_tags($text_anti_xss);   //过滤html标签  
    $text_anti_xss = htmlspecialchars($text_anti_xss);   //将字符内容转化为html实体  
    $text_anti_xss = addslashes($text_anti_xss);  //防止SQL注入
    return $text_anti_xss;
}

function auth_check($auth, $name, $link){
    $sql = "SELECT * FROM `".TABLE_USERAUTH."` WHERE username = '$name'";
    $res = mysqli_query($link, $sql);
    $arr = mysqli_fetch_assoc($res);
    if($arr){
        if($arr['userauth']==$auth && $arr['authtime'] + 3600 > time()){//wait for timestamp check.
            return 1;
        }
        else {
            return 0;
        }
    }
    else {
        return -1;
    }
}

function auth_insert($auth, $name, $link){
    //Check that user authentication exists.
        $tmp = auth_check($auth, $name, $link);
        if($tmp >= 0){
            //$sql2 = "update ".TABLE_USERAUTH." set `userauth`='$auth', 'authtime'= ".time()." WHERE username = '$name'";
            $sql2 = "UPDATE `user_auth` SET `userauth` = '$auth', `authtime` = '".time()."' WHERE `user_auth`.`username` = '$name'";
            mysqli_query($link, $sql2);
            //echo "<script>alert(123)</script>";
        }
        else if($tmp == -1){
            $sql2 = "INSERT INTO `".TABLE_USERAUTH."` (`username`, `userauth`, `authtime`) VALUES ('$name', '$auth', '".time()."')";
            mysqli_query($link, $sql2);
            //echo "<script>alert(456)</script>";
        }    
}

function user_login($username, $password, $link){
    $sql = "SELECT * FROM `final_user` WHERE username = '$username'";
    $res = mysqli_query($link, $sql);
    $arr = mysqli_fetch_assoc($res);
    $password_md5ed = md5($password);
    if($arr){
        if($arr['password']==$password_md5ed){
            $auth_tmp = rand(1000000,9999999);
            setcookie("username",$_POST["username"],time()+3600);
            setcookie("headpath",$arr["head_path"],time()+3600);
            setcookie("userauth",$auth_tmp,time()+3600);
            auth_insert($auth_tmp, $username, $link);
            echo"<script>alert('登录成功！');location.href = 'index.php';</script>";
        }else{
            echo"<script>alert('密码错误！');location.href = 'index.php';</script>";
        }
    }else{
        echo "<script>alert('".$username."');</script>";
        echo"<script>alert('用户名不存在，请先注册！');location.href = 'index.php';</script>";
    }
}

function user_register($username, $password, $link){
    $username_anti_xss = anti_xss($username);
    $sql = "SELECT * FROM `".TABLE_USERLIST."` WHERE username = '$username_anti_xss'";
    $res = mysqli_query($link, $sql);
    $arr = mysqli_fetch_assoc($res);
    if($arr){
        echo"<script>alert('用户名已存在！');location.href = 'index.php';</script>";
    }else{
        //INSERT INTO `final_user` (`username`, `password`, `head_path`) VALUES ('panedioic', 'abc123', NULL)
        $password_md5ed = md5($password);
        $sql2 = "INSERT INTO `".TABLE_USERLIST."` (`username`, `password`, `head_path`) VALUES ('$username_anti_xss', '$password_md5ed', NULL)";
        mysqli_query($link, $sql2);
        echo"<script>alert('注册成功！');location.href = 'index.php';</script>";
    }
}

function user_logout(){
    //$sql2 = "update ".TABLE_USERAUTH." set `userauth`='0', 'authtime'= '".time()-3600."' WHERE username = '$user_name'";
    //mysqli_query($link, $sql2);
    setcookie("username",$user_name,time()-3600);
    setcookie("headpath",$head_path,time()-3600);
    setcookie("userauth",$user_auth,time()-3600);
    echo "<script>alert('注销成功！');location.href = 'index.php';</script>";
}

function post_msg($text, $username, $headpath, $link){
    $text_anti_xss = anti_xss($text);
    $sql2 = "INSERT INTO `".TABLE_MSGBOOK."` (`username`, `post_time`, `text`, `post_head`) VALUES ('$username', CURRENT_TIMESTAMP, '$text_anti_xss', '$headpath')";
    mysqli_query($link, $sql2);
    echo"<script>alert('发送成功！');location.href = 'index.php';</script>";
}

function post_img($img, $username, $link){
      // 服务器中文件的存放目录
  $tmp_dir=$img['tmp_name'];
  //用户上传的文件名（带后缀）
  $fileName=$img['name'];
 
  if($img['error']!=0){
     echo '上传文件出现错误！请重试！';
     return;
  }else if($img['size']>10240000){
     echo "文件超过".$maxSize;
     return;
  }else if($img['size']<20480){
    echo "上传文件出现错误！请重试！";
    return;
 }else{
    //创建目录
    $fileDir='./upload/';
    if(!is_dir($fileDir)){
        mkdir($fileDir);
    }
    //文件名
    $newFileName=date('YmdHis',time()).rand(100,999);
    //文件后缀
    $FileExt=substr($fileName,strrpos($fileName,'.'));
    $FilePath=$newFileName.$FileExt;

    //生成缩略图
    $img_info=getimagesize($tmp_dir);
    // var_dump($img_info);
    $width=$img_info[0]; //原图的宽
    $height=$img_info[1]; //原图的高
    $newWidth=$width*0.5;
    $newHeight=$height*0.5;

    //绘制画布
    $thumb=imagecreatetruecolor($newWidth, $newHeight);
    //创建一个和原图一样的资源
    $source=imagecreatefromjpeg($tmp_dir);

    //根据原图创建缩略图
    imagecopyresized($thumb,$source,0,0,0,0,$newWidth,$newHeight,$width,$height);

    //保存缩略图
    imagejpeg($thumb,'./upload/'.$newFileName.$FileExt,100);

    //移动文件到指定的目录
    move_uploaded_file($tmp_dir,'./upload/'.$newFileName.'_temp'.$FileExt);
    unlink('./upload/'.$newFileName.'_temp'.$FileExt);
    $sql2 = "update final_user set `head_path`='$FilePath' WHERE username = '$username'";
    mysqli_query($link, $sql2);
    setcookie("headpath",$FilePath,time()+3600);
    echo "<script>alert('上传成功！');location.href = 'index.php';</script>";
    
  }
}

?>