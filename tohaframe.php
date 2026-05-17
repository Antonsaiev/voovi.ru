<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ru" lang="ru"> <head>
 
<meta http-equiv="content-type" content="text/html" charset="utf-8" />
<link href="css/bootstrap.min.css" rel="stylesheet"> </head>
<body>
<div style="
  height: 50px;
  margin-bottom: 25px;
  font-size: 20px;
  background: #26BB84;
  text-align: center;
  padding: 12px;
  color: #fff;
">ООО "частное охранное предприятие "Факел плюс"" <b>(СЧЕТ № 1500A203921)</b>
</div>
<div class="container">
<div class="row">

<div class="col-md-6">
<p><b><span>Дата создания &nbsp;24.03.15</span></b></p>
<br>
<table class="table">
 <thead>
 <tr>
 <td>
 <p><b><span>№</span></b></p>
 </td>
 <td>
 <p><b><span>Наименование</span></b></p>
 </td>
 <td>
 <p><b><span>Ед.</span></b></p>
 </td>
 <td>
 <p><b><span>Кол-во</span></b></p>
 </td>
 <td>
 <p><b><span>Цена</span></b></p>
 </td>
 <td>
 <p><b><span>В т.ч. НДС</span></b></p>
 </td>
 <td>
 <p><b><span>Сумма</span></b></p>
 </td>
 </tr>
 </thead> 
<tr><td><p><span>1</span></p></td><td><span>Оформление и проверка  документов для генерации ключа</span></td><td><p><span>шт</span></p></td><td><p><span>2</span></p></td><td><p><span>800.00</span></p></td><td><p><span>Без НДС</span></p></td><td><p><span>1 600.00</span></p></td></tr>
 <tr>
 <td >
 <p><b><span>Итого:</span></b></p>
 </td>
 <td>
 </td>
 <td>
 </td>
 <td>
 </td>
 <td>
 </td>
 <td>
 </td>
 <td>
 <p><span>1 600.00</span></p>
 </td>
 </tr>   
</table> <a ></a>
<a onclick="addb()"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Добавить новый продукт</a> <div id="contaiq"  onclick="contaiq()"></div> <br>  



<div id="konttakt">Контактное лицо:
<q id="konactinfo">Рудневский Андрей Алексеевич  88793975988 ivanyuta.e@mail.ru</q><select id="kontakti" name="kontakti" onchange="konTakti(this.value)" style="display: none;"><option  value="0"></option><option  value="629">Рудневский Андрей Алексеевич (Директор: 88793975988)</option><option  value="0"></option></select>
<a onclick="konttakt()" style="float: right;"><span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a></div>

</div>
<div class="col-md-6">  <table class="table">
 <tr>
 <th style="padding: 1px 5px;" class="col-md-3">Идентификатор клиента:</th><th style="font-weight: 100; padding: 1px 5px;">20392</th>
 </tr>
 <tr>
 <th style="padding: 1px 5px;">ОГРН:</th><th style="font-weight: 100; padding: 1px 5px;">
 1112651006214
 </th>
 </tr>
 <tr>
 <th style="padding: 1px 5px;">ИНН:</th><th style="font-weight: 100; padding: 1px 5px;"><a href="https://focus.kontur.ru/search?query=2632800647">2632800647</a></th>
 </tr>
 <tr>
 <th style="padding: 1px 5px;">КПП:</th><th style="font-weight: 100; padding: 1px 5px;">263201001</th>
 </tr>
 <tr>
 <th style="padding: 1px 5px;">Юридический адрес:</th><th style="font-weight: 100; padding: 1px 5px;">Ставропольский край, г Пятигорск, Суворовский проезд, д 2А<br></th>
 </tr>
 <tr>
 <th style="padding: 1px 5px;">Фактический адрес::</th><th style="font-weight: 100; padding: 1px 5px;"><br></th>
 </tr>
 <tr>
 <th style="padding: 1px 5px;">Информация:</th><th style="font-weight: 100; padding: 1px 5px;"> <br></th>
 </tr>
 </table> 
</div>
</div>
</div><script>
function contaiq()
{

        $("#contaiq").empty();
		$("#contaiq").className = "";

}
function konttakt()
{
document.getElementById("kontakti").style.display="block";
}
function konTakti(str) {
if (str=="0") {
$.ajax({
type: "GET",
url: "pusya.php",
data: "lico="+str+"&rand=308119018365420150324",
success: function(msg){
$("#konactinfo").html(msg);
document.getElementById("kontakti").style.display="none";
}});
} else {
$.ajax({
type: "GET",
url: "pusya.php",
data: "lico="+str+"&rand=308119018365420150324",
success: function(msg){
$("#konactinfo").html(msg);
document.getElementById("kontakti").style.display="none";
}}
);
}
}

function addb()
{
 var c = document.getElementById("contaiq");
 var d = document.createElement("iframe");
 var t = document.createTextNode("11111");
 d.appendChild(t);
 c.appendChild(d);
	d.src = "/";
	d.width = "1000px";
	d.height = document.documentElement.clientHeight - 100;
	d.className = "iframestyle";
	document.getElementById("contaiq").className = "contai";
}



    

</script>
 <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
 <script src="js/bootstrap.min.js"></script>
</body>
</html>