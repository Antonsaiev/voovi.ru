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

if (!function_exists('newschet_h')) {
    function newschet_h($value)
    {
        return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists('newschet_icon_url')) {
    function newschet_icon_url($value)
    {
        $value = trim((string)$value);
        if ($value === '' || $value === '0' || $value === '1') {
            return '/img/product_icons_20x20.png';
        }
        if (preg_match('/^url\((["\']?)(.*?)\1\)$/', $value, $matches)) {
            $value = trim($matches[2]);
        }
        if ($value === '') {
            return '/img/product_icons_20x20.png';
        }
        if ($value[0] !== '/') {
            $value = '/'.ltrim($value, '/');
        }
        if (strpos($value, '/img/') !== 0) {
            return '/img/product_icons_20x20.png';
        }

        return $value;
    }
}

$newschetEmbedded = isset($_GET['rand']);
$newschetClientId = isset($_GET['id']) ? intval($_GET['id']) : 0;
$newschetProductId = isset($_GET['parent']) ? intval($_GET['parent']) : 0;
$newschetTipId = isset($_GET['tip']) ? intval($_GET['tip']) : 0;
$newschetProduct = mysql_fetch_assoc(mysql_query("SELECT * FROM `produkti` WHERE id = ".$newschetProductId));
$newschetService = mysql_fetch_assoc(mysql_query("SELECT * FROM `uslugi` WHERE id = ".$newschetTipId));
$newschetClient = mysql_fetch_assoc(mysql_query("SELECT * FROM `ogrn` WHERE id = ".$newschetClientId));
$newschetIconValue = isset($newschetProduct['icon']) && $newschetProduct['icon'] !== '' ? $newschetProduct['icon'] : (isset($newschetProduct['tel']) ? $newschetProduct['tel'] : '');
$newschetIconUrl = newschet_icon_url($newschetIconValue);
$newschetIconIsSprite = $newschetIconUrl === '/img/product_icons_20x20.png';
$newschetBackParams = array(
    'id' => isset($_GET['id']) ? $_GET['id'] : '',
    'ogrn' => isset($_GET['ogrn']) ? $_GET['ogrn'] : '',
    'parent' => isset($_GET['tip']) ? $_GET['tip'] : ''
);
if (isset($_GET['inn'])) {
    $newschetBackParams['inn'] = $_GET['inn'];
}
if (isset($_GET['kpp'])) {
    $newschetBackParams['kpp'] = $_GET['kpp'];
}
if (isset($_GET['head'])) {
    $newschetBackParams['head'] = $_GET['head'];
}
if (isset($_GET['rand'])) {
    $newschetBackParams['rand'] = $_GET['rand'];
}
$newschetBackHref = '/newusluga.php?'.http_build_query($newschetBackParams);
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
body.newschet-modern {
    margin: 0;
    background: #f3f6f8;
    color: #26313d;
    font-family: "Helvetica Neue", Arial, sans-serif;
}
.newschet-modern *,
.newschet-modern *:before,
.newschet-modern *:after {
    box-sizing: border-box;
}
.newschet-page {
    width: 100%;
    max-width: 1180px;
    margin: 74px auto 34px;
    padding: 0 16px;
}
.newschet-page.is-embedded {
    margin-top: 18px;
}
.newschet-main {
    padding-left: 0;
}
.newschet-sidebar {
    padding-right: 0;
}
.newschet-titlebar,
.newschet-card,
.newschet-sidebar-card {
    border: 1px solid #dfe6ec;
    border-radius: 8px;
    background: #fff;
    box-shadow: 0 8px 22px rgba(31, 45, 58, 0.05);
}
.newschet-titlebar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
    margin-bottom: 14px;
    padding: 18px 20px;
}
.newschet-title-main {
    display: flex;
    align-items: center;
    min-width: 0;
    gap: 13px;
}
.newschet-product-icon {
    flex: 0 0 42px;
    width: 42px;
    height: 42px;
    border: 1px solid #dfe6ec;
    border-radius: 8px;
    background-color: #fff;
    background-repeat: no-repeat;
    background-position: center;
    background-size: contain;
}
.newschet-product-icon.is-sprite {
    background-size: auto;
}
.newschet-eyebrow {
    margin-bottom: 4px;
    color: #526170;
    font-size: 12px;
    font-weight: 700;
    line-height: 1.2;
    text-transform: uppercase;
}
.newschet-titlebar h1 {
    margin: 0;
    color: #26313d;
    font-size: 24px;
    font-weight: 700;
    line-height: 1.25;
    overflow-wrap: anywhere;
}
.newschet-client {
    margin-top: 5px;
    color: #526170;
    font-size: 14px;
    line-height: 1.35;
    overflow-wrap: anywhere;
}
.newschet-back {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-height: 36px;
    padding: 8px 13px;
    border: 1px solid #cfd8e3;
    border-radius: 6px;
    background: #fff;
    color: #26313d;
    font-size: 13px;
    font-weight: 700;
    line-height: 1.2;
    text-decoration: none;
    white-space: nowrap;
}
.newschet-back:hover,
.newschet-back:focus {
    border-color: #2f7fb8;
    color: #2f7fb8;
    text-decoration: none;
    outline: 0;
}
.newschet-card,
.newschet-sidebar-card {
    padding: 16px;
}
.newschet-card form {
    margin: 0;
}
.newschet-card table.table {
    margin-bottom: 12px;
    border: 1px solid #edf1f5;
    border-radius: 8px;
    background: #fff;
    overflow: hidden;
}
.newschet-card table.table > tbody > tr > td {
    border-color: #edf1f5;
    padding: 10px !important;
    vertical-align: middle;
}
.newschet-card input[type="text"],
.newschet-card input[type="number"],
.newschet-card input[type="date"],
.newschet-card select,
.newschet-card textarea,
.newschet-card .form-control {
    min-height: 36px;
    border: 1px solid #cfd8e3;
    border-radius: 6px;
    box-shadow: none;
    color: #26313d;
    font-size: 14px;
}
.newschet-card input[type="submit"] {
    min-height: 42px;
    padding: 9px 16px;
    border: 0;
    border-radius: 8px;
    background: #2f86c1;
    color: #fff;
    font-size: 16px;
    font-weight: 700;
}
.newschet-card input[type="submit"]:hover,
.newschet-card input[type="submit"]:focus {
    background: #2876aa;
    outline: 0;
}
@media (max-width: 860px) {
    .newschet-page {
        margin-top: 64px;
        padding: 0 10px;
    }
    .newschet-page.is-embedded {
        margin-top: 10px;
    }
    .newschet-titlebar {
        align-items: flex-start;
        flex-direction: column;
        padding: 14px;
    }
    .newschet-back {
        width: 100%;
    }
    .newschet-main,
    .newschet-sidebar {
        padding: 0;
    }
    .newschet-sidebar {
        margin-top: 14px;
    }
}
@media (max-width: 560px) {
    .newschet-title-main {
        align-items: flex-start;
    }
    .newschet-titlebar h1 {
        font-size: 20px;
    }
    .newschet-card table.table > tbody > tr > td {
        display: block;
        width: 100% !important;
    }
}
</style>

