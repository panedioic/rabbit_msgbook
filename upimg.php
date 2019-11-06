<?php
  header('content-type:text/html;charset=utf-8');
  date_default_timezone_set("PRC");
  include "include.php";
 
  $img=$_FILES['userImg'];
  
  post_img($img, $user_name, $link);

  //参考：PHP上传用户头像及头像的缩略图
  //https://blog.csdn.net/Lyj1010/article/details/82776006

?>