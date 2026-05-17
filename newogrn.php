<?php
# подключаем конфиг
include 'conf.php';# проверка авторизации
$q = "SELECT * FROM ogrn WHERE id=(SELECT MAX(id) FROM ogrn)";
$result = mysql_query($q);
$person = mysql_fetch_array($result);
$q2 = "SELECT * FROM klient WHERE id=(SELECT MAX(id) FROM klient)";
$result2 = mysql_query($q2);
$person2 = mysql_fetch_array($result2);
if (isset($_COOKIE['id']) and isset($_COOKIE['hash']))
{
$userdata = mysql_fetch_assoc(mysql_query("SELECT * FROM users WHERE users_id = '".intval($_COOKIE['id'])."' LIMIT 1"));if(($userdata['users_hash'] !== $_COOKIE['hash']) or ($userdata['users_id'] !== $_COOKIE['id']))
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
<link href="css/bootstrap.min.css" rel="stylesheet">
<link href="https://dadata.ru/static/css/lib/suggestions-4.7.css" type="text/css" rel="stylesheet" />

</head>
<body>
<?php
# шапка
include 'header.php';
?>
<div class="container" style="margin-top: 40px;"><div class="row"><?php
if(isset($_POST['submit'])){
$ogrn = "INSERT INTO `ogrn` (
`d`, 
`m`, 
`y`, 
`users`, 
`inn`, 
`kpp`, 
`naim`, 
`ogrn`, 
`tip`, 
`region_type`, 
`uridadress`, 
`fakadress`, 
`budjet_ogrn`, 
`r_schet`, 
`bank`, 
`k_schet`, 
`bik`, 
`adress`, 
`city`, 
`primechan`
) VALUES (
'".date('d')."', 
'".date('m')."', 
'".date('Y')."', 
'".$userdata['users_id']."', 
'$_POST[inn]', 
'$_POST[kpp]', 
'$_POST[naim]', 
'$_POST[ogrn]', 
'$_POST[type_org]', 
'$_POST[region_org]', 
'$_POST[uridadress]', 
'$_POST[fakadress]', 
'$_POST[budjet_ogrn]', 
'$_POST[r_schet]', 
'$_POST[bank]', 
'$_POST[k_schet]', 
'$_POST[bik]', 
'$_POST[adress]', 
'$_POST[city]', 
'$_POST[info]'
)";
mysql_query($ogrn) or die(mysql_error($links));
$lico = "INSERT INTO `klient`(`fio`, `dol`, `tel`, `email`, `pol`, `naosnovanii`) VALUES ('$_POST[fio]', '$_POST[dol]', '$_POST[tel]', '$_POST[email]', '$_POST[pol]', '$_POST[naosnovanii]')";
mysql_query($lico) or die(mysql_error($links));
$url2 = $person2['id'] + 1;
$url = $person['id'] + 1;
$ogrnlico = "INSERT INTO `klient_ogrn`(`idkli`, `klient`)  VALUES ('".$url."', '".$url2."')";
mysql_query($ogrnlico) or die(mysql_error($links));



$rand = rand(100000, 999999);
/*
$user = "INSERT INTO `users`(`tip`, `f_name`, `users_login`, `tel`, `users_password`)  VALUES ('88', '$_POST[fio]', '$_POST[tel]', '$_POST[tel]', '".md5(md5($rand))."')";
mysql_query($user) or die(mysql_error($links));

$body=file_get_contents("http://sms.ru/sms/send?api_id=513439c3-5ece-a954-e5b2-31b36fe77cbf&to=79097565645&text=".urlencode(iconv("utf-8","utf-8","Новый клиент: Пароль: ".$rand." ")));
*/

echo '<script type="text/javascript">'; 
echo 'window.location.href="/kartklient.php?id='.$url.'";'; 
echo '</script>';
echo '';
}
?><form action="" method="post">
<div class="col-md-12">
<strong><h4 style="border-bottom: 1px #333 solid; font-weight: bold;margin-top: 25px;">Новая организация: <?php echo $person['id']; ?></h4></strong>
</div>

