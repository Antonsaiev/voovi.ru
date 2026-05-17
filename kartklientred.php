<?php
# подключаем конфиг
include 'conf.php';# проверка авторизации
$q = "SELECT * FROM ogrn WHERE id=$_GET[id]";
$result = mysql_query($q);
$person = mysql_fetch_array($result);
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
<html>
<head> 
<meta charset="utf-8">
<meta http-equiv="Content-Style-Type" content="text/css" />
<link href="css/bootstrap.min.css" rel="stylesheet">

</head>
<body>
<?php
# шапка
include 'header.php';
?>
<div class="container" style="margin-top: 40px;"><div class="row"><?php
if(isset($_POST['submit'])){
$ogrn = "UPDATE `ogrn` SET 
`inn`='$_POST[inn]',
`kpp`='$_POST[kpp]',
`naim`='$_POST[naim]',
`ogrn`='$_POST[ogrn]',
`tip`='$_POST[type_org]',
`region_type`='$_POST[region_org]',
`budjet_ogrn`='$_POST[budjet_ogrn]',
`uridadress`='$_POST[uridadress]',
`fakadress`='$_POST[fakadress]',
`r_schet`='$_POST[r_schet]',
`bank`='$_POST[bank]',
`k_schet`='$_POST[k_schet]',
`bik`='$_POST[bik]',
`city`='$_POST[city]',
`mark`='$_POST[mark]',
`adress`='$_POST[adress]',
`primechan`='$_POST[info]'
WHERE id = $_GET[id]";
mysql_query($ogrn) or die(mysql_error($links));
echo '<script type="text/javascript">'; 
echo 'window.location.href="/kartklient.php?id='.$_GET['id'].'";'; 
echo '</script>';
}
?>
<form action="" method="post">
<div class="col-md-12">
<strong><h4 style="border-bottom: 1px #333 solid; font-weight: bold;margin-top: 25px;">Новая организация: <?php echo $person['id']; ?></h4></strong>
</div>
<div class="input-group" style="padding: 0 5px;">
<span class="input-group-addon"style="background: #fff;font-size: 20px;padding: 0 10px;"><span class="icon-search"></span></span>
<input class="form-control" id="party" name="party" value="<?php echo $_GET['inn']; ?>" type="text" style="box-sizing: border-box; padding-left: 7px; padding-right: 7px;height: 36px;border-color: #ccc;border-bottom-right-radius: 4px;border-top-right-radius: 4px;font-size: 18px;">
</div><div style="margin-top: 6px;"></div>
<div class="col-md-6" style="margin-top: 10px;">
<div style="background: #ddd; padding: 6px; padding-top: 0;border-radius: 5px;">
<strong>
<h4 style="border-bottom: 1px #FFF solid; font-weight: bold;background:#5bc0de;padding: 4px 6px;border-radius: 3px;border-bottom-left-radius: 0;border-bottom-right-radius: 0;color: #fff;margin: 0px -5px 6px -5px;"><span class="icon-globe"></span> Данные организации</h4></strong>
<div class="input-group">
<span class="input-group-addon">Наименование:</span>
<input class="form-control" name="naim" type="text" id="naim" value='<?php echo $person['naim']; ?>'>
</div><div style="margin-top: 6px;"></div>
<div class="input-group">
<span class="input-group-addon">ИНН:</span>
<input class="form-control" name="inn" type="text" id="inn" value='<?php echo $person['inn']; ?>' > 
</div><div style="margin-top: 6px;"></div>
<div id="kpp12" class="input-group">
<span class="input-group-addon">КПП:</span>
<input class="form-control" name="kpp" type="text" id="kpp" value='<?php echo $person['kpp']; ?>'> 
</div>
<div style="margin-top: 6px;"></div>
<div class="input-group">
<span class="input-group-addon">ОГРН:</span>
<input class="form-control" name="ogrn" type="text" id="ogrn" value='<?php echo $person['ogrn']; ?>'> 
</div>
<div style="margin-top: 6px;"></div>
<div class="input-group">
<span class="input-group-addon">Юридический адрес:</span>
<input class="form-control" name="uridadress" type="text" id="uridadress" value='<?php echo $person['uridadress']; ?>'> 
</div>
<div style="margin-top: 6px;"></div>
<div class="input-group">
<span class="input-group-addon">Фактический адрес:</span>
<input class="form-control" name="fakadress" type="text" id="fakadress" value='<?php echo $person['fakadress']; ?>'> 
</div>
<div style="margin-top: 6px;"></div>
<div class="input-group">
    <span class="input-group-addon">Регион ЮЛ:</span>
    <select id="regionOrg" class="form-control"  name="region_org">
        <?php if (!$person['region_type']) {echo '<option selected value=""></option>';}?>
        <option<?php if ($person['region_type'] === '26') {echo ' selected';}?> value="26">Ставропольский край</option>
        <option<?php if ($person['region_type'] === '01') {echo ' selected';}?>  value="01">Республика Адыгея (Адыгея)</option>
        <option<?php if ($person['region_type'] === '04') {echo ' selected';}?>  value="04">Республика Алтай</option>
        <option<?php if ($person['region_type'] === '02') {echo ' selected';}?>  value="02">Республика Башкортостан</option>
        <option<?php if ($person['region_type'] === '03') {echo ' selected';}?>  value="03">Республика Бурятия</option>
        <option<?php if ($person['region_type'] === '05') {echo ' selected';}?>  value="05">Республика Дагестан</option>
        <option<?php if ($person['region_type'] === '06') {echo ' selected';}?>  value="06">Республика Ингушетия</option>
        <option<?php if ($person['region_type'] === '07') {echo ' selected';}?>  value="07">Кабардино-Балкарская Республика</option>
        <option<?php if ($person['region_type'] === '08') {echo ' selected';}?>  value="08">Республика Калмыкия</option>
        <option<?php if ($person['region_type'] === '09') {echo ' selected';}?>  value="09">Карачаево-Черкесская Республика</option>
        <option<?php if ($person['region_type'] === '10') {echo ' selected';}?>  value="10">Республика Карелия</option>
        <option<?php if ($person['region_type'] === '11') {echo ' selected';}?>  value="11">Республика Коми</option>
        <option<?php if ($person['region_type'] === '12') {echo ' selected';}?>  value="12">Республика Марий Эл</option>
        <option<?php if ($person['region_type'] === '13') {echo ' selected';}?>  value="13">Республика Мордовия</option>
        <option<?php if ($person['region_type'] === '14') {echo ' selected';}?>  value="14">Республика Саха (Якутия)</option>
        <option<?php if ($person['region_type'] === '15') {echo ' selected';}?>  value="15">Республика Северная Осетия — Алания</option>
        <option<?php if ($person['region_type'] === '16') {echo ' selected';}?>  value="16">Республика Татарстан (Татарстан)</option>
        <option<?php if ($person['region_type'] === '17') {echo ' selected';}?>  value="17">Республика Тыва</option>
        <option<?php if ($person['region_type'] === '18') {echo ' selected';}?>  value="18">Удмуртская Республика</option>
        <option<?php if ($person['region_type'] === '19') {echo ' selected';}?>  value="19">Республика Хакасия</option>
        <option<?php if ($person['region_type'] === '20') {echo ' selected';}?>  value="20">Чеченская Республика</option>
        <option<?php if ($person['region_type'] === '21') {echo ' selected';}?>  value="21">Чувашская Республика — Чувашия</option>
        <option<?php if ($person['region_type'] === '22') {echo ' selected';}?>  value="22">Алтайский край</option>
        <option<?php if ($person['region_type'] === '23') {echo ' selected';}?>  value="23">Краснодарский край</option>
        <option<?php if ($person['region_type'] === '24') {echo ' selected';}?>  value="24">Красноярский край</option>
        <option<?php if ($person['region_type'] === '59') {echo ' selected';}?>  value="59">Пермский край</option>
        <option<?php if ($person['region_type'] === '25') {echo ' selected';}?>  value="25">Приморский край</option>
        <option<?php if ($person['region_type'] === '27') {echo ' selected';}?>  value="27">Хабаровский край</option>
        <option<?php if ($person['region_type'] === '28') {echo ' selected';}?>  value="28">Амурская область</option>
        <option<?php if ($person['region_type'] === '29') {echo ' selected';}?>  value="29">Архангельская область</option>
        <option<?php if ($person['region_type'] === '30') {echo ' selected';}?>  value="30">Астраханская область</option>
        <option<?php if ($person['region_type'] === '31') {echo ' selected';}?>  value="31">Белгородская область</option>
        <option<?php if ($person['region_type'] === '32') {echo ' selected';}?>  value="32">Брянская область</option>
        <option<?php if ($person['region_type'] === '33') {echo ' selected';}?>  value="33">Владимирская область</option>
        <option<?php if ($person['region_type'] === '34') {echo ' selected';}?>  value="34">Волгоградская область</option>
        <option<?php if ($person['region_type'] === '35') {echo ' selected';}?>  value="35">Вологодская область</option>
        <option<?php if ($person['region_type'] === '36') {echo ' selected';}?>  value="36">Воронежская область</option>
        <option<?php if ($person['region_type'] === '37') {echo ' selected';}?>  value="37">Ивановская область</option>
        <option<?php if ($person['region_type'] === '38') {echo ' selected';}?>  value="38">Иркутская область</option>
        <option<?php if ($person['region_type'] === '39') {echo ' selected';}?>  value="39">Калининградская область</option>
        <option<?php if ($person['region_type'] === '40') {echo ' selected';}?>  value="40">Калужская область</option>
        <option<?php if ($person['region_type'] === '42') {echo ' selected';}?>  value="42">Кемеровская область — Кузбасс</option>
        <option<?php if ($person['region_type'] === '43') {echo ' selected';}?>  value="43">Кировская область</option>
        <option<?php if ($person['region_type'] === '44') {echo ' selected';}?>  value="44">Костромская область</option>
        <option<?php if ($person['region_type'] === '45') {echo ' selected';}?>  value="45">Курганская область</option>
        <option<?php if ($person['region_type'] === '46') {echo ' selected';}?>  value="46">Курская область</option>
        <option<?php if ($person['region_type'] === '47') {echo ' selected';}?>  value="47">Ленинградская область</option>
        <option<?php if ($person['region_type'] === '48') {echo ' selected';}?>  value="48">Липецкая область</option>
        <option<?php if ($person['region_type'] === '49') {echo ' selected';}?>  value="49">Магаданская область</option>
        <option<?php if ($person['region_type'] === '50') {echo ' selected';}?>  value="50">Московская область</option>
        <option<?php if ($person['region_type'] === '51') {echo ' selected';}?>  value="51">Мурманская область</option>
        <option<?php if ($person['region_type'] === '52') {echo ' selected';}?>  value="52">Нижегородская область</option>
        <option<?php if ($person['region_type'] === '53') {echo ' selected';}?>  value="53">Новгородская область</option>
        <option<?php if ($person['region_type'] === '54') {echo ' selected';}?>  value="54">Новосибирская область</option>
        <option<?php if ($person['region_type'] === '55') {echo ' selected';}?>  value="55">Омская область</option>
        <option<?php if ($person['region_type'] === '56') {echo ' selected';}?>  value="56">Оренбургская область</option>
        <option<?php if ($person['region_type'] === '57') {echo ' selected';}?>  value="57">Орловская область</option>
        <option<?php if ($person['region_type'] === '58') {echo ' selected';}?>  value="58">Пензенская область</option>
        <option<?php if ($person['region_type'] === '60') {echo ' selected';}?>  value="60">Псковская область</option>
        <option<?php if ($person['region_type'] === '61') {echo ' selected';}?>  value="61">Ростовская область</option>
        <option<?php if ($person['region_type'] === '62') {echo ' selected';}?>  value="62">Рязанская область</option>
        <option<?php if ($person['region_type'] === '63') {echo ' selected';}?>  value="63">Самарская область</option>
        <option<?php if ($person['region_type'] === '64') {echo ' selected';}?>  value="64">Саратовская область</option>
        <option<?php if ($person['region_type'] === '65') {echo ' selected';}?>  value="65">Сахалинская область</option>
        <option<?php if ($person['region_type'] === '66') {echo ' selected';}?>  value="66">Свердловская область</option>
        <option<?php if ($person['region_type'] === '67') {echo ' selected';}?>  value="67">Смоленская область</option>
        <option<?php if ($person['region_type'] === '68') {echo ' selected';}?>  value="68">Тамбовская область</option>
        <option<?php if ($person['region_type'] === '69') {echo ' selected';}?>  value="69">Тверская область</option>
        <option<?php if ($person['region_type'] === '70') {echo ' selected';}?>  value="70">Томская область</option>
        <option<?php if ($person['region_type'] === '71') {echo ' selected';}?>  value="71">Тульская область</option>
        <option<?php if ($person['region_type'] === '72') {echo ' selected';}?>  value="72">Тюменская область</option>
        <option<?php if ($person['region_type'] === '73') {echo ' selected';}?>  value="73">Ульяновская область</option>
        <option<?php if ($person['region_type'] === '74') {echo ' selected';}?>  value="74">Челябинская область</option>
        <option<?php if ($person['region_type'] === '76') {echo ' selected';}?>  value="76">Ярославская область</option>
        <option<?php if ($person['region_type'] === '77') {echo ' selected';}?>  value="77">Москва</option>
        <option<?php if ($person['region_type'] === '78') {echo ' selected';}?>  value="78">Санкт-Петербург</option>
        <option<?php if ($person['region_type'] === '79') {echo ' selected';}?>  value="79">Еврейская автономная область</option>
        <option<?php if ($person['region_type'] === '83') {echo ' selected';}?>  value="83">Ненецкий автономный округ</option>
        <option<?php if ($person['region_type'] === '86') {echo ' selected';}?>  value="86">Ханты-Мансийский автономный округ — Югра</option>
        <option<?php if ($person['region_type'] === '87') {echo ' selected';}?>  value="87">Чукотский автономный округ</option>
        <option<?php if ($person['region_type'] === '89') {echo ' selected';}?>  value="89">Ямало-Ненецкий автономный округ</option>
        <option<?php if ($person['region_type'] === '91') {echo ' selected';}?>  value="91">Республика Крым</option>
        <option<?php if ($person['region_type'] === '92') {echo ' selected';}?>  value="92">Севастополь</option>
        <option<?php if ($person['region_type'] === '99') {echo ' selected';}?>  value="99">Байконур</option>
    </select>