</head>
<body class="newschet-modern">
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
    echo '<script src="/js/jquery-1.11.0.min.js"></script><script src="/js/jquery-live-compat.js?v=1"></script>';
}else{
    include 'header.php';
}
?>
<div class="container newschet-page<?php if ($newschetEmbedded) { echo ' is-embedded'; } ?>">
<div class="row">
<?php 

if(isset($_GET['rand'])){
echo '<div class="col-md-12 newschet-main">';
}else{
echo '<div class="col-md-8 newschet-main">';
}

?>
<div class="newschet-titlebar">
    <div class="newschet-title-main">
        <span class="newschet-product-icon<?php if ($newschetIconIsSprite) { echo ' is-sprite'; } ?>" style="background-image: url('<?php echo newschet_h($newschetIconUrl); ?>'); background-position: <?php echo $newschetIconIsSprite ? '7px -300px' : 'center'; ?>;"></span>
        <div>
            <div class="newschet-eyebrow">Выставление счета</div>
            <h1><?php echo newschet_h(isset($newschetProduct['name']) ? $newschetProduct['name'] : ''); ?></h1>
            <div class="newschet-client"><?php echo newschet_h(isset($newschetClient['naim']) ? $newschetClient['naim'] : ''); ?><?php if (!empty($newschetService['name'])) { ?> · <?php echo newschet_h($newschetService['name']); ?><?php } ?></div>
        </div>
    </div>
    <a class="newschet-back" href="<?php echo newschet_h($newschetBackHref); ?>"><span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>&nbsp;Назад к продуктам</a>
</div>
<div class="bs-example newschet-card">
<?php
$person = $newschetProduct ? $newschetProduct : mysql_fetch_array(mysql_query("SELECT * FROM `produkti` WHERE id =".intval($_GET['parent'])));
?>
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
echo '<div class="col-md-4 newschet-sidebar"><div class="newschet-sidebar-card">';
include 'docmyogrn.php';
echo '</div></div>';
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
