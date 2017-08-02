<?php
   $modify_filename = $_REQUEST["filename"];
    $act = $_REQUEST["act"];
    $pattern = '/\.\/file([\S]+)/';
    preg_match($pattern,$modify_filename,$brief_filename);
?>
<!DOCTYPE HTML>
<html>
    <head>
        <title>内容修改</title>
        <style>
            html,body{
                height:100%;
                width:100%;
            }
            textarea{
                display:block;
                margin:auto;
            }
            form{
                text-align:center;
            }
            #content{
                text-align:center;
            }
            #title{
                text-align:center;
            }
            #button{
                width:25em;
                height:4em;
            }
        </style>
    </head>
    <body>
        <div id ="title"><?php echo "<h1>$brief_filename[1]</h1>" ?></div>
        <div id ="content">
            <?php
            switch($act)
            {
                case "edit_content":
                    $contents=file_get_contents($modify_filename);
                    $edit_area = <<<EOF
                    <form action="edit.php?act=save_change" method="post">
                    <textarea cols="100" rows="50" name="contents">$contents</textarea><br />
                    <input type="hidden" name="filename" value="$modify_filename" >
                    <input class="button" type="submit" value="保存">
                    <input class="button" type="button" onclick="window.close()" value="取消">
                    </form>
EOF;
                    echo $edit_area;
                    break;
                case "save_change":
                    $modified_contents = $_POST["contents"];
                    $result_area=<<<EOF
                    <a href="index.php">返回首页</a>
                    <a href="edit.php?act=edit_content&filename=$modify_filename">继续修改</a>
EOF;
                    if(file_put_contents($modify_filename,$modified_contents))
                    {
                        echo '<script type="text/javascript">alert("修改成功");</script>';
                    }
                    else
                    {
                        echo '<script type="text/javascript">alert("修改失败");</script>';
                    }
                    echo $result_area;

            }
?>
        </div>
    </body>
</html>