<?php 
$qewrerf = "SELECT * from ogrn WHERE inn='$_GET[inn]' AND kpp='$_GET[kpp]'";
$gfhghjg = mysql_query($qewrerf);
$person54yt = mysql_fetch_array($gfhghjg);

$result = mysql_query("SELECT count(*) from ogrn WHERE inn='$_GET[inn]' AND kpp='$_GET[kpp]'");
if(mysql_result($result, 0) == 1){
echo '<script type="text/javascript">'; 
echo 'window.location.href="/kartklient.php?id='.$person54yt['id'].'";'; 
echo '</script>';
}if(mysql_result($result, 0) > 1){
echo '<script type="text/javascript">'; 
echo 'window.location.href="/admin_dela.php?naim=&inn='.$_GET['inn'].'&kpp='.$_GET['kpp'].'&ogrn=&email=";'; 
echo '</script>';
}
?>

<div class="input-group" style="padding: 0 5px;">
<span class="input-group-addon"style="background: #fff;font-size: 20px;padding: 0 10px;"><span class="icon-search"></span></span>
<input class="form-control" id="party" name="party" value="<?php echo $_GET['inn']; ?>" type="text" style="box-sizing: border-box; padding-left: 7px; padding-right: 7px;height: 36px;border-color: #ccc;border-bottom-right-radius: 4px;border-top-right-radius: 4px;font-size: 18px;"> 
</div><div style="margin-top: 6px;"></div>
<div class="col-md-6" style="margin-top: 10px;">
<div style="background: #ddd; padding: 6px; padding-top: 0;border-radius: 5px;">
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script type="text/javascript" src="https://dadata.ru/static/js/lib/jquery.suggestions-4.7.min.js"></script>
<strong>
<h4 style="border-bottom: 1px #FFF solid; font-weight: bold;background:#5bc0de;padding: 4px 6px;border-radius: 3px;border-bottom-left-radius: 0;border-bottom-right-radius: 0;color: #fff;margin: 0px -5px 6px -5px;"><span class="icon-globe"></span> Данные организации</h4></strong>
<div class="input-group">
<span class="input-group-addon">Наименование:</span>
<input class="form-control" name="naim" type="text" id="naim" > 
</div><div style="margin-top: 6px;"></div>
<div class="input-group">
<span class="input-group-addon">ИНН:</span>
<input class="form-control" name="inn" type="text" id="inn" value="<?php echo $_GET['inn']; ?>" > 
</div><div style="margin-top: 6px;"></div>
<div id="kpp12" class="input-group">
<span class="input-group-addon">КПП:</span>
<input class="form-control" name="kpp" type="text" id="kpp" value="<?php echo $_GET['kpp']; ?>"> 
</div>
<div style="margin-top: 6px;"></div>
<div class="input-group">
<span class="input-group-addon">ОГРН:</span>
<input class="form-control" name="ogrn" type="text" id="ogrn" value=""> 
</div>
<div style="margin-top: 6px;"></div>
<div class="input-group">
<span class="input-group-addon">Юридический адрес:</span>
<input class="form-control" name="uridadress" type="text" id="uridadress" value=""> 
</div>
<div style="margin-top: 6px;"></div>
<div class="input-group">
<span class="input-group-addon">Фактический адрес:</span>
<input class="form-control" name="fakadress" type="text" id="fakadress" value=""> 
</div>
<div class="input-group">
    <span class="input-group-addon">Регион ЮЛ:</span>
    <select id="regionOrg" class="form-control"  name="region_org">
        <option value=""></option>
        <option value="26">Ставропольский край</option>
        <option value="01">Республика Адыгея (Адыгея)</option>
        <option value="04">Республика Алтай</option>
        <option value="02">Республика Башкортостан</option>
        <option value="03">Республика Бурятия</option>
        <option value="05">Республика Дагестан</option>
        <option value="06">Республика Ингушетия</option>
        <option value="07">Кабардино-Балкарская Республика</option>
        <option value="08">Республика Калмыкия</option>
        <option value="09">Карачаево-Черкесская Республика</option>
        <option value="10">Республика Карелия</option>
        <option value="11">Республика Коми</option>
        <option value="12">Республика Марий Эл</option>
        <option value="13">Республика Мордовия</option>
        <option value="14">Республика Саха (Якутия)</option>
        <option value="15">Республика Северная Осетия — Алания</option>
        <option value="16">Республика Татарстан (Татарстан)</option>
        <option value="17">Республика Тыва</option>
        <option value="18">Удмуртская Республика</option>
        <option value="19">Республика Хакасия</option>
        <option value="20">Чеченская Республика</option>
        <option value="21">Чувашская Республика — Чувашия</option>
        <option value="22">Алтайский край</option>
        <option value="23">Краснодарский край</option>
        <option value="24">Красноярский край</option>
        <option value="59">Пермский край</option>
        <option value="25">Приморский край</option>
        <option value="27">Хабаровский край</option>
        <option value="28">Амурская область</option>
        <option value="29">Архангельская область</option>
        <option value="30">Астраханская область</option>
        <option value="31">Белгородская область</option>
        <option value="32">Брянская область</option>
        <option value="33">Владимирская область</option>
        <option value="34">Волгоградская область</option>
        <option value="35">Вологодская область</option>
        <option value="36">Воронежская область</option>
        <option value="37">Ивановская область</option>
        <option value="38">Иркутская область</option>
        <option value="39">Калининградская область</option>
        <option value="40">Калужская область</option>
        <option value="42">Кемеровская область — Кузбасс</option>
        <option value="43">Кировская область</option>
        <option value="44">Костромская область</option>
        <option value="45">Курганская область</option>
        <option value="46">Курская область</option>
        <option value="47">Ленинградская область</option>
        <option value="48">Липецкая область</option>
        <option value="49">Магаданская область</option>
        <option value="50">Московская область</option>
        <option value="51">Мурманская область</option>
        <option value="52">Нижегородская область</option>
        <option value="53">Новгородская область</option>
        <option value="54">Новосибирская область</option>
        <option value="55">Омская область</option>
        <option value="56">Оренбургская область</option>
        <option value="57">Орловская область</option>
        <option value="58">Пензенская область</option>
        <option value="60">Псковская область</option>
        <option value="61">Ростовская область</option>
        <option value="62">Рязанская область</option>
        <option value="63">Самарская область</option>
        <option value="64">Саратовская область</option>
        <option value="65">Сахалинская область</option>
        <option value="66">Свердловская область</option>
        <option value="67">Смоленская область</option>
        <option value="68">Тамбовская область</option>
        <option value="69">Тверская область</option>
        <option value="70">Томская область</option>
        <option value="71">Тульская область</option>
        <option value="72">Тюменская область</option>
        <option value="73">Ульяновская область</option>
        <option value="74">Челябинская область</option>
        <option value="76">Ярославская область</option>
        <option value="77">Москва</option>
        <option value="78">Санкт-Петербург</option>
        <option value="79">Еврейская автономная область</option>
        <option value="83">Ненецкий автономный округ</option>
        <option value="86">Ханты-Мансийский автономный округ — Югра</option>
        <option value="87">Чукотский автономный округ</option>
        <option value="89">Ямало-Ненецкий автономный округ</option>
        <option value="91">Республика Крым</option>
        <option value="92">Севастополь</option>
        <option value="99">Байконур</option>
    </select>
