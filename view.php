<?php
    require_once 'kit.php';
    session_start();
    $operation_path = $_SESSION["rootpath"].$_SESSION["path"].'/'.$_REQUEST["filename"];
    $act = $_REQUEST["act"];
    $filename = $_REQUEST["filename"];
		$contents=file_get_contents($operation_path);
?>
<!DOCTYPE HTML>
<html>
    <head>
        <title>内容查看</title>
        <link href="css/view.css" rel="stylesheet">
    </head>
    <body onmouseup = "moveEnd()">
        <div id ="title"><?php echo "$filename" ?></div>
        <div id ="content">
            <div id="Music_view">
								<audio id ="music" src="<?php echo $operation_path ?>" preload="auto" ontimeupdate="getProcess()" onended="stop()"></audio>
                <div id="music_picture">
									我是图片区
								</div>
								<div id="control">
									<div id="stop" class="button" onclick="stop()">
										<img src="imgs/stop.png" alt="stop" />
									</div>
									<div id="pause" class="button" onclick="play_pause()">
										<img src="imgs/play.png" alt="pause" id ="status_picture" />
									</div>
									<div id="process" class="button">
										<div id="process_bar" onmousedown="moveActive()" onmousemove="moving()">
											<div id="process_in">
											</div>
										</div>
									</div>
									<div id="download" class="button">
										<img src="imgs/_download.png" alt="download">
									</div>
								</div>
						</div>
				</div>
		<script type="text/javascript" src="js/view.js"></script>
    </body>
</html>

