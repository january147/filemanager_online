<?php
    require_once 'kit.php';
    session_start();
    $operation_path = $_SESSION["rootpath"].$_SESSION["path"].'/'.$_REQUEST["filename"];
    $act = $_REQUEST["act"];
    $filename = $_REQUEST["filename"];
		$music= "none";
		$picture = "none";
		$other ="none";
		if(isMusic($filename))
		{
			$music = "block";
		}
		else if(isPicture($filename))
		{
			$picture = "block";
		}
		else
		{
			$other = "block";
			$contents=addslashes(file_get_contents($operation_path));
		}
		
?>
<!DOCTYPE HTML>
<html>
    <head>
        <title>内容查看</title>
				<link href="css/view.css" rel="stylesheet">
    </head>
    <body>
        <div id ="title"><?php echo "$filename" ?></div>
        <div id ="content">
            <div id="music_view" style="display:<?php echo $music?>">
							<audio id ="music" src="<?php echo $operation_path ?>" preload="auto" ontimeupdate="getProcess()" onended="stop()"></audio>
							<div id="music_picture">
								<img src="music_picture/index.png" alt="图片" id="m_picture">
							</div>
							<div id="control">
								<div id="stop" class="button" onclick="stop()">
									<img src="imgs/stop.png" alt="stop" />
								</div>
								<div id="pause" class="button" onclick="play_pause()">
									<img src="imgs/play.png" alt="pause" id ="status_picture" />
								</div>
								<div id="process" class="button" onmousedown="moveActive()" onmousemove="moving()" onmouseup="moveEnd()" onmousemoveout="moveEnd()">
									<div id="process_bar" >
										<div id="process_in">
										</div>
									</div>
								</div>
								<div id="mute" class="button">
									<img src="imgs/mute.png" alt="mute" id="mute_picture" onclick="mute()">
								</div>
							</div>
					</div>
					<div id="pircture_view" style="display:<?php echo $picture?>">
						<img src="<?php echo $operation_path?>" alt="<?php echo $filename?>" id="picture">
					</div>
					<div id="text_view" style="display:<?php echo $other?>">
						<textarea readonly="readonly" id="text"><?php if($other!="none") echo $contents?></textarea>
					</div>
				</div>
				
				<div id ="end" onclick="window.close()">关闭</div>
		<script type="text/javascript" src="js/view.js"></script>
    </body>
</html>

