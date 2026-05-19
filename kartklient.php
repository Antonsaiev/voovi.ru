<?php
# подключаем конфиг
include 'conf.php';
include 'invoice_action.php';

if (!function_exists('kartklient_h')) {
    function kartklient_h($value) {
        return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
    }
}
if (!function_exists('kartklient_mark_class')) {
    function kartklient_mark_class($value) {
        $value = trim((string)$value);
        if ($value == 'VIP') {
            return 'is-vip';
        }
        if ($value == 'Друг') {
            return 'is-friend';
        }
        if ($value == 'SOS') {
            return 'is-sos';
        }
        return 'is-mark';
    }
}

		$q = "SELECT * FROM ogrn WHERE id =$_GET[id]";
		$result = mysql_query($q);
		$person = mysql_fetch_array($result);




if($person['rand'] == 0){
 mysql_query("UPDATE ogrn SET `rand`='".rand(100000000,999999999).date('dmY')."' WHERE `id`=$_GET[id]");
}
$ogrnq = "SELECT * FROM ogrn";
		$ogrnresult = mysql_query($ogrnq);
		$ogrn = mysql_fetch_array($ogrnresult);

$qq = "SELECT * from tekkli WHERE idkli = '".$_GET['id']."'";
		$resultt = mysql_query($qq);
		$personn = mysql_fetch_array($resultt);

$qqq = mysql_query("SELECT DISTINCT nomerschet,otdel,filial,god,nomerdog,data,produkt,price,priceks,kto,rand FROM schet WHERE del = '0' AND idkli = '".$_GET['id']."'");
$perq = mysql_fetch_array($qqq);



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

<?php
$regions = array(
    "01" => "Республика Адыгея (Адыгея)",
    "04" => "Республика Алтай",
    "02" => "Республика Башкортостан",
    "03" => "Республика Бурятия",
    "05" => "Республика Дагестан",
    "06" => "Республика Ингушетия",
    "07" => "Кабардино-Балкарская Республика",
    "08" => "Республика Калмыкия",
    "09" => "Карачаево-Черкесская Республика",
    "10" => "Республика Карелия",
    "11" => "Республика Коми",
    "12" => "Республика Марий Эл",
    "13" => "Республика Мордовия",
    "14" => "Республика Саха (Якутия)",
    "15" => "Республика Северная Осетия — Алания",
    "16" => "Республика Татарстан (Татарстан)",
    "17" => "Республика Тыва",
    "18" => "Удмуртская Республика",
    "19" => "Республика Хакасия",
    "20" => "Чеченская Республика",
    "21" => "Чувашская Республика — Чувашия",
    "22" => "Алтайский край",
    "23" => "Краснодарский край",
    "24" => "Красноярский край",
    "59" => "Пермский край",
    "25" => "Приморский край",
    "26" => "Ставропольский край",
    "27" => "Хабаровский край",
    "28" => "Амурская область",
    "29" => "Архангельская область",
    "30" => "Астраханская область",
    "31" => "Белгородская область",
    "32" => "Брянская область",
    "33" => "Владимирская область",
    "34" => "Волгоградская область",
    "35" => "Вологодская область",
    "36" => "Воронежская область",
    "37" => "Ивановская область",
    "38" => "Иркутская область",
    "39" => "Калининградская область",
    "40" => "Калужская область",
    "42" => "Кемеровская область — Кузбасс",
    "43" => "Кировская область",
    "44" => "Костромская область",
    "45" => "Курганская область",
    "46" => "Курская область",
    "47" => "Ленинградская область",
    "48" => "Липецкая область",
    "49" => "Магаданская область",
    "50" => "Московская область",
    "51" => "Мурманская область",
    "52" => "Нижегородская область",
    "53" => "Новгородская область",
    "54" => "Новосибирская область",
    "55" => "Омская область",
    "56" => "Оренбургская область",
    "57" => "Орловская область",
    "58" => "Пензенская область",
    "60" => "Псковская область",
    "61" => "Ростовская область",
    "62" => "Рязанская область",
    "63" => "Самарская область",
    "64" => "Саратовская область",
    "65" => "Сахалинская область",
    "66" => "Свердловская область",
    "67" => "Смоленская область",
    "68" => "Тамбовская область",
    "69" => "Тверская область",
    "70" => "Томская область",
    "71" => "Тульская область",
    "72" => "Тюменская область",
    "73" => "Ульяновская область",
    "74" => "Челябинская область",
    "76" => "Ярославская область",
    "77" => "Москва",
    "78" => "Санкт-Петербург",
    "79" => "Еврейская автономная область",
    "83" => "Ненецкий автономный округ",
    "86" => "Ханты-Мансийский автономный округ — Югра",
    "87" => "Чукотский автономный округ",
    "89" => "Ямало-Ненецкий автономный округ"
);

$type_company = array(
    "1" => "ФЛ",
    "2" => "ИП",
    "3" => "ЮЛ"
);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "https://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="https://www.w3.org/1999/xhtml" xml:lang="ru" lang="ru">
<head>
	<title></title>
	<meta http-equiv="content-type" content="text/html" charset="utf-8" />
	<meta http-equiv="Content-Style-Type" content="text/css" />
	<meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="css/bootstrap.min.css" rel="stylesheet">
	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js" ></script>
	<script type="text/javascript"src="js/script.js" defer></script>
<link rel="shortcut icon" href="/favicon.ico">

</head>
<body class="kartklient-page">
<?php
# шапка
include 'header.php';
?>

<div id="allmadal" class="allca"></div>
<div id="allc" class="allc">
</div>
<div class="container kartklient-container">
<div class="row">
    <div class="col-md-12 kartklient-layout">
<?php
if(isset($_POST['nksubmit'])){
$q2 = "SELECT * FROM klient WHERE id=(SELECT MAX(id) FROM klient)";
$result2 = mysql_query($q2);
$person2 = mysql_fetch_array($result2);
$lico = "INSERT INTO `klient`(`fio`, `dol`, `tel`, `email`, `pol`) VALUES ('$_POST[fio]', '$_POST[dol]', '$_POST[tel]', '$_POST[email]', '$_POST[pol]')";
mysql_query($lico) or die(mysql_error($links));
$url2 = $person2['id'] + 1;
$ogrnlico = "INSERT INTO `klient_ogrn`(`idkli`, `klient`)  VALUES ('".$_GET['id']."', '".$url2."')";
mysql_query($ogrnlico) or die(mysql_error($links));
}
?>

<section class="kartklient-hero <?php echo $person['bl'] == 0 ? '' : 'is-blocked'; ?>">
    <div class="kartklient-hero-main">
        <div class="kartklient-eyebrow kartklient-hero-meta">
            <span>ИНН <?php echo kartklient_h($person['inn']); ?></span>
            <?php if (!empty($person['kpp'])): ?>
                <span>КПП <?php echo kartklient_h($person['kpp']); ?></span>
            <?php endif; ?>
            <?php if (!empty($person['ogrn'])): ?>
                <span>ОГРН <?php echo kartklient_h($person['ogrn']); ?></span>
            <?php endif; ?>
        </div>
        <h1><?php echo kartklient_h($person['naim']); ?></h1>
    </div>
    <div class="kartklient-hero-side">
        <?php if ($person['mark']): ?>
            <span class="kartklient-badge kartklient-mark-badge <?php echo kartklient_mark_class($person['mark']); ?>"><?php echo kartklient_h($person['mark']); ?></span>
        <?php endif; ?>
        <span id="kartklientBlacklistBadge" class="kartklient-badge is-danger <?php echo $person['bl'] == 0 ? 'is-hidden' : ''; ?>">
            Черный список
        </span>
        <a class="btn kartklient-edit-link" href="/kartklientred.php?id=<?php echo (int)$person['id']; ?>&inn=<?php echo urlencode($person['inn']); ?>">
            <span class="glyphicon glyphicon-cog" aria-hidden="true"></span> Редактировать
        </a>
    </div>
</section>

<div class="col-md-2 kartklient-actions">


<div style="margin-bottom: 6px; padding: 0; " class="btn-group col-md-12 ">
  <button type="button" class="btn btn-success dropdown-toggle col-md-12" data-toggle="dropdown" aria-expanded="false">
   <span class="glyphicon glyphicon-plus"></span> Выставить счет <span class="caret"></span>
  </button>
  <ul class='dropdown-menu' role='menu'>
  <?php
		$query214 = mysql_query("SELECT * from users_access  WHERE users = '".$userdata['users_id']."'");
		while($row214 = mysql_fetch_array($query214)) {
		$query32 = mysql_query("SELECT * from uslugi  WHERE del = '0' AND go = '1' AND id = '".$row214['uslugi']."' ORDER BY name ");
		while($row32 = mysql_fetch_array($query32)) {

    echo "<li>";
	echo "<a href='./newusluga.php?id=".$_GET['id']."&ogrn=".$person['ogrn']."&inn=".$person['inn']."&kpp=".$person['kpp']."&parent=".$row32['id']."'>";
	echo $row32['name'];
	echo "</a>";
	echo "</li>";
  }
  }
  ?>
  </ul>
</div>

<!--<div style="margin-bottom: 6px; padding: 0; " class="btn-group col-md-12 "  role="group">
        <button class="btn btn-danger dropdown-toggle col-md-10" type="button" data-toggle="dropdown" aria-expanded="true">
          <span class="glyphicon glyphicon-earphone"></span> Новый звонок <span class="caret"></span>
        </button>
        <button class="btn btn-danger col-md-2" value="История звонков" type="button">
          <span class="glyphicon glyphicon-list-alt" aria-hidden="true"></span>
        </button>
        <ul class="dropdown-menu" role="menu">
          <li><a href="#">Исходящий звонок</a></li>
          <li><a href="#">Входящий звонок</a></li>
		  <li class="divider"></li>
		  <li><a href="#">История звоноков</a></li>
        </ul>
      </div>

