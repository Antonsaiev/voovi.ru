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
<link href="blog.css" rel="stylesheet">
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

<div class="col-md-10">
<strong><h4 style="margin-top: 10px; font-weight: bold; border-bottom: 1px #333 solid;">Обработка заявки</h4></strong>
<?php
include 'conn.php';
if(isset($_POST['submit'])){
$u = "INSERT INTO `user40727_iholmes`.`organizacii` 
(
`data`,
`obnovl`,
`kn`,
`pn`,
`inn`,
`kpp`,
`ogrn`,
`okpo`,
`dataobr`,
`datalik`,
`adres`,
`kno`,
`tel`,
`pfr`,
`fss`,
`foms`,
`ustavkop`,
`serch`
) VALUES (
'".date('d.m.Y в H:i')."',
'".date('d.m.Y в H:i')."',
'$_POST[kn]',
'$_POST[pn]', 
'$_POST[inn]',
'$_POST[kpp]',
'$_POST[ogrn]',
'$_POST[okpo]', 
'$_POST[dataobr]',
'$_POST[datalik]',
'$_POST[adres]',
'$_POST[kno]',
'$_POST[tel]',
'$_POST[pfr]',
'$_POST[fss]',
'$_POST[foms]',
'$_POST[ustavkop]',
'$_POST[kn] ИНН: $_POST[inn] ОГРН: $_POST[ogrn]'
)";
mysql_query($u) or die(mysql_error($link));
echo '<div class="alert alert-success"><strong>Удачно!</strong> ИП/Орагницация успешно созданна.</div>';

}
?>
<form method="POST"><div style="margin-top: 6px;"></div>
<div class="input-group">
<span class="input-group-addon">Краткое наименование:</span>
<input class="form-control" type="text" name="kn" value="<?php echo $person['kn']; ?>"  />
</div><div style="margin-top: 6px;"></div><div class="input-group">
<span class="input-group-addon">Полное наименование:</span>
<input class="form-control" type="text" name="pn" value="<?php echo $person['pn']; ?>"  />
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
<input class="form-control" type="text" name="nko" value="<?php echo $person['kno']; ?>"  />
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
<input type="submit" name="submit" value="Создать" id="submitSuggestion" class="btn btn-success" style="float: right;"/><br></div>
</form>

</div>


</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="/js/bootstrap.min.js"></script>
</body>
</html>