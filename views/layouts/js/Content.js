function notice(message)
{
  var notice_dom = document.getElementById('layout_notice');
  notice_dom.innerHTML = message;
  notice_dom.style.top = '0.5rem';
  setTimeout(function(){notice_dom.style.top = '-3rem'},1000);
}