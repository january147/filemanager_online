function ajax(method,url,content)
{
  xhr = new XMLHttpRequest();
  xhr.onload = function()
  {
    if(this.readyState == 4&&this.status==200)
    { 
      //alert(this.readyState);  
      var data = JSON.parse(this.responseText);
      if(data.state == 'ok')
      {
        notice('修改成功');
      }
      else if(data.state=='notice')
      {
        notice(data.info);
      }
      else
      {
        switch(data.info)
        {
          case 'file_exist':
            notice('文件已存在');
            break;
          case 'no_file':
            notice('文件不存在');
            break;
          case 'invalid_filename':
            notice('文件名不合法');
            break;
          default:
            alert(this.responseText);
            break;
        }
      }
    }
  }
  xhr.open(method,url,true);
  xhr.send(content);
}
function saveChange()
{
  var new_content = document.getElementById('content_form');
  var formdata = new FormData(new_content);
  var url = 'index.php?r=Content/edit';
  ajax('POST',url,formdata);
}