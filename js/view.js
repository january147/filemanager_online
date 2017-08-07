
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

var music_paused=true;
var effect=false;
var process_percentage;
var total_width = document.getElementById("process_bar").offsetWidth;
function moveActive()
{
  effect = true;
  music_paused = music_handle.paused;
}
function moving()
{
  //alert("移动触发");
  var process_in = document.getElementById("process_in");
  if(effect)
  {
    if(!music_paused)
    {
      music_handle.pause();
    }
    process_in.style.width = event.offsetX +"px";
  }
}
function moveEnd()
{
  var process_in = document.getElementById("process_in");
  process_in.style.width = event.offsetX +"px";
  percent = event.offsetX/total_width;
  music_handle.currentTime = music_handle.duration * percent;
  if(!music_paused)
  {
    music_handle.play();
  }
  effect = false;
}

function mute()
{
  var mute_picture = document.getElementById("mute_picture");
  if(music_handle.muted)
  {
    music_handle.muted = false;
    mute_picture.setAttribute("src","imgs/mute.png")
  }
  else
  {
    music_handle.muted = true;
    mute_picture.setAttribute("src","imgs/no_mute.png")
  }
}