<div style="margin-bottom: 6px; padding: 0; " class="btn-group col-md-12 ">
        <button class="btn btn-warning dropdown-toggle col-md-12" type="button" data-toggle="dropdown" aria-expanded="true">
          <span class="glyphicon glyphicon-envelope"></span> Почта & Сообщение <span class="caret"></span>
        </button>
        <ul class="dropdown-menu" role="menu">
          <li><a href="#">Написать СМС</a></li>
          <li><a href="#">Написать E-mail</a></li>
          <li><a href="/mailrus.php<?php/* echo "?id=".$_GET['id']."&ogrn=".$person['ogrn']."&inn=".$person['inn']."&kpp=".$person['kpp'];*/?>">Почта России</a></li>
		  <li class="divider"></li>
		  <li><a href="#">История писем</a></li>
        </ul>
      </div>!-->


<button style="margin-bottom: 6px;" type="button" class="btn btn-primary col-md-12" data-toggle="modal" data-target="#kartklientContactModal">
<span class="icon-user4"></span> Новый контакт</button>

	<a href="/svyaz.php?id=<?php echo $_GET['id']; ?>&svyaz=<?php 	echo $person['rand'];?>">
		<button style="margin-bottom: 6px;" type="button" class="btn btn-serii col-md-12" >
			<span class="glyphicon glyphicon-random" aria-hidden="true"></span> Связать с организациями
		</button>
	</a>

<button id="valuebl" style="margin-bottom: 6px;" type="button" class="btn btn-serii col-md-12"  data-toggle="modal" data-target="#myModalBL">

<?php
	 if($person['bl'] == 0){
		 echo '<span class="glyphicon glyphicon-fire" aria-hidden="true"></span> Добавить в черный список';
	 }else{
		 echo '<span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span> Убрать из черного списока';
	 }
	 ?></button>

<!-- Modal -->
<div class="modal fade kartklient-confirm-modal" id="myModalBL" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="" id="myModalLabelBL"><?php
	 if($person['bl'] == 0){
		 echo 'Вы уверены что хотите добавить организацию в черный список';
	 }else{
		 echo 'Вы уверены что хотите убрать организацию из черного списока';
	 }
	 ?></h5>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">НЕТ</button>
        <button type="button"  id="bl" class="btn btn-primary"  data-dismiss="modal">ДА</button>
      </div>
    </div>
  </div>
</div>
	<script>
		$('#bl').click(function () {
			$.ajax({
				type: "GET",
				url: "pusya.php",
				data: "tip=bl&ogrn=<?php echo $_GET['id']; ?>",
				success: function(msg){
					var hero = document.querySelector(".kartklient-hero");
					var blacklistBadge = document.getElementById("kartklientBlacklistBadge");
					if(msg == '0'){
						if (hero) {
							hero.className = hero.className.replace(/\bis-blocked\b/g, "").replace(/\s+/g, " ").replace(/^\s|\s$/g, "");
						}
						if (blacklistBadge) {
							blacklistBadge.className = "kartklient-badge is-danger is-hidden";
							blacklistBadge.innerHTML = "Черный список";
						}
						document.getElementById("valuebl").innerHTML = '<span class="glyphicon glyphicon-fire" aria-hidden="true"></span> Добавить в черный список';
						document.getElementById("myModalLabelBL").innerHTML = 'Вы уверены что хотите добавить организацию в черный список';
					}else{
						if (hero && hero.className.indexOf("is-blocked") === -1) {
							hero.className += " is-blocked";
						}
						if (blacklistBadge) {
							blacklistBadge.className = "kartklient-badge is-danger";
							blacklistBadge.innerHTML = "Черный список";
						}
						document.getElementById("valuebl").innerHTML = '<span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span> Убрать из черного списока';
						document.getElementById("myModalLabelBL").innerHTML = 'Вы уверены что хотите убрать организацию из черного списока';
					}
				}
			});
		});
	</script>

<div id="kartklientContactModal" class="modal fade kartklient-contact-modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg kartklient-contact-dialog">
    <div class="modal-content kartklient-contact-content">
<form action="" method="post" class="kartklient-contact-form">
<div class="col-md-12 kartklient-contact-body">
<div class="kartklient-contact-panel"><strong><h4><span class="icon-user4"></span> Контактное лицо <button type="button" class="close kartklient-contact-close" data-dismiss="modal" aria-label="Закрыть"><span aria-hidden="true">&times;</span></button></h4></strong><div class="input-group">
<span class="input-group-addon">ФИО:</span>
<input class="form-control" name="fio" type="text" id="fio" value="" style="box-sizing: border-box; border-color: rgb(204, 204, 204); border-bottom-right-radius: 4px; border-top-right-radius: 4px; padding-left: 7px;">
</div>
<div style="margin-top: 6px;"></div><div class="input-group">
<span class="input-group-addon">Должность:</span>
<input id="dol" type="text" name="dol"class="form-control" />
</div>
<div style="margin-top: 6px;"></div>
<div class="input-group">
<span class="input-group-addon">Телефон:</span>
<input  class="form-control" type="tel" required pattern="[0-9_\-]{10}" placeholder="+7(___) ___ __ __" id="user_phone999" name="tel" />
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

<input type="submit" name="nksubmit" value="Зарегистрировать" class="btn btn-primary kartklient-contact-submit" role="button">

</form>
    </div>
  </div>
</div>
<script>
    $(function () {
        var contactModal = $('#kartklientContactModal');
        var blacklistModal = $('#myModalBL');
        var rowContactModals = $('.kartklient-row-contact-modal');
        var topModals = contactModal.add(blacklistModal).add(rowContactModals);
        topModals.each(function () {
            $(this).appendTo(document.body);
        });
        topModals.on('show.bs.modal', function () {
            $('#modal-shadowkube, #allmadal, #allc, #allmadalc, #allct').hide();
        });
        topModals.on('shown.bs.modal', function () {
            $('.modal-backdrop').last().css('z-index', 30040);
            $(this).css('z-index', 30050);
        });
        $('.kartklient-collapse-heading').on('keydown', function (event) {
            if (event.which === 13 || event.which === 32) {
                event.preventDefault();
                $(this).trigger('click');
            }
        });
    });
</script>

</div>

	 <div class="col-md-10 kartklient-content">
	 <div class="col-md-6 kartklient-info-column">
	 <div class="col-md-12 kartklient-card kartklient-details-card">
	 <h3 class="headerh3" id="blheader1">
	         Реквизиты
         <?php
         if ($person['mark']) {
             if ($person['mark'] == "VIP") {
                 $labelClass = 'label-warning';  // Зеленый (Отлично)
             } elseif ($person['mark'] == "Друг") {
                 $labelClass = 'label-primary';     // Голубой (Хорошо)
             } elseif ($person['mark'] == "SOS") {
                 $labelClass = 'label-danger';  // Желтый (Средне)
             } else {
                 $labelClass = 'label-default';   // Красный (Плохо)
             }
         echo '<span class="label ' . $labelClass . '" style="margin-left: 45px">' . $person['mark'] . '</span>';
         }
         ?>

         <a href="/kartklientred.php?id=<?php  echo $person['id']; ?>&inn=<?php  echo $person['inn']; ?>" style="font-size: 15px; float: right; margin-top: 2px;">
     <span class="glyphicon glyphicon-cog" style="font-size: 18px; margin-top: -2px; padding-right: 2px;"></span></a></h3>
	 <table class="table">

		<tr>
        <th style="padding: 1px 5px; font-size: 14px;">Название</th><th style="font-weight: 100; padding: 1px 5px; font-size: 14px;"><?php  echo $person['naim']; ?></th>
		</tr>
		<tr>
        <th style="padding: 1px 5px; font-size: 14px;">ОГРН</th><th style="font-weight: 100; padding: 1px 5px; font-size: 14px;">
		<?php  echo $person['ogrn']; ?>
		</th>
		</tr>
		<tr>
		<th style="padding: 1px 5px; font-size: 14px;">ИНН</th><th style="font-weight: 100; padding: 1px 5px; font-size: 14px;"><a href="https://focus.kontur.ru/search?query=<?php  echo $person['inn']; ?>"><?php  echo $person['inn']; ?></a></th>
		</tr>
		<?php
		if(!empty($person['inn'])){
		 echo'<tr>
        <th style="padding: 1px 5px; font-size: 14px;">КПП</th><th style="font-weight: 100; padding: 1px 5px; font-size: 14px;">'.$person['kpp'].'</th>
		</tr>';
		}
		?>
		<tr>
        <th  style="padding: 1px 5px; font-size: 14px;">Юридический адрес</th><th style="font-weight: 100; padding: 1px 5px; font-size: 14px;"><?php  echo $person['uridadress'],"<br>"; ?></th>
		</tr>
		<tr>
        <th  style="padding: 1px 5px; font-size: 14px;">Фактический адрес</th><th style="font-weight: 100; padding: 1px 5px; font-size: 14px;"><?php  echo $person['fakadress'],"<br>"; ?></th>
		</tr>
         <tr>
             <th style="padding: 1px 5px; font-size: 14px;">Регион</th>
             <th style="font-weight: 100; padding: 1px 5px; font-size: 14px;">
                 <?php
                 if (!empty($person['region_type']) && array_key_exists($person['region_type'], $regions)) {
                     echo $regions[$person['region_type']];
                 } else {
                     echo '<span style="color: red;">Заполните регион</span>';
                 }
                 ?>
             </th>
         </tr>
         <tr>
             <th style="padding: 1px 5px; font-size: 14px;">Тип ЮЛ</th>
             <th style="font-weight: 100; padding: 1px 5px; font-size: 14px;">
                 <?php
                 if (!empty($person['tip']) && array_key_exists($person['tip'], $type_company)) {
                     echo $type_company[$person['tip']];
                 } else {
                     echo '<span style="color: red;">Заполните тип</span>';
                 }
                 ?>
             </th>
         </tr>
		</table>



        </div>
				 <div class="col-md-12 kartklient-card kartklient-collapsible-card">

			<h3 class="headerh3 kartklient-collapse-heading collapsed" id="blheader2" role="button" tabindex="0" data-toggle="collapse" data-target="#kartklientBankCollapse" aria-expanded="false" aria-controls="kartklientBankCollapse">
				<span class="kartklient-collapse-toggle">
					<span>Банк</span>
					<span class="glyphicon glyphicon-chevron-down" aria-hidden="true"></span>
				</span>
			</h3>
		<div id="kartklientBankCollapse" class="collapse kartklient-collapse-body">
	 <table class="table">
		<tr>
		<th style="padding: 1px 5px; font-size: 14px;" class="col-md-3">БИК</th><th style="font-weight: 100; padding: 1px 5px; font-size: 14px;"><?php  echo $person['bik']; ?></th>
		</tr>
		<tr>
        <th style="padding: 1px 5px; font-size: 14px;">Название банка</th><th style="font-weight: 100; padding: 1px 5px; font-size: 14px;"><?php  echo $person['bank'],"<br>"; ?></th>
		</tr>
		<tr>
        <th style="padding: 1px 5px; font-size: 14px;">Адрес</th><th style="font-weight: 100; padding: 1px 5px; font-size: 14px;"><?php  echo $person['city']," ",$person['adress'],"<br>"; ?></th>
		</tr>
		<tr>
        <th style="padding: 1px 5px; font-size: 14px;">Рас/счет</th><th style="font-weight: 100; padding: 1px 5px; font-size: 14px;"><?php  echo $person['r_schet'],"<br>"; ?></th>
		</tr>
		<tr>
		<th  style="padding: 1px 5px; font-size: 14px;"><?php if($person['budjet_ogrn'] == '0'){echo 'Кор';}else{echo 'л';}?>/счет</th><th style="font-weight: 100; padding: 1px 5px; font-size: 14px;"><?php  echo $person['k_schet'],"<br>"; ?></th>
		</tr>
		</table>
		</div>
		</div>
		<div class="col-md-12 kartklient-card">
		<h3 class="headerh3" id="blheader3">Контакты</h3>
		<div class="table-responsive kartklient-contacts-wrap">
		<table class="table kartklient-contacts-table">
		<thead>
