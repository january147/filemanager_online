<?php
  Class Air
  {
    private $router;
    private $controller = 'MainPage';
    private $action = 'index';

    public function __construct($r)
    {
      $slipt = explode('/',$r);
      if(count($slipt) == 2)
      {
        $this->controller = $slipt[0];
        $this->action = $slipt[1];
      }
      else if($r==NULL)
        return;
      else
      {
        _Error::noPage(); 
        exit;
      }
    }

    public function run()
    {
      $ctrl_name = $this->controller;
      $act_name = $this->action;
      if(!class_exists($ctrl_name))
      {
        _Error::noPage(); 
        exit;
      }
      $ctrl_obj = new $ctrl_name;
      echo $ctrl_obj->$act_name();
    }
  }
?>