</div>
<div class="input-group">
    <span class="input-group-addon">Тип юр лица:</span>
    <select id="typeOrg" class="form-control"  name="type_org">
        <option value="1">ФЛ</option>
        <option value="2">ИП</option>
        <option value="3">Юр. лицо</option>
    </select>
</div>
<div style="margin-top: 6px;"></div>
<div class="input-group">
<span class="input-group-addon">Бюджетная организация:</span>
<select  class="form-control"  name="budjet_ogrn">
<option value="0">Нет</option>
<option value="1">Да</option>
</select>
</div>
</div>
<div style="background: #ddd; padding: 6px; padding-top: 0;margin-top: 10px;border-radius: 5px;"><strong><h4 style="border-bottom: 1px #FFF solid; font-weight: bold;background: #5D75A8;padding: 4px 6px;border-radius: 3px;border-bottom-left-radius: 0;border-bottom-right-radius: 0;color: #fff;margin: 0px -5px 6px -5px;padding-bottom: 4px;"><span class="icon-user4"></span> Руководитель</h4></strong><div class="input-group">
<span class="input-group-addon">ФИО:</span>
<input class="form-control" name="fio" type="text" id="fio" value="" style="box-sizing: border-box; border-color: rgb(204, 204, 204); border-bottom-right-radius: 4px; border-top-right-radius: 4px; padding-left: 7px;"> 
</div>
<div style="margin-top: 6px;"></div><div class="input-group">
<span class="input-group-addon">Должность:</span> 
<input id="dol" type="text" name="dol"class="form-control" />
</div>
<div style="margin-top: 6px;"></div>
<div class="input-group">
<span class="input-group-addon">На основании:</span>
<input class="form-control" name="naosnovanii" type="text" id="naosnovanii" value=""> 
</div>
<div style="margin-top: 6px;"></div>
<div class="input-group">
<span class="input-group-addon">Телефон:</span> 
<input  class="form-control" type="tel"  required pattern="[0-9_\-]{10}" placeholder="+7(___) ___ __ __" id="user_phone3" name="tel" />
</div>
<div style="margin-top: 6px;"></div>
<div class="input-group">
<span class="input-group-addon">E-mail:</span> 
<input id="email" class="form-control" type="text"name="email" />
</div>
<div style="margin-top: 6px;"></div>
<div class="input-group">
<div id="fullname-gender">
<label class="sgt-granular_label" style="font-size: 13px; font-weight: normal;">
<input type="radio" name="pol" id="fullname-gender-male" value="1" class="inline">
Мужской
</label>
<label class="sgt-granular_label" style="
font-size: 13px;
font-weight: normal;
">
<input type="radio" name="pol" id="fullname-gender-female" value="2" class="inline">
Женский
</label>
</div>
</div>
</div>
</div>
<div class="col-md-6" style="margin-top: 10px;">
<div style="background: #ddd; padding: 6px; padding-top: 0;border-radius: 5px;">
<strong><h4 style="border-bottom: 1px #FFF solid; font-weight: bold;background: #5FC964;padding: 4px 6px;border-radius: 3px;border-bottom-left-radius: 0;border-bottom-right-radius: 0;color: #fff;margin: 0px -5px 6px -5px;"><span class="glyphicon glyphicon-usd"></span> Банк</h4></strong>
<div class="input-group">
<span class="input-group-addon">Расчетный счет:</span>
<input class="form-control" name="r_schet" type="text" id="r_schet" value=""> 
</div>
<div style="margin-top: 6px;"></div>
<div class="input-group">
<span class="input-group-addon">БИК:</span> 
<input id="bik" type="text" size="8"name="bik"class="form-control" />
</div>
<div style="margin-top: 6px;"></div>
<div class="input-group">
<span class="input-group-addon">Банк:</span> 
<input id="bank" class="form-control" type="text" size="64" name="bank" />
</div>
<div style="margin-top: 6px;"></div>
<div class="input-group">
<span class="input-group-addon">Город:</span> 
<input id="city" class="form-control" type="text" size="64" name="city" />
</div>
<div style="margin-top: 6px;"></div>
<div class="input-group">
<span class="input-group-addon">Адрес:</span> 
<input id="adress" class="form-control" type="text" size="64" name="adress" />
</div>
<div style="margin-top: 6px;"></div>
<div class="input-group">
<span class="input-group-addon">Корр. cчет:</span> 
<input id="k_schet" class="form-control" type="text" size="20" name="k_schet" />
</div>
</div>
<div style="background: #ddd; margin-top: 10px; padding: 6px; padding-top: 0;border-radius: 5px;">
<strong><h4 style="border-bottom: 1px #FFF solid; font-weight: bold;background:#999;padding: 4px 6px;border-radius: 3px;border-bottom-left-radius: 0;border-bottom-right-radius: 0;color: #fff;margin: 0px -5px 6px -5px;"><span class=" icon-quotes-left"></span> Дополнительная информация</h4></strong>
<div>
<textarea class="form-control" rows="9" name="info" type="text" id="info" value="" > </textarea>
</div>
</div>
</div>
<div class="col-md-12" style=" margin-top: 10px; margin-bottom: 20px;">
<input type="submit" name="submit" value="Зарегестрировать" class="btn btn-primary" role="button"/>
</div>
</div>
</div>
</form>

