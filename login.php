<!DOCTYPE HTML>
<html>
  <head>
    <title>Air storage</title>
    <link href="css/login.css" rel = "stylesheet">
  </head>
  <body>
    <div id = "picture">我是图片展示区</div>
    <div id = "log_area">
      <div id = "center_log">
        <img id = "logo" src = "imgs/logo.png"> 
        <form>
          <input id ="username" class ="input_area" type="text" name="username" placeholder="请输入用户名"><br />
          <input id ="password" class ="input_area" type="password" name="password" placeholder="请输入密码"><br /><br />
          <input onclick = "login()" id = "login_button" class="input_area" type="button" value="登录">
        </form>
      </div>
    </div>
    <script text="text/javascript" src="js/login.js"></script>
  </body>
<html>
