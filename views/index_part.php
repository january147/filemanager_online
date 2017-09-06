<table>
    <tbody>
      <tr id = "table_head">
        <th hidden="hidden" style="width:5%">多选</th>
        <th id="filename" style="width:50%">文件名</th>
        <th id="type" style="width:3rem">类型</th>
        <th id="size" style="width:4rem">文件大小</th>
        <th id="m_time" style="width:8rem">修改时间</th>
        <th id="operation" style="width:20%">操作</th>
      </tr>
      <?php foreach($dir_content as $key=>$content_p):?>
      <tr> 
        <td hidden="hidden"><input type="checkbox"></td>
        <td><?php echo $content_p['name']?></td>
        <td><?php echo $content_p['type']?></td>
        <td><?php echo $content_p['size']?></td>
        <td><?php echo $content_p['m_time']?></td>
        <td id = "<?php echo $key?>">
          <?php if($content_p['type']=='目录'):?>
            <div onclick="enterDir('<?php echo $content_p['name']?>')"><img src="static/imgs/icons/open.png" alt="open" title="打开" /></div>
            <div onclick="copyDir('<?php echo $content_p['name']?>')"><img src="static/imgs/icons/copy.png" alt="copy" title="复制" /></div>
            <div onclick="moveDir('<?php echo $content_p['name']?>')"><img src="static/imgs/icons/cut.png" alt="cut" title="剪切"/></div>
            <div onclick="renameDir('<?php echo $content_p['name']?>')"><img src="static/imgs/icons/rename.png" alt="rename" title="重命名" /></div>
            <div onclick="deleteDir('<?php echo $content_p['name']?>')"><img src="static/imgs/icons/delete.png" alt="delete" title="删除"/></div>
            <div onclick="downloadDir('<?php echo $content_p['name']?>')"><img src="static/imgs/icons/download.png" alt="download" title="下载" /></div>
          <?php endif?>
          <!--按照类型确定可以进行的操作-->
          <?php if($content_p['type']!='目录'):?>
            <?php if($content_p['type']!='other'):?>
              <?php $src = 'static/imgs/icons/'.$content_p['type'].'_view.png'?>
              <div onclick="view('<?php echo $content_p['name']?>')"><img src="<?php echo $src?>" alt="view" title="查看" /></div>
            <?php endif?>
            <?php if($content_p['type']=='text'):?>
              <div onclick="edit('<?php echo $content_p['name']?>')"><img src="static/imgs/icons/edit.png" alt="edit" title="编辑"/></div>
            <?php endif?>
            <div onclick="copyFile('<?php echo $content_p['name']?>')"><img src="static/imgs/icons/copy.png" alt="copy" title="复制" /></div>
            <div onclick="moveFile('<?php echo $content_p['name']?>')"><img src="static/imgs/icons/cut.png" alt="cut" title="剪切"/></div>
            <div onclick="renameFile('<?php echo $content_p['name']?>')"><img src="static/imgs/icons/rename.png" alt="rename" title="重命名" /></div>
            <div onclick="deleteFile('<?php echo $content_p['name']?>')"><img src="static/imgs/icons/delete.png" alt="delete" title="删除"/></div>
            <div onclick="downloadFile('<?php echo $content_p['name']?>')"><img src="static/imgs/icons/download.png" alt="download" title="下载" /></div>
          <?php endif?>
        </td>
      </tr>
      <?php endforeach?>
    </tbody>
  </table>