<script type="text/javascript">
const regions = {
    "RU-AD": {"code": "01", "name": "Республика Адыгея (Адыгея)"},
    "RU-AL": {"code": "04", "name": "Республика Алтай"},
    "RU-BA": {"code": "02", "name": "Республика Башкортостан"},
    "RU-BU": {"code": "03", "name": "Республика Бурятия"},
    "RU-DA": {"code": "05", "name": "Республика Дагестан"},
    "RU-IN": {"code": "06", "name": "Республика Ингушетия"},
    "RU-KB": {"code": "07", "name": "Кабардино-Балкарская Республика"},
    "RU-KL": {"code": "08", "name": "Республика Калмыкия"},
    "RU-KC": {"code": "09", "name": "Карачаево-Черкесская Республика"},
    "RU-KR": {"code": "10", "name": "Республика Карелия"},
    "RU-KO": {"code": "11", "name": "Республика Коми"},
    "RU-ME": {"code": "12", "name": "Республика Марий Эл"},
    "RU-MO": {"code": "13", "name": "Республика Мордовия"},
    "RU-SA": {"code": "14", "name": "Республика Саха (Якутия)"},
    "RU-SE": {"code": "15", "name": "Республика Северная Осетия — Алания"},
    "RU-TA": {"code": "16", "name": "Республика Татарстан (Татарстан)"},
    "RU-TY": {"code": "17", "name": "Республика Тыва"},
    "RU-UD": {"code": "18", "name": "Удмуртская Республика"},
    "RU-KK": {"code": "19", "name": "Республика Хакасия"},
    "RU-CE": {"code": "20", "name": "Чеченская Республика"},
    "RU-CU": {"code": "21", "name": "Чувашская Республика — Чувашия"},
    "RU-ALT": {"code": "22", "name": "Алтайский край"},
    "RU-KDA": {"code": "23", "name": "Краснодарский край"},
    "RU-KYA": {"code": "24", "name": "Красноярский край"},
    "RU-PER": {"code": "59", "name": "Пермский край"},
    "RU-PRI": {"code": "25", "name": "Приморский край"},
    "RU-STA": {"code": "26", "name": "Ставропольский край"},
    "RU-KHA": {"code": "27", "name": "Хабаровский край"},
    "RU-AMU": {"code": "28", "name": "Амурская область"},
    "RU-ARK": {"code": "29", "name": "Архангельская область"},
    "RU-AST": {"code": "30", "name": "Астраханская область"},
    "RU-BEL": {"code": "31", "name": "Белгородская область"},
    "RU-BRY": {"code": "32", "name": "Брянская область"},
    "RU-VLA": {"code": "33", "name": "Владимирская область"},
    "RU-VGG": {"code": "34", "name": "Волгоградская область"},
    "RU-VLG": {"code": "35", "name": "Вологодская область"},
    "RU-VOR": {"code": "36", "name": "Воронежская область"},
    "RU-IVA": {"code": "37", "name": "Ивановская область"},
    "RU-IRK": {"code": "38", "name": "Иркутская область"},
    "RU-KGD": {"code": "39", "name": "Калининградская область"},
    "RU-KLU": {"code": "40", "name": "Калужская область"},
    "RU-KEM": {"code": "42", "name": "Кемеровская область — Кузбасс"},
    "RU-KIR": {"code": "43", "name": "Кировская область"},
    "RU-KOS": {"code": "44", "name": "Костромская область"},
    "RU-KGN": {"code": "45", "name": "Курганская область"},
    "RU-KRS": {"code": "46", "name": "Курская область"},
    "RU-LEN": {"code": "47", "name": "Ленинградская область"},
    "RU-LIP": {"code": "48", "name": "Липецкая область"},
    "RU-MAG": {"code": "49", "name": "Магаданская область"},
    "RU-MOS": {"code": "50", "name": "Московская область"},
    "RU-MUR": {"code": "51", "name": "Мурманская область"},
    "RU-NIZ": {"code": "52", "name": "Нижегородская область"},
    "RU-NGR": {"code": "53", "name": "Новгородская область"},
    "RU-NVS": {"code": "54", "name": "Новосибирская область"},
    "RU-OMS": {"code": "55", "name": "Омская область"},
    "RU-ORE": {"code": "56", "name": "Оренбургская область"},
    "RU-ORL": {"code": "57", "name": "Орловская область"},
    "RU-PNZ": {"code": "58", "name": "Пензенская область"},
    "RU-PSK": {"code": "60", "name": "Псковская область"},
    "RU-ROS": {"code": "61", "name": "Ростовская область"},
    "RU-RYA": {"code": "62", "name": "Рязанская область"},
    "RU-SAM": {"code": "63", "name": "Самарская область"},
    "RU-SAR": {"code": "64", "name": "Саратовская область"},
    "RU-SAK": {"code": "65", "name": "Сахалинская область"},
    "RU-SVE": {"code": "66", "name": "Свердловская область"},
    "RU-SMO": {"code": "67", "name": "Смоленская область"},
    "RU-TAM": {"code": "68", "name": "Тамбовская область"},
    "RU-TVE": {"code": "69", "name": "Тверская область"},
    "RU-TOM": {"code": "70", "name": "Томская область"},
    "RU-TUL": {"code": "71", "name": "Тульская область"},
    "RU-TYU": {"code": "72", "name": "Тюменская область"},
    "RU-ULY": {"code": "73", "name": "Ульяновская область"},
    "RU-CHE": {"code": "74", "name": "Челябинская область"},
    "RU-YAR": {"code": "76", "name": "Ярославская область"},
    "RU-MOW": {"code": "77", "name": "Москва"},
    "RU-SPE": {"code": "78", "name": "Санкт-Петербург"},
    "RU-YEV": {"code": "79", "name": "Еврейская автономная область"},
    "RU-NEN": {"code": "83", "name": "Ненецкий автономный округ"},
    "RU-KHM": {"code": "86", "name": "Ханты-Мансийский автономный округ — Югра"},
    "RU-CHU": {"code": "87", "name": "Чукотский автономный округ"},
    "RU-YAN": {"code": "89", "name": "Ямало-Ненецкий автономный округ"}
}

