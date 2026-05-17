<?php
# подключаем конфиг
include 'conf.php';  
require_once 'schet_identity.php';

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
$schetLoadingVisible = isset($_POST['submitsch']);
$schetLoadingTitle = 'Создаем счет';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ru" lang="ru">
<head>
<title></title>
<meta http-equiv="content-type" content="text/html" charset="utf-8" />
<meta http-equiv="Content-Style-Type" content="text/css" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="css/bootstrap.min.css" rel="stylesheet">
<style type="text/css">
.schet-loading-overlay {
    display: none;
    position: fixed;
    top: 0;
    right: 0;
    bottom: 0;
    left: 0;
    z-index: 99999;
    background: rgba(15, 23, 42, 0.55);
    text-align: center;
}
.schet-loading-overlay.is-visible {
    display: block;
}
.schet-loading-overlay:before {
    content: "";
    display: inline-block;
    height: 100%;
    vertical-align: middle;
}
.schet-loading-card {
    display: inline-block;
    width: 340px;
    max-width: 86%;
    padding: 28px 24px;
    vertical-align: middle;
    background: #fff;
    border-radius: 8px;
    box-shadow: 0 18px 54px rgba(0, 0, 0, 0.26);
    font-family: Arial, Helvetica, sans-serif;
}
.schet-loading-spinner {
    width: 56px;
    height: 56px;
    margin: 0 auto 18px;
    border: 4px solid #e7edf3;
    border-top-color: #26BB84;
    border-right-color: #3f77ae;
    border-radius: 50%;
    animation: schet-loading-spin 0.8s linear infinite;
}
.schet-loading-title {
    margin-bottom: 8px;
    color: #1f2937;
    font-size: 20px;
    font-weight: bold;
}
.schet-loading-text {
    color: #64748b;
    font-size: 14px;
    line-height: 1.45;
}
@keyframes schet-loading-spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}
</style>

</head>
<body>
<div id="schetLoadingOverlay" class="schet-loading-overlay<?php if ($schetLoadingVisible) { echo ' is-visible'; } ?>">
    <div class="schet-loading-card">
        <div class="schet-loading-spinner"></div>
        <div id="schetLoadingTitle" class="schet-loading-title"><?php echo $schetLoadingTitle; ?></div>
        <div class="schet-loading-text">Пожалуйста, подождите. Анкета откроется автоматически.</div>
    </div>
</div>
<?php
if ($schetLoadingVisible) {
    echo str_repeat(' ', 2048);
    @ob_flush();
    @flush();
}
?>
<?php
if(isset($_GET['rand'])){
echo '<script src="/js/jquery-1.11.0.min.js"></script><script src="/js/jquery-live-compat.js?v=1"></script><div style="
margin-bottom: 25px;
  font-size: 21px;
  background: #26BB84;
  text-align: center;
  padding: 8px;
  color: #fff;
">Новый продукт для ';

		$q1 = "SELECT * FROM `ogrn` WHERE id =$_GET[id]";
		$result1 = mysql_query($q1);
		$person1 = mysql_fetch_array($result1);
		
		echo $person1['naim'].'</div>';
}else{
include 'header.php';  }

if(isset($_GET['rand'])){
echo '<div class="container" style="margin-top: 20px;">';
}else{
echo '<div class="container" style="margin-top: 60px;">';  }
?>
<div class="row">
<?php 

if(isset($_GET['rand'])){
echo '<div class="col-md-12">';
}else{
echo '<div class="col-md-8">';
}

?>
<div class="bs-example">
<strong><h4 style="margin-top: 10px; font-weight: bold; border-bottom: 1px #333 solid;"><a href="/newusluga.php?id=<?php echo $_GET['id']; ?>&ogrn=<?php echo $_GET['ogrn']; ?>&parent=<?php echo $_GET['tip']; 

if(isset($_GET['rand'])){
echo '&rand='.$_GET['rand'];
}




?>"><< Назад</a> : Счет на "<?php
$q = "SELECT * FROM `produkti` WHERE id =$_GET[parent]";
$result = mysql_query($q);
$person = mysql_fetch_array($result);
echo $person['name'];
?>"</h4></strong>
<?php 
$shetrand = '';

