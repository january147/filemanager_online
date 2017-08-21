<?php
  /*以下是功能性的静态类*/
  class FileType
  {
    public static $image_ext = array('gif','jpg','jpeg','png','bmp');
    public static $music_ext = array('mp3');
    public static $text_ext = array('txt','js','css','php');

    public static function __callstatic($method_name,$argv)
    {
      $ext = strtolower(end(explode('.',argv[0])));
      switch($method_name)
      {
        case 'isImage':
          $file_ext = self::$image_ext;
          break;
        case 'isMusic':
          $file_ext = self::$music_ext;
          break;
        case 'isText':
          $file_ext = self::$text_ext;
          break;
      }
      if(in_array($ext,$file_ext))
        return true;
      else
        return false;
    }

    public static function get($file_name)
    {
      $ext = strtolower(end(explode('.',$file_name)));
      if(in_array($ext,self::$image_ext))
        return 'image';
      else if(in_array($ext,self::$music_ext))
        return 'music';
      else if(in_array($ext,self::$text_ext))
        return 'text';
      else
        return 'other';
    }
  }

  /*下面是一些功能性函数
  *与系统内置函数同名或名称相似的函数以_(下划线)开头，提供更易用的功能
  *有依赖关系的函数会在函数前指出，并尽量放在一起
  */
  /*根据字节数换算为合适的单位*/
  function transByte($byte)
  {
    $i = 0;
    $result = $byte;
    while($result >= 1024)
    {
      $result = $result/1024;
      $i++;
    }
    $result = round($result,2);
    switch($i)
    {
      case 0: 
        $result = $result."B";
        break;
      case 1: 
        $result = $result."KB";
        break;
      case 2: 
        $result = $result."MB";
        break;
      case 3: 
        $result = $result."GB";
        break;
    }
    return $result;
  }

  function _readDir($dir_path)
  {
    if(($dir_handle = opendir($dir_path))!==false)
    {
      $dir_content = array(
        'file'=>array(),
        'dir'=>array()
      );
      while(($content_p = readdir($dir_handle)) !== false )
      {
        if($content_p!='.'&&$content_p!='..')
        {
          $operation_path = $dir_path.'/'.$content_p;
          if(is_file($operation_path))
            $dir_content['file'][] = $content_p;
          else
            $dir_content['dir'][] = $content_p;
        }
      }
      closedir($dir_handle);
      return $dir_content;
    }
    else
      return NULL;
  }

  /*依赖_readDir函数*/
  function copyDir($src,$des)
  {
    $dir_to_copy = array(['src'=>$src,'des'=>$des]);
    while(count($dir_to_copy)!=0)
    {
      $task = array_shift($dir_to_copy);
      $dir_content = _readDir($task['src']);
      if(!mkdir($task['des'])) 
        return false;
      foreach($dir_content['file'] as $file_p)
      {
        $from = $task['src'].'/'.$file_p;
        $to = $task['des'].'/'.$file_p;
        if(!copy($from,$to))
          return false;
      }
      foreach($dir_content['dir'] as $dir_p)
      {
        $from = $task['src'].'/'.$dir_p;
        $to = $task['des'].'/'.$dir_p;
        $dir_to_copy[] = ['src'=>$from,'des'=>$to];
      }
    }
    return true;
  }
  /*依赖_readDir*/
  function deleteDir($dir_path)
  {
    $dir_with_file = array($dir_path);
    while(count($dir_with_file)!=0)
    {
      $dir = array_shift($dir_with_file);
      $dir_content = _readDir($dir);
      foreach($dir_content['file'] as $file_p)
      {
        $operation_path = $dir.'/'.$file_p;
        if(!unlink($operation_path))
          return false;
      }
      if(count($dir_content['dir'])==0)
      {
        if(!rmdir($dir))
          return false;
      }
      else
      {
        $dir_to_delete[] = $dir;
        foreach($dir_content['dir'] as $dir_p)
          $dir_with_file[] = $dir.'/'.$dir_p;
      }
    }
    while(count($dir_to_delete)!=0)
    {
      $dir = array_pop($dir_to_delete);
      if(!rmdir($dir))
        return false;
    }
    return true;
  }

  /*压缩文件夹,$des是压缩包内部的路径,依赖_readDir函数*/
  function zipDir(&$zip,$src,$des=NULL)
  {
    $dir_content = _readDir($src);
    foreach($dir_content['file'] as $file_p)
    {
      $from = $src.'/'.$file_p;
      $to = $des.'/'.$file_p;
      $zip->addFile($from,$to);
    }
    foreach($dir_content['dir'] as $dir_p)
    {
      $from = $src.'/'.$dir_p;
      $to = $des.'/'.$dir_p;
      $zip->addEmptyDir($to);
      zipDir($zip,$from,$to);
    }
  }

  /*计算文件夹的大小,依赖_readDir和transByte*/
  function dirSize($dir_path)
  {
    if(is_dir($dir_path))
    {
      $sum = 0;
      $dir_to_read = array($dir_path);
      while(count($dir_to_read)!=0)
      {
        $dir = array_shift($dir_to_read);
        $dir_content = _readDir($dir);
        foreach($dir_content['file'] as $file_p)
        {
          $operation_path = $dir.'/'.$file_p;
          //echo filesize($operation_path).'@';
          $sum = $sum + filesize($operation_path);
          //echo $sum.'#';
        }
        foreach($dir_content['dir'] as $dir_p)
        {
          $operation_path = $dir.'/'.$dir_p;
          $dir_to_read[] = $operation_path;
        }
      }
      //echo $sum.'%';
      return transByte($sum);
    }
    else
      return false;
  }

  /*检验文件名是否和法*/
  function validFile($file_name)
  {
    $pattern = '/[\/,\\,\*,<,>,\|]/';
    if(preg_match($pattern,$file_name))
      return false;
    else
      return true;
  }

  function get($name)
  {
    $value = $_GET[$name];
    if(!$value||$value=='.'||$value=='..')
      exit;
    else
      return $value;
  }
  ?>