<tr>
<th>ФИО</th>
<th>Должность</th>
<th>Телефон</th>
<th>E-mail</th>
<th class="kartklient-contact-action-cell"></th>
</tr>
    </thead>
		<tbody>
		<?php
				$a = 1;
				$contactCount = 0;
		$query2 = mysql_query("SELECT * from klient_ogrn WHERE idkli = '".$_GET['id']."' ORDER BY id DESC");
		while($row2 = mysql_fetch_array($query2)) {
		$query3 = mysql_query("SELECT * from klient WHERE id = '".$row2['klient']."' ORDER BY id DESC");
		while($row3 = mysql_fetch_array($query3)) {
				$contactCount++;
				$contactModalId = 'kartklientContactEditModal'.(int)$row3['id'];

		echo '<tr>';
		echo '<td>'.kartklient_h($row3['fio']).'</td>';
		echo '<td>'.kartklient_h($row3['dol']).'</td>';
		echo '<td><span class="kartklient-contact-phone">'.kartklient_h($row3['tel']).'</span></td>';
		echo '<td>'.kartklient_h($row3['email']).'</td>';
		echo '<td class="kartklient-contact-action-cell">';

		echo '<button title="Настройки контакта" type="button" class="btn btn-info btn-xs kartklient-contact-edit-button" data-toggle="modal" data-target="#'.$contactModalId.'">
<span class="icon-wrench"></span></button>

<div id="'.$contactModalId.'" class="modal fade kartklient-contact-modal kartklient-row-contact-modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg kartklient-contact-dialog">
    <div class="modal-content kartklient-contact-content">
<form action="'.kartklient_h($_SERVER['REQUEST_URI']).'" method="post" class="kartklient-contact-form">
<div class="col-md-12 kartklient-contact-body">
<div class="kartklient-contact-panel"><strong><h4><span class="icon-user4"></span> Контактное лицо <button type="button" class="close kartklient-contact-close" data-dismiss="modal" aria-label="Закрыть"><span aria-hidden="true">&times;</span></button></h4></strong><div class="input-group">
<span class="input-group-addon">ФИО:</span>
<input class="form-control" name="fio" type="text" value="'.kartklient_h($row3['fio']).'">
</div>
<div class="input-group">
<span class="input-group-addon">Должность:</span>
<input type="text" name="dol" class="form-control" value="'.kartklient_h($row3['dol']).'" />
</div>
<div class="input-group">
<span class="input-group-addon">Телефон:</span>
<input class="form-control" type="tel" required pattern="[0-9_\-]{10}" placeholder="+7(___) ___ __ __" id="';
$str00 = substr($row3['tel'], 2, 1);
if($str00 == 9){
echo 'user_phone';
}else{
echo 'guser_phone';
}
echo $a++ .'" name="tel" value="'.kartklient_h($row3['tel']).'" />
</div>
<div class="input-group">
<span class="input-group-addon">E-mail:</span>
<input class="form-control" type="text" name="email" value="'.kartklient_h($row3['email']).'" />
</div>
<div class="input-group">
<span class="input-group-addon">На оснований:</span>
<input class="form-control" type="text" name="naosnovanii" value="'.kartklient_h($row3['naosnovanii']).'" />
</div>
</div>
</div>
<div class="kartklient-contact-modal-actions">
<input type="submit" name="nksubmitw'.$row3['id'].'" value="Редактировать" class="btn btn-success kartklient-contact-submit" role="button">
<input type="submit" name="nkdel'.$row3['id'].'" value="Удалить" class="btn btn-primary kartklient-contact-delete" role="button">
</div>
</form>
    </div>
  </div>
</div>

';

if(isset($_POST['nksubmitw'.$row3['id']])){
$licoetw = "UPDATE `klient` SET `fio`='$_POST[fio]',`dol`='$_POST[dol]',`tel`='$_POST[tel]',`email`='$_POST[email]',`naosnovanii`='$_POST[naosnovanii]' WHERE id =".$row3['id'];
mysql_query($licoetw) or die(mysql_error($links));
echo '<script type="text/javascript">
   document.location.href = "'.$_SERVER['REQUEST_URI'].'";
</script>';
}if(isset($_POST['nkdel'.$row3['id']])){
$licoetw = "DELETE FROM `klient` WHERE id =".$row3['id'];
mysql_query($licoetw) or die(mysql_error($links));
echo '<script type="text/javascript">
   document.location.href = "'.$_SERVER['REQUEST_URI'].'";
</script>';
}

		echo '</td>';
		echo '</tr>';

		}
		}
		if ($contactCount == 0) {
			echo '<tr><td colspan="5" class="kartklient-contact-empty">Контактов нет</td></tr>';
		}
		?>
		</tbody>
		</table>
		</div>

      </div>

         <div class="col-md-12">
             <div class="callkart">
<!--             <h3 class="--><?php
//             if($person['bl'] == 0){
//                 echo 'headerh3';
//             }else{
//                 echo 'headerbl';
//             }
//             ?><!--" id="blheader2">Звонки</h3>-->
<!--             <label id="allcall">Звонки организации</label>-->
<!--             </div>-->
<!--             <div style="height: 148px;overflow-y: scroll;">-->
<!--             <table class="table" style="font-size: 9pt;">-->
<!--                 <thead>-->
<!--                 <tr>-->
<!--                     <th>№</th>-->
<!--                     <th>тип звонка</th>-->
<!--                     <th>кто звонили</th>-->
<!--                     <th>кому звонил</th>-->
<!--                     <th>звонок</th>-->
<!--                     <th>дата звонка</th>-->
<!--                 </tr>-->
<!--                 </thead>-->
<!--                 <tbody>-->
<!--                 --><?php
//                 $nom=0;
//                 $r = mysql_query("SELECT *,vidcall.name_call,users.f_name,users.l_name,users.o_name,ogrn.naim from telefonia left join vidcall on telefonia.vid_call=vidcall.id left join users on telefonia.idkto=users.users_id left join ogrn on ogrn.id=telefonia.idkli where telefonia.idkli='".$_GET["id"]."' order by telefonia.id desc");
//                 while($res = mysql_fetch_assoc($r))  :
//                     $nom++;?>
<!---->
<!--                     <tr>-->
<!--                         <td>--><?//echo $nom;?><!--</td>-->
<!--                         <td>--><?//echo $res["name_call"];?><!--</td>-->
<!--                         <td>--><?//echo $res['f_name'].' '.mb_substr($res['l_name'],0,1,'UTF-8'),'. '.mb_substr($res['o_name'],0,1,'UTF-8').'.';?><!--</td>-->
<!--                         <td>--><?//echo $res['towhom'];?><!--</td>-->
<!--                         <td style="width: 20px;"><audio controls style="height: 40px;width: 250px;"><source src="/voicecatalog/--><?//echo $res["callmessage"];?><!--"></audio></td>-->
<!--                         <td style="width: 130px;">--><?//echo $res['date_answer'];?><!--</td>-->
<!--                     </tr>-->
<!--                 --><?//endwhile;?>
<!--                 </tbody>-->
<!--             </table>-->
             </div>
         </div>






        </div>

	 <div class="col-md-6">

		<div class="col-md-12 kartklient-card kartklient-products-card">
	 <h3 class="headerh3 kartklient-section-title kartklient-collapse-heading collapsed" id="blheader4" role="button" tabindex="0" data-toggle="collapse" data-target="#kartklientProductsCollapse" aria-expanded="false" aria-controls="kartklientProductsCollapse">
		 <span class="kartklient-collapse-toggle">
			 <span>Карта продуктов</span>
			 <span class="glyphicon glyphicon-chevron-down" aria-hidden="true"></span>
		 </span>
		 <a onclick="event.stopPropagation(); yesnonep();" id="yesnonep" class="btn btn-info btn-xs" style="font-size: 14px;float: right;margin-top: 0px;margin-left: 10px;">Показать все продукты <span class="glyphicon glyphicon-tags" aria-hidden="true"></span></a>
	 </h3>

