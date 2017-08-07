<?php
function isPicture($filename)
{
  $ext = strtolower(end(explode('.',$filename)));
  $imageExt=array('gif','jpg','jpeg','png','bmp');
  if(in_array($ext,$imageExt))
  {
    return true;
  }
  else
  {
    return false;
  }
}

function isMusic($filename)
{
  $ext = strtolower(end(explode('.',$filename)));
  $imageExt=array('mp3');
  if(in_array($ext,$imageExt))
  {
    return true;
  }
  else
  {
    return false;
  }
}

function nopath_filename($filename)
{
  $brief_name = strtolower(end(explode('/',$filename)));
  return $brief_name;
}

function dirSize($dirname)
{
  $arr = readDirectory($dirname);
  $sum = 0;
  foreach($arr["file"] AS $file_name)
  {
    $current_path = $dirname.'/'.$file_name;
    $sum = $sum + filesize($current_path);
  }
  foreach($arr["dir"] AS $dir_name)
  {
    $current_path = $dirname.'/'.$dir_name;
    $sum = $sum + dirSize($current_path);
  }
  return $sum;
}

function readDirectory($path)
{
  $handle = opendir($path);
  $arr = array(
    "file" => array(),
    "dir" => array()
  );
  while(($item = readdir($handle))!==false)
  {
    if($item != "."&&$item != "..")
    {
      if(is_file($path.'/'.$item))
      {
        $arr['file'][] = $item;
      }
      else 
      {
        $arr['dir'][] = $item;
      }
    }
  }
  closedir($handle);
  return $arr;
}

function transbyte($size)
{ 
  $i = 0;
  $result = $size;
  while($result >= 1024)
  {
    $result = $result/1024;
    $i++;
  }
  $result = round($result,2);
  switch($i)
  {
    case 0: 
      $result = $result."B";
      break;
    case 1: 
      $result = $result."KB";
      break;
    case 2: 
      $result = $result."MB";
      break;
    case 3: 
      $result = $result."GB";
      break;
  }
  return $result;
}

function authority($file)
{
  $result = NULL;
  if(is_readable($file))
  {
    $result = "r";
  }
  if(is_writeable($file))
  {
    $result = $result."w";
  }
  if(is_executable($file))
  {
    $result = $result."x";
  }
  return $result;
}

function createFile($operation_path,$filename)
{
  $pattern = "/[\/,\*,<,>,\|]/";
  if(preg_match($pattern,basename($filename)))
  {
    //echo '<script type="text/javascript">alert("不合法的文件名");</script>';
    echo 'invalid_filename';
  }
  else
  {
    if(file_exists($operation_path))
    {
      //echo '<script type="text/javascript">confirm("文件已存在");</script>';
      echo 'file_exist';
    }
    else
    {
      if(!touch($operation_path))
      {
        //echo '<script type="text/javascript">alert("创建失败");</script>';
        echo 'failed';
      }
      else
      {
        //echo '<script type="text/javascript">alert("创建成功");</script>';
        //echo $current_path;
        echo "success";
      }
    }
  }
}

function createDir($operation_path,$dirname)
{
  $pattern = "/[\/,\*,<,>,\|]/";
  //echo $current_path;
  if(preg_match($pattern,$dirname))
  {
    echo 'invalid_dirname';
  }
  else
  {
    if(file_exists($operation_path)&&is_dir($operation_path))
    {
      echo 'dir_exist';
    }
    else
    {
      if(mkdir($operation_path,0777,true))
      {
        echo 'success';
      }
      else
      {
        echo 'failed';
      }
    }
  }
}