$(document).ready(function(){
$("input[id='bik']").keyup(function(){
$.ajax({
dataType: 'json',
url: 'https://htmlweb.ru/service/api.php?bic='+$("input[id='bik']").val()+'&json',
success: function(jsondata){$('#bank').val(jsondata.name.replace(/&quot;/g,'"')); 
$('#city').val(jsondata.city.replace(/&quot;/g,'"'));
$('#adress').val(jsondata.adress.replace(/&quot;/g,'"'));
$('#k_schet').val(jsondata.ks.replace(/&quot;/g,'"'));
}
});
});
});

$("#fio").suggestions({
serviceUrl: "https://dadata.ru/api/v2",
token: "f3572f41a27b229dbf3d42366fc83044ab78f15c",
type: "NAME",
/* Вызывается, когда пользователь выбирает одну из подсказок */
onSelect: function (suggestion) {
var data = suggestion.data;
 
if (data.gender == 'MALE'){
$("#fullname-gender-male").attr('checked',true)
}if (data.gender == 'FEMALE') {
$("#fullname-gender-female").attr('checked',true)
}if (data.gender == 'UNKNOWN') {
}


}
});

$("#party").suggestions({
serviceUrl: "https://dadata.ru/api/v2",
token: "f3572f41a27b229dbf3d42366fc83044ab78f15c",
type: "PARTY",
count: 7,
onSelect: showSelected
});

