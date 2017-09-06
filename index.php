<?php
  require_once './func/Air.php';
  require_once './func/file_function.php';
  require_once './controller/Controller.php';
  require_once './controller/MainPage.php';
  require_once './controller/Content.php';
  require_once './controller/LogPage.php';
  require_once './controller/Error.php';
  require_once './controller/FormPage.php';
  require_once './models/Dir.php';
  require_once './models/File.php';
  session_start();
  $r = $_GET['r'];

  /*测试模拟传入数据*/
  //$_SESSION['root_path'] = './file/test';

  //测试登录
  if($_SESSION['log_status']!='ok')
  {
    $r = 'LogPage/login';
  }
  
  $air = new Air($r);
  $air->run();
?>