<div id="kartklientProductsCollapse" class="collapse kartklient-collapse-body">
<div id="contaicallp"></div>
									<script type="text/javascript">

									function yesnonep()
									{
										var c = document.getElementById("contaicallp");
										var d = document.createElement("iframe");
										var t = document.createTextNode("<p>Закрыть окно</p>");
										c.appendChild(t);
										c.appendChild(d);
										d.src = "<?php echo VOOVI_MAIN_URL; ?>/kliprodpage.php?id=<?php echo $_GET['id'];?>";
										d.width = "900px";
										d.height = "450px";
										d.className = "iframestyle";
										d.Name = "f2";
										document.getElementById("contaicallp").className = "contaidivcall";
										// d.style.minWidth = "200px";
									}
									$(document).ready(function(){
										$("#contaicallp").click(function(){
											$("#contaicallp").empty();
											document.getElementById("contaicallp").className = "";
											 document.location.href = "/kartklient.php?id=<?php echo $_GET['id'];?>";
										});
									});
									</script>



	<table class="table tablehover">
	<thead>
        <tr>
		  <th  style="width: 160px; font-size: 14px;">Продукт</th>
		  <th  style="width: 80px; font-size: 14px;">Дата</th>
		  <th style="font-size: 14px;">Коментарии</th>
		  <th style="width: 1px; font-size: 14px;">Комп</th>
		  <th style="width: 1px;"><span class="glyphicon glyphicon-play-circle" aria-hidden="true"></span></th>
        </tr>
    </thead>
					<?php

						$num = 100;
						$page = $_GET['page'];
						$result00 = mysql_query("SELECT COUNT(*) FROM produkti WHERE kartkli = '1' ");
						$temp = mysql_fetch_array($result00);
						$posts = $temp[0];
						$total = (($posts - 1) / $num) + 1;
						$total =  intval($total);
						$page = intval($page);
						if(empty($page) or $page < 0) $page = 1;
						if($page > $total) $page = $total;
						$start = $page * $num - $num;


						$query = mysql_query("SELECT * from produkti WHERE kartkli = '1'  ORDER BY name LIMIT $start, $num");
							while($row = mysql_fetch_array($query)) {
							if($row['parent'] == $userdata['inogrn']) {


							$aqiq = "SELECT * FROM ogtrnprod WHERE prod ='".$row['id']."' AND idkli ='".$_GET['id']."' ORDER BY id DESC";
						$aqresiult = mysql_query($aqiq);
						$aqoigrn = mysql_fetch_array($aqresiult);

if($aqoigrn['redaktor'] > '0'){
								echo '<tr   style="font-size: 14px;"  id="disp"><td style="width: 160px;">';
								echo $row['name'];
								echo '</td>';
								echo '<td style="width: 180px; font-size: 14px;" ';
								echo 'onclick="ktoprodu'.$row['id'].'()"';
								echo '>';

								if(!empty($aqoigrn['kto'])){
								echo $aqoigrn['date']." <br> ";
								$usersaqiq = "SELECT * FROM users WHERE users_id ='".$aqoigrn['kto']."'";
								$usersaqresiult = mysql_query($usersaqiq);
								$usersaqoigrn = mysql_fetch_array($usersaqresiult);
								if(!empty($usersaqoigrn['f_name'])){
								echo '<div id="kakadu'.$row['id'].'">'.$usersaqoigrn['f_name']." ".$usersaqoigrn['l_name'].'</div>';
								}else{
								echo 'Я незнаю';
								}
								if($aqoigrn['kto'] == $userdata['users_id']){
								echo '<select ';
								echo 'onchange="ktoProduu'.$row['id'].'(this.value)"';
								echo ' id="ktoprodu'.$row['id'].'" style="display: none; font-size: 14px;" >';
								echo "<option></option>";
									$querysas = mysql_query("SELECT * from users WHERE users_id != '".$userdata['users_id']."' AND tip != '88'");
									while($rowsas = mysql_fetch_array($querysas)) {
									echo "<option value='".$rowsas['users_id']."'>";
									echo $rowsas['f_name']," ",$rowsas['l_name'];
									echo '</option>
									';
									}
								echo '</select>
								<script>
										function ktoprodu'.$row['id'].'()
										{
											document.getElementById("ktoprodu'.$row['id'].'").style.display="block";
										}
										function ktoProduu'.$row['id'].'(str)
										{
												$.ajax({
													type: "GET",
													url: "pusya.php",
													data: "lico="+str+"&tip=ktoprodu&idkli='.$_GET['id'].'&prod='.$row['id'].'",
													success: function(msg){
														$("#kakadu'.$row['id'].'").html(msg);
														document.getElementById("ktoprodu'.$row['id'].'").style.display="none";
													}
												});
										}
								</script>';
								} else if($userdata['otvetstven']=='1') {
									echo '<select ';
								echo 'onchange="ktoProduu'.$row['id'].'(this.value)"';
								echo ' id="ktoprodu'.$row['id'].'" style="display: none;" >';
								echo "<option></option>";
									$querysas = mysql_query("SELECT * from users WHERE users_id != '".$userdata['users_id']."' AND tip != '88'");
									while($rowsas = mysql_fetch_array($querysas)) {
									echo "<option value='".$rowsas['users_id']."'>";
									echo $rowsas['f_name']," ",$rowsas['l_name'];
									echo '</option>
									';
									}
								echo '</select>
								<script>
										function ktoprodu'.$row['id'].'()
										{
											document.getElementById("ktoprodu'.$row['id'].'").style.display="block";
										}
										function ktoProduu'.$row['id'].'(str)
										{
												$.ajax({
													type: "GET",
													url: "pusya.php",
													data: "lico="+str+"&tip=ktoprodu&idkli='.$_GET['id'].'&prod='.$row['id'].'",
													success: function(msg){
														$("#kakadu'.$row['id'].'").html(msg);
														document.getElementById("ktoprodu'.$row['id'].'").style.display="none";
													}
												});
										}
								</script>';
								}
								}



								echo '</td>';
								echo '<td>';
								echo $aqoigrn['text'];

									$wusersaqiq = "SELECT * FROM users WHERE users_id ='".$aqoigrn['redaktor']."'";
									$wusersaqresiult = mysql_query($wusersaqiq);
									$wusersaqoigrn = mysql_fetch_array($wusersaqresiult);

									echo ' <i> ('.$wusersaqoigrn['f_name']." ".$wusersaqoigrn['l_name'].')</i>';


								$resulte = mysql_query("SELECT COUNT(*) FROM ogtrnprod WHERE prod ='".$row['id']."' AND idkli ='".$_GET['id']."'");
								$classe = mysql_result($resulte, 0);
								if($classe > '1'){
									echo '<span onclick="prod'.$row['id'].'()" style="float:right;" class="glyphicon glyphicon-list-alt" aria-hidden="true"></span>

									<script type="text/javascript">
									function prod'.$row['id'].'()
									{
										var docc = document.getElementById("prodtai'.$row['id'].'");
										var docd = document.createElement("iframe");
										var doct = document.createTextNode("11111");
										docd.appendChild(doct);
										docc.appendChild(docd);
										docd.src = "/info.php?tip=histprod&idkli='.$_GET['id'].'&prod='.$row['id'].'";
										docd.width = document.documentElement.clientWidth - document.documentElement.clientWidth / 2;
										docd.height = "200px";
										docd.className = "iframestyle";
										document.getElementById("prodtai'.$row['id'].'").className = "contai";
									}
									$(document).ready(function(){
										$("#prodtai'.$row['id'].'").click(function(){
											$("#prodtai'.$row['id'].'").empty();
											document.getElementById("prodtai'.$row['id'].'").className = "";
										});
									});
									</script>
									<div id="prodtai'.$row['id'].'"></div>';
								}

								echo '</td>';
								echo '<td>';
								echo $aqoigrn['kol'];
								echo '</td>';
								echo '<td ';
								echo 'onclick="produ'.$row['id'].'()"';
								echo' id="produ'.$row['id'].'" style="width: 1px;">';
								echo '<span class="glyphicon glyphicon-play-circle" aria-hidden="true"></span>';
								echo '</td></tr>';
								echo '<div id="contaicall"></div>
									<script type="text/javascript">

									function produ'.$row['id'].'()
									{
										var c = document.getElementById("contaicall");
										var d = document.createElement("iframe");
										var t = document.createTextNode("<p>Закрыть окно</p>");
										c.appendChild(t);
										c.appendChild(d);
										d.src = "'.VOOVI_MAIN_URL.'/kartkliprod.php?id='.$row['id'].'&idkli='.$_GET['id'].'";
										d.width = "900px";
										d.height = "450px";
										d.className = "iframestyle";
										d.Name = "f2";
										document.getElementById("contaicall").className = "contaidivcall";
										// d.style.minWidth = "200px";
									}
									$(document).ready(function(){
										$("#contaicall").click(function(){
											$("#contaicall").empty();
											document.getElementById("contaicall").className = "";
											 document.location.href = "/kartklient.php?id='.$_GET['id'].'";
										});
									});
									</script>';

															}
															}
															}
					?>
				</table>
</div>



				 <h3 class="headerh3 kartklient-collapse-heading collapsed" id="blheader5" role="button" tabindex="0" data-toggle="collapse" data-target="#kartklientSrokCollapse" aria-expanded="false" aria-controls="kartklientSrokCollapse">
					 <span class="kartklient-collapse-toggle">
						 <span>Срок действия</span>
						 <span class="glyphicon glyphicon-chevron-down" aria-hidden="true"></span>
					 </span>
				 </h3>

<div id="kartklientSrokCollapse" class="collapse kartklient-collapse-body">

		<table class="table">
<thead>
<tr>
<th style="font-size: 14px;">Продукт</th>
<th style="font-size: 14px;">Тип продления</th>
<th style="font-size: 14px;">Дата продления</th>
</tr>
    </thead>
<?php
$srokDateFrom = date('Ymd', strtotime('-3 years'));
$query = mysql_query("SELECT * FROM call_center INNER JOIN schet ON call_center.ns=schet.ns WHERE call_center.idogrn = '".$_GET['id']."' AND call_center.ns != '' AND call_center.date >= '".$srokDateFrom."' AND schet.dateotkaz = '0000-00-00' GROUP BY call_center.id ORDER BY call_center.date; ");
while($row = mysql_fetch_array($query)) {
echo '<tr style="font-size: 14px;">';
echo '<td style="font-size: 14px;">';
$q1z = "SELECT * FROM `produkti` WHERE id = $row[4]";
$person1z = mysql_fetch_array(mysql_query($q1z));
echo $person1z['name'];
echo '</td>';
echo '<td style="font-size: 14px;">';
echo $row['6'];
echo '</td>';
echo '<td style="font-size: 14px;">';
echo substr($row['date'], 6, 2).'.'.substr($row['date'], 4, 2).'.'.substr($row['date'], 0, 4);
echo '</td>';

echo '</tr>';
}
?>
</table>
</div>









        </div>