function join(arr /*, separator */) {
var separator = arguments.length > 1 ? arguments[1] : ", ";
return arr.filter(function(n){return n}).join(separator);
}function showSelected(suggestion) {
var party = suggestion.data;
 
$("#tip").val(
join([party.opf.full + ' "' + party.name.full+'"'], " ")
);
if(suggestion.data.opf.short == null){
	$("#naim").val(suggestion.value);
}else{
    if (party.type === 'LEGAL') {
	$("#naim").val(suggestion.data.opf.short+' "'+suggestion.data.name.full+'"');
    } else {
        $("#naim").val(suggestion.data.opf.short+' '+suggestion.data.name.full);
    }
}
$("#uridadress").val(join([party.address && party.address.value || ""], " "));
$("#ogrn").val(join([party.ogrn], " "));
$("#kpp").val(join([party.kpp], " "));
console.log(suggestion.data.fio)
if (suggestion.data.hasOwnProperty('management')) {
    $("#fio").val(suggestion.data.management.name);
    $("#dol").val(suggestion.data.management.post);
} else if (suggestion.data.hasOwnProperty('fio')) {

    $("#fio").val(suggestion.data.fio.surname+' '+suggestion.data.fio.name+' '+suggestion.data.fio.patronymic);
    $("#dol").val(suggestion.data.opf.short);
}

console.log(party)
if (party.type === 'LEGAL') {
    $("#typeOrg").val("3");
} else if (party.type === 'INDIVIDUAL') {
    $("#typeOrg").val("2");
} else {
    $("#typeOrg").val("1");
}

if (regions.hasOwnProperty(party.address.data.region_iso_code)) {
    $("#regionOrg").val(regions[party.address.data.region_iso_code].code);
} else {
    $("#regionOrg").val('26'); // Пример установки стандартного значения
}


}

$("input[id='inn']").ready(function kpp13(){
number = $("input[id='inn']").val().length;
if (number==12){
document.getElementById("kpp12").style.display="none";
}else{
document.getElementById("kpp12").style.display="";
}
});
</script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    var submitButton = document.querySelector('[name="submit"]');
    var innField = document.getElementById('inn');

    submitButton.addEventListener('click', function(event) {
        var innValue = innField.value.trim(); // Удаление пробелов в начале и в конце строки
        innField.value = innValue; // Обновление значения поля ввода

        // Проверка длины значения поля
        if (innValue.length !== 10 && innValue.length !== 12) {
            innField.style.borderColor = 'red'; // Выделение поля красной рамкой
            event.preventDefault(); // Предотвращение отправки формы
        } else {
            innField.style.borderColor = ''; // Удаление выделения, если условие выполняется
        }
    });
});
</script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
</body>
</html>
