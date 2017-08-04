<?php
    require_once 'kit.php';
    session_start();
    $operation_path = $_SESSION["rootpath"].$_SESSION["path"].'/'.$_REQUEST["filename"];
    $act = $_REQUEST["act"];
    $filename = $_REQUEST["filename"];
?>
<!DOCTYPE HTML>
<html>
    <head>
        <title>内容查看</title>
        <link href="css/icon.css" rel="stylesheet">
        <style>
            html,body{
                height:100%;
                width:100%;
                padding-top:1em;
                padding-bottom:1em;
            }
            img{
                width:80%;
            }
            textarea{
                display:block;
                margin:auto;
            }
            #title{
                text-align:center;
            }
            #content{
                text-align:center;
                width:50%;
                background-color:whitesmoke;
                padding-top:5em;
                padding-bottom:5em;
                margin:auto;
            }
            #cancel{
                width:8.5em;
                height:2.5em;
                font-size:3em;
                background-color:whitesmoke;
                border:solid;
                border-color:#a4adb7;
                color:gray;
            }
            #cancel:hover{
                border-color:black;
                color:black;
            }
            .switch:hover{
                opacity:0.9;
            }
        </style>
        <script type="text/javascript">
            function _play(id)
            {
                var music_ctl = document.getElementById(id);
                var status_icon = document.getElementById("status");
                if(status_icon.className == "icon-play")
                {
                    music_ctl.play();
                    status_icon.className = "icon-pause";
                }
                else
                {
                    music_ctl.pause();
                    status_icon.className = "icon-play";
                }
            }
            function _stop(id)
            {
                var music_ctl = document.getElementById(id);
                var status_icon = document.getElementById("status");
                music_ctl.load();
                status_icon.className = "icon-play";
            }
        </script>
    </head>
    <body>
        <div id ="title"><?php echo "<h1>$filename</h1>" ?></div>
        <div id ="content">
            <?php
            switch($act)
            {
                case "show_content":
                $contents=file_get_contents($operation_path);
                if(isPicture($operation_path))
                {
                    $view_area=<<<EOF
                    <img src='$operation_path'> <br />
EOF;
                }
                else if(isMusic($operation_path))
                {
                    $view_area=<<<EOF
                    <audio id ="music" src='$operation_path'>$operation_path</audio> <br />
                    <div>
                         <span onclick = "_stop('music')" class="icon icon-huge"><span  id = "stop" class="switch icon-stop"></span></span>
                        <span onclick = "_play('music')" class="icon icon-huge"><span  id = "status" class="switch icon-play"></span></span>
                    </div>
EOF;
                }
                else
                {
                    $view_area=<<<EOF
                    <textarea cols="100" rows="50" readonly="readonly">$contents</textarea><br />
EOF;
                }
                echo $view_area;
                
                break;
            }
?>
        </div>
        <div style="text-align:center;margin-top:2em;"><input class="button" type="button" id="cancel" onclick="window.close()" value="关闭"></div>
    </body>
</html>

