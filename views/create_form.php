<div id=>
  <form id="form">
    <input type="hidden" id="col_num" name="col_num" value="1">
    <div>
    <label for="1">字段名称</label>
    <input type="text" name="1">
    <label for="1_type">字段类型</label>
    <select name="1_type">
    <option value="text">text</option>
    <option value="select">select</option>
    <option value="checkbox">checkbox</option>
    <option value="file">file</option>
    </select>
    </div>
  </form>
  <div id="add_col" onclick="addCol()">新增字段</div>
</div>