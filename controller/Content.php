<?php
class Content
{
  protected function renderPart($view_name,$value)
  {
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
    extract($value);
    $view_path = './views/'.$view_name.'.php';
    $view_css = './static/css/'.$view_name.'.css';
    $view_js = './static/js/'.$view_name.'.js';
    $view_content = $this->renderPart($view_name,$value);
    //return $view_content;
    ob_start();
    include './views/layouts/Content.php';
    $page_content = ob_get_contents();
    ob_end_clean();
    return $page_content;
  }

  public function view()
  {
    $filename = get('filename');
    $type = Filetype::get($filename);
    $file_path = $_SESSION['root_path'].$_SESSION['path'].'/'.$filename;
    switch($type)
    {
      case 'text':
        $file = new File($file_path);
        $content = $file->read();
        return $this->render('text_view',['content'=>$content,'title'=>$filename]);
        break;
      case 'music':
        return $this->render('music_view',['src'=>$file_path,'title'=>$filename]);
        break;
      case 'image':
        return $this->render('image_view',['src'=>$file_path,'title'=>$filename]);
        break;
    }
  }
  public function edit()
    {
      $new_content = $_POST['new_content'];
      $editing_path = $_POST['editing_path'];
      if($new_content == NULL||$editing_path==NULL)
      {
        $filename = $_GET['filename'];
        $file_path =  $_SESSION['root_path'].$_SESSION['path'].'/'.$filename;
        $file = new File($file_path);
        $content = $file->read();
        $editing_path = $_SESSION['path'].'/'.$filename;
        return $this->render('text_edit',['title'=>$filename,'content'=>$content,'editing_path'=>$editing_path]);
      }
      else
      {
        $file_path = $_SESSION['root_path'].$editing_path;
        $file = new File($file_path);
        if($file->write($new_content)===false)
          $state = 'failed';
        else
          $state = 'ok';
        $return_content = [
        'state'=>$state,
        'info'=>$info,
        'content'=>$content
        ];
        return json_encode($return_content);
      }

    }
}
?>