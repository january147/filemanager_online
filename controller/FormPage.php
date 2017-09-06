<?php
  class FormPage extends Controller
  {
    protected $layout = './views/layouts/MainPage.php';
    protected $title = '我的表格';
    public function index()
    {
      return $this->render('menu');
    }


  }
?>