<?php
    require_once 'kit.php';
    session_start();
   
    //调试用
    echo $_SESSION["path"];
    echo $_SESSION["clipper"];
    //

    //设置当前路径
    if($_SESSION["path"] == NULL||$_REQUEST["path"]=="home")
    {
      $_SESSION["path"] = './file';
    }
    else if(preg_match('/^\.\/file/',$_REQUEST["path"])&&is_dir($_REQUEST["path"]))
    {
      $_SESSION["path"] = $_REQUEST["path"];
    }
    else if(!is_dir($_SESSION["path"]))
    {
      $_SESSION["path"] = './file';
    }

    //读当前目录
    $path = $_SESSION["path"];
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
    <table id="navigation">
      <tr>
        <td onclick = "home()"><span class="icon icon-small button"><span class="icon-home"></span></span></td>
        <td onclick = "createFile()"><span class="icon icon-small button"><span class="icon-plus"></span></span></td>
        <td onclick = "upFile()"><span class="icon icon-small button"><span class="icon-upload"></span></span></td>
        <td onclick = "createDir()"><span class="icon icon-small button"><span class="icon-folder"></span></span></td>
        <td onclick="paste_button_click()" ondblclick="paste_button_dbclick()"><span class="icon icon-small button" id="paste" style="border:<?php if($_SESSION["clipper"]!= NULL) echo "solid";?>"><span class="icon-file"></span></span></td>
        <td onclick = "back('<?php echo dirname($path)?>')"><span class="icon icon-small button"><span class="icon-arrowLeft"></span></span></td>
        <td width="80%"><td>
      </tr>
    </table>
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
          $currentpath = $path."/".$dir_name;
      ?>
        <tr>
          <td><?php echo $dir_name ?></td>
          <td><?php echo "目录" ?></td>
          <td><?php echo transbyte(dirsize($currentpath)) ?></td>
          <td><?php echo date("Y.n.j G:i:s",filemtime($currentpath))?></td>
          <td><?php echo authority($currentpath)?></td>
          <td>
            <a href="index.php?path=<?php echo $currentpath?>">查看</a>
            <span onclick="copy('<?php echo $currentpath?>')">复制</span>
            <span onclick="cut('<?php echo $currentpath?>')">剪切</span>
            <span class="icon icon-small button" onclick="<?php echo"renameFile('$currentpath')" ?>"><span class="icon-file"></span></span>
            <span class="icon icon-small button" onclick="<?php echo"deleteDir('$currentpath')" ?>"><span class="icon-minus"></span></span>
          </td>
        </tr>
      <?php } ?>
      <?php
        foreach($file["file"] as $file_name)
        {
          $currentpath = $path."/".$file_name;
      ?>
        <tr>
          <td><?php echo $file_name ?></td>
          <td><?php echo "文件" ?></td>
          <td><?php echo transbyte(filesize($currentpath)) ?></td>
          <td><?php echo date("Y.n.j G:i:s",filemtime($currentpath))?></td>
          <td><?php echo authority($currentpath)?></td>
          <td>
            <span onclick="view('<?php echo $currentpath?>')">查看</span>
            <?php
              if(!isPicture($file_name)&&!isMusic($file_name))
              {
                $modify_choice=<<<EOF
                  <span onclick=edit("$currentpath")>修改</span>
EOF;
                echo $modify_choice;
              }
?>
            <span onclick="copy('<?php echo $currentpath?>')">复制</span>
            <span onclick="cut('<?php echo $currentpath?>')">剪切</span>
            <span class="icon icon-small button" onclick="<?php echo"renameFile('$currentpath')" ?>"><span class="icon-file"></span></span>
            <span class="icon icon-small button" onclick="<?php echo"deleteFile('$currentpath')" ?>"><span class="icon-minus"></span></span>
            <a href="data.php?act=down_file&filename=<?php echo $currentpath ?>"><span class="icon icon-small button" ?><span class="icon-download"></span></span></a>
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
