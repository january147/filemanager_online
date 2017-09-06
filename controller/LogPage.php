<?php
class LogPage extends Controller
{
  /*当前控制器默认属性编辑，如需使用全局默认值，不要定义相应属性（继承父类属性值）
  *title为页面标签名称
  *layout为布局文件路径
  */
  protected $title = '登录Air';
  
  public function login()
  {
    $username = addslashes($_POST['username']);
    $password = addslashes($_POST['password']);
    if($username==NULL||$password==NULL)
    {
      return $this->render('login',NULL);
    }
    $mysql = new mysqli('localhost','log_info','120120','log_info');
    $mysql->set_charset('utf8');
    $sql_query = "SELECT * FROM log_info WHERE username = '$username'";
    $result = $mysql->query($sql_query);
    $row_data = $result->fetch_assoc();
    if($result == NULL)
    {
      $state = 'failed';
      $info ='no_user';
    }
    else if($row_data['password']!=$password)
    {
      $state = 'failed';
      $info ='password_incorrect';
    }
    else if($row_data['main_dir']==NULL)
    {
      $state = 'failed';
      $info ='no_authority';
    }
    else
    {
      $_SESSION['log_status'] = 'ok';
      $_SESSION['root_path'] = './file/'.$row_data['main_dir'];
      $_SESSION['username'] = $username;
      $state = 'ok';
    }

    $return_data = [
      'state'=>$state,
      'info'=>$info
      ];
    return json_encode($return_data);
  }
  public function logout()
  {
    session_unset();
    session_destroy();
    header('Location:index.php');
  }
}
?>