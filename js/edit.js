function saveChange()
{
  var modify_file = document.getElementById("modify_file");
  var formdata =new FormData(modify_file);
  var xmlhttp = new XMLHttpRequest();
  xmlhttp.onreadystatechange=function()
  {
    if(xmlhttp.readyState == 4 && xmlhttp.status == 200)
    {
      switch(xmlhttp.responseText)
      {
        
        case "success":
          notice("已保存");
          break;
        case "failed":
          notice("修改失败");
          break; 
      }
      //alert(xmlhttp.responseText);
    }
  }
  xmlhttp.open("POST","data.php?act=save_change",true);
  xmlhttp.send(formdata);
}
function notice(notice_content)
{
  var notice_area = document.getElementById("notice");
  notice_area.innerHTML = notice_content;
  notice_area.style.left="2.5%";
  setTimeout(function(){
    notice_area.style.left= "-"+notice_area.offsetWidth+"px";
  },1000);
}