function renameFile($old_filename,$new_filename)
{
  $pattern = "/[\/,\*,<,>,\|]/";
  $current_path = $_SESSION["rootpath"].$SESSION["path"].'/'.$new_filename;
  if(preg_match($pattern,basename($new_filename)))
  {
    //echo '<script type="text/javascript">alert("不合法的文件名");</script>';
    echo 'invalid_filename';
  }
  else 
  { 
    if(file_exists($current_path))
    {
        //echo '<script type="text/javascript">confirm("文件已存在,请重新命名");</script>';
        echo 'file_exist';
    }
    else
    {
      if(!rename($old_filename,$current_path))
      {
        //echo '<script type="text/javascript">alert("重命名失败");</script>';
        echo 'failed';
      }
      else
      {
        //echo '<script type="text/javascript">alert("重命名成功");</script>';
        echo "success";
      }
    }    
  }    
}

function deleteDir($dirname)
{
  $arr = readDirectory($dirname);
  foreach($arr["file"] AS $file_name)
  {
    $complete_filename = $dirname.'/'.$file_name;
    unlink($complete_filename);
  }
  foreach($arr["dir"] AS $dir_name)
  {
    $complete_filename = $dirname.'/'.$dir_name;
    deleteDir($complete_filename);
  }
  rmdir($dirname);
}

function deleteFile($filename)
{
  if(unlink($filename))
  {
    //echo '<script type="text/javascript">alert("删除成功");</script>';
    echo "success";
  }
  else
  {
    //echo '<script type="text/javascript">alert("删除失败");</script>';
    echo 'failed';
  }
}

//下载
function downFile($filename)
{
  header("Content-Type:application/octet-stream");
  header("Accept-Ranges:bytes");
  header("Accept-Length:".filesize($filename));
  header("Content-Disposition:attachment;filename=".nopath_filename($filename));
  readfile($filename);
}

//粘贴功能
function paste($src,$des,$mode)
{
  if($src!=NULL)
  {
    if($mode =="copy")
      copyFile($src,$des);
    else if($mode == "cut")
      moveFile($src,$des);
  }
  else
    echo "nothing in clipper";
}
function copyFile($oldpath,$newpath)
{
  if(strpos($newpath,$oldpath)!==false)
  {
    echo  'under_itself'; //'<script type="text/javascript">alert("请不要将目录递归复制");</script>';
  }
  else
  {
    $dirname = nopath_filename($oldpath);
    if(is_file($oldpath))
    {
      if(file_exists($newpath.'/'.$dirname))
      {        
        if($_REQUEST["confirm"]==NULL)
        {
          echo "file_exist";
          return false;
        }
        if($_REQUEST["confirm"]=="yes")
          if(!copy($oldpath,$newpath.'/'.$dirname))
          {
            echo $dirname."failed";
            //echo "failed";
            return false;
          }
      }
      else
      {
        //echo $oldpath;
        if(!copy($oldpath,$newpath.'/'.$dirname))
        {
            echo $dirname."failed";
            //echo "failed";
            return false;
        }
      }
    }
    else
    {
      $arr = readDirectory($oldpath);
      if(!is_dir($newpath.'/'.$dirname))
      {
        if(!mkdir($newpath.'/'.$dirname,0777,true))
        {
          echo $dirname."failed";
          //echo "faild";
          return false;
        }
        foreach($arr["file"] AS $file_name)
        {
          $old_path = $oldpath.'/'.$file_name;
          $new_path = $newpath.'/'.$dirname.'/'.$file_name;
          //echo $old_path."^^^";
          //echo $new_path."@@@";
          if(!copy($old_path,$new_path))
          {
              echo $file_name."failed";
              //echo "failed";
              return false;
          }
        }
        foreach($arr["dir"] AS $dir_name)
        {
          $old_path = $oldpath.'/'.$dir_name;
          $new_path = $newpath.'/'.$dirname;
          //echo $old_path."***";
          //echo $new_path."---";
          if(!copyFile($old_path,$new_path))  //成功时需要给一个返回值，否则使用if判断时会视为false
          {
              echo $old_path."failed";
              return false;
          }
        }
      }
      else
      {
          echo 'dir_exist';
          return false;
      }
    }
  }
  return true;    //成功时需要给一个返回值，否则使用if判断时会视为false
}