</div>

<div style="margin-top: 6px;"></div>
<div class="input-group">
    <span class="input-group-addon">Особая отметка:</span>
    <select id="mark" class="form-control"  name="mark">
        <option<?php if ($person['mark'] === '') {echo ' selected';}?> value="">Нет</option>
        <option<?php if ($person['mark'] === 'VIP') {echo ' selected';}?> value="VIP">VIP</option>
        <option<?php if ($person['mark'] === 'Друг') {echo ' selected';}?> value="Друг">Друг</option>
        <option<?php if ($person['mark'] === 'SOS') {echo ' selected';}?> value="SOS">SOS</option>
    </select>
</div>

<div style="margin-top: 6px;"></div>
<div class="input-group">
    <span class="input-group-addon">Тип юр лица:</span>
    <select id="typeOrg" class="form-control"  name="type_org">
        <?php if (!$person['tip']) {echo '<option selected value=""></option>';}?>
        <option<?php if ($person['tip'] === '1') {echo ' selected';}?> value="1">ФЛ</option>
        <option<?php if ($person['tip'] === '2') {echo ' selected';}?> value="2">ИП</option>
        <option<?php if ($person['tip'] === '3') {echo ' selected';}?> value="3">Юр. лицо</option>
    </select>
</div>
<div style="margin-top: 6px;"></div>
<div class="input-group">
<span class="input-group-addon">Бюджетная организация:</span>
<select  class="form-control"  name="budjet_ogrn">
<?php if ($person['budjet_ogrn'] == 0) {
echo '<option value="0">Нет</option>'; 
echo '<option value="1">Да</option>'; 
}else{
echo '<option value="1">Да</option>'; 
echo '<option value="0">Нет</option>'; 
}
?>
</select>
</div>
</div>
</div>
<div class="col-md-6" style="margin-top: 10px;">
<div style="background: #ddd; padding: 6px; padding-top: 0;border-radius: 5px;">
<strong><h4 style="border-bottom: 1px #FFF solid; font-weight: bold;background: #5FC964;padding: 4px 6px;border-radius: 3px;border-bottom-left-radius: 0;border-bottom-right-radius: 0;color: #fff;margin: 0px -5px 6px -5px;"><span class="glyphicon glyphicon-usd"></span> Банк</h4></strong>
<div class="input-group">
<span class="input-group-addon">Расчетный счет:</span>
<input class="form-control" name="r_schet" type="text" id="r_schet" value='<?php echo $person['r_schet']; ?>'> 
</div>
<div style="margin-top: 6px;"></div>
<div class="input-group">
<span class="input-group-addon">БИК:</span> 
<input id="bik" type="text" size="8"name="bik"class="form-control" value='<?php echo $person['bik']; ?>'/>
</div>
<div style="margin-top: 6px;"></div>
<div class="input-group">
<span class="input-group-addon">Банк:</span> 
<input id="bank" class="form-control" type="text" size="64" name="bank"  value='<?php echo $person['bank']; ?>'/>
</div>
<div style="margin-top: 6px;"></div>
<div class="input-group">
<span class="input-group-addon">Город:</span> 
<input id="city" class="form-control" type="text" size="64" name="city"  value='<?php echo $person['city']; ?>'/>
</div>
<div style="margin-top: 6px;"></div>
<div class="input-group">
<span class="input-group-addon">Адрес:</span> 
<input id="adress" class="form-control" type="text" size="64" name="adress"  value='<?php echo $person['adress']; ?>'/>
</div>
<div style="margin-top: 6px;"></div>
<div class="input-group">
<span class="input-group-addon">Корр./Лиц. cчет:</span> 
<input id="k_schet" class="form-control" type="text" size="20" name="k_schet"  value='<?php echo $person['k_schet']; ?>'/>
</div>
</div>

