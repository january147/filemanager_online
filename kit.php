<?php
ini_set('display_error','on');

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

function createFile($filename)
{
    $pattern = "/[\/,\*,<,>,\|]/";
    $current_path = $_SESSION["_path"].'/'.$filename;
    if(preg_match($pattern,basename($filename)))
    {
        echo '<script type="text/javascript">alert("不合法的文件名");</script>';
    }
    else
    {
        if(file_exists($current_path))
        {
            echo '<script type="text/javascript">confirm("文件已存在");</script>';
        }
        else
        {
            if(!touch($current_path))
            {
                echo '<script type="text/javascript">alert("创建失败");</script>';
            }
            else
            {
                echo '<script type="text/javascript">alert("创建成功");</script>';
            }
        }
        
    }
}

function createDir($dirname)
{
    $pattern = "/[\/,\*,<,>,\|]/";
    $current_path = $_SESSION["_path"].'/'.$dirname;
    //echo $current_path;
    if(preg_match($pattern,$dirname))
    {
        echo 'invalid_dirname';
    }
    else
    {
        if(file_exists($current_path)&&is_dir($current_path))
        {
            echo 'exist';
        }
        else
        {
            if(mkdir($current_path,0777,true))
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
    $current_path = $_SESSION["_path"].'/'.$new_filename;
    if(preg_match($pattern,basename($new_filename)))
    {
        echo '<script type="text/javascript">alert("不合法的文件名");</script>';
    }
    else
    {
        if(file_exists($current_path))
        {
            echo '<script type="text/javascript">confirm("文件已存在,请重新命名");</script>';
        }
        else
        {
            if(!rename($old_filename,$current_path))
            {
                echo '<script type="text/javascript">alert("重命名失败");</script>';
            }
            else
            {
                echo '<script type="text/javascript">alert("重命名成功");</script>';
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
        echo '<script type="text/javascript">alert("删除成功");</script>';
    }
    else
    {
        echo '<script type="text/javascript">alert("删除失败");</script>';
    }
}

function downFile($filename)
{
    header("content-disposition:attachment;filename=".nopath_filename($filename));
    header("content-length:".filesize($filename));
    readfile($filename);
}

function copyDir($oldpath,$newpath)
{
    if(strpos($newpath,$oldpath)!==false)
    {
        echo '<script type="text/javascript">alert("请不要将目录递归复制");</script>';
    }
    else
    {
        $dirname = nopath_filename($oldpath);
         if(is_file($oldpath))
        {
            if(file_exists($newpath.'/'.$dirname))
            {
                $confirm_area=<<<EOF
                <script type="text/javascript">
                    var yes=confirm("存在同名文件,是否替换");
                    if(yes)
                    {
                        window.location.href = "index.php?confirm=yes&act=paste";
                    }
                </script>
EOF;
                if($_REQUEST["confirm"]==NULL)
                {
                    echo $confirm_area;
                }
                if($_REQUEST["confirm"]==yes)
                    copy($oldpath,$newpath.'/'.$dirname);
            }
            else
            {
                echo $oldpath;
                copy($oldpath,$newpath.'/'.$dirname);
            }
        }
        else
        {
            $arr = readDirectory($oldpath);
            if(!is_dir($newpath.'/'.$dirname))
            {
                mkdir($newpath.'/'.$dirname,0777,true);
                foreach($arr["file"] AS $file_name)
                {
                    $old_path = $oldpath.'/'.$file_name;
                    $new_path = $newpath.'/'.$dirname.'/'.$file_name;
                    //echo $old_path."^^^";
                    //echo $new_path."@@@";
                    copy($old_path,$new_path);
                }
                foreach($arr["dir"] AS $dir_name)
                {
                    $old_path = $oldpath.'/'.$dir_name;
                    $new_path = $newpath.'/'.$dirname;
                    //echo $old_path."***";
                    //echo $new_path."---";
                    copyDir($old_path,$new_path);
                }
            
            }
            else
            {
                echo '<script type="text/javascript">alert("存在同名目录，请重命名后再试");</script>';
            }
        }
    }
}

function moveFile($oldpath,$newpath)
{
     if(strpos($newpath,$oldpath)!==false)
    {
        echo '<script type="text/javascript">alert("无法移动到自身目录下");</script>';
    }
    else
    {
        $filename = nopath_filename($oldpath);
        $complete_newpath = $newpath.'/'.$filename;
        if(!file_exists($complete_newpath))
        {
           rename($oldpath,$complete_newpath);
        }
        else
        {
            $confirm_area=<<<EOF
                <script type="text/javascript">
                    var yes=confirm("存在同名文件,是否替换");
                    if(yes)
                    {
                        window.location.href = "index.php?confirm=yes&act=paste";
                    }
                </script>
EOF;
                if($_REQUEST["confirm"]==NULL)
                {
                    echo $confirm_area;
                }
                if($_REQUEST["confirm"]==yes)
                    rename($oldpath,$complete_newpath);
        }
    }
}

function is_valid($file)
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
    else if($file["size"]>52428800)
    {
        echo "size_beyond_limit";
        return false;
    }
    else if(file_exists($_SESSION["_path"].'/'.$file["name"]))
    {
        $new_name = substr(md5(time()),5,5).$file["name"];
        return $new_name;
    }
    else
        return true;
}

function uploadFile($file)
{
    $result = is_valid($file);
    if($result === true)
    {
        $des_path = $_SESSION["_path"].'/'.$file["name"];
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
        $des_path = $_SESSION["_path"].'/'.$result;
        if(move_uploaded_file($file["tmp_name"],$des_path))
        {
            echo "rename_success";
        }
        else
        {
            echo "failed";
        }
    }
}

?>