function moveFile($oldpath,$newpath)
{
  if(strpos($newpath,$oldpath)!==false)
  {
    //echo '<script type="text/javascript">alert("无法移动到自身目录下");</script>';
    echo 'under_itself';
  }
  else
  {
    $filename = nopath_filename($oldpath);
    $complete_newpath = $newpath.'/'.$filename;
    if(!file_exists($complete_newpath))
    {
      if(!rename($oldpath,$complete_newpath))
      {
          echo "failed";
          return false;
      }
    }
    else
    {
      if($_REQUEST["confirm"]==NULL)
      {
        echo "file_exist";
      }
      if($_REQUEST["confirm"]=="yes")
      {
        if(!rename($oldpath,$complete_newpath))
        {
            echo "failed";
            return false;
        }
      }
    }
  }
  return true;
}

//上传文件
function is_valid($file,$path)
{
  if($file["error"]!=0)
  {
    switch($file["error"])
    {
      case 1:
        echo "size_beyond_limit";
        break;
      case 2:
        echo "size_beyond_limit";
        break;
      case 3:
        echo "not complete";
        break;
      case 4:
        echo "no file";
        break;
      case 5:
        echo "empty file";
        break;
   }
   return false;
  }
  else if($file["size"]>20971520)
  {
    echo "size_beyond_limit";
    return false;
  }
  else if(file_exists($path.'/'.$file["name"]))
  {
    $new_name = substr(md5(time()),5,5).$file["name"];
    return $new_name;
  }
  else
    return true;
}

function uploadFile($file,$path)
{
  $result = is_valid($file,$path);
  //echo $file["name"];
  if($result === true)
  {
    $des_path = $path.'/'.$file["name"];
    if(move_uploaded_file($file["tmp_name"],$des_path))
    {
      echo "success";
    }
    else
    {
      echo "failed";
    }
  }
  else if($result!==false)
  {
    $des_path = $path.'/'.$result;
    if(move_uploaded_file($file["tmp_name"],$des_path))
    {
      echo "rename_success";
    }
    else
    {
      echo "failed";
    }
  }
  else
  {
    echo "unknown_error";
  }
}

function login($username,$password)
{
  //echo 'username'.$username;
  //echo 'password'.$password;
  $log_info = mysqli_connect("localhost","log_info","120120","log_info");
  $query = "SELECT * FROM log_info WHERE username = '$username'";
  $result = mysqli_query($log_info,$query);
  //print_r(mysqli_error($log_info));
  $data_pointer = mysqli_fetch_array($result);
  //$count = mysqli_num_rows($result);
  //print_r($data_pointer);
  if(!$data_pointer["password"])
  {
    //echo "no_user";
    $_SESSION["log_status"] = "no_usr";
    header("Location:login.php");
    return false;
  }
  if($password == $data_pointer["password"])
  {
    if(!$data_pointer["main_dir"])
    {
      $_SESSION["log_status"] = "no_dir";
      header("Location:login.php");
      return false;
    }
    $_SESSION["rootpath"] = "./file/".$data_pointer["main_dir"];
    $_SESSION["log_status"] = "ok";
    $_SESSION["username"] = $username;
    header("Location:index.php");
    return true;
  }
  else
  {
    $_SESSION["log_status"] = "ps_error";
    header("Location:login.php");
    return false;
  }
}
function logout()
{
  unset($_SESSION["log_status"]);
  unset($_SESSION["rootpath"]);
  unset($_SESSION["path"]);
  unset($_SESSION["model"]);
  unset($_SESSION["clipper"]);
  unset($_SESSION["username"]);
  header("Location:login.php");
}

function saveChange($editing_file_path,$contens)
{
  if(file_put_contents($editing_file_path,$contens))
  {
    echo "success";
  }
  else
  {
    echo "failed";
  }
}
?>
