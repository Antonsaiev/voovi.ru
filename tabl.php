
<form class="form" action="<?php $_SERVER['PHP_SELF']?>" method="post" style="
    background: #FFFFFF;
    padding: 10px;
    border: 2px solid #5CB8A3;
    border-radius: 5px;
">
<div style="
    margin: -10px -10px 10px -10px;
    padding: 4px 10px 0px 4px;
    background: #5CB8A3;
">
<input class="btn btn-xs" type="submit" name="ingroup" value="Сгрупировать" style="background: #fff;margin-bottom: 5px; border-bottom-right-radius: 0; border-top-right-radius: 0;" />
<input class="btn btn-xs" type="submit" name="ungroup" value="Разгрупировать" style="background: #fff;margin-bottom: 5px; border-bottom-left-radius: 0; border-top-left-radius: 0;" /> -
<input class="btn btn-xs" type="submit" name="doljen" value="Должен" style="background: #fff;margin-bottom: 5px;" /> -
<input class="btn btn-xs" type="submit" name="doljenop" value="Должен оплатить" style="background: #fff;margin-bottom: 5px;" /> -
<?php
	if($userdata['otvetstven']==1){
		echo '
			<input class="btn btn-xs" type="submit" name="oplachen" value="Оплата" style="background: #fff;margin-bottom: 5px;" /> -
			<input class="btn btn-xs" type="submit" name="proveren" value="Готов" style="background: #fff;margin-bottom: 5px;" /> -
			<input class="btn btn-xs" type="submit" name="akt" value="В архив" style="background: #fff;margin-bottom: 5px;" /> -
			<input class="btn btn-xs" type="submit" name="sertifikat" value="Установка сертификата" style="background: #fff;margin-bottom: 5px;" /> -
			
			
		';
	}

	 if($person47['akt'] == 1)
		{
			echo $person['akt'];
		}
	
?>
<!--<input class="btn btn-xs" type="submit" name="cher" value="Отказались" style="background: #fff;margin-bottom: 5px;" />!-->
<!--<input class="btn btn-xs" type="submit" name="otk" value="В черновики" style="background: #fff;margin-bottom: 5px;" /> !-->
    <input class="btn btn-xs" type="submit" name="kross" value="Кросы" style="background: #fff;margin-bottom: 5px;" /> -
    <input class="btn btn-xs" type="submit" name="prodplus" value="Проделние +" style="background: #fff;margin-bottom: 5px;" /> -
    <input class="btn btn-xs" type="submit" name="incoming" value="Входящие" style="background: #fff;margin-bottom: 5px;" /> -
<input class="btn btn-xs" type="reset" id="deleteol" value="Убрать отмеченые" style="background: #fff;margin-bottom: 5px;" />-
<?php
	if($userdata['users_id']==20 ||$userdata['users_id']==17||$userdata['users_id']==4||$userdata['users_id']==4107||$userdata['users_id']==3||$userdata['users_id']==4120){
		echo '
			<input class="btn btn-xs" type="submit" name="postprod" value="Поставить продление" style="background: #fff;margin-bottom: 5px;" /> 
			
		';
	}
	
?>
<?/*php
	if($userdata['otvetstven']==1){
		echo '<input onclick=\'return confirm("Для удаления счета нажмите OK!");\' class="btn btn-xs" type="submit" name="deletemarked" value="Удалить" style="background: #fff;margin-bottom: 5px;" /> ';
	}
*/?>
</div>
<div id="status"></div>

<table class="table tablehover rowclick" id="rowclick2" >
<thead>
<tr>
<th style="width: 1px;"><span class="glyphicon glyphicon-ok" aria-hidden="true"></span></th>
<th style="width: 1px;"><span class="glyphicon glyphicon-random" aria-hidden="true"></span></th>
<th style="width: 1px;">№</th>
<th style="width: 1px;">Дата</th>
<th style="width: 1px;">ИНН</th>
<th style="width: 2%;">КПП</th>
<th style="width: 180px; text-align: center;">Название</th>
<th style="width: 5%; text-align: center;">Продукт</th>
<th >Комментарии</th>
<th style="width: 1px;"><span class="glyphicon glyphicon-star"></span></th>
<th style="width: 1px;"><span class="glyphicon glyphicon-ruble" aria-hidden="true"></span></th>
<!--<th style="width: 1px;"><span class="glyphicon glyphicon-open-file" aria-hidden="true"></span></th>!-->
<th style="width: 8px; text-align: center;">Контур</th>
<th style="width: 8px; text-align: center;">S</th>
<th style="width: 80px; text-align: center;">Услуга</th>
<th style="width: 80px; text-align: center;">Выставил</th>
<th style="width: 80px; text-align: center;">Агент</th>
<th style="width: 1px;">Исполнитель</th>
<th style="width: 1px;">Открыть</th>
</tr>
</thead>

<tbody id="active_body">
<?php
if(isset($_GET['gotov'])){
$gotov = $_GET['gotov'];
}
$iz = 1;
$otgr_number_start = 0; // после цикла сюда запишем следующий номер для нумерации архивных строк
$query = mysql_query("SELECT DISTINCT
id,
ns,
status,
kolichschet,
d,
m,
y,
nomerschet,
nomerschetks,
ogrn,
prodlen,
generac,
name,
lico,
rand,
otdel,
filial,
god,
nomerdog,
data,
produkt,
price,
kto,
inn,
kpp,
idkli,
goroddd,
akt_date,
cher,
postprod,
otk,
ust_sert,
koment,
oplachen,
oplachenks,
priceks,
doljenop,
doljen,
gotov,
akt,
url,
groupi,
install,
gr,
agent,
prichotk,
otl3,
prod,
krossprod,
prodplus,
incoming

FROM schet WHERE
del = '0'  and akt='0' and cher=0 and otk='0' and idkli = '".$_GET['id']."' group by rand ORDER BY id desc ");

while($row = mysql_fetch_array($query )) {
		$udosrpod = "SELECT * FROM produkti WHERE id =".$row['produkt'];
	$udosresultrpod = mysql_query($udosrpod);
	$udospersonrpod = mysql_fetch_array($udosresultrpod);

if($userdata['inogrn'] != 89097565645){
	if($udospersonrpod['parent'] == $userdata['inogrn']) {
		include 'inctoha.php';
	}
}else{
	$udos = mysql_query("SELECT * FROM users_access WHERE users = '".$userdata['users_id']."' AND uslugi = '".$udospersonrpod['parent']."'");
	while($udostup = mysql_fetch_array($udos)) {
		include 'inctoha.php';
	}
}
}
$otgr_number_start = $iz; // продолжаем нумерацию с этого номера при подгрузке архивных
?>
</tbody>
<tbody id="otgr_body">
<!-- сюда AJAX будет дописывать отгруженные -->
</tbody>
</table>
</form>

</div>
</div>
