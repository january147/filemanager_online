<!DOCTYPE HTML>
<html>
  <head>
    <meta charset="utf-8">
    <title>内容查看</title>
    <link href="./views/layouts/css/Content.css" rel="stylesheet">
    <link href="<?php echo $view_css?>" rel="stylesheet">
  </head>
  <body>
    <div id="layout_notice"></div>
    <div onclick="notice('系统遇到严重问题需要重新启动')" id="layout_title">
      <?php echo $title?>
    </div>
    <div id="layout_content">
      <?php echo $view_content?>
    </div>
  <script src="./views/layouts/js/Content.js" type='text/javascript'></script>
  <script src="<?php echo $view_js?>" type='text/javascript'></script>
  </body>
</html>