<div class="col-md-12 kartklient-card kartklient-linked-card">

<div class="bs-example">
<h3 class="kartklient-section-title kartklient-collapse-heading collapsed" role="button" tabindex="0" data-toggle="collapse" data-target="#kartklientLinkedCollapse" aria-expanded="false" aria-controls="kartklientLinkedCollapse" style="border-bottom: 1px #333 solid;">
	<span class="kartklient-collapse-toggle">
		<span>Cвязанные организации</span>
		<span class="glyphicon glyphicon-chevron-down" aria-hidden="true"></span>
	</span>
</h3>
<div id="kartklientLinkedCollapse" class="collapse kartklient-collapse-body">
<?

						$num = 8;
						$pagee = $_GET['pagee'];
						$result00 = mysql_query("SELECT COUNT(*) FROM ogrn WHERE svyaz = '$person[rand]'");
						$temp = mysql_fetch_array($result00);
						$posts = $temp[0];
						$total = (($posts - 1) / $num) + 1;
						$total =  intval($total);
						$pagee = intval($pagee);
						if(empty($pagee) or $pagee < 0) $pagee = 1;
						if($pagee > $total) $pagee = $total;
						$start = $pagee * $num - $num;

// Проверяем нужны ли стрелки назад
if ($pagee != 1) $pervpagee = '<a href=?pagee=1&id='.$_GET['id'].'>Первая</a> | <a href=?pagee='. ($pagee - 1) .'&id='.$_GET['id'].'>Предыдущая</a> | ';
// Проверяем нужны ли стрелки вперед
if ($pagee != $total) $nextpagee = ' | <a href=?pagee='. ($pagee + 1) .'&id='.$_GET['id'].'>Следующая</a> | <a href=?pagee=' .$total.'&id='.$_GET['id'].'>Последняя</a>';

// Находим две ближайшие станицы с обоих краев, если они есть
if($pagee - 5 > 0) $pagee5left = ' <a href=?pagee='. ($pagee - 5) .'&id='.$_GET['id'].'>'. ($pagee - 5) .'</a> | ';
if($pagee - 4 > 0) $pagee4left = ' <a href=?pagee='. ($pagee - 4) .'&id='.$_GET['id'].'>'. ($pagee - 4) .'</a> | ';
if($pagee - 3 > 0) $pagee3left = ' <a href=?pagee='. ($pagee - 3) .'&id='.$_GET['id'].'>'. ($pagee - 3) .'</a> | ';
if($pagee - 2 > 0) $pagee2left = ' <a href=?pagee='. ($pagee - 2) .'&id='.$_GET['id'].'>'. ($pagee - 2) .'</a> | ';
if($pagee - 1 > 0) $pagee1left = '<a href=?pagee='. ($pagee - 1) .'&id='.$_GET['id'].'>'. ($pagee - 1) .'</a> | ';

if($pagee + 5 <= $total) $pagee5right = ' | <a href=?pagee='. ($pagee + 5) .'&id='.$_GET['id'].'>'. ($pagee + 5) .'</a>';
if($pagee + 4 <= $total) $pagee4right = ' | <a href=?pagee='. ($pagee + 4) .'&id='.$_GET['id'].'>'. ($pagee + 4) .'</a>';
if($pagee + 3 <= $total) $pagee3right = ' | <a href=?pagee='. ($pagee + 3) .'&id='.$_GET['id'].'>'. ($pagee + 3) .'</a>';
if($pagee + 2 <= $total) $pagee2right = ' | <a href=?pagee='. ($pagee + 2) .'&id='.$_GET['id'].'>'. ($pagee + 2) .'</a>';
if($pagee + 1 <= $total) $pagee1right = ' | <a href=?pagee='. ($pagee + 1) .'&id='.$_GET['id'].'>'. ($pagee + 1) .'</a>';

// Вывод меню если страниц больше одной

if ($total > 1)
{
Error_Reporting(E_ALL & ~E_NOTICE);
echo "<div class=\"pstrnav\">";
echo $pervpagee.$pagee5left.$pagee4left.$pagee3left.$pagee2left.$pagee1left.'<b>'.$pagee.'</b>'.$pagee1right.$pagee2right.$pagee3right.$pagee4right.$pagee5right.$nextpagee;
echo "</div>";
}
?>

	<table class="table tablehover">
	<thead>
        <tr>
		<th style="font-size: 14px;">Название</th>
		  <th style="font-size: 14px;">ИНН</th>
          <th style="font-size: 14px;">КПП</th>
		  <th style="font-size: 14px;"></th>
        </tr>
    </thead>
				<?php

		$ogrn = mysql_fetch_array(mysql_query("SELECT * FROM ogrn WHERE rand =$person[svyaz]"));


						if(isset($ogrn['id'])){
								echo '<tr class="alert alert-success" id="open'.$ogrn['id'].'"  style="font-size: 12px;">';
								echo '<td style="font-size: 14px;">';
								echo $ogrn['naim'];
								echo '<script>
$("#open'.$ogrn['id'].'").live("dblclick", function() {
window.location.href="./kartklient.php?id='.$ogrn['id'].'";
});
</script></td>';
								echo '<td style="width: 100px; font-size: 14px;">';
								echo $ogrn['inn'];
								echo '</td>';
								echo '<td style="width: 100px; font-size: 14px;">';
								echo $ogrn['kpp'];
								echo '</td>';
								echo '<td style="width: 100px; font-size: 14px;">';
								echo 'Основной';
								echo '</td>';
								echo '</tr>';
						}else{
						echo '<tr class="alert alert-success" id="open'.$person['id'].'"  style="font-size: 12px;">';
								echo '<td style="font-size: 14px;">';
								echo $person['naim'];
								echo '<script>
$("#open'.$person['id'].'").live("dblclick", function() {
window.location.href="./kartklient.php?id='.$person['id'].'";
});
</script></td>';
								echo '<td style="width: 100px; font-size: 14px;">';
								echo $person['inn'];
								echo '</td>';
								echo '<td style="width: 100px; font-size: 14px;">';
								echo $person['kpp'];
								echo '</td>';
								echo '<td style="width: 100px; font-size: 14px;">';
								echo 'Основной';
								echo '</td>';
								echo '</tr>';
						}
					?>
					<?php
						$query = mysql_query("SELECT * from ogrn WHERE `svyaz` = $person[rand]  ORDER BY id DESC LIMIT $start, $num");
							while($row = mysql_fetch_array($query)) {
								echo '<tr  id="open'.$row['id'].'"  style="font-size: 12px;">';
								echo '<td style="font-size: 14px;">';
								echo $row['naim'];
								echo '<script>
$("#open'.$row['id'].'").live("dblclick", function() {
window.location.href="./kartklient.php?id='.$row['id'].'";
});
</script></td>';
								echo '<td style="width: 100px; font-size: 14px;">';
								echo $row['inn'];
								echo '</td>';
								echo '<td style="width: 100px; font-size: 14px;">';
								echo $row['kpp'];
								echo '</td>';
								echo '<td style="width: 100px; font-size: 14px;">';
								echo '<a style="font-size: 14px;" title="Отвязать" href="./svyazonoff.php?id='.$row['id'].'&svyaz='.$_GET['svyaz'].'&go=0"><span class="glyphicon glyphicon-random" aria-hidden="true"></span> Отвезать</a>';
								echo '</td>';
								echo '</tr>';
						}
					?>
				</table>


<?


// Вывод меню если страниц больше одной

if ($total > 1)
{
Error_Reporting(E_ALL & ~E_NOTICE);
echo "<div class=\"pstrnav\">";
echo $pervpagee.$pagee5left.$pagee4left.$pagee3left.$pagee2left.$pagee1left.'<b>'.$pagee.'</b>'.$pagee1right.$pagee2right.$pagee3right.$pagee4right.$pagee5right.$nextpagee;
echo "</div>";
}
?>
</div>
</div>
</div>









        </div>



      </div>





<div class="col-md-12 kartklient-card kartklient-invoices-card">
<h3 class="headerh3">Счета</h3>








		<br>
		<br>





































			<?php
$id = $_POST['id'];
if(isset($_POST['deletemarked'])){
	/*if($userdata['users_id'] != 20){*/
		if (empty($id) || $id == 0){
			echo 'Ошибка'.$id;
		}else{
			$impid = implode(", ",$id);
			$Qdelete = mysql_query("DELETE FROM schet WHERE del = '0' AND rand IN (".$impid.")") or die ("error in delete");
			if(isset($Qdelete))
			{
				echo "Успешно удален счет";
			}
		}
	/*}else{
		echo '<div class="alert alert-danger" role="alert"> <strong>Опасно!</strong> Вы не ответственный человек!!!</div>';
	}*/
}

if(isset($_POST['ingroup'])){
	if (empty($id) || $id == 0){
		echo 'Ошибка'.$id;
	}else{
		$impid = implode(", ",$id);
		$Qdelete = mysql_query("UPDATE `schet` SET `gr` =  '".rand(0,255).rand(0,255).rand(200,255)."', `groupi` =  'rgb(".rand(0,255).", ".rand(0,255).", ".rand(200,255).")'  WHERE rand IN (".$impid.")") or die ("error in delete");
		if(isset($Qdelete))
		{
			echo "Успешно  ".$person."";
		}
	}
}

if(isset($_POST['ungroup'])){
	if (empty($id) || $id == 0){
		echo 'Ошибка'.$id;
	}else{
		$impid = implode(", ",$id);
		$Qdelete = mysql_query("UPDATE `schet` SET `gr` =  '0', `groupi` =  '' WHERE rand IN (".$impid.")") or die ("error in delete");
		if(isset($Qdelete))
		{
			echo "Успешно  ".$person."";
		}
	}
}

