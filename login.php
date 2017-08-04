<?php session_start()?>
<!DOCTYPE HTML>
<html>
  <head>
    <title>Air storage</title>
    <link href="css/login.css" rel = "stylesheet">
  </head>
  <body>
    <div id = "log_area">
      <div id = "center_log">
        <div>
        <img id = "logo" src = "imgs/logo.png"> 
        <form action="data.php?act=login" method="post">
          <input id ="username" class ="input_area" type="text" name="username" placeholder="请输入用户名"><br />
          <input id ="password" class ="input_area" type="password" name="password" placeholder="请输入密码"><br /><br />
          <input onclick = "return input_check()" id = "login_button" class="input_area" type="submit" value="登录">
        </form>
        </div>
      </div>
    </div>
    <script text="text/javascript" src="js/login.js"></script>
    <script text="text/javascript">
    window.onload=function()
    {
      log_status = "<?php echo $_SESSION["log_status"]?>";
      if(log_status == "ps_error")
      {
        password = document.getElementById("password");
        password.value = "";
        password.setAttribute("placeholder","用户名和密码不匹配");
        password.focus();
      }
      else if( log_status == "no_usr")
      {
        //alert("用户名不存在");
        username = document.getElementById("username");
        username.value = "";
        username.setAttribute("placeholder","用户名不存在,请重新输入");
        username.focus();
      }
      else if(log_status == "no_dir")
      {
        alert("当前用户没有访问权限");
      }
    }
    </script>
  </body>
<html>
<?php unset($_SESSION["log_status"])?>
