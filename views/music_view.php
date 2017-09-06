<div id="content_wrapper">
  <div id = "music_wrapper">
    <audio id ="music" src="<?php echo 'readFile.php?path='.$src ?>"  ontimeupdate="getProcess()" onended="stop()"></audio>
    <div id="music_picture">
    <img src="static/imgs/m_imgs/index.png" alt="图片" id="m_imgs">
    </div>
    <div id="control">
    <div id="stop" onclick="stop()">
      <img src="static/imgs/icons/stop.png" alt="stop" />
    </div>
    <div id="pause" onclick="play_pause()">
      <img src="static/imgs/icons/play.png" alt="pause" id ="status_picture" />
    </div>
    <div id="process_bar" onmousedown="moveActive()" onmousemove="moving()" onmouseup="moveEnd()" onmouseout="moveEnd()">
      <div id="_process_bar" >
        <div id="process">
        </div>
      </div>
    </div>
    <div id="mute">
      <img src="static/imgs/icons/mute.png" alt="mute" id="mute_picture" onclick="mute()">
    </div>
    </div>
  </div>
</div>
<div id ="end" onclick="window.close()">关闭</div>