if(isset($_POST['doljen'])){
	if (empty($id) || $id == 0){
		echo 'Ошибка'.$id;
	}else{
		$impid = implode(", ",$id);
		$q47 = "SELECT * FROM schet WHERE del = '0' AND rand =".$impid;
		$result47 = mysql_query($q47);
		$person47 = mysql_fetch_array($result47);
		if($person47['doljen'] == 0){
			$Qdelete = mysql_query("UPDATE `schet` SET `doljen` =  '1' WHERE rand IN (".$impid.")") or die ("error in delete");
			if(isset($Qdelete)){
				echo "Успешно  ".$person."";
			}
		}if($person47['doljen'] == 1){
			$Qdelete = mysql_query("UPDATE `schet` SET `doljen` =  '0' WHERE rand IN (".$impid.")") or die ("error in delete");
			if(isset($Qdelete)){
				echo "Успешно  ".$person."";
			}
		}
	}
}

if(isset($_POST['doljenop'])){
	if (empty($id) || $id == 0){
		echo 'Ошибка'.$id;
	}else{
		$impid = implode(", ",$id);
		$q47 = "SELECT * FROM schet WHERE del = '0' AND rand =".$impid;
		$result47 = mysql_query($q47);
		$person47 = mysql_fetch_array($result47);
		if($person47['doljenop'] == 0){
			$Qdelete = mysql_query("UPDATE `schet` SET `doljenop` =  '1' WHERE rand IN (".$impid.")") or die ("error in delete");
			if(isset($Qdelete)){
				echo "Успешно  ".$person."";
			}
		}if($person47['doljenop'] == 1){
			$Qdelete = mysql_query("UPDATE `schet` SET `doljenop` =  '0' WHERE rand IN (".$impid.")") or die ("error in delete");
			if(isset($Qdelete)){
				echo "Успешно  ".$person."";
			}
		}
	}
}

if(isset($_POST['proveren'])){
	if (empty($id) || $id == 0){
		echo 'Ошибка'.$id;
	}else{
		$impid = implode(", ",$id);
		$q47 = "SELECT * FROM schet WHERE del = '0' AND rand =".$impid;
		$result47 = mysql_query($q47);
		$person47 = mysql_fetch_array($result47);
		if($person47['gotov'] == 0){
			$Qdelete = mysql_query("UPDATE `schet` SET `gotov` =  '1' WHERE rand IN (".$impid.")") or die ("error in delete");
			if(isset($Qdelete)){
				echo "Успешно  ".$person."";
			}
		}if($person47['gotov'] == 1){
			$Qdelete = mysql_query("UPDATE `schet` SET `gotov` =  '0' WHERE rand IN (".$impid.")") or die ("error in delete");
			if(isset($Qdelete)){
				echo "Успешно  ".$person."";
			}
		}
	}
}

if(isset($_POST['oplachen'])){
	if (empty($id) || $id == 0){
		echo 'Ошибка'.$id;
	}else{
		$dateop=date("Y-m-d");
		$impid = implode(", ",$id);
		$q47 = "SELECT * FROM schet WHERE del = '0' AND rand =".$impid;
		$result47 = mysql_query($q47);
		$person47 = mysql_fetch_array($result47);
		if($person47['oplachenks'] == 0){
			$Qdelete = mysql_query("UPDATE `schet` SET `oplachenks` =  '1',`date_op`='".$dateop."' WHERE rand IN (".$impid.")") or die ("error in delete");
            echo "<script>console.log('" . json_encode($Qdelete) . "');</script>";
			if(isset($Qdelete)){
				echo "Успешно  ".$person."";
			}
		}if($person47['oplachenks'] == 1){
			$Qdelete = mysql_query("UPDATE `schet` SET `oplachenks` =  '0',`date_op`='' WHERE rand IN (".$impid.")") or die ("error in delete");
			if(isset($Qdelete)){
				echo "Успешно  ".$person."";
			}
		}
	}
}

if(isset($_POST['akt'])){
    echo updateSchet($id);
}



if(isset($_POST['otk'])){
	if (empty($id) || $id == 0){
		echo 'Ошибка'.$id;
	}else{
		$impid = implode(", ",$id);
		$q47 = "SELECT * FROM schet WHERE del = '0' AND rand =".$impid;
		$result47 = mysql_query($q47);
		$person47 = mysql_fetch_array($result47);
		if($person47['otk'] == 0){
			$Qdelete = mysql_query("UPDATE `schet` SET `otk` =  '1' WHERE rand IN (".$impid.")") or die ("error in delete");
			if(isset($Qdelete)){
				echo "Успешно  ".$person."";
			}
		}if($person47['otk'] == 1){
			$Qdelete = mysql_query("UPDATE `schet` SET `otk` =  '0' WHERE rand IN (".$impid.")") or die ("error in delete");
			if(isset($Qdelete)){
				echo "Успешно  ".$person."";
			}
		}
	}
}
if(isset($_POST['sertifikat'])){
	if (empty($id) || $id == 0){
		echo 'Ошибка'.$id;
	}else{
		$impid = implode(", ",$id);
		$q47 = "SELECT * FROM schet WHERE del = '0' AND rand =".$impid;
		$result47 = mysql_query($q47);
		$person47 = mysql_fetch_array($result47);
		if($person47['ust_sert'] == 0){
			$Qdelete = mysql_query("UPDATE `schet` SET `ust_sert` =  '1' WHERE rand IN (".$impid.")") or die ("error in delete");
			if(isset($Qdelete)){
				echo "Успешно  ".$person."";
			}
		}if($person47['ust_sert'] == 1){
			$Qdelete = mysql_query("UPDATE `schet` SET `ust_sert` =  '0' WHERE rand IN (".$impid.")") or die ("error in delete");
			if(isset($Qdelete)){
				echo "Успешно  ".$person."";
			}
		}
	}
}
            if(isset($_POST['cher'])){
                if (empty($id) || $id == 0){
                    echo 'Ошибка'.$id;
                }else{
                    $impid = implode(", ",$id);
                    $q47 = "SELECT * FROM schet WHERE del = '0' AND rand =".$impid;
                    $result47 = mysql_query($q47);
                    $person47 = mysql_fetch_array($result47);
                    if($person47['cher'] == 0){
                        $Qdelete = mysql_query("UPDATE `schet` SET `cher` =  '1' WHERE rand IN (".$impid.")") or die ("error in delete");
                        if(isset($Qdelete)){
                            echo "Успешно  ".$person."";
                        }
                    }if($person47['cher'] == 1){
                        $Qdelete = mysql_query("UPDATE `schet` SET `cher` =  '0' WHERE rand IN (".$impid.")") or die ("error in delete");
                        if(isset($Qdelete)){
                            echo "Успешно  ".$person."";
                        }
                    }
                }
            }
            /*Контур*/
            if(isset($_POST['kross'])){
                if (empty($id) || $id == 0){
                    echo 'Ошибка'.$id;
                }else{
                    $impid = implode(", ",$id);
                    $q47 = "SELECT * FROM schet WHERE del = '0' AND rand =".$impid;
                    $result47 = mysql_query($q47);
                    $person47 = mysql_fetch_array($result47);
                    if($person47['krossprod'] == 0){
                        $Qdelete = mysql_query("UPDATE `schet` SET `krossprod` =  '1' WHERE rand IN (".$impid.")") or die ("error in delete");
                        if(isset($Qdelete)){
                            echo "Успешно  ".$person."";
                        }
                    }if($person47['krossprod'] == 1){
                        $Qdelete = mysql_query("UPDATE `schet` SET `krossprod` =  '0' WHERE rand IN (".$impid.")") or die ("error in delete");
                        if(isset($Qdelete)){
                            echo "Успешно  ".$person."";
                        }
                    }
                }
            }
            if(isset($_POST['prodplus'])){
                if (empty($id) || $id == 0){
                    echo 'Ошибка'.$id;
                }else{
                    $impid = implode(", ",$id);
                    $q47 = "SELECT * FROM schet WHERE del = '0' AND rand =".$impid;
                    $result47 = mysql_query($q47);
                    $person47 = mysql_fetch_array($result47);
                    if($person47['prodplus'] == 0){
                        $Qdelete = mysql_query("UPDATE `schet` SET `prodplus` =  '1' WHERE rand IN (".$impid.")") or die ("error in delete");
                        if(isset($Qdelete)){
                            echo "Успешно  ".$person."";
                        }
                    }if($person47['prodplus'] == 1){
                        $Qdelete = mysql_query("UPDATE `schet` SET `prodplus` =  '0' WHERE rand IN (".$impid.")") or die ("error in delete");
                        if(isset($Qdelete)){
                            echo "Успешно  ".$person."";
                        }
                    }
                }
            }
            if(isset($_POST['incoming'])){
                if (empty($id) || $id == 0){
                    echo 'Ошибка'.$id;
                }else{
                    $impid = implode(", ",$id);
                    $q47 = "SELECT * FROM schet WHERE del = '0' AND rand =".$impid;
                    $result47 = mysql_query($q47);
                    $person47 = mysql_fetch_array($result47);
                    if($person47['incoming'] == 0){
                        $Qdelete = mysql_query("UPDATE `schet` SET `incoming` =  '1' WHERE rand IN (".$impid.")") or die ("error in delete");
                        if(isset($Qdelete)){
                            echo "Успешно  ".$person."";
                        }
                    }if($person47['incoming'] == 1){
                        $Qdelete = mysql_query("UPDATE `schet` SET `incoming` =  '0' WHERE rand IN (".$impid.")") or die ("error in delete");
                        if(isset($Qdelete)){
                            echo "Успешно  ".$person."";
                        }
                    }
                }
            }
            /*-----------*/
if(isset($_POST['postprod'])){
	if (empty($id) || $id == 0){
		echo 'Ошибка'.$id;
	}else{
		$impid = implode(", ",$id);
		$q47 = "SELECT * FROM schet WHERE del = '0' AND rand =".$impid;
		$result47 = mysql_query($q47);
		$person47 = mysql_fetch_array($result47);
		if($person47['postprod'] == 0){
			$Qdelete = mysql_query("UPDATE `schet` SET `postprod` =  '1' WHERE rand IN (".$impid.")") or die ("error in delete");
			if(isset($Qdelete)){
				echo "Успешно  ".$person."";
			}
		}if($person47['postprod'] == 1){
			$Qdelete = mysql_query("UPDATE `schet` SET `postprod` =  '0' WHERE rand IN (".$impid.")") or die ("error in delete");
			if(isset($Qdelete)){
				echo "Успешно  ".$person."";
			}
		}
	}
}
?>


