<!DOCTYPE HTML>
<html>
  <head>
    <meta charset="utf-8">
    <title><?php echo $this->title?></title>
    <link href = './views/layouts/css/layout.css' rel='stylesheet'>
    <link href = '<?php echo $view_css?>' rel='stylesheet'>
  </head>
  <body>
    <?php echo $view_content ?>
  <body>
  <script src="./views/layouts/js/layout.js" type='text/javascript'></script>
  <script src="<?php echo $view_js?>" type='text/javascript'></script>
</html>