</div>
<div class="col-md-12" style=" margin-top: 0px; margin-bottom: 10px;">
<div style="background: #ddd; margin-top: 10px; padding: 6px; padding-top: 0;border-radius: 5px;">
<strong><h4 style="border-bottom: 1px #FFF solid; font-weight: bold;background:#999;padding: 4px 6px;border-radius: 3px;border-bottom-left-radius: 0;border-bottom-right-radius: 0;color: #fff;margin: 0px -5px 6px -5px;"><span class=" icon-quotes-left"></span> Дополнительная информация</h4></strong>
<div>
<textarea class="form-control" rows="9" name="info" type="text" id="info" ><?php echo $person['primechan']; ?></textarea>
</div>
</div>
<br>
<input type="submit" name="submit" value="Зарегестрировать" class="btn btn-primary" role="button"/>
</div>
</div>
</div>
</form>
<link href="https://dadata.ru/static/css/lib/suggestions-4.8.css" type="text/css" rel="stylesheet" />
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script type="text/javascript" src="https://dadata.ru/static/js/lib/jquery.suggestions-4.8.min.js"></script>
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
url: 'http://htmlweb.ru/service/api.php?bic='+$("input[id='bik']").val()+'&json',
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
        token: "b955dbc625a2b4895757691d955ff2e983a46f1c",
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
        token: "b955dbc625a2b4895757691d955ff2e983a46f1c",
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
$("#uridadress").val(
join([party.address && party.address.value || ""], " ")
);
$("#ogrn").val(
join([party.ogrn], " ")
);
$("#kpp").val(
join([party.kpp], " ")
);

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
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
</body>
</html>
