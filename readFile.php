<?php
  session_start();
  if($_SESSION['log_status']!='ok')
  {
    session_unset();
    session_destroy();
    exit;
  }
  else
  {
    $file_path = $_SESSION['root_path'].$_GET['path'];
    readfile($file_path);
  }
?>