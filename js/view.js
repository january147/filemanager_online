
var music_handle = document.getElementById("music");
music_handle.canplay = okToPlay();
function okToPlay()
{
  var play = document.getElementById("pause");
  play.style.opacity = "1";
  music_handle.pause();
}

function play_pause()
{
  //alert("点击了播放键");
  var status_picture = document.getElementById("status_picture");
  if(music_handle.paused)
  {
    music_handle.play();
    status_picture.setAttribute("src","imgs/pause.png");
  }
  else
  {
    music_handle.pause();
    status_picture.setAttribute("src","imgs/play.png");
  }  
}

function getProcess()
{
  var process_in = document.getElementById("process_in");
  var percent = Math.round(music_handle.currentTime*100/music_handle.duration);
  process_in.style.width = percent+"%";
}
function stop()
{
  var status_picture = document.getElementById("status_picture");
  status_picture.setAttribute("src","imgs/play.png");
  music_handle.currentTime = 0;
  music_handle.pause();
}

var effect=false;

function moveActive()
{
  //alert("点击触发");
  effect = true;
}
function moving()
{
  //alert("移动触发");
  var process_in = document.getElementById("process_in");
  var test =document.getElementById("music_picture");
  if(effect)
  {
    process_in.style.width = event.offsetX +"px";
  }
 
}
function moveEnd()
{
   effect = false;
}