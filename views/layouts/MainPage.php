<!DOCTYPE HTML>
<html>
  <head>
    <meta charset="utf-8">
    <title><?php echo $this->title?></title>
    <link href="./views/layouts/css/MainPage.css" rel="stylesheet">
    <link href="<?php echo $view_css?>" rel="stylesheet">
  </head>
  <body>
    <div id="layout_notice"></div>
    <div id="layout_navigation">
      <img onclick="logout()" src="./static/imgs/photos/photo.png" alt="头像" title="点我退出">
      <div id="file"><a href='index.php?r=MainPage/index'>我的文件</a></div>
      <div id="file"><a href='index.php?r=FormPage/index'>我的表格</a></div>
    </div>
    <div id="layout_content">
      <?php echo $view_content?>
    </div>
    <div id="layout_state">
      <div id="process"></div>
    </div>
  <script src="./views/layouts/js/MainPage.js" type='text/javascript'></script>
  <script src="<?php echo $view_js?>" type='text/javascript'></script>
  </body>
</html>