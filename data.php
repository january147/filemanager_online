<?php
    require_once "kit.php";
    session_start();
    /*读取当前操作*/
    $act = $_REQUEST["act"];
     /*读取用户名和密码登录*/
    if($_REQUEST["username"]!=NULL&&$_REQUEST["password"]!=NULL)
    {
      $_SESSION["username"] = $_REQUEST["username"];
      $_SESSION["password"] = $_REQUEST["password"];
    }
    if($_SESSION["log_status"]!="ok"&&$act=="login")
    {
      login($_SESSION["username"],$_SESSION["password"]);
    }
    else if($_SESSION["log_status"]=="ok")
    {
      /*读取粘贴操作的模式：复制或者剪切*/
      if($_REQUEST["model"]!=NULL)
      {
        $_SESSION["model"] = $_REQUEST["model"];
      }
      /*读取剪切板数据（用于粘贴操作）*/
      if($_REQUEST["clipper"]!=NULL)
      {
        $_SESSION["clipper"] = $_SESSION["path"].'/'.$_REQUEST["clipper"];
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
          case "logout":
            logout();
            break;
          case "save_change":
            saveChange($_REQUEST["editing"],$_REQUEST["contents"]);
            break;
      }
      //print_r ($_FILES);
    }
    else
      echo "非法访问,请先登录";
?>