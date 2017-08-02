function createFile()
{
  var filename = prompt("请输入文件名");
  if(filename==null||filename.length==0)
  {
    return;
  }
  var xmlhttp= new XMLHttpRequest();
  xmlhttp.onreadystatechange=function()
  {
    if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
      switch(xmlhttp.responseText)
      {
        case "invalid_filename":
          alert("文件名不合法");
          break;
        case "file_exist":
          alert("文件已存在");
          break;
        case "failed":
          alert("创建失败");
        case "success":
          break;
        default:
          alert(xmlhttp.responseText);
          break;
      }
      location.reload();
    }
  }
  xmlhttp.open("GET","data.php?act=create_file&filename="+filename,true);
  xmlhttp.send();
}
function createDir()
{
    var filename = prompt("请输入目录名");
  if(filename==null||filename.length==0)
  {
    return;
  }
  var xmlhttp= new XMLHttpRequest();
  xmlhttp.open("GET","data.php?act=create_dir&filename="+filename,true);
  xmlhttp.send();
  xmlhttp.onreadystatechange=function(){
    if (xmlhttp.readyState==4 && xmlhttp.status==200)
      {
      switch(xmlhttp.responseText)
      {
        case "invalid_dirname":
          alert("目录名不合法，请改名后再试");
          break;
        case "dir_exist":
          alert("目录已存在");
          break;
        case "success":
          break
        case "failed":
          alert("创建失败");
        default:
          alert(xmlhttp.responseText);
      }
      location.reload();
    }
  }
}
function deleteDir(dirname)
{
  var arr = dirname.split("/");
  brief_dirname = arr[arr.length-1];
  if(confirm("确定要删除目录"+brief_dirname+"吗？")==false)
  {
    return;
  }
  var xmlhttp= new XMLHttpRequest();
  xmlhttp.open("GET","data.php?act=delete_dir&filename="+dirname,true);
  xmlhttp.send();
  xmlhttp.onreadystatechange=function()
  {
    if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
      location.reload();
    }
  }
}
function renameFile(old_filename)
{
  var filename = prompt("请输入新文件名");
  if(filename==null||filename.length==0)
  {
    return;
  }
  else
  {
    window.location.href = "index.php?filename="+old_filename+"&new_name="+filename+"&act=rename_file";
  }

}
function deleteFile(filename)
{
  var arr = filename.split("/");
  brief_filename = arr[arr.length-1];
  if(!confirm("是否确定删除文件"+brief_filename))
  {
    return
  }
  else
  {
    var xmlhttp= new XMLHttpRequest();
    xmlhttp.open("GET","data.php?act=delete_file&filename="+filename,true);
    xmlhttp.send();
    xmlhttp.onreadystatechange=function()
    {
      if (xmlhttp.readyState==4 && xmlhttp.status==200)
      {
        location.reload();
      }
    }
  }
}
function cut(filename)
{
  var xmlhttp= new XMLHttpRequest();
  xmlhttp.open("GET","data.php?act=cut&clipper="+dirname,true);
  xmlhttp.send();
  xmlhttp.onreadystatechange=function()
  {
    if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
      var paste = documemt.getElementById("paste");
      paste.style.border = "solid";
    }
  }
}
function copy(filename)
{
  var xmlhttp= new XMLHttpRequest();
  xmlhttp.open("GET","data.php?act=copy&clipper="+filename,true);
  xmlhttp.send();
  xmlhttp.onreadystatechange=function()
  {
    if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
      var paste = document.getElementById("paste");
      paste.style.border = "solid";
    }
  }
}
function upFile()
{
  document.getElementById("file").click();
}
function doUpFile()
{
  file_handle = document.getElementById("file");
  if(file_handle.value)
  {
    if(file_handle.files[0].size > 20971520)
    {
        alert(file_handle.files[0].size);
        alert("文件过大，最多20M");
        return;
    }
    var file_form = document.getElementById("file_form");
    var formdata = new FormData(file_form);
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.upload.addEventListener("progress",uploadProgress,false);
      xmlhttp.upload.addEventListener("load",uploadComplete,false);
    xmlhttp.onreadystatechange=function()
    {
      if (xmlhttp.readyState==4 && xmlhttp.status==200)
      {
        if(xmlhttp.responseText)
        {
          switch(xmlhttp.responseText)
          {
            case "failed":
              alert("未知错误");
              break;
            case "size_beyond_limit":
              alert("文件过大，最多20M");
              break;
            case "not complete":
              alert("文件不完整");
              break;
            case "no file":
              alert("没有获取到文件");
              break;
            case "empty_file":
              alert("无效空文件");
              break;
            case "success":
              alert("上传成功");
              break;
            case "rename_success":
              alert("存在同名文件，上传的文件已被重命名");
              break;
            default:
              document.write(xmlhttp.responseText);
              return;
              break;
          }
        }
        location.reload();
      }
    }
    xmlhttp.open("POST","data.php?act=upload_file",true);
    xmlhttp.send(formdata);
  }
  //alert("xmlhttp.readyState="+xmlhttp.readyState);
  
}
function view(filename)
{
  window.open("./view.php?act=show_content&filename="+filename);
}
function edit(filename)
{
  window.open("./edit.php?act=edit_content&filename="+filename);
}
function downFile(filename)
{
  //window.location.href="index.php?filename="+filename+"&act=down_file";
  window.open("data.php?act=down_file&filename="+filename,"_blank");
}
function back(former_dir)
{
  window.location.href="index.php?path="+former_dir;
}
function paste()
{
  var xmlhttp= new XMLHttpRequest();
  xmlhttp.onreadystatechange=function()
  {
    if (xmlhttp.readyState==4 && xmlhttp.status==200)
    {
      switch(xmlhttp.responseText)
      {
          case "file_exist":
            if(alert("存在同名文件，是否覆盖")==true)
            {
              xmlhttp.open("GET","data.php?confirm=yes&act=paste",true);
              xmlhttp.send();
              return ;
            }
            else
            {
              alert("复制操作取消");
            }
            break;
          case "dir_exist":
            alert("目录已存在，请重命名后再试")
          case "failed":
            alert("粘贴失败");
            break;
      }
      location.reload();
    }
  }
  xmlhttp.open("GET","data.php?act=paste",true);
  xmlhttp.send();
}

//文件上传事件处理函数
function uploadProgress(evt)
{
  var process = document.getElementById("process");
  if(evt.lengthComputable)
  {
    var percentage = Math.round(evt.loaded*100/evt.total);
    process.style.width = percentage+"%";
    process.display = "block";
  }
}
function uploadComplete(){
  var process = document.getElementById("process");
  process.style.display = "none";
  process.style.width = 0;
}