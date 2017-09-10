<?php
  class FormPage extends Controller
  {
    protected $layout = './views/layouts/MainPage.php';
    protected $title = '我的表格';
    public function index()
    {
      return $this->render('form');
    }
    public function createForm()
    {
      /*开发中
      $col_num = $_POST['col_num'];
      if(!$col_num)
        return $this->renderPart('create_form');
      */
      return <<<EOF
      <div style="height:34rem;width:779px;margin:auto">
      <img src = "static/imgs/developing.png" style="height:34rem" alt='working'>
      </div>
EOF;
    }

    public function checkForm()
    {
      return <<<EOF
      <div style="height:34rem;width:779px;margin:auto">
      <img src = "static/imgs/developing.png" style="height:34rem" alt='working'>
      </div>
EOF;
    }
  }
?>