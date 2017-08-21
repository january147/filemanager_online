<?php
class Dir{
  public $name;
  public $size;
  public $m_time;
  public $p_dir;
  public $path;
  public $error;

  public function __construct($dir_path = NULL)
  {
    $this->path = $dir_path;
    if($dir_path!=NULL)
    {
      $this->name = basename($dir_path);
      $this->p_dir = dirname($dir_path);
    }
  }
 
  public function getName()
  {
    return basename($this->path);
  }

  /*怎么使用迭代的方式来读取呢*/
  private function getSize()
  {
   
    return dirSize($this->path);
  }

  private function getModifiedTime()
  {
    return date("Y.n.j G:i:s",filemtime($this->path));
  }

  private function getDirname()
  {
    return dirname($this->path);
  }
  /*未完成*/
  public function getAttrs()
  {
    if(is_dir($this->path))
    {
      $this->name = $this->getName();
      $this->size = $this->getSize();
      $this->m_time = $this->getModifiedTime();
      $this->p_dir = $this->getDirname();
      return true;
    }
    else
      return false;
  }

  public function create()
  {
    if(is_dir($this->path))
    {
      $this->error = 'dir_exist';
      return false;
    }
    if(mkdir($this->path))
      return true;
    else
      return false;
  }

  public function rename($new_name)
  {
    $des = $this->p_dir.'/'.$new_name; 
    if(is_dir($des))
    {
      $this->error = 'dir_exist';
      return false;
    }
    if(rename($this->path,$des))
      return true;
    else
      return false;
  }
  
  public function read()
  {
    return _readDir($this->path);
  }

  public function copy($des_dir)
  {
    $des = $des_dir.'/'.$this->name;
    if(is_dir($des))
    {
      $this->error = 'dir_exist';
      return false;
    }
    if(strpos($des,$src)!==false)
    {
      $this->error = 'under_itself';
      return false;
    }
    return copyDir($this->path,$des);
  }

  /*传入目的目录即可*/
  public function move($des_dir)
  {
    $des = $des_dir.'/'.$this->name;
    if(is_dir($des))
    {
      $this->error = $des; //'dir_exist';
      return false;
    }
    else
      return rename($this->path,$des);
  }

  public function delete()
  {
    return deleteDir($this->path);
  }

  public function toZip($des_dir=NULL)
  {
    if($des_dir!=NULL)
      $des = $des_dir.'/'.$this->name.'.zip';
    else
      $des = $this->path.'.zip';
    $zip = new ZipArchive;
    $zip->open($des,ZIPARCHIVE::CREATE);
    zipDir($zip,$this->path);
    $zip->close();
  }

  public function download()
  {
    if(is_dir($this->path))
    {
      $download_src = './temp/'.$this->name.'.zip';
      if(!is_file($download_src))
        $this->toZip('./temp');
      $temp_file = new File($download_src);
      $temp_file->download();
      return true;
    }
    else
    {
      $this->error = 'no_dir';
      return false;
    }
  }
}
?>