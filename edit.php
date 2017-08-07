<?php
    session_start();
    if($_SESSION["log_status"]!="ok")
    {
        echo "非法访问，请先登录";
        return;
    }
    $operation_path = $_SESSION["rootpath"].$_SESSION["path"].'/'.$_REQUEST["filename"];
    $act = $_REQUEST["act"];
    $filename = $_REQUEST["filename"];
    $contents=file_get_contents($operation_path);
    file_put_contents($operation_path,$modified_contents);
?>
<!DOCTYPE HTML>
<html>
    <head>
        <title>内容修改</title>
        <link href="css/edit.css" rel="stylesheet">
    </head>
    <body>
        <div id ="title" onclick="notice()">
            <?php echo $filename ?>
            <div id="notice"></div>
        </div>
        <div id ="content">
            <form id="modify_file">
                <textarea name="contents" id="text"><?php echo $contents?></textarea>
                <input type="hidden" name="editing" value="<?php echo $operation_path?>" >
            </form> 
        </div>
        <div id="end">
            <div class="button" onclick="saveChange()">保存</div>
            <div class="button" onclick="window.close()">退出</div>
        </div>
    <script src="js/edit.js"></script>
    </body>
</html>