<?php
class Controller
{
  protected $layout = './views/layouts/layout.php';
  protected $title = '主页';
  protected function renderPart($view_name,$value=NULL)
  {
    if($value!=NULL)
      extract($value);
    $view_path = './views/'.$view_name.'.php';
    //$view_css = './static/css/'.$view_name.'.css';
    ob_start();
    include $view_path;
    $view_content = ob_get_contents();
    ob_end_clean();
    return $view_content;
  }
  protected function render($view_name,$value=NULL)
  {
    if($value!=NULL)
      extract($value);
    $view_path = './views/'.$view_name.'.php';
    $view_css = './static/css/'.$view_name.'.css';
    $view_js = './static/js/'.$view_name.'.js';
    ob_start();
    include $view_path;
    $view_content = ob_get_contents();
    ob_end_clean();
    ob_start();
    include $this->layout;
    $page_content = ob_get_contents();
    ob_end_clean();
    return $page_content;
  }
}
?>