<?php
    require_once 'kit.php';
    session_start();
   
    //调试用
    //echo $_SESSION["path"]."@";
    //echo $_SESSION["clipper"]."@";
    //echo $_SESSION["model"]."@";
    //
    
    //获取主目录
    if($_SESSION["log_status"] != "ok"||$_SESSION["rootpath"] == NULL)
    {
      header("Location:login.php");
      return false;
    }

    //设置当前路径
    if($_REQUEST["path"]=="home")
    {
      $_SESSION["path"] = null;
    }
    else if($_REQUEST["path"]=="former")
    {
      $former = dirname($_SESSION["path"]);
      if($former != '\\')
        $_SESSION["path"] = $former;
      else
        $_SESSION["path"] = null;
      //echo dirname($_SESSION["path"]);
    }
    else if($_REQUEST["path"] != NULL&&is_dir($_SESSION["rootpath"].$_SESSION["path"].'/'.$_REQUEST["path"]))
    {
      $_SESSION["path"] = $_SESSION["path"].'/'.$_REQUEST["path"];
    }
    else if(!is_dir($_SESSION["rootpath"].$_SESSION["path"]))
    {
      $_SESSION["path"] = null;
    }

    //读当前目录
    $path = $_SESSION["rootpath"].'/'.$_SESSION["path"];
    $file = readDirectory($path);

    //判断剪切板内容
    if($_SESSION["clipper"] == NULL)
    {
      $paste_src = "imgs/paste_empty.png";
    }
    else
    {
      $paste_src = "imgs/paste_full.png";
    }
?>

<!DOCTYPE HTML>
<html>
  <head>
    <title>My File</title>
    <link href="css/index.css" rel="stylesheet">
    <link href="css/icon.css" rel="stylesheet">
  </head>
  <body>
    <div id="title">
      <div id="photo_area">
        <img src="photo/1.png" alt="头像" id="photo" />
        <div id="menu"><img src="imgs/logout.png" alt="logout" title="注销" id="logout_button" onclick="logout()"></div>
      </div>
      <div id="name"><?php echo $_SESSION["username"]?></div>
    </div>
    <table id="navigation" cellspacing="10px">
      <tr>
        <td onclick = "home()"><img src="imgs/home.png" alt="home" title="主目录" /></td>
        <td onclick = "createFile()"><img src="imgs/create_file.png" alt="+file" title="新建文件" /></td>
        <td onclick = "upFile()"><img src="imgs/upload.png" alt="upload" title="上传" /></td>
        <td onclick = "createDir()"><img src="imgs/create_dir.png" alt="+dir" title="新建目录" /></td>
        <td onclick="paste_button_click()" ondblclick="paste_button_dbclick()"><img src="<?php echo $paste_src?>" alt="paste" title="粘贴" id="paste" /></td>
        <td onclick = "back()"><img src="imgs/back.png" alt="back" title="返回" /></td>
        <td width="70%"><td>
      </tr>
    </table>
    <table id="file_content">
      <tr>
        <th id="filename">文件名</th>
        <th id="type">类型</th>
        <th id="size">文件大小</th>
        <th id="m_time">修改时间</th>
        <th id="authority">存取权限</th>
        <th>操作</th>
      </tr>
      <!--动态显示目录-->
      <?php
        $count = 0;
        foreach($file["dir"] as $dir_name)
        {
          $count++;
          $currentpath = $path."/".$dir_name;
      ?>
        <tr>
          <td><?php echo $dir_name ?></td>
          <td><?php echo "目录" ?></td>
          <td><?php echo transbyte(dirsize($currentpath)) ?></td>
          <td><?php echo date("Y.n.j G:i:s",filemtime($currentpath))?></td>
          <td><?php echo authority($currentpath)?></td>
          <td>
            <a href="index.php?path=<?php echo $dir_name?>"><img src="imgs/open.png" alt="open" title="打开" /></a>
            <img src="imgs/copy.png" alt="copy" title="复制"  onclick="copy('<?php echo $dir_name?>')" />
            <img src="imgs/cut.png" alt="cut" title="剪切"  onclick="cut('<?php echo $dir_name?>')" />
            <img src="imgs/rename.png" alt="rename" title="重命名"  onclick="<?php echo"renameFile('$dir_name')" ?>" />
            <img src="imgs/delete.png" alt="delete" title="删除"  onclick="<?php echo"deleteDir('$dir_name')" ?>" />
         
          </td>
        </tr>
      <?php } ?>
      <!--动态显示文件-->
      <?php
        foreach($file["file"] as $file_name)
        {
          $count++;
          $currentpath = $path."/".$file_name;
          $file_name = addslashes($file_name);
          if(isPicture($file_name))
          {
            $src = 'imgs/picture_view.png';
          }
          else if(isMusic($file_name))
          {
            $src = 'imgs/music_view.png';
          }
          else
          {
            $src = 'imgs/view.png';
            $type = 'other';
          }
      ?>
        <tr>
          <td><?php echo $file_name ?></td>
          <td><?php echo "文件" ?></td>
          <td><?php echo transbyte(filesize($currentpath)) ?></td>
          <td><?php echo date("Y.n.j G:i:s",filemtime($currentpath))?></td>
          <td><?php echo authority($currentpath)?></td>
          <td>
            <img src="<?php echo $src?>" alt="view" title="查看" onclick="view('<?php echo $file_name?>')"/>
            <?php
              if($type == 'other')
              {
                $modify_choice=<<<EOF
                  <img src="imgs/edit.png" alt="edit" title="编辑"  onclick=edit("$file_name") />
EOF;
                echo $modify_choice;
              }
?>
            <img src="imgs/copy.png" alt="copy" title="复制"  onclick="copy('<?php echo $file_name?>')" />
            <img src="imgs/cut.png" alt="cut" title="剪切"  onclick="cut('<?php echo $file_name?>')" />
            <img src="imgs/rename.png" alt="rename" title="重命名"  onclick="<?php echo"renameFile('$file_name')" ?>" />
            <img src="imgs/delete.png" alt="delete" title="删除"  onclick="<?php echo"deleteFile('$file_name')" ?>" />
            <a href="data.php?act=down_file&filename=<?php echo $file_name ?>" ><img src="imgs/download.png" alt="download" title="下载" /></a>
            
          </td>  
        </tr>
      <?php } ?>
    </table>
    <form action="data.php" id = "file_form" method="post">
    <input style="display:none" type = "file" name ="myfile" id = "file" onchange="doUpFile()">
    </form>
    <div id="process_bar"><div id="process"></div></div>
    <script type="text/javascript" src="js/index.js"></script>
  </body>
</html>
