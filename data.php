<?php
    require_once "kit.php";
    session_start();
    $modify_filename = $_REQUEST["filename"];
    $act = $_REQUEST["act"];
    if($_REQUEST["model"]!=NULL)
    {
      $_SESSION["model"] = $_REQUEST["model"];
    }
    if($_REQUEST["clipper"]!=NULL&&preg_match('/^\.\/file/',$_REQUEST["clipper"]))
    {
      $_SESSION["clipper"] = $_REQUEST["clipper"];
    }
    if($_Requet["username"]!=NULL&&$_REQUEST["password"]!=NULL)
    {
      $_SESSION["username"] = $_Requet["username"];
      $_SESSION["password"] = $_Requet["password"];
    }
    switch($act)
    {
        case "create_file":
          createFile($modify_filename);
          break;
        case "rename_file":
          $new_filename = $_REQUEST["new_name"];
          renameFile($modify_filename,$new_filename);
          break;
        case "down_file":
          downFile($modify_filename);
          break;
        case "paste":
          //echo 'act'.$_SESSION["model"];
          //echo 'clipper'.$_SESSION["clipper"];
          //echo 'current_path'.$_SESSION["path"];
          paste();
          break;
        case "create_dir":
          createDir($modify_filename);
          break;
        case "delete_dir":
          deleteDir($modify_filename);
          break;
        case "delete_file":
          deleteFile($modify_filename);
          break;
        case "upload_file":
          uploadFile($_FILES["myfile"]);
          break;
        case "clear_clipper":
          unset($_SESSION["clipper"]);
          echo "success";
          break;
        case "login":
          login($_SESSION["username"],$_SESSION["password"]);
          break;
    }
    //print_r ($_FILES);
?>