<!--<div class="spoiler_links blue" >Отобразить таблицу</div>
<div class="spoiler_body"seamless>!-->
<!--<div class="test">!-->
<?php
include 'tabl.php';
?>
<div class="text-center" style="margin-top: 10px;">
    <button
        id="btn_otgr"
        type="button"
        class="btn btn-success btn-sm"
        style="min-width: 200px; margin-right: 6px;"
    >
        Подгрузить ещё
    </button>
    <button
        id="btn_otgr_all"
        type="button"
        class="btn btn-default btn-sm"
        style="min-width: 200px;"
    >
        Загрузить все счёта
    </button>
</div>

<div id="otgr_status" style="margin-top: 6px; font-size: 13px;"></div>
<!--</div>!-->
</div>
<script type="text/javascript">
    // <![CDATA[
    window.otgr_offset = 0;
    window.otgr_loading = 0;

    window.otgr_client_id   = <?php echo (int)$_GET['id']; ?>;
    window.otgr_user_id     = <?php echo (int)$userdata['users_id']; ?>;
    window.otgr_user_inogrn = <?php echo (int)$userdata['inogrn']; ?>;
    // Отдел: из URL (?otdel=N) или из профиля пользователя (выбор в шапке → users.otdel)
    window.otgr_otdel       = <?php echo isset($_GET['otdel']) ? (int)$_GET['otdel'] : (isset($userdata['otdel']) ? (int)$userdata['otdel'] : 0); ?>;
    // С какого номера продолжать нумерацию при подгрузке архивных (после активных счетов)
    window.otgr_number_start = <?php echo isset($otgr_number_start) ? (int)$otgr_number_start : 0; ?>;
    // ]]>
</script>
        <!-- -----------------------------------------------------------------




        <table class="table tablehover rowclick" id="rowclick2" style="width: 400px;">
        <?/*
        if(isset($_GET['gotov'])){$gotov = $_GET['gotov'];}
        $iz = 1;
        $query = mysql_query("SELECT DISTINCT produkt FROM schet WHERE del = '0' AND idkli = '".$_GET['id']."'  ORDER BY id DESC");
        while($row = mysql_fetch_array($query)) {
        $nameprod = mysql_fetch_array(mysql_query("SELECT * FROM produkti WHERE id =".$row['produkt']));
        $nameuslu = mysql_fetch_array(mysql_query("SELECT * FROM uslugi WHERE id =".$nameprod['parent']));
        $olddate = mysql_fetch_array(mysql_query("SELECT * FROM schet WHERE del = '0' AND idkli = '".$_GET['id']."' AND `produkt`='".$row['produkt']."' ORDER BY date_schet "));
        $newdate = mysql_fetch_array(mysql_query("SELECT * FROM schet WHERE del = '0' AND idkli = '".$_GET['id']."' AND `produkt`='".$row['produkt']."' ORDER BY date_schet DESC"));
        echo '<tr>';
        echo '<td style="padding: 6px; font-size:13px;">';
                echo $nameuslu['name'].': ';
                echo $nameprod['name'].' (';

                echo substr($olddate['date_schet'], 8, 2);
                echo '.';
                echo substr($olddate['date_schet'], 5, 2);
                echo '.';
                echo substr($olddate['date_schet'], 0, 4);

            if(!empty($newdate['dataprod']) || !empty($newdate['dataprod'])){
                echo '/';
            }
            if($newdate['dataprod'] > date('Y-m-d')){
                if(!empty($newdate['dataprod'])){
                    echo substr($newdate['dataprod'], 8, 2);
                    echo '-';
                    echo substr($newdate['dataprod'], 5, 2);
                    echo '-';
                    echo substr($newdate['dataprod'], 0, 4);
                }
                if(!empty($newdate['datasert'])){
                    echo '/';
                    echo substr($newdate['datasert'], 8, 2);
                    echo '-';
                    echo substr($newdate['datasert'], 5, 2);
                    echo '-';
                    echo substr($newdate['datasert'], 0, 4);
                }
            }else{
                if(!empty($newdate['dataprod'])){
                    echo substr($newdate['dataprod'], 8, 2);
                    echo '.';
                    echo substr($newdate['dataprod'], 5, 2);
                    echo '.';
                    echo substr($newdate['dataprod'], 0, 4);
                }
                if(!empty($newdate['datasert'])){
                    echo '/';
                    echo substr($newdate['datasert'], 8, 2);
                    echo '-';
                    echo substr($newdate['datasert'], 5, 2);
                    echo '.';
                    echo substr($newdate['datasert'], 0, 4);
                }
            }

                echo ')';
        echo '</td>';
        echo '<td>';
        $result = mysql_query("SELECT count(*) from `schet` WHERE  del = '0' AND idkli = '".$_GET['id']."' AND produkt='".$row['produkt']."'");
        echo mysql_result($result, 0);
        echo '</td>';
        echo '</tr>';
        }
        */?>
        </table>!-->



</div>

<div class="col-md-12">
<?php

# подвал
include 'footer.php';
?>
<br>

</div>

</div>
</div>
<br>
        <br>
        <br>
        <br>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <script src="js/bootstrap.min.js" async></script>
	
	
