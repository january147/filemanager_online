<?php
    require_once 'kit.php';
    session_start();
    echo $_SESSION["_path"];
    echo $_SESSION["clipper"];
    if($_REQUEST["model"]!=NULL)
    {
      $_SESSION["model"] = $_REQUEST["model"];
    }
    if($_REQUEST["clipper"]!=NULL&&preg_match('/^\.\/file/',$_REQUEST["clipper"]))
    {
      $_SESSION["clipper"] = $_REQUEST["clipper"];
    }
    if($_SESSION["_path"] == NULL)
    {
      $_SESSION["_path"] = './file';
    }
    else if(preg_match('/^\.\/file/',$_REQUEST["path"])&&is_dir($_REQUEST["path"]))
    {
      $_SESSION["_path"] = $_REQUEST["path"];
    }
    else if(!is_dir($_SESSION["_path"]))
    {
      $_SESSION["_path"] = './file';
    }
    $path = $_SESSION["_path"];
    $modify_filename = $_GET["filename"];
    $act = $_GET["act"];
     switch($act)
      {
        case "create_file":
          createFile($modify_filename);
          break;
        case "rename_file":
          $new_filename = $_REQUEST["new_name"];
          renameFile($modify_filename,$new_filename);
          break;
        case "delete_file":
          deleteFile($modify_filename);
          break;
        case "down_file":
          downFile($modify_filename);
          break;
        case "paste":
          echo $_SESSION["model"].'%%%';
          echo $_SESSION["clipper"];
          echo $_SESSION["_path"];
          if($_SESSION["model"]=="copy")
            copyDir($_SESSION["clipper"],$_SESSION["_path"]);
          else
            moveFile($_SESSION["clipper"],$_SESSION["_path"]);
          break;
      }
    $file = readDirectory($path);
?>
<!DOCTYPE HTML>
<html>
  <head>
    <title>My File</title>
    <link href="css/index.css" rel="stylesheet">
    <link href="css/icon.css" rel="stylesheet">
  </head>
  <body>
    <div id="title"><h1>MY FILE</h1></div>
    <!--<div id="navigation" > -->
    <table id="navigation">
      <tr>
        <td onclick = "createFile()"><span class="icon icon-small button"><span class="icon-plus"></span></span></td>
        <td onclick = "upFile()"><span class="icon icon-small button"><span class="icon-upload"></span></span></td>
        <td onclick = "createDir()"><span class="icon icon-small button"><span class="icon-folder"></span></span></td>
        <td><span class="icon icon-small button" onclick="paste()">"><span class="icon-file"></span></span></td>
        <td onclick = "back('<?php echo dirname($path)?>')"><span class="icon icon-small button"><span class="icon-arrowLeft"></span></span></td>
        <td width="80%"><td>
      </tr>
    </table>
    <!--</div>-->
    <table width="100%">
      <tr>
        <th>文件名</th>
        <th>类型</th>
        <th>文件大小</th>
        <th>修改时间</th>
        <th>存取权限</th>
        <th>操作</th>
      </tr>
      <?php
        foreach($file["dir"] as $dir_name)
        {
          $current_path = $path."/".$dir_name;
      ?>
        <tr>
          <td><?php echo $dir_name ?></td>
          <td><?php echo "目录" ?></td>
          <td><?php echo transbyte(dirsize($current_path)) ?></td>
          <td>^_^</td>
          <td>^_^</td>
          <td>
            <a href="index.php?path=<?php echo $current_path?>">查看</a>
            <a href="index.php?model=copy&clipper=<?php echo $current_path?>">复制</a>
            <a href="index.php?model=cut&clipper=<?php echo $current_path?>">剪切</a>
            <span class="icon icon-small button" onclick="<?php echo"renameFile('$current_path')" ?>"><span class="icon-file"></span></span>
            <span class="icon icon-small button" onclick="<?php echo"deleteDir('$current_path')" ?>"><span class="icon-minus"></span></span>
          </td>
        </tr>
      <?php } ?>
      <?php
        foreach($file["file"] as $file_name)
        {
          $current_path = $path."/".$file_name;
      ?>
        <tr>
          <td><?php echo $file_name ?></td>
          <td><?php echo "文件" ?></td>
          <td><?php echo transbyte(filesize($current_path)) ?></td>
          <td><?php echo date("Y.n.j G:i:s",filemtime($current_path))?></td>
          <td><?php echo authority($current_path)?></td>
          <td>
            <span onclick="view('<?php echo $current_path?>')">查看</span>
            <?php
              if(!isPicture($file_name)&&!isMusic($file_name))
              {
                $modify_choice=<<<EOF
                <a target="_blank" href = "./edit.php?act=edit_content&filename=$current_path">修改</a>
EOF;
                echo $modify_choice;
              }
?>
            <a href="index.php?model=copy&clipper=<?php echo $current_path?>">复制</a>
            <a href="index.php?model=cut&clipper=<?php echo $current_path?>">剪切</a>
            <span class="icon icon-small button" onclick="<?php echo"renameFile('$current_path')" ?>"><span class="icon-file"></span></span>
            <span class="icon icon-small button" onclick="<?php echo"deleteFile('$current_path')" ?>"><span class="icon-minus"></span></span>
            <span class="icon icon-small button" onclick="<?php echo"downFile('$current_path')" ?>"><span class="icon-download"></span></span>
          </td>  
        </tr>
      <?php } ?>
    </table>
    <form action="data.php" id = "file_form" method="post">
    <input style="display:none" type = "file" name ="myfile" id = "file" onchange="doUpFile()">
    <input type ="hidden" name="act" value = "upload_file">
    </form>
     <div id="process_bar"><div id="process"></div></div>
    <script type="text/javascript" src="index.js"></script>
  </body>
</html>
