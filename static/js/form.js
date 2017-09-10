function ajaxPage(url)
{
  xhr = new XMLHttpRequest();
  xhr.onload = function()
  {
    if(this.readyState == 4&&this.status==200)
    { 
      var content = document.getElementById('content_wrapper');
      content.innerHTML=xhr.responseText;
    }
  }
  xhr.open('GET',url,false);
  xhr.send();
}

function addCol()
{
  var form = document.getElementById('form');
  var col_num = document.getElementById('col_num');
  var new_col = document.createElement('div');
  var new_col_num = parseInt(col_num.value)+1;
  var label1 = document.createElement('label');
  label1.innerHTML = '字段名称';
  label1.setAttribute('for',new_col_num);
  var col_name = document.createElement('input');
  col_name.type = 'text';
  col_name.name = new_col_num;
  var label2 = document.createElement('label');
  label2.innerHTML = '字段类型';
  label2.setAttribute('for',new_col_num+'_type');
  var col_type = document.createElement('select');
  col_type.name = new_col_num+'_type';
  col_type.options.add(new Option('text','text'));
  col_type.options.add(new Option('select','select'));
  col_type.options.add(new Option('checkbox','checkbox'));
  col_type.options.add(new Option('file','file'));
  new_col.appendChild(label1);
  new_col.appendChild(col_name);
  new_col.appendChild(label2);
  new_col.appendChild(col_type);
  form.appendChild(new_col);
  col_num.value = new_col_num;

}