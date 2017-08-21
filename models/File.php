<?php
class File{
  public $name;
  public $size;
  public $m_time;
  public $type;
  public $p_dir;
  public $path;
  public $error;

  /*构造函数，传入一个路径来构造一个File类*/
  public function __construct($file_path=NULL)
  {
    $this->path = $file_path;
    if($file_path!=NULL)
    {
      $this->name = basename($file_path);
      $this->p_dir = dirname($file_path);
    }
  }
  /*以下是读取属性的内部方法*/
  private function getName()
  {
    return basename($this->path);
  }

  private function getSize()
  {
    return transByte(filesize($this->path));
  }

  private function getModifiedTime()
  {
    return date("Y.n.j G:i:s",filemtime($this->path));
  }

  private function getDirname()
  {
    return dirname($this->path);
  }

  private function getType()
  {
    return FileType::get($this->name);
  }
  public function getAttrs()
  {
    if(is_file($this->path))
    {
      $this->name = $this->getName();
      $this->type = $this->getType();
      $this->m_time = $this->getModifiedTime();
      $this->size = $this->getSize();
      $this->p_dir = $this->getDirname();
      return true;
    }
    else
      return false;
  }

  /*以下是操作文件的外部方法*/
  public function create()
  {
    if(is_file($this->path))
    {
      $this->error = 'file_exist';
      return false;
    }
    if(touch($this->path))
      return true;
    else
      return false;
  }

  public function rename($new_name)
  {
    $des = $this->p_dir.'/'.$new_name;
    if(is_file($des))
    {
      $this->error = 'file_exist';
      return false;
    }
    if(rename($this->path,$des))
      return true;
    else
      return false;
  }
  public function read()
  {
    return file_get_contents($this->path);
  }

  public function write($content)
  {
    if(file_put_contents($this->path,$content)===false)
      return false;
    else
      return true;
    
  }

  public function copy($des_dir)
  {
    $des = $des_dir.'/'.$this->name;
    if(is_file($des)&&$_GET['confirm']!='yes')
    {
      $this->error='file_exist';
      return false;
    }
    return copy($this->path,$des);
  }

  public function move($des_dir)
  {
    $des = $des_dir.'/'.$this->name;
    if(is_file($des)&&$_GET['confirm']!='yes')
    {
      $this->error='file_exist';
      return false;
    }
    return rename($this->path,$des);
  }

  public function delete()
  {
    return unlink($this->path);
  }

  public function download()
  {
    if(is_file($this->path))
    {
      header("Content-Type:application/octet-stream");
      header("Accept-Ranges:bytes");
      header("Content-Length:".filesize($this->path));
      header("Content-Disposition:attachment;filename=".basename($this->path));
      readfile($this->path);
      return true;
    }
    else
    {
      $this->error = 'no_file';
      return false;
    }
  }
 
  public function upload($file)
  {
    $current_path = $_SESSION['root_path'].$_SESSION['path']; 
    if($file['error']!=0)
    {
      switch($file['error'])
      {
        case 1:
        case 2:
          $this->error = 'beyond_limit';
          break;
        case 3:
          $this->error = 'not_complete';
          break;
        case 4:
          $this->error='no_file';
          break;
        case 5:
          $this->error = 'empty_file';
          break;
      }
      return false;
    }
    else if($file['size']>20971520)
    {
       $this->error = 'beyond_limit';
       return false;
    }
    else if(file_exists($current_path.'/'.$file['name']))
    {
      $new_name = '['.substr(md5(time()),5,5).']'.$file["name"];
      $file['name'] = $new_name;
    }
    $des_path = $current_path.'/'.$file['name'];
    return move_uploaded_file($file['tmp_name'],$des_path);
  }
}
?>