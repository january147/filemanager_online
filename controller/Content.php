<?php
class Content extends Controller
{
  protected $layout = './views/layouts/Content.php';
  protected $title = '文件查看';

  public function view()
  {
    $filename = get('filename');
    $type = Filetype::get($filename);
    $file_path = $_SESSION['path'].'/'.$filename;
    switch($type)
    {
      case 'text':
        $file = new File($_SESSION['root_path'].$file_path);
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