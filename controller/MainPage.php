<?php
  class MainPage extends Controller
  {
    protected $layout = './views/layouts/MainPage.php';
    protected $title = '我的文件';
    public function index($part=false)
    { 
      $current_path = $_SESSION['root_path'].$_SESSION['path'];
      if(!is_dir($current_path))
      {
        $_SESSION['path'] = NULL;
        $current_path = $_SESSION['root_path'];
      } 
      $dir = new Dir($current_path);
      $dir_content = $dir->read();
      $content_info = array();
      $dir_obj = new Dir();
      $file_obj = new File();
      foreach($dir_content['dir'] as $dir_p)
      {
        $operation_path = $current_path.'/'.$dir_p;
        $dir_obj->path = $operation_path;
        $dir_obj->getAttrs();
        $content_info[] = [
          'name'=>addslashes($dir_p),
          'type'=>'目录',
          'size'=>$dir_obj->size,
          'm_time'=>$dir_obj->m_time
        ];
      }
      foreach($dir_content['file'] as $file_p)
      {
        $operation_path = $current_path.'/'.$file_p;
        //echo $operation_path;
        $file_obj->path = $operation_path;
        $file_obj->getAttrs();
        $content_info[] = [
          'name'=>addslashes($file_p),
          'type'=>$file_obj->type,
          'size'=>$file_obj->size,
          'm_time'=>$file_obj->m_time
        ];
      }
      $clipper = ($_SESSION['clipper'])? 'full':'empty';
      //$this->render('test',['dir_content'=>$dir_content,'test'=>'it works']);
      if(!$part)
        return $this->render('index',['dir_content'=>$content_info,'clipper'=>$clipper]);
      else
        return $this->renderPart('index_part',['dir_content'=>$content_info]);
      //echo 'it works';
    }

    public function addFile()
    {
      $new_filename = $_GET['new_filename'];
      if(!validFile($new_filename))
      {
        $state = 'failed';
        $info = 'invalid_filename';
        goto RETURN_PART;
      }
      $file_path = $_SESSION['root_path'].$_SESSION['path'].'/'.$new_filename;
      $file =new File($file_path);
      if(!$file->create())
      {
        $state = 'failed';
        $info = $file->error;
      }
      else
      {
        $state ='ok';
        $content = $this->index(true);
      }

      RETURN_PART:
      $return_content = [
        'state'=> $state,
        'info'=>$info,
        'content'=>$content
      ];
      return json_encode($return_content);
    }

    public function renameFile()
    {
      $new_filename = get('new_filename');
      $filename = get('filename');
      if(!validFile($new_filename))
      {
        $state = 'failed';
        $info = 'invalid_filename';
        goto RETURN_PART;
      }
      $file_path =  $_SESSION['root_path'].$_SESSION['path'].'/'.$filename;
      $file = new File($file_path);
      if(!$file->rename($new_filename))
      {
        $state = 'failed';
        $info = $file->error;
      }
      else
      {
        $state = 'ok';
        $content = $this->index(true);
      }

      RETURN_PART:
      $return_content = [
        'state'=> $state,
        'info'=>$info,
        'content'=>$content
      ];
      return json_encode($return_content);
    }

    public function copyFile()
    {
      $filename = $_GET['filename'];
      $file_path = $_SESSION['root_path'].$_SESSION['path'].'/'.$filename;
      $_SESSION['clipper'] = $file_path;
      $_SESSION['mode'] = 'copyFile';
      return json_encode(['state'=>'notice','info'=>'已添加到剪切板']);
    }

    public function moveFile()
    {
      $filename = $_GET['filename'];
      $file_path = $_SESSION['root_path'].$_SESSION['path'].'/'.$filename;
      $_SESSION['clipper'] = $file_path;
      $_SESSION['mode'] = 'moveFile';
      return json_encode(['state'=>'notice','info'=>'已添加到剪切板']);
    }

    public function paste()
    {
      if(!file_exists($_SESSION['clipper']))
      {
        $state = 'failed';
        $info = 'nothing_in_clipper';
        goto RETURN_PART;
      }
      $des_path = $_SESSION['root_path'].$_SESSION['path'];
      switch($_SESSION['mode'])
      {
        case 'copyFile':
          $file = new File($_SESSION['clipper']);
          $result = $file->copy($des_path);
          break;
        case 'moveFile':
          $file = new File($_SESSION['clipper']);
          $result = $file->move($des_path);
          break;
        case 'copyDir':
          $file = new Dir($_SESSION['clipper']);
          $result = $file->copy($des_path);
          break;
        case 'moveDir':
          $file = new Dir($_SESSION['clipper']);
          $result = $file->move($des_path);
          break;
      }
      if(!$result)
      {
        $state = 'failed';
        $info = $file->error;
      }
      else
      {
        $state = 'ok';
        $content = $this->index(true);
      }

      RETURN_PART:
      $return_content = [
        'state'=> $state,
        'info'=>$info,
        'content'=>$content
      ];
      return json_encode($return_content);
    }

    public function deleteFile()
    {
      $filename = $_GET['filename'];
      $file_path =  $_SESSION['root_path'].$_SESSION['path'].'/'.$filename;
      $file = new File($file_path);
      if(!$file->delete())
      {
        $state = 'failed';
        $info = $this->error;
      }
      else
      {
        $state = 'ok';
        $content = $this->index(true);
      }

      RETURN_PART:
      $return_content = [
        'state'=> $state,
        'info'=> $info,
        'content'=>$content
      ];
      return json_encode($return_content);
    }

    public function downloadFile()
    {
      $filename = $_GET['filename'];
      $file_path = $_SESSION['root_path'].$_SESSION['path'].'/'.$filename;
      //$file_path = './file/test/music.mp3';
      $file = new File($file_path);
      if(!$file->download())
      {
        header('Content-type:text/html');
        return $file->error;
      }
      else
        return NULL;
    }
    public function uploadFile()
    {
      $file = $_FILES['upload_file'];
      if($file == NULL)
      {
        $state = 'failed';
        $info = 'no_file';
        goto RETURN_PART;
      }
      $file_obj = new File();
      if(!$file_obj->upload($file))
      {
        $state = 'failed';
        $info = $file_obj->error;
      }
      else
      {
        $state = 'ok';
        $content = $this->index(true);
      }

      RETURN_PART:
      $return_content = [
        'state'=> $state,
        'info'=>$info,
        'content'=>$content
      ];
      return json_encode($return_content);
    }

    public function addDir()
    {
      $new_dirname = $_GET['new_dirname'];
      if(!validFile($new_dirname))
      {
        $state = 'failed';
        $info = 'invalid_filename';
        goto RETURN_PART;
      }
      $dir_path = $_SESSION['root_path'].$_SESSION['path'].'/'.$new_dirname;
      $dir =new Dir($dir_path);
      if(!$dir->create())
      {
        $state = 'failed';
        $info = $dir->error;
      }
      else
      {
        $state ='ok';
        $content = $this->index(true);
      }

      RETURN_PART:
      $return_content = [
        'state'=> $state,
        'info'=>$info,
        'content'=>$content
      ];
      return json_encode($return_content);
    }

    public function renameDir()
    {
      $new_dirname = get('new_dirname');
      $dirname = get('dirname');
      if(!validFile($new_dirname))
      {
        $state = 'failed';
        $info = 'invalid_filename';
        goto RETURN_PART;
      }
      $dir_path = $_SESSION['root_path'].$_SESSION['path'].'/'.$dirname;
      $dir =new Dir($dir_path);
      if(!$dir->rename($new_dirname))
      {
        $state = 'failed';
        $info = $dir->error;
      }
      else
      {
        $state ='ok';
        $content = $this->index(true);
      }

      RETURN_PART:
      $return_content = [
        'state'=> $state,
        'info'=>$info,
        'content'=>$content
      ];
      return json_encode($return_content);
    }

    public function enterDir()
    {
      $dirname = get('dirname');
      $path = $_SESSION['path'].'/'.$dirname;
      if(is_dir($_SESSION['root_path'].$path))
        $_SESSION['path'] = $path;
      return $this->index(true);
    }
    public function copyDir()
    {
      $dirname = get('dirname');
      $dir_path = $_SESSION['root_path'].$_SESSION['path'].'/'.$dirname;
      $_SESSION['clipper'] = $dir_path;
      $_SESSION['mode'] = 'copyDir';
      return json_encode(['state'=>'notice','info'=>'已添加到剪切板']);
    }
    public function moveDir()
    {
      $dirname = $_GET['dirname'];
      $dir_path = $_SESSION['root_path'].$_SESSION['path'].'/'.$dirname;
      $_SESSION['clipper'] = $dir_path;
      $_SESSION['mode'] = 'moveDir';
      return json_encode(['state'=>'notice','info'=>'已添加到剪切板']);
    }
    public function deleteDir()
    {
      $dirname = get('dirname');
      $dir_path = $_SESSION['root_path'].$_SESSION['path'].'/'.$dirname;
      $dir = new Dir($dir_path);
      if(!$dir->delete())
      {
        $state = 'failed';
        $info = $dir->error;
      }
      else
      {
        $state = 'ok';
        $content = $this->index(true);
      }

      RETURN_PART:
      $return_content = [
        'state'=> $state,
        'info'=>$info,
        'content'=>$content
      ];
      return json_encode($return_content);
    }
    public function downloadDir()
    {
      $dirname = get('dirname');
      $dir_path = $_SESSION['root_path'].$_SESSION['path'].'/'.$dirname;
      $dir = new Dir($dir_path);
      $dir->download();
    }
    public function back()
    {
      $former_path = dirname($_SESSION['path']);
      if($former_path!='\\')
        $_SESSION['path'] = $former_path;
      else
       $_SESSION['path'] = NULL; 
      return $this->index(true);
    }
    public function home()
    {
      $_SESSION['path'] = NULL;
      return $this->index(true);
    }

    public function uploadTest()
    {
      $file = $_FILES['upload_file'];
      echo '结果如下';
      print_r($file);
      echo '@'.$_POST['md5'];
      echo '@'.md5_file($file['tmp_name']);
      echo '@'.md5($_POST['test']);
      //echo '@'.$_POST['test'];
      //echo '@'.file_get_contents($file['tmp_name']);
      exit;
    }
  }

  
?>