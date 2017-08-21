var music_handle = document.getElementById("music");
music_handle.oncanplay = okToPlay();
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
    status_picture.setAttribute("src","static/imgs/icons/pause.png");
  }
  else
  {
    music_handle.pause();
    status_picture.setAttribute("src","static/imgs/icons/play.png");
  }  
}

function getProcess()
{
  var process = document.getElementById("process");
  var percent = Math.round(music_handle.currentTime*100/music_handle.duration);
  process.style.width = percent+"%";
}
function stop()
{
  var status_picture = document.getElementById("status_picture");
  status_picture.setAttribute("src","static/imgs/icons/play.png");
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
  var process = document.getElementById("process");
  if(effect)
  {
    if(!music_paused)
    {
      music_handle.pause();
    }
    process.style.width = event.offsetX +"px";
  }
}
function moveEnd()
{
  if(effect)
  {
    var process = document.getElementById("process");
    process.style.width = event.offsetX +"px";
    percent = event.offsetX/total_width;
    music_handle.currentTime = music_handle.duration * percent;
    if(!music_paused)
    {
      music_handle.play();
    }
    effect = false;
  }
}

function mute()
{
  var mute_picture = document.getElementById("mute_picture");
  if(music_handle.muted)
  {
    music_handle.muted = false;
    mute_picture.setAttribute("src","static/imgs/icons/mute.png")
  }
  else
  {
    music_handle.muted = true;
    mute_picture.setAttribute("src","static/imgs/icons/no_mute.png")
  }
}