<script>
    $('#allcall').click(function () {
        document.getElementById("allmadal").style.display = 'block';
        document.getElementById("allc").style.display = 'block';

        $.ajax({
            type: "GET",
            url: "allcalltable.php",
            data: "lico=<?echo $_GET["id"]?>",
            success: function(html){
                $("#allc").html(html);
            }
        });
    });
    $('#allmadal').click(function () {
        document.getElementById("allmadal").style.display = 'none';
        document.getElementById("allc").style.display = 'none';
    });
    $('#allmadalc').click(function () {
        document.getElementById("allmadalc").style.display = 'none';
        document.getElementById("allct").style.display = 'none';
    });
    let key = document.querySelectorAll('.key');
    let display = document.querySelector('.display');
    let clear = document.querySelector('.clear');
    let clear_ownce = document.querySelector('.clear_once');
    let reg = new RegExp('^\\d+$');
    let a="";
    for(let k of key){
        k.onclick = function(){
            display.value += k.textContent;
        }
    }


    clear.onclick = function(evt){
        evt.preventDefault();
        display.value = '';
    }
    clear_ownce.onclick=function (evt)
    {
        evt.preventDefault();
        display.value=display.value.substring(0, display.value.length - 1);
    }

    $('#callphone').click(function () {
        let naim="<?php  echo htmlspecialchars($person['naim']); ?>";
        document.getElementById("allmadalc").style.display = 'none';
        document.getElementById("allct").style.height = 60+'px';
        document.getElementById("allctscrool").style.width = 700+'px';
        document.getElementById("allct").style.display = 'none';
        document.getElementById("allct").style.padding = 10+'px';
        document.getElementById("allct").style.background = '#d3d3d3';
        document.getElementById("down_once").style.display = 'block';
        document.getElementById("header").style.display = 'none';
        document.getElementById("kew").style.display = 'none';
        document.getElementById("allctscrool").style.display = 'block';
        document.getElementById("inn_call").innerHTML=<?php  echo $person['inn']; ?>;
        document.getElementById("naim_call").innerHTML=naim.split("").join("").substr(0,50)+"....";
        document.getElementById("call_incoming").style.display='block';
        writeCookie('naim', naim.split("").join("").substr(0,50)+"....", 30);

        function writeCookie(name, val, expires) {
            let date = new Date;
            date.setDate(date.getDate() + expires);
            document.cookie = name+"="+val+"; path=/; expires=" + date.toUTCString();
        }
        writeCookie('inn', <?php  echo $person['inn']; ?>, 30);

        function writeCookie(name, val, expires) {
            let date = new Date;
            date.setDate(date.getDate() + expires);
            document.cookie = name+"="+val+"; path=/; expires=" + date.toUTCString();
        }
        if(reg.test(display.value))
        {
            writeCookie('call', 'active', 30);

            function writeCookie(name, val, expires) {
                let date = new Date;
                date.setDate(date.getDate() + expires);
                document.cookie = name+"="+val+"; path=/; expires=" + date.toUTCString();
            }
            let number=display.value;
            document.getElementById("numberscrool").value = number;
            let d = new Date();
            var dd=d.getDate();
            if (dd < 10)
            {
                dd = '0' + dd;
            }
            var mm=d.getMonth()+1;
            if (mm < 10)
            {
                mm = '0' + mm;
            }
            let stat="call";
            var yy=d.getFullYear();
            var hh=d.getHours();
            var mi=d.getMinutes();
            var ss=d.getSeconds();
            var newdate=dd+"."+mm+"."+yy+": "+hh+": "+mi+": "+ss;

            $.ajax({
                type: "GET",
                url: "iptel.php",
                data: "stat="+stat+"&nuberzvon="+number+"&idkli=<?echo $_GET["id"];?>&who=<?echo $userdata['iptel'];?>&date_call="+newdate+"&id=<?echo $userdata["users_id"];?>",
                success: function(msg){
                    console.log(msg);
                    display.value=msg;
                    a=msg;
                   // console.log(a);
            writeCookiess('chanel', msg, 30);

            function writeCookiess(name, val, expires) {
                let date = new Date;
                date.setDate(date.getDate() + expires);
                document.cookie = name+"="+val+"; path=/; expires=" + date.toUTCString();
            }
            // writeCookiess('camm', "commingenter", 30);
            //
            //         function writeCookiess(name, val, expires) {
            //             let date = new Date;
            //             date.setDate(date.getDate() + expires);
            //             document.cookie = name+"="+val+"; path=/; expires=" + date.toUTCString();
            //         }
            writeCookies('number', number, 30);

            function writeCookies(name, val, expires) {
                let date = new Date;
                date.setDate(date.getDate() + expires);
                document.cookie = name+"="+val+"; path=/; expires=" + date.toUTCString();
            }
               }
            });
        }
        else
        {
            alert("Некоректный ввод номер, повторите ввод");
            display.value = '';
            document.getElementById("allmadalc").style.display = 'none';
            document.getElementById("allct").style.display = 'none';

        }
    });
    $('#hangup').click(function () {
        document.getElementById("allmadalc").style.display = 'block';
        if(a!='') {
            let stat = "hangup";
            let chynnel = a;
            $.ajax({

                type: "GET",
                url: "iptel.php",
                data: "stat=" + stat + "&chanell=" + a + "",
                success: function (msg) {
                   document.getElementById("allmadalc").style.display = 'none';
                    document.getElementById("allct").style.display = 'none';
                    console.log(a);
                   a="";
                    writeCookie('call', 'disactiv', 30);

                    function writeCookie(name, val, expires) {
                        let date = new Date;
                        document.cookie = name+"="+val+"; path=/; expires=" + date.toUTCString();
                    }
                    writeCookiess('camm', "none", 30);

                    function writeCookiess(name, val, expires) {
                        let date = new Date;
                        date.setDate(date.getDate() + expires);
                        document.cookie = name+"="+val+"; path=/; expires=" + date.toUTCString();
                    }
                    writeCookiess('rep', "none", 30);

                    function writeCookiess(name, val, expires) {
                        let date = new Date;
                        date.setDate(date.getDate() + expires);
                        document.cookie = name+"="+val+"; path=/; expires=" + date.toUTCString();
                    }
                    writeCookiess('repnumber', "none", 30);

                    function writeCookiess(name, val, expires) {
                        let date = new Date;
                        date.setDate(date.getDate() + expires);
                        document.cookie = name+"="+val+"; path=/; expires=" + date.toUTCString();
                    }
                }
            });
            document.getElementById('modal-shadowkube').style.display="none";
            document.getElementById('kube').style.display="none";
        }
        else
        {
            alert("Нет активных звонков");

            document.getElementById("allmadalc").style.display = 'none';
            document.getElementById("allct").style.display = 'none';
            document.getElementById('modal-shadowkube').style.display="none";
            document.getElementById('kube').style.display="none";
            writeCookie('call', 'disactiv', 30);

            function writeCookie(name, val, expires) {
                let date = new Date;
                date.setDate(date.getDate() + expires);
                document.cookie = name+"="+val+"; path=/; expires=" + date.toUTCString();
            }
            writeCookiessr('camm', "none", 30);

            function writeCookiessr(name, val, expires) {
                let date = new Date;
                date.setDate(date.getDate() + expires);
                document.cookie = name+"="+val+"; path=/; expires=" + date.toUTCString();
            }
            writeCookiess('rep', "none", 30);

            function writeCookiess(name, val, expires) {
                let date = new Date;
                date.setDate(date.getDate() + expires);
                document.cookie = name+"="+val+"; path=/; expires=" + date.toUTCString();
            }
            writeCookiesss('repnumber', "none", 30);

            function writeCookiesss(name, val, expires) {
                let date = new Date;
                date.setDate(date.getDate() + expires);
                document.cookie = name+"="+val+"; path=/; expires=" + date.toUTCString();
            }
            writeCookiesssr('naim', '', 30);

            function writeCookiesssr(name, val, expires) {
                let date = new Date;
                date.setDate(date.getDate() + expires);
                document.cookie = name+"="+val+"; path=/; expires=" + date.toUTCString();
            }
            writeCookierr('inn', '', 30);

            function writeCookierr(name, val, expires) {
                let date = new Date;
                date.setDate(date.getDate() + expires);
                document.cookie = name+"="+val+"; path=/; expires=" + date.toUTCString();
            }
            document.getElementById('modal-shadowkube').style.display="none";
            document.getElementById('kube').style.display="none";
        }
    });

    function fn(el) {
        writeCookie('chanel', '', 30);

        function writeCookie(name, val, expires) {
            let date = new Date;
            date.setDate(date.getDate() + expires);
            document.cookie = name + "=" + val + "; path=/; expires=" + date.toUTCString();
        }
        writeCookie('chanel2', '', 30);

        function writeCookie(name, val, expires) {
            let date = new Date;
            date.setDate(date.getDate() + expires);
            document.cookie = name + "=" + val + "; path=/; expires=" + date.toUTCString();
        }
        document.getElementById("allct").style.height = 'auto';
        document.getElementById("allct").style.display = 'block';
        document.getElementById("allct").style.width = 35+'%';
        document.getElementById("allct").style.padding = 30+'px';
        document.getElementById("down_once").style.display = 'none';
        document.getElementById("kew").style.display = 'block';
        document.getElementById("allct").style.background = 'white';
        document.getElementById("allctscrool").style.display = 'none';
        document.getElementById("numberscrool").value = "";
        document.getElementById("replay").style.display = 'none';
        document.getElementById("header").style.display = 'block';
        document.getElementById("call_incoming").style.display='none';
        let gh=el.id.substring(5,16);
        display.value=gh;
    }
    </script>
<script type="text/javascript">
    // <![CDATA[
    /**
     * Подгрузка отгруженных счетов.
     * arg:
     *   - Event (клик по "Подгрузить ещё")
     *   - {mode: 'all'} (клик по "Загрузить все счёта")
     */
    window.load_otgr_5 = function (arg) {
        var loadAll = false;

        // Если пришёл объект настроек, а не событие
        if (arg && typeof arg === 'object' && !('preventDefault' in arg) && arg.mode === 'all') {
            loadAll = true;
        }
        // Если пришло событие — отменим стандартное действие
        if (arg && typeof arg === 'object' && typeof arg.preventDefault === 'function') {
            arg.preventDefault();
        }

        if (window.otgr_loading) return false;
        window.otgr_loading = 1;

        var btn     = document.getElementById('btn_otgr');
        var btnAll  = document.getElementById('btn_otgr_all');
        var body    = document.getElementById('otgr_body');
        var status  = document.getElementById('otgr_status');

        if (!btn || !body || !status) {
            alert('Не найден btn_otgr / otgr_body / otgr_status');
            window.otgr_loading = 0;
            return false;
        }

        btn.disabled = true;
        if (btnAll) btnAll.disabled = true;
        status.innerHTML = 'Загрузка...';

        var returnUrl = window.location.pathname + window.location.search;

        var url = 'table_otgr_ajax.php' +
            '?id=' + encodeURIComponent(window.otgr_client_id) +
            '&offset=' + encodeURIComponent(window.otgr_offset) +
            '&uid=' + encodeURIComponent(window.otgr_user_id) +
            '&inogrn=' + encodeURIComponent(window.otgr_user_inogrn) +
            '&otdel=' + encodeURIComponent(window.otgr_otdel) +
            '&number_start=' + encodeURIComponent(window.otgr_number_start) +
            '&return=' + encodeURIComponent(returnUrl);

        var xhr = new XMLHttpRequest();
        xhr.open('GET', url, true);

        xhr.onreadystatechange = function () {
            if (xhr.readyState !== 4) return;

            window.otgr_loading = 0;
            btn.disabled = false;
            if (btnAll) btnAll.disabled = false;

            if (xhr.status !== 200) {
                status.innerHTML = 'Ошибка загрузки';
                return;
            }

            var data;
            try {
                data = JSON.parse(xhr.responseText);
            } catch (e) {
                status.innerHTML = 'Неверный JSON';
                return;
            }

            if (data.html) {
                // Скрипты в inctoha.php выводятся внутри <td> (внутри <tr>). Их нужно
                // вынуть из фрагмента, вставить строки в DOM, затем выполнить скрипты
                // (чтобы jQuery/document находили элементы по id).
                var tmp = document.createElement('tbody');
                tmp.innerHTML = data.html;

                var scriptNodes = tmp.getElementsByTagName('script');
                var scriptTexts = [];
                var i, node;
                for (i = 0; i < scriptNodes.length; i++) {
                    node = scriptNodes[i];
                    scriptTexts.push(node.src ? { src: node.src, text: null } : { src: null, text: (node.textContent || node.innerText || '') });
                }
                for (i = scriptNodes.length - 1; i >= 0; i--) {
                    node = scriptNodes[i];
                    if (node.parentNode) node.parentNode.removeChild(node);
                }
                while (tmp.firstChild) {
                    body.appendChild(tmp.firstChild);
                }
                for (i = 0; i < scriptTexts.length; i++) {
                    var s = document.createElement('script');
                    if (scriptTexts[i].src) {
                        s.src = scriptTexts[i].src;
                    } else {
                        s.text = scriptTexts[i].text;
                    }
                    (document.body || document.documentElement).appendChild(s);
                }
            }

            var fetched = parseInt(data.fetched || 0, 10);
            if (!isNaN(fetched) && fetched > 0) {
                window.otgr_offset += fetched;
            }

            // Режим "подгрузить ещё" — одна пачка
            if (!loadAll) {
                if (fetched < 5) {
                    btn.style.display = "none";
                    if (btnAll) btnAll.style.display = "none";
                    status.innerHTML = "Больше счетов нет";
                } else {
                    status.innerHTML = "";
                    btn.innerHTML = "Подгрузить ещё";
                }
                return;
            }

            // Режим "загрузить все" — тянем, пока приходит по 5 строк
            if (fetched < 5) {
                // всё загрузили
                btn.style.display = "none";
                if (btnAll) btnAll.style.display = "none";
                status.innerHTML = "Загружены все счёта";
            } else {
                // ещё есть данные — тянем следующую пачку
                window.load_otgr_5({mode: 'all'});
            }
        };

        xhr.send(null);
        return false;
    };

    document.addEventListener("DOMContentLoaded", function () {
        var b  = document.getElementById("btn_otgr");
        var ba = document.getElementById("btn_otgr_all");

        if (b)  b.addEventListener("click", window.load_otgr_5);
        if (ba) ba.addEventListener("click", function (e) {
            window.load_otgr_5({mode: 'all'});
            if (e && typeof e.preventDefault === 'function') {
                e.preventDefault();
            }
        });
    });
    // ]]>
</script>

</body>
</html>
