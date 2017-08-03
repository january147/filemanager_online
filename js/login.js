function input_focus(id)
{
  
}

function input_check()
{
  var username = document.getElementById("username");
  var password = document.getElementById("password");
  var pattern = /^[0-9a-zA-z]*$/;
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