if(isset($_POST['submitsch'])){

$ogrn = "SELECT * FROM ogrn WHERE inn ='".$_GET['inn']."' AND kpp ='".$_GET['kpp']."' ";
$rogrn = mysql_query($ogrn);
$pogrn = mysql_fetch_array($rogrn);
$inn = $pogrn['inn'];
$kpp = $pogrn['kpp'];
$naim = $pogrn['naim'];
$agent=htmlspecialchars($_POST["agent"]);
$schetIdentity = voovi_allocate_schet_identity(date("y"), $userdata['users_id'], $userdata['otdel']);
$shetrand = $schetIdentity['rand'];
$sujmdfhsd = $schetIdentity['kolichschet'];
$schetNs = $schetIdentity['ns'];
$_POST['shetrand'] = $shetrand;

$qrand = "SELECT sortir FROM `schet` ORDER BY sortir DESC LIMIT 1";
					$resultrand = mysql_query($qrand);
					$personrand = mysql_fetch_array($resultrand);
					
echo $personrand['sortir'];
$var = $personrand['sortir'] + 1;


$qs = "SELECT count(*) FROM schet WHERE idkli = '".$_GET['id']."' AND produkt = '".$_GET['parent']."'";
$rs = mysql_query($qs);
$ps = mysql_result($rs, 0);
$zs = $ps['nomerchet']+ 1;
	$azaza = "INSERT INTO `schet` (
    `ns`,
	`idkli`,
`sortir`,
`otdel`,
`filial`,
`god`,
`nomerdog`,
`nomerschet`,
`kolichschet`,
`nomerschetks`,
`kvo`,
`prod`,
`goroddd`,
`prodlen`,
`skidka`,
`priceks`,
`ogrn`,
`name`,
`inn`,
`kpp`,
`produkt`,
`kto`,
`data`,
`d`,
`m`,
`y`,
`time`,
`rand`,
`oferta`,
`url`,
`agent`
	) VALUES (
    '".$schetNs."',
	'".$_GET['id']."',
'".$var."',
'".$userdata['otdel']."',
'".$userdata['filial']."',
'".date("y")."',
'В КС',
'В КС',
'".$sujmdfhsd."',
'$_POST[nomerschetks]',
'".$_POST["input".$rowazaza['id']]."',
'".$rowazaza['id']."',
'$_POST[goroddd]',
'0',
'$_POST[skidka]',
'$_POST[aallsumm]',
'".$_GET['ogrn']."',
'".$naim."',
'".$_GET['inn']."',
'".$_GET['kpp']."',
'$_GET[parent]',
'".$userdata['users_id']."',
'".date("d.m.y в H:i")."',
'".date("d")."',
'".date("m")."',
'".date("Y")."',
'".date("H:i")."',
'$_POST[shetrand]',
'$_POST[dog]',
'$_POST[url]',
'$agent'
)";
mysql_query($azaza) or die(mysql_error($link));
$data_documents_str = "INSERT INTO `document_data` (
    `rand`,
    `d_bill`,
    `d_contract`,
    `d_specification`,
    `d_act`,
    `d_tn`
    ) VALUES (
    '$_POST[shetrand]',
    '".date("y-m-d")."',
    '".date("y-m-d")."',
    '".date("y-m-d")."',
    '".date("y-m-d")."',
    '".date("y-m-d")."'
    )";
mysql_query($data_documents_str) or die(mysql_error($link));
echo '<script type="text/javascript">'; 
echo 'window.location.href="/kartklient.php?id='.$_GET['id'].'";'; 
echo '</script>';

}

?>
<form method="post" id="schetForm">
<table class="table">
<tr><td style="width:20px;">
Ссылка
</td>
<td style="padding: 5px;">
<input id="url" name="url" class="form-control" type="text" value="" style="width:100%;">
</td>
</tr>
<tr><td style="width:1px;">
Сумма:
</td>
<td  style="padding: 5px;">
<p id="allsumm"></p>
<input type="text" class="form-control" name="aallsumm" id="aallsumm">
</td></tr>
<tr><td style="width:1px;">
Номер счета:
</td>
<td  style="padding: 5px;">
<input type="text" class="form-control" name="nomerschetks" id="nomerschetks"  value="">
</td></tr>
</table>
<div>
<br>
</div>
<input type="text" style="display: none;" name="shetrand" id="shetrand" value="<?php echo $shetrand; ?>">
<input type="submit" name="submitsch" value="Создать" class="btn btn-success" />
</form>
</div>



</div>

<?php 

if(isset($_GET['rand'])){
}else{
echo '<div class="col-md-4">';
include 'docmyogrn.php';
echo '</div>';
}

?>


</div>
</div>







<script src="/js/jquery-1.11.0.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script type="text/javascript">
(function () {
    function onReady(fn) {
        if (document.readyState === 'complete' || document.readyState === 'interactive') {
            window.setTimeout(fn, 0);
        } else if (document.addEventListener) {
            document.addEventListener('DOMContentLoaded', fn);
        } else {
            window.attachEvent('onload', fn);
        }
    }

    onReady(function () {
        var form = document.getElementById('schetForm');
        var overlay = document.getElementById('schetLoadingOverlay');
        var title = document.getElementById('schetLoadingTitle');
        var submittedBy = '';

        if (!form || !overlay) {
            return;
        }

        var inputs = form.getElementsByTagName('input');
        for (var i = 0; i < inputs.length; i++) {
            if (inputs[i].type === 'submit') {
                inputs[i].onclick = function () {
                    submittedBy = this.name || '';
                };
            }
        }

        form.onsubmit = function () {
            if (form.getAttribute('data-loading') === '1') {
                return false;
            }

            if (form.checkValidity && !form.checkValidity()) {
                if (form.reportValidity) {
                    form.reportValidity();
                }
                return false;
            }

            form.setAttribute('data-loading', '1');
            if (!submittedBy && form.elements['submitsave']) {
                submittedBy = 'submitsave';
            }
            if (title) {
                title.innerHTML = submittedBy === 'submitsave' ? 'Сохраняем счет' : 'Создаем счет';
            }
            if (overlay.className.indexOf('is-visible') === -1) {
                overlay.className += ' is-visible';
            }

            return true;
        };
    });
}());
</script>
</body>
</html>
