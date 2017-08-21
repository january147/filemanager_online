<?php
class LogPage{
  protected function renderPart($view_name,$value)
  {
    if($value)
      extract($value);
    $view_path = './views/'.$view_name.'.php';
    //$view_css = './static/css/'.$view_name.'.css';
    ob_start();
    include $view_path;
    $view_content = ob_get_contents();
    ob_end_clean();
    return $view_content;
  }
  protected function render($view_name,$value)
  {
    if($value)
      extract($value);
    $view_path = './views/'.$view_name.'.php';
    $view_css = './static/css/'.$view_name.'.css';
    $view_js = './static/js/'.$view_name.'.js';
    $view_content = $this->renderPart($view_name,$value);
    //return $view_content;
    ob_start();
    include './views/layouts/layout.php';
    $page_content = ob_get_contents();
    ob_end_clean();
    return $page_content;
  }
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