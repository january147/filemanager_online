function ajax(method,url,content)
{
  xhr = new XMLHttpRequest();
  xhr.onload = function()
  {
    if(this.readyState == 4&&this.status==200)
    {  
      var data = JSON.parse(this.responseText);
      if(data.state == 'ok')
        location.reload();
      else
      {
        var username = document.getElementById("username");
        var password = document.getElementById("password");
        switch(data.info)
        {
          case 'no_user':
            username.value = null;
            username.setAttribute("placeholder","用户名不存在");
            break;
          case 'password_incorrect':
            password.value = null;
            password.setAttribute("placeholder","密码错误");
            break;
          case 'no_authority':
            username.value = null;
            username.setAttribute("placeholder","你被耍了，这是个假的账户");
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
function inputCheck()
{
  var username = document.getElementById("username");
  var password = document.getElementById("password");
  var pattern = /^[0-9a-zA-Z]*$/;
  if(!username.value)
  {
    username.setAttribute("placeholder","用户名不能为空");
    username.focus();
    return false;
  }
  if(!pattern.test(username.value))
  {
      username.value = null;
      username.setAttribute("placeholder","用户名只能包含字母和数字");
      username.focus();
      return false;
  }
  if(!password.value)
  {
      password.setAttribute("placeholder","密码不能为空");
      password.focus();
      return false;
  }
  return true;
}

function login()
{
  if(!inputCheck())
    return false;
  else
  {
    var form = document.getElementById('login_data');
    var formdata = new FormData(form);
    var url = 'index.php?r=LogPage/login';
    ajax('POST',url,formdata);
  }
}