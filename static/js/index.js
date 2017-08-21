/*前面部分是封装的功能性函数*/
/*封装了xmlhttprequest请求*/
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
        var content = document.getElementById('content_wrapper');
        notice('成功');
        content.innerHTML=data.content;
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
function ajaxConfirm(method,url,content)
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
        var content = document.getElementById('content_wrapper');
        notice('成功');
        content.innerHTML=data.content;
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
            var yes = confirm('文件已存在，是否覆盖？');
            if(yes)
            {
              var url = 'index.php?r=MainPage/paste&confirm=yes';
              xhr.open('GET',url,false);
              xhr.send();
            }
            else
              notice('粘贴操作取消');
            break;
          case 'dir_exist':
            notice('文件夹已存在');
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
function ajaxPage(url)
{
  xhr = new XMLHttpRequest();
  xhr.onload = function()
  {
    if(this.readyState == 4&&this.status==200)
    { 
      var content = document.getElementById('content_wrapper');
      content.innerHTML=xhr.responseText;
    }
  }
  xhr.open('GET',url,false);
  xhr.send();
}
function ajaxUpload(method,url,content)
{
  xhr = new XMLHttpRequest();
  xhr.upload.addEventListener('loadstart',stateOn,false)
  xhr.upload.addEventListener('progress',uploadProgress,false);
  xhr.upload.addEventListener('loadend',stateOff,false);
  xhr.onreadystatechange = function()
  {
    if(this.readyState == 4&&this.status==200)
    { 
      //alert(this.readyState);  
      var data = JSON.parse(this.responseText);
      if(data.state == 'ok')
      {
        var content = document.getElementById('content_wrapper');
        notice('上传成功');
        content.innerHTML=data.content;
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
          case 'beyond_limit':
            notice('文件过大，不能超过20M');
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
/**下面是各种操作的响应函数*/
/**公共操作*/
function _paste()
{
  var url = 'index.php?r=MainPage/paste';
  ajaxConfirm('GET',url,null);
}
function back()
{
  var url = 'index.php?r=MainPage/back';
  ajaxPage(url);
}
function home()
{
  var url = 'index.php?r=MainPage/home';
  ajaxPage(url);
}

function uploadFile(id)
{
  file_handle = document.getElementById(id);
  if(file_handle.value)
  {
    if(file_handle.files[0].size > 20971520)
    {
        //alert(file_handle.files[0].size);
        notice("文件过大，最多20M");
        return;
    }
    var url = 'index.php?r=MainPage/uploadFile';
    var file_form = document.getElementById("file_form");
    var formdata = new FormData(file_form);
    ajaxUpload('POST',url,formdata);
  }
}

function uploadProgress(evt)
{
  var process = document.getElementById("process");
  if(evt.lengthComputable)
  {
    var percentage = Math.round(evt.loaded*100/evt.total);
    process.style.width = percentage+"%";
  }
}
function uploadComplete()
{
  var process = document.getElementById("process");
  notice('上传成功');
  process.style.width = 0;
}
/**文件操作*/
/**新建文件的响应函数*/
function addFile(filename)
{
  var new_filename = prompt('请输入新文件名');
  if(!new_filename)
    return;
  var url = 'index.php?r=MainPage/addFile&new_filename='+new_filename;
  ajax('GET',url,null);
}
function copyFile(filename)
{
  var url = 'index.php?r=MainPage/copyFile&mode=copyFile&filename='+filename;
  ajax('GET',url,null);
}
function moveFile(filename)
{
  var url = 'index.php?r=MainPage/moveFile&mode=moveFile&filename='+filename;
  ajax('GET',url,null);
}
function renameFile(filename)
{
  var new_filename = prompt('请输入新文件名');
  if(!new_filename)
    return;
  var url = 'index.php?r=MainPage/renameFile&filename='+filename+'&new_filename='+new_filename;
  ajax('GET',url,null);
}
function deleteFile(filename)
{
  if(!confirm('是否删除'+filename+'?'))
    return;
  var url = 'index.php?r=MainPage/deleteFile&filename='+filename;
  ajax('GET',url,null);
}
function view(filename)
{
  var url = 'index.php?r=Content/view&filename='+filename;
  window.open(url);
}
function edit(filename)
{
  var url = 'index.php?r=Content/edit&filename='+filename;
  window.open(url);
}
/**下载文件的响应函数*/
function downloadFile(filename)
{
  var url = 'index.php?r=MainPage/downloadFile&filename='+filename;
  window.open(url);
}
/**目录操作*/
function addDir()
{
  var new_dirname = prompt('请输入新目录名');
  if(!new_dirname)
    return;
  var url = 'index.php?r=MainPage/addDir&new_dirname='+new_dirname;
  ajax('GET',url,null);
}
function copyDir(dirname)
{
  var url = 'index.php?r=MainPage/copyDir&mode=copyDir&dirname='+dirname;
  ajax('GET',url,null);
}
function moveDir(dirname)
{
  var url = 'index.php?r=MainPage/moveDir&mode=moveDir&dirname='+dirname;
  ajax('GET',url,null);
}
function renameDir(dirname)
{
  var new_dirname = prompt('请输入目录名');
  if(!new_dirname)
    return;
  var url = 'index.php?r=MainPage/renameDir&dirname='+dirname+'&new_dirname='+new_dirname;
  ajax('GET',url,null);
}
function deleteDir(dirname)
{
  if(!confirm('是否删除'+dirname+'?'))
    return;
  var url = 'index.php?r=MainPage/deleteDir&dirname='+dirname;
  ajax('GET',url,null);
}
function enterDir(dirname)
{
  var url = 'index.php?r=MainPage/enterDir&dirname='+dirname;
  ajaxPage(url);;
}
function downloadDir(dirname)
{
  var url = 'index.php?r=MainPage/downloadDir&dirname='+dirname;
  window.open(url);
}
