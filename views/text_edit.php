<div id="content_wrapper">
  <div id="text_edit">
      <form id="content_form">
        <textarea name="new_content" id="text"><?php echo $content?></textarea>
        <input type="hidden" name="editing_path" value="<?php echo $editing_path?>" >
      </form> 
  </div>
</div>
<div id="end">
  <div onclick="saveChange()">保存</div>
  <div onclick="window.close()">退出</div>
</div>