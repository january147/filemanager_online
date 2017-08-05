<?php
    require_once "kit.php";
    session_start();
    $act = $_REQUEST["act"];
    if($_REQUEST["model"]!=NULL)
    {
      $_SESSION["model"] = $_REQUEST["model"];
    }
    if($_REQUEST["clipper"]!=NULL)
    {
      $_SESSION["clipper"] = $_SESSION["path"].'/'.$_REQUEST["clipper"];
    }
    if($_REQUEST["username"]!=NULL&&$_REQUEST["password"]!=NULL)
    {
      $_SESSION["username"] = $_REQUEST["username"];
      $_SESSION["password"] = $_REQUEST["password"];
    }
    $operation_path = $_SESSION["rootpath"].$_SESSION["path"].'/'.$_REQUEST["filename"];
    
    switch($act)
    {
        case "create_file":
          createFile($operation_path,$_REQUEST["filename"]);
          break;
        case "rename_file":
          $new_filename = $_SESSION["rootpath"].$_SESSION["path"].'/'.$_REQUEST["new_name"];
          renameFile($operation_path,$new_filename);
          break;
        case "down_file":
          downFile($operation_path);
          break;
        case "paste":
          $clipper_path = $_SESSION["rootpath"].$_SESSION["clipper"];
          $current_path = $_SESSION["rootpath"].$_SESSION["path"];
          //调试代码
          //echo $clipper_path.'@';
          //echo $current_path.'@';
          //
          paste($clipper_path,$current_path,$_SESSION["model"]);
          break;
        case "create_dir":
          createDir($operation_path,$_REQUEST["filename"]);
          break;
        case "delete_dir":
          deleteDir($operation_path);
          break;
        case "delete_file":
          deleteFile($operation_path);
          break;
        case "upload_file":
          $current_path = $_SESSION["rootpath"].$_SESSION["path"];
          uploadFile($_FILES["myfile"],$current_path);
          break;
        case "clear_clipper":
          unset($_SESSION["clipper"]);
          unset($_SESSION["model"]);
          echo "success";
          break;
        case "login":
          login($_SESSION["username"],$_SESSION["password"]);
          break;
        case "logout":
          logout();
          break;
    }
    //print_r ($_FILES);
?>