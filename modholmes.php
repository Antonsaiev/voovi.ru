<?php
# подключаем конфиг
include 'conf.php';  

# проверка авторизации
if (isset($_COOKIE['id']) and isset($_COOKIE['hash'])) 
{    
$userdata = mysql_fetch_assoc(mysql_query("SELECT * FROM users WHERE users_id = '".intval($_COOKIE['id'])."' LIMIT 1"));
if(($userdata['users_hash'] !== $_COOKIE['hash']) or ($userdata['users_id'] !== $_COOKIE['id'])) 
{ 
setcookie('id', '', time() - 60*24*30*12, '/'); 
setcookie('hash', '', time() - 60*24*30*12, '/');
setcookie('errors', '1', time() + 60*24*30*12, '/'); 
header('Location: index.php'); exit();
} 
} 
else 
{ 
setcookie('errors', '2', time() + 60*24*30*12, '/');
header('Location: index.php'); exit();
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ru" lang="ru">
<head>
<title></title>
<meta http-equiv="content-type" content="text/html" charset="utf-8" />
<meta http-equiv="Content-Style-Type" content="text/css" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="css/bootstrap.min.css" rel="stylesheet">
<link rel="shortcut icon" href="/favicon.ico">

</head>
<body>
<?php
# шапка
include 'header.php';  
?>
<div class="container" style="margin-top: 60px;">
<div class="row">
<?php
# левая колонка сайта
include 'left_sitebar.php';  

?>

<div class="col-md-8">
<strong><h4 style="margin-top: 10px; font-weight: bold; border-bottom: 1px #333 solid;">Обработка заявки</h4></strong>
<?php
include 'conn.php';
$q = "SELECT * FROM organizacii WHERE id =$_GET[id]";
$result = mysql_query($q);
$person = mysql_fetch_array($result);
if(isset($_POST['submit'])){
$u = "
UPDATE organizacii SET 
`obnovl`='".date('d.m.Y в H:i')."',
`kn`='$_POST[kn]',
`pn`='$_POST[pn]',
`inn`='$_POST[inn]',
`kpp`='$_POST[kpp]',
`ogrn`='$_POST[ogrn]',
`okpo`='$_POST[okpo]',
`dataobr`='$_POST[dataobr]',
`adres`='$_POST[adres]',
`kno`='$_POST[kno]',
`tel`='$_POST[tel]',
`pfr`='$_POST[pfr]',
`fss`='$_POST[fss]',
`foms`='$_POST[foms]',
`ustavkop`='$_POST[ustavkop]'
WHERE id = $_GET[id]" 
;
mysql_query($u) or die(mysql_error($link));
echo '<div class="alert alert-success"><strong>Удачно!</strong> Изменения успешно сохранены.</div>';
header('Location: index.php');
}
?>
<form method="POST"><div style="margin-top: 6px;"></div>
<div class="input-group">
<span class="input-group-addon">Краткое наименование:</span>
<input class="form-control" type="text" name="kn" value='<?php echo $person['kn']; ?>'  />
</div><div style="margin-top: 6px;"></div><div class="input-group">
<span class="input-group-addon">Полное наименование:</span>
<input class="form-control" type="text" name="pn" value='<?php echo $person['pn']; ?>'  />
</div><div style="margin-top: 6px;"></div><div class="input-group">
<span class="input-group-addon">ИНН:</span>
<input class="form-control" type="text" name="inn" value="<?php echo $person['inn']; ?>"  />
</div><div style="margin-top: 6px;"></div><div class="input-group">
<span class="input-group-addon">КПП:</span>
<input class="form-control" type="text" name="kpp" value="<?php echo $person['kpp']; ?>"  />
</div><div style="margin-top: 6px;"></div><div class="input-group">
<span class="input-group-addon">ОГРН:</span>
<input class="form-control" type="text" name="ogrn" value="<?php echo $person['ogrn']; ?>"  />
</div><div style="margin-top: 6px;"></div><div class="input-group">
<span class="input-group-addon">ОКПО:</span>
<input class="form-control" type="text" name="okpo" value="<?php echo $person['okpo']; ?>"  />
</div><div style="margin-top: 6px;"></div><div class="input-group">
<span class="input-group-addon">Дата образования:</span>
<input class="form-control" type="text" name="dataobr" value="<?php echo $person['dataobr']; ?>"  />
</div><div style="margin-top: 6px;"></div><div class="input-group">
<span class="input-group-addon">Дата ликвидации:</span>
<input class="form-control" type="text" name="datalik" value="<?php echo $person['datalik']; ?>"  />
</div><div style="margin-top: 6px;"></div><div class="input-group">
<span class="input-group-addon">Адрес:</span>
<input class="form-control" type="text" name="adres" value="<?php echo $person['adres']; ?>"  />
</div><div style="margin-top: 6px;"></div><div class="input-group">
<span class="input-group-addon">Код налогового органа:</span>
<input class="form-control" type="text" name="kno" value="<?php echo $person['kno']; ?>"  />
</div><div style="margin-top: 6px;"></div><div class="input-group">
<span class="input-group-addon">Телефон:</span>
<input class="form-control" type="text" name="tel" value="<?php echo $person['tel']; ?>"  />
</div><div style="margin-top: 6px;"></div><div class="input-group">
<span class="input-group-addon">Уставный капитал:</span>
<input class="form-control" type="text" name="ustavkop" value="<?php echo $person['ustavkop']; ?>"  />
</div><div style="margin-top: 6px;"></div><div class="input-group">
<span class="input-group-addon">ПФР:</span>
<input class="form-control" type="text" name="pfr" value="<?php echo $person['pfr']; ?>"  />
</div><div style="margin-top: 6px;"></div><div class="input-group">
<span class="input-group-addon">ФСС:</span>
<input class="form-control" type="text" name="fss" value="<?php echo $person['fss']; ?>"  />
</div><div style="margin-top: 6px;"></div><div class="input-group">
<span class="input-group-addon">ФОМС:</span>
<input class="form-control" type="text" name="foms" value="<?php echo $person['foms']; ?>"  />
</div><div style="margin-top: 6px;"></div>
<div class="input-group">
<input type="submit" name="submit" value="Сохранить" id="submitSuggestion" class="btn btn-success" style="float: right;"/><br></div>
</form>

</div>
<div class="col-md-2">
<button type="button" style="padding: 5px; width: 100%; " class="btn btn-success col-md-12" data-toggle="modal" data-target="#myModal">Добавить номер телефона</button>
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header" style="
    font-size: 14px;
    font-weight: bold;
">
        Новый номер телефона
      </div>
      <div class="modal-body">
        <form method="POST"><div style="margin-top: 6px;"></div>
<input class="form-control col-md-12" type="text" name="tel" value="" style="
    width: 100%;
    margin-bottom: 10px;
" />
<div style="margin-top: 6px;"></div>
<div class="input-group">
<input type="submit" name="submittel" value="Сохранить" class="btn btn-success" style="float: right;"/>&nbsp;&nbsp;&nbsp;<button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button></div>
</form>
<?php
include 'conn.php';
if(isset($_POST['submittel'])){
$u = "INSERT INTO `user40727_iholmes`.`tel` 
(
`ogrn`,
`tel`
) VALUES (
'".$person['ogrn']."',
'$_POST[tel]'
)";
mysql_query($u) or die(mysql_error($link));
echo '<div class="alert alert-success"><strong>Удачно!</strong> ИП/Орагницация успешно созданна.</div>';

}
?>

      </div>

    </div>
  </div>
</div>
<br>
<button type="button" style="padding: 5px; width: 100%; " class="btn btn-primary col-md-12" data-toggle="modal" data-target="#myModal2">Добавить контактное лицо</button>
<div class="modal fade" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModal2Label" aria-hidden="true" style="display: none;">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header" style="
    font-size: 14px;
    font-weight: bold;
">
        Новое контактное лицо
      </div>
      <div class="modal-body">
<script language="javascript">
      
      $(document).ready(function()
      {
$("#inn").blur(function()
{
$("#msgbox").removeClass().addClass('messagebox').text('Проверка...').fadeIn("slow");
$("#submitbox").removeClass('').addClass('btn').text('Проверка...').fadeIn("slow");



$.post("user_availability.php",{ inn:$(this).val() } ,function(data){


if(data=='yes') {

$("#msgbox").fadeTo(200,0.1,function() {
$(this).html('ИНН доступен для регистрации').addClass('messageboxok').fadeTo(900,1);
});

$("#submitbox").fadeTo(200,0.1,function() {
$(this).html('Создать / Прикрепить').addClass('btn btn-success').fadeTo(900,1); 
});

}else{


$('#name').fadeTo(200,0.1,function(){
                $(this).val(data).fadeTo(900,1);
            });


$("#msgbox").fadeTo(200,0.1,function() { 
$(this).html('Контактное лицо с таким ИНН существует').addClass('messageboxerror').fadeTo(900,1);
});

$("#submitbox").fadeTo(200,0.1,function() { 
$(this).html('Прикрепить').addClass('btn btn-success').fadeTo(900,1);
});

$("#fiobox").fadeTo(200,0.1,function() {
$(this).html('Прикрепить').addClass('').fadeTo(900,1); 
});





}

});

});
});
</script>
        <form method="POST"><div style="margin-top: 6px;"></div>
<div class="input-group">
<span class="input-group-addon">ИНН:</span>
<input class="form-control" name="inn" type="text" id="inn" value="" maxlength="15" > 
<span class="input-group-addon"><span id="msgbox" style="display:none"></span>
</div>
<div style="margin-top: 6px;"></div>
<div class="input-group" >
<span class="input-group-addon" >ФИО:</span>
<input class="form-control" name="name" type="text" id="name" value=""> 
</div>
<div style="margin-top: 6px;"></div>

<div class="input-group">
<span class="input-group-addon">Телефон:</span>
<input class="form-control" type="text" name="tel">
</div>
<div style="margin-top: 6px;"></div>
<div class="input-group">
<span class="input-group-addon">Город:</span>
<input class="form-control" type="text" name="gorod">
</div>
<div style="margin-top: 6px;"></div>
<div class="input-group">
<span class="input-group-addon">Удица:</span>
<input class="form-control" type="text" name="ulica">
</div>
<div style="margin-top: 6px;"></div>
<div class="input-group">
<span class="input-group-addon">Дом:</span>
<input class="form-control" type="text" name="dom">
</div>
<div style="margin-top: 6px;"></div>
<div class="input-group">

<button type="button" name="submittel2" id="submitbox"  style="display:none;" ></button>
</div>
</form>
<?php
include 'conn.php';
if(isset($_POST['submittel2'])){
$u2 = "INSERT INTO `user40727_iholmes`.`lico` 
(
`name`,
`gorod`,
`tel`,
`ulica`,
`dom`,
`inn`,
`ogrn`
) VALUES (
'$_POST[name]',
'$_POST[gorod]',
'$_POST[tel]',
'$_POST[ulica]',
'$_POST[dom]',
'$_POST[inn]',
'".$person['ogrn']."'
)";
mysql_query($u2) or die(mysql_error($link));

}
?>

      </div>

    </div>
  </div>
</div>
<br>



<a href="organ.php?id=<?php echo $_GET['id'];?>"><button type="button" style="padding: 5px; width: 100%; " class="btn btn-info col-md-12" data-toggle="modal" data-target="#myModal3">Деятельность организации</button></a>


<br>
<br>
<br>
 
Доп. номера телефонов:
<br>

	<table class="table table-striped">
<?php
						$query = mysql_query("SELECT * from tel WHERE ogrn = ".$person['ogrn']);	
							while($row = mysql_fetch_array($query)) {
								echo '<tr><td>';
								echo $row['tel'];
								echo '</td>';
								echo '<td>';
								echo '<a href="deldoptel.php?id=',$row['id'],'"><span class="glyphicon glyphicon-trash"></span></a>';
								echo '</td></tr>';
						}
					?>
				</table>
<br>
<br>
 
Контактные лица:
<br>

	<table class="table table-striped">
<?php
						$query = mysql_query("SELECT * from lico WHERE ogrn = ".$person['ogrn']);	
							while($row = mysql_fetch_array($query)) {
								echo '<tr><td><strong>';
								echo $row['name'];
								echo '</strong><br>';
								echo $row['dela'];
								echo '</td>';
								echo '<td>';
								echo '<a href="uvoldoplico.php?id=',$row['id'],'"><span class="glyphicon glyphicon-eye-close"></span></a>';
								echo '</td>';
								echo '<td>';
								echo '<a href="deldoplico.php?id=',$row['id'],'"><span class="glyphicon glyphicon-trash"></span></a>';
								echo '</td></tr>';
						}
					?>
				</table>

				
				<br>
				<br>
				<br>
				
				Выписки:
<br>

	<table class="table table-striped">
<?php
						$query = mysql_query("SELECT * from vipiska WHERE ogrn = ".$_GET['id']);	
							while($row = mysql_fetch_array($query)) {
								echo '<tr><td>';
								echo $row['data'];
				 				echo '</td>';
								echo '<td>';
								echo '<a href="vipiska/',$row['url'],'"><span class="glyphicon glyphicon-cloud-download"></span></a>';
				 				echo '</td>';
								echo '<td>';
								echo '<a href="delvipiska.php?id=',$row['id'],'"><span class="glyphicon glyphicon-trash"></span></a>';
								echo '</td></tr>';
						}
					?>
				</table>

				
				<br>
				<br>
				<br>
				
<center style="
    padding: 5px;
    background: #F5F5F5;
    border: 1px solid;
    padding-top: 0;
">

<p><div class="alert alert-warning">Выбирите файл для загрузки:</div><br />

<form enctype="multipart/form-data" method="post" >
<input type="file" name="userfile" style="
width: 100%; 
"/>
<br/>
<input  type="submit" value="Загрузить выписку" name="button" style="
height: 29px;
width: 100%; 
">
</form>
<?php
//Выполняем код только при нажатии на кнопку
if($_POST['button']== true){



//Для начала проверим не пытаются ли нам загрузить файл с расширением .php и т.д
$path = array(".php",".php4",".php3",".phtml",".pl");
 foreach ($path as $item){
 //Проверяем регулярным выражением регистр
  if(preg_match("/$item\$/i", $_FILES['userfile']['name'])) {
   echo "Разрешено загружать, только картинки<br />";
   exit();
  }
 }
//Проверим на ошибки, если они есть прекращаем скрипт и создаем отчет
if($_FILES['userfile']['error'] != 0){
//Создаем файл и записываем  него код ошибки, а так же удаляем временный файл
$error = fopen("error/error.dat","wb");
if(fwrite($error,$_FILES['userfile']['error']) == false){
	echo "Ошибка записи в файл!!!";
	exit();
}else
{
	echo "<b>error.dat</b> - был успешно создан! &nbsp;&nbsp;<a href='error/error.inc'>Смотреть код ошибки</a>";
	//Удаляем временный файл
	unlink($_FILES['userfile']['tmp_name']);
	//выходим
	exit();
}
fclose($error);
}
//Определяем директорию, куда будем загружать изображения
$dir = 'vipiska/';
$file = $dir.basename($_FILES['userfile']['name']);

$sql = "INSERT INTO `user40727_iholmes`.`vipiska` (`ogrn`,`url`,`data`) VALUES ('$_GET[id]','" . basename($_FILES['userfile']['name']) . "','". date("d.m.Y в H:i") ."')";
$query = mysql_query($sql);

if (move_uploaded_file($_FILES['userfile']['tmp_name'], $file)) {
    echo '<br><div class="alert alert-success"> Файл успешно загружен.</div>';
} else {
    echo "Произошла ошибка";
    exit;
}
}

?>
</center>
 </div>

</div>

</div>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
</body>
</html>