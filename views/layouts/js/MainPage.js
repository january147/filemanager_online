function notice(message)
{
  var notice_dom = document.getElementById('layout_notice');
  notice_dom.innerHTML = message;
  notice_dom.style.top = '0.5rem';
  setTimeout(function(){notice_dom.style.top = '-3rem'},1000);
}
function stateOn()
{
  var state_dom = document.getElementById('layout_state');
  state_dom.style.bottom = '0.5rem';
}
function stateOff()
{
  var state_dom = document.getElementById('layout_state');
  state_dom.style.bottom = '-2rem';
}
function logout()
{
  if(confirm('确定要退出当前账号吗?'))
    window.location = 'index.php?r=LogPage/logout';
}