<?php
# подключаем конфиг
include 'conf.php';  
require_once 'schet_identity.php';
require_once 'tarif_identity.php';

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

$qestar = "SELECT DISTINCT nomerschet,ogrn,prodlen,generac,name,kolichschet,lico,rand,otdel,filial,god,nomerdog,data,produkt,price,kto,inn,kpp,idkli,koment,oplachen,oplachenks,doljen,gotov,akt,url,groupi,gr,gen,kvo,prod,kvogorod,goroddd,skidka,priceks,d,m,y,time,oferta,del FROM schet WHERE del = '0' AND rand ='".$_GET['rand']."'";
$restar = mysql_query($qestar);
$pestar = mysql_fetch_array($restar);

$qestar2 = "SELECT * FROM schet WHERE rand ='".$_GET['rand']."'";
$restar2 = mysql_query($qestar2);
$pestar2 = mysql_fetch_array($restar2);
$schetLoadingVisible = isset($_POST['submitsch']) || isset($_POST['submitsave']);
$schetLoadingTitle = isset($_POST['submitsave']) ? 'Сохраняем счет' : 'Создаем счет';

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

$newschetEmbedded = isset($_GET['rand']) || (isset($_GET['head']) && $_GET['head'] == 1);
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
    <script src="/js/cdnjs.cloudflare.com_ajax_libs_decimal.js_9.0.0_decimal.min.js"></script>
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
    float: none;
    width: 100%;
    padding: 0;
}
.newschet-titlebar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
    margin-bottom: 14px;
    padding: 18px 20px;
    border: 1px solid #dfe6ec;
    border-radius: 8px;
    background: #fff;
    box-shadow: 0 8px 22px rgba(31, 45, 58, 0.06);
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
.newschet-card {
    padding: 16px;
    border: 1px solid #dfe6ec;
    border-radius: 8px;
    background: #fff;
    box-shadow: 0 8px 22px rgba(31, 45, 58, 0.05);
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
.newschet-card table.table > tbody > tr > td,
.newschet-card table.table > thead > tr > th {
    border-color: #edf1f5;
    padding: 10px !important;
    vertical-align: middle;
}
.newschet-card table.table > thead > tr > th {
    background: #eef3f7;
    color: #52616f;
    font-size: 12px;
    font-weight: 700;
    text-transform: uppercase;
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
.newschet-card input[type="checkbox"] {
    width: 18px;
    height: 18px;
    margin: 0;
}
.newschet-card input[type="submit"] {
    width: 100%;
    min-height: 42px;
    margin-top: 8px;
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
.newschet-card #prodkschet {
    overflow-x: auto;
}
.newschet-card #tab {
    min-width: 560px;
    font-size: 13px;
}
.newschet-card #tab > tbody > tr > td,
.newschet-card #tab > thead > tr > th {
    padding: 5px 7px !important;
    line-height: 1.2;
}
.newschet-card #tab > thead > tr > th {
    font-size: 11px;
}
.newschet-card #tab .newschet-tarif-check {
    width: 28px;
    text-align: center;
}
.newschet-card #tab .newschet-tarif-price,
.newschet-card #tab .newschet-tarif-sum {
    width: 84px;
}
.newschet-card #tab .newschet-tarif-qty {
    width: 62px;
}
.newschet-card #tab input[type="checkbox"] {
    width: 16px;
    height: 16px;
}
.newschet-card #tab .newschet-qty-input {
    width: 56px !important;
    min-height: 28px;
    height: 28px;
    padding: 2px 5px;
    font-size: 13px;
}
.newschet-card #tab .newschet-total-sum {
    font-size: 14px;
}
.newschet-card #oldns {
    float: none !important;
    width: 100% !important;
    margin: 12px 0;
    padding: 12px;
    border: 1px solid #dfe6ec;
    border-radius: 8px;
    background: #f8fafb;
}
.newschet-card #oldns > div:first-child {
    float: none !important;
    width: auto !important;
    display: flex;
    align-items: center;
    gap: 10px;
    flex-wrap: wrap;
}
.newschet-mode-row {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 12px;
}
.newschet-mode-row label {
    margin: 0;
    color: #526170;
    font-size: 13px;
    font-weight: 700;
}
.newschet-mode-row #idprodkschet {
    width: auto;
    min-width: 190px;
}
.newschet-old-input-row {
    display: flex;
    align-items: flex-end;
    gap: 12px;
    flex-wrap: wrap;
}
.newschet-old-input {
    flex: 1 1 260px;
    min-width: 220px;
}
.newschet-old-input label {
    display: block;
    margin: 0 0 6px;
    color: #526170;
    font-size: 13px;
    font-weight: 700;
}
.newschet-old-input input {
    width: 100%;
}
.newschet-old-status {
    flex: 1 1 320px;
    min-width: 240px;
    padding: 8px 0;
    color: #526170;
    font-size: 13px;
}
.newschet-old-suggestions {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    margin-top: 12px;
}
.newschet-old-option {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    max-width: 100%;
    padding: 7px 10px;
    border: 1px solid #cfd8e3;
    border-radius: 6px;
    background: #fff;
    color: #26313d;
    font-size: 13px;
    font-weight: 700;
    line-height: 1.25;
    text-align: left;
}
.newschet-old-option span {
    color: #526170;
    font-weight: 400;
    overflow-wrap: anywhere;
}
.newschet-old-option:hover,
.newschet-old-option:focus {
    border-color: #2f7fb8;
    color: #2f7fb8;
    outline: 0;
}
.newschet-tarif-row {
    transition: background 0.15s ease, box-shadow 0.15s ease;
}
.newschet-tarif-row.is-selected {
    background: #f3f9fd;
    box-shadow: inset 3px 0 0 #2f86c1;
}
.newschet-tarif-name {
    font-weight: 700;
}
.newschet-tarif-price,
.newschet-tarif-sum {
    white-space: nowrap;
}
.newschet-qty-input,
.newschet-discount-input {
    width: 74px !important;
    max-width: 100%;
    text-align: center;
}
.newschet-total-table {
    background: #f8fafb !important;
}
.newschet-total-row td {
    background: #eaf3f9;
    font-size: 15px;
}
.newschet-total-label {
    color: #26313d;
    font-size: 16px;
    font-weight: 800;
}
.newschet-discount-cell {
    white-space: nowrap;
}
.newschet-discount-wrap {
    display: inline-flex;
    align-items: center;
    gap: 8px;
}
.newschet-total-sum {
    margin: 0;
    color: #1f6f9d;
    font-size: 18px;
    font-weight: 800;
    white-space: nowrap;
}
.newschet-card .av {
    margin-top: 12px;
    clear: both;
}
.newschet-card .av .form-control,
.newschet-card .newschet-av-panel {
    float: none !important;
    width: 100% !important;
    height: auto !important;
    min-height: 0;
    margin-bottom: 12px;
    padding: 12px;
}
.newschet-card .av label {
    color: #526170;
    font-size: 13px;
    line-height: 1.35;
}
.newschet-card .av select {
    max-width: 100%;
}
.newschet-av-panel {
    border: 1px solid #edf1f5;
    border-radius: 8px;
    background: #fff;
}
.newschet-av-row,
.newschet-dp-row {
    display: flex !important;
    flex-wrap: wrap;
    align-items: center;
    gap: 8px 12px;
    width: 100% !important;
    min-height: 0 !important;
    height: auto !important;
    float: none !important;
    overflow: visible;
}
#dobp.newschet-av-panel {
    display: grid !important;
    gap: 8px;
    overflow: visible;
}
.newschet-dp-row {
    display: grid !important;
    grid-template-columns: minmax(250px, 1.35fr) minmax(190px, 0.8fr) auto;
    padding: 7px 8px !important;
    border: 1px solid #edf1f5;
    border-radius: 8px;
    background: #f8fafb;
}
.newschet-av-product,
.newschet-av-field {
    display: inline-flex !important;
    align-items: center;
    gap: 7px;
    width: auto !important;
    height: auto !important;
    min-height: 32px;
    float: none !important;
    margin: 0 !important;
    padding: 0 !important;
}
.newschet-av-product {
    flex: 1 1 330px;
    min-width: 230px;
    color: #26313d;
    font-weight: 800;
}
.newschet-av-product span {
    color: #526170;
    font-size: 13px;
    font-weight: 800;
    text-transform: uppercase;
}
.newschet-av-product strong {
    display: inline-block;
    min-width: 0;
    overflow-wrap: anywhere;
}
.newschet-av-field label {
    width: auto !important;
    margin: 0 !important;
    padding: 0 !important;
    color: #26313d !important;
    font-size: 12px !important;
    font-weight: 800;
    white-space: nowrap;
}
.newschet-av-field select,
.newschet-av-field input {
    width: auto !important;
    min-width: 0;
    min-height: 32px !important;
    height: 32px !important;
    margin: 0 !important;
    padding: 4px 8px !important;
    font-size: 13px !important;
}
.newschet-av-type {
    flex: 0 0 280px;
}
.newschet-dp-product {
    display: grid !important;
    grid-template-columns: auto auto minmax(150px, 1fr);
    flex: 1 1 320px;
    min-width: 0;
}
.newschet-dp-select-wrap {
    min-width: 0;
}
.newschet-dp-select-wrap select {
    width: 100% !important;
}
.newschet-dp-type {
    display: grid !important;
    grid-template-columns: auto minmax(130px, 1fr);
    flex: 0 0 220px;
    min-width: 0;
}
.newschet-av-services {
    flex: 1 1 100%;
    width: 100% !important;
    margin-top: 4px !important;
    padding-top: 8px !important;
    border-top: 1px solid #edf1f5;
}
.newschet-dp-services {
    grid-column: 1 / -1;
}
.newschet-av-services > div {
    flex: 1 1 220px;
}
.newschet-av-services select {
    width: 100% !important;
}
.newschet-av-sht,
.newschet-dp-sht {
    flex: 0 0 auto;
    display: grid !important;
    grid-template-columns: auto 56px;
    min-width: 0;
}
.newschet-av-sht input,
.newschet-dp-sht input {
    width: 56px !important;
    min-width: 56px;
    text-align: center;
}
.newschet-add-dp-button {
    min-height: 32px;
    padding: 5px 9px;
    border: 1px solid #cfd8e3;
    border-radius: 6px;
    background: #fff;
    color: #2f7fb8;
    font-size: 13px;
    font-weight: 800;
    line-height: 1;
}
.newschet-is-hidden {
    display: none !important;
}
.newschet-card #countdisplay {
    display: none !important;
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
    .newschet-card {
        padding: 12px;
    }
    .newschet-mode-row,
    .newschet-old-input-row {
        align-items: stretch;
        flex-direction: column;
    }
    .newschet-mode-row #idprodkschet,
    .newschet-old-input,
    .newschet-old-status {
        width: 100%;
        min-width: 0;
    }
    .newschet-card .av [style*="float: left"] {
        float: none !important;
        width: 100% !important;
        height: auto !important;
        margin-left: 0 !important;
        padding-left: 0 !important;
    }
    .newschet-card .av select[style],
    .newschet-card .av input[style] {
        width: 100% !important;
        margin-left: 0 !important;
    }
    .newschet-av-row,
    .newschet-dp-row {
        flex-wrap: wrap;
        grid-template-columns: 1fr;
        overflow: visible;
    }
    .newschet-av-product,
    .newschet-av-type,
    .newschet-dp-product,
    .newschet-dp-type {
        flex: 1 1 100%;
        min-width: 0;
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
    .newschet-card #tab > tbody > tr > td,
    .newschet-card #tab > thead > tr > th,
    .newschet-card .newschet-total-table > tbody > tr > td {
        display: table-cell;
        width: auto !important;
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
if($newschetEmbedded){
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
$id = (int)$_GET['parent']; // приводим к int
$person = $newschetProduct ? $newschetProduct : mysql_fetch_assoc(mysql_query("SELECT * FROM `produkti` WHERE id = $id"));

// tip → uslugi
$tip    = isset($_GET['tip']) ? (int)$_GET['tip'] : 0;
$usluga = $newschetService ? $newschetService : mysql_fetch_assoc(mysql_query("SELECT * FROM `uslugi` WHERE `id` = $tip"));

if ($usluga && $usluga['nds'] !== '' && $usluga['nds'] !== 'none' && $usluga['nds'] !== null) {
    $nds = "'" . mysql_real_escape_string($usluga['nds']) . "'";
} else {
    // в базе будет именно NULL
    $nds = "NULL";
}

?>
<?php
if(isset($_GET['rand'])){
$shetrand = $_GET['rand'];
}else{
$shetrand = '';
}
$sujmdfhsd = '';
$schetNs = '';

$qs = mysql_query("SELECT count(*) FROM schet WHERE idkli = '".$_GET['id']."' AND produkt = '".$_GET['parent']."'");
$newschetExistingCount = mysql_result($qs, 0);
$qs = mysql_query("SELECT * FROM schet WHERE idkli = '".$_GET['id']."' ORDER BY id DESC LIMIT 1");
$ps = mysql_fetch_array($qs);
$zs = $ps['nomerschet'] + 1;

$qre = "SELECT * FROM `produkti` WHERE id =$_GET[parent]";
$resultre = mysql_query($qre);
$personre = mysql_fetch_array($resultre);
if(isset($_POST['submitsch'])){
	//if($_GET['parent']==319&&$_POST['kol_opis']==""||$_GET['parent']==400&&$_POST['kol_opis']=="" ||$_GET['parent']==391&&$_POST['kol_opis']==""&&$_GET['parent']==319)
    if (in_array($_GET['parent'], [319, 400, 391, 426, 428, 427]) && $_POST['kol_opis'] == "")
	{
		echo'<script>alert("Не введено количесвто описей")</script>';
	}
		else
		{
        $schetIdentity = voovi_allocate_schet_identity(date("y"), $userdata['users_id'], $userdata['otdel']);
        $shetrand = $schetIdentity['rand'];
        $sujmdfhsd = $schetIdentity['kolichschet'];
        $schetNs = $schetIdentity['ns'];
        $_POST['shetrand'] = $shetrand;
	    if($_GET['tip']==12 && $_POST['nomerschetks']!="")
	{
		/*echo'<script>alert('.$_POST['dobprod'].')</script>';*/
		if($_POST['av']==0)
		{
			$pagent=$_POST['nomerschetks']."L";
			$sagent=$_POST['nomerschetks'];
			$chisrol=2;
			if($_POST['sht']!="0"&&$_POST['sht']!="")
			{
				$dagent=$_POST['nomerschetks'];
				$shtsert=$_POST['sht'];
				$chisrol=3;
			}
			 $dop_yslygi = $_POST['dobusl'];
        $count = count($dop_yslygi); //это количество элементов массива, для цикла.
		for ($i=0; $i < $count; $i++)
        {
				$azaza = "INSERT INTO `av` (
			`os_prod`,
			`dob_usl`,
			`pagent`,
			`sagent`,
			`dagent`,
			`shtsert`,
			`chisrol`,
			`rand`,
			`schet`,
			`summschet`,
			`kto`
		) VALUES (
		'$_GET[parent]',
		'$dop_yslygi[$i]',
		'$pagent',
		'$sagent',
		'$dagent',
		'$shtsert',
		'$chisrol',
		'$_POST[shetrand]',
		'$_POST[nomerschetks]',
		'$_POST[priceks]',
		'".$userdata['users_id']."'
		)";
		mysql_query($azaza) or die(mysql_error($link));
		}
		}
		if($_POST['av']==1)
		{
			$sagent=$_POST['nomerschetks'];
			$chisrol=1;
			if($_POST['sht']!="0"&&$_POST['sht']!="")
			{
				$dagent=$_POST['nomerschetks'];
				$shtsert=$_POST['sht'];
				$chisrol=2;
			}
				 $dop_yslygi = $_POST['dobusl'];
        $count = count($dop_yslygi); //это количество элементов массива, для цикла.
		for ($i=0; $i < $count; $i++)
        {
				$azaza = "INSERT INTO `av` (
			`os_prod`,
			`dob_usl`,
			`pagent`,
			`sagent`,
			`dagent`,
			`shtsert`,
			`chisrol`,
			`rand`,
			`schet`,
			`summschet`,
			`kto`
		) VALUES (
		'$_GET[parent]',
		'$dop_yslygi[$i]',
		'$pagent',
		'$sagent',
		'$dagent',
		'$shtsert',
		'$chisrol',
		'$_POST[shetrand]',
		'$_POST[nomerschetks]',
		'$_POST[priceks]',
		'".$userdata['users_id']."'
		)";
		mysql_query($azaza) or die(mysql_error($link));
		}
		}
		if($_POST['av']==2)
		{
			if($_POST['sht']!="0"&&$_POST['sht']!="")
			{
				$dagent=$_POST['nomerschetks'];
				$shtsert=$_POST['sht'];
				$chisrol=1;
			}
			 $dop_yslygi = $_POST['dobusl'];
        $count = count($dop_yslygi); //это количество элементов массива, для цикла.
		for ($i=0; $i < $count; $i++)
        {
				$azaza = "INSERT INTO `av` (
			`os_prod`,
			`dob_usl`,
			`pagent`,
			`sagent`,
			`dagent`,
			`shtsert`,
			`chisrol`,
			`rand`,
			`schet`,
			`summschet`,
			`kto`
		) VALUES (
		'$_GET[parent]',
		'$dop_yslygi[$i]',
		'$pagent',
		'$sagent',
		'$dagent',
		'$shtsert',
		'$chisrol',
		'$_POST[shetrand]',
		'$_POST[nomerschetks]',
		'$_POST[priceks]',
		'".$userdata['users_id']."'
		)";
		mysql_query($azaza) or die(mysql_error($link));
		}
		}
		if($_POST['av']=="3")
		{
			$pagent="Без АВ";
			$sagent="Без АВ";
			$chisrol=2;
			if($_POST['sht']!="0"&&$_POST['sht']!="")
			{
				$dagent="Без АВ";
				$shtsert=$_POST['sht'];
				$chisrol=3;
			}
			 $dop_yslygi = $_POST['dobusl'];
        $count = count($dop_yslygi); //это количество элементов массива, для цикла.
		for ($i=0; $i < $count; $i++)
        {
				$azaza = "INSERT INTO `av` (
			`os_prod`,
			`dob_usl`,
			`pagent`,
			`sagent`,
			`dagent`,
			`shtsert`,
			`chisrol`,
			`rand`,
			`schet`,
			`summschet`,
			`kto`
		) VALUES (
		'$_GET[parent]',
		'$dop_yslygi[$i]',
		'$pagent',
		'$sagent',
		'$dagent',
		'$shtsert',
		'$chisrol',
		'$_POST[shetrand]',
		'$_POST[nomerschetks]',
		'$_POST[priceks]',
		'".$userdata['users_id']."'
		)";
		mysql_query($azaza) or die(mysql_error($link));
		}
		}
        if($_POST['av']=="4")
        {
            $pagent="Без АВ";
            $sagent="Без АВ";
            $chisrol=2;
            if($_POST['sht']!="0"&&$_POST['sht']!="")
            {
                $dagent="Без АВ";
                $shtsert=$_POST['sht'];
                $chisrol=3;
            }
            $dop_yslygi = $_POST['dobusl'];
            $count = count($dop_yslygi); //это количество элементов массива, для цикла.
            for ($i=0; $i < $count; $i++)
            {
                $azaza = "INSERT INTO `av` (
			`os_prod`,
			`dob_usl`,
			`pagent`,
			`sagent`,
			`dagent`,
			`shtsert`,
			`chisrol`,
			`rand`,
			`schet`,
			`summschet`,
			`kto`
		) VALUES (
		'$_GET[parent]',
		'$dop_yslygi[$i]',
		'$pagent',
		'$sagent',
		'$dagent',
		'$shtsert',
		'$chisrol',
		'$_POST[shetrand]',
		'$_POST[nomerschetks]',
		'$_POST[priceks]',
		'".$userdata['users_id']."'
		)";
                mysql_query($azaza) or die(mysql_error($link));
            }
        }
		$dop_prod = $_POST['dobprod'];
        $count = count($dop_prod);
		$avdob = $_POST['avdob'];
		$shtsertdd=$_POST['shtpr'];
		if($count!=0)
        {
			for ($i=0; $i < $count; $i++)
        {
		if($avdob[$i]==0)
		{
			$dobprodschet=$_POST['nomerschetks']."dob";
			$pagentd=$_POST['nomerschetks']."L";
			$sagentd=$_POST['nomerschetks'];
			$chisroldob=2;
			if($shtsertdd[$i]!="0"&&$shtsertdd[$i]!="")
			{
				$dagentd=$_POST['nomerschetks'];
				$shtsertd=$shtsertdd[$i];
				$chisroldob=3;
			}
			 $dop_yslygi = $_POST['dobuslpr'];
			  //это количество элементов массива, для цикла.
		
				$azaza = "INSERT INTO `av` (
			`os_prod`,
			`dob_prod`,
		`dobprodschet`,
			`pagent`,
			`sagent`,
			`dagent`,
			`shtsert`,
			`chisrol`,
			`rand`,
			`schet`,
			`summschet`,
			`kto`
		) VALUES (
		'$_GET[parent]',
		'$dop_prod[$i]',
		'$dobprodschet',
		'$pagentd',
		'$sagentd',
		'$dagentd',
		'$shtsertd',
		'$chisroldob',
		'$_POST[shetrand]',
		'$_POST[nomerschetks]',
		'$_POST[priceks]',
		'".$userdata['users_id']."'
		)";
		mysql_query($azaza) or die(mysql_error($link));
		    $pagentd="";
			$sagentd="";
			$chisroldob="";
		    $dagentd="";
		}
		
		if($avdob[$i]==1)
		{
			$dobprodschet=$_POST['nomerschetks']."dob";
			$sagentd=$_POST['nomerschetks'];
			$chisroldob=1;
			if($shtsertdd[$i]!="0"&&$shtsertdd[$i]!="")
			{
				$dagentd=$_POST['nomerschetks'];
				$shtsertd=$shtsertdd[$i];
				$chisroldob=2;
			}
				$azaza = "INSERT INTO `av` (
			`os_prod`,
			`dob_prod`,
		`dobprodschet`,
			`pagent`,
			`sagent`,
			`dagent`,
			`shtsert`,
			`chisrol`,
			`rand`,
			`schet`,
			`summschet`,
			`kto`
		) VALUES (
		'$_GET[parent]',
		'$dop_prod[$i]',
		'$dobprodschet',
		'$pagentd',
		'$sagentd',
		'$dagentd',
		'$shtsertd',
		'$chisroldob',
		'$_POST[shetrand]',
		'$_POST[nomerschetks]',
		'$_POST[priceks]',
		'".$userdata['users_id']."'
		)";
		mysql_query($azaza) or die(mysql_error($link));
		    $pagentd="";
			$sagentd="";
			$chisroldob="";
		    $dagentd="";
		}

		if($avdob[$i]==2)
		{
			$chisroldob=0;
			if($shtsertdd[$i]!="0"&&$shtsertdd[$i]!="")
			{
				$dagentd=$_POST['nomerschetks'];
				$shtsertd=$shtsertdd[$i];
				$chisroldob=1;
			}
			$dobprodschet=$_POST['nomerschetks']."dob";
				$azaza = "INSERT INTO `av` (
			`os_prod`,
			`dob_prod`,
		`dobprodschet`,
			`pagent`,
			`sagent`,
			`dagent`,
			`shtsert`,
			`chisrol`,
			`rand`,
			`schet`,
			`summschet`,
			`kto`
		) VALUES (
		'$_GET[parent]',
		'$dop_prod[$i]',
		'$dobprodschet',
		'$pagentd',
		'$sagentd',
		'$dagentd',
		'$shtsertd',
		'$chisroldob',
		'$_POST[shetrand]',
		'$_POST[nomerschetks]',
		'$_POST[priceks]',
		'".$userdata['users_id']."'
		)";
		mysql_query($azaza) or die(mysql_error($link));
		    $pagentd="";
			$sagentd="";
			$chisroldob="";
		    $dagentd="";
		}
		if($avdob[$i]=="3")
		{
			$dobprodschet=$_POST['nomerschetks']."dob";
			$pagentd="Без АВ";
			$sagentd="Без АВ";
			$chisroldob=2;
			if($shtsertdd[$i]!="0"&&$shtsertdd[$i]!="")
			{
				$dagentd="Без АВ";
				$shtsert=$shtsertdd[$i];
				$chisroldob=3;
			}
				$azaza = "INSERT INTO `av` (
			`os_prod`,
			`dob_prod`,
		`dobprodschet`,
			`pagent`,
			`sagent`,
			`dagent`,
			`shtsert`,
			`chisrol`,
			`rand`,
			`schet`,
			`summschet`,
			`kto`
		) VALUES (
		'$_GET[parent]',
		'$dop_prod[$i]',
		'$dobprodschet',
		'$pagentd',
		'$sagentd',
		'$dagentd',
		'$shtsertd',
		'$chisroldob',
		'$_POST[shetrand]',
		'$_POST[nomerschetks]',
		'$_POST[priceks]',
		'".$userdata['users_id']."'
		)";
		mysql_query($azaza) or die(mysql_error($link));
		    $pagentd="";
			$sagentd="";
			$chisroldob="";
		    $dagentd="";
		}
        if($avdob[$i]=="4")
            {
                $dobprodschet=$_POST['nomerschetks']."dob";
                $pagentd="Без АВ";
                $sagentd="Без АВ";
                $chisroldob=2;
                if($shtsertdd[$i]!="0"&&$shtsertdd[$i]!="")
                {
                    $dagentd="Без АВ";
                    $shtsert=$shtsertdd[$i];
                    $chisroldob=3;
                }
                $azaza = "INSERT INTO `av` (
			`os_prod`,
			`dob_prod`,
		`dobprodschet`,
			`pagent`,
			`sagent`,
			`dagent`,
			`shtsert`,
			`chisrol`,
			`rand`,
			`schet`,
			`summschet`,
			`kto`
		) VALUES (
		'$_GET[parent]',
		'$dop_prod[$i]',
		'$dobprodschet',
		'$pagentd',
		'$sagentd',
		'$dagentd',
		'$shtsertd',
		'$chisroldob',
		'$_POST[shetrand]',
		'$_POST[nomerschetks]',
		'$_POST[priceks]',
		'".$userdata['users_id']."'
		)";
                mysql_query($azaza) or die(mysql_error($link));
                $pagentd="";
                $sagentd="";
                $chisroldob="";
                $dagentd="";
            }
		}	
			
			
	    
       /* $dop_yslygi = $_POST['dobuslpr'];
        $count = count($dop_yslygi); //это количество элементов массива, для цикла.
				for ($i=0; $i < $count; $i++)
        {
					$azaza = "INSERT INTO `av` (
		
		
		`dob_usl`,
		`kto`
		) VALUES (
		
		'$dop_yslygi[$i]',
		'".$userdata['users_id']."'
		)";
		mysql_query($azaza) or die(mysql_error($link));
        }*/
		}
    }
	if($_POST['dog'] == 2){
		if(!empty($_POST['checkl']))
		{
			$checkl=1;
		}
		else
		{
			$checkl=0;
		}
		if($_GET['parent']==319||$_GET['parent']==400||$_GET['parent']==391)
		{
			$sos_opis=$_POST['sos_opis'];
			$kol_opis=$_POST['kol_opis'];
		}
		else
		{
			$sos_opis=0;
			$kol_opis=0;
		}
		if($_POST['nsold']!=""&&$_POST['nsold']!="0")
		{
			if($_POST['priceks']!=""&&$_POST['priceks']!="0")
			{
					$komenti = "UPDATE schet SET `prodlenks`='1' WHERE ns='".$_POST['nsold']."'";
		            mysql_query($komenti) or die(mysql_error($linki));
			}
			if($_POST['aallsumm']!=""&&$_POST['aallsumm']!="0")
			{
			    if($_POST['skidka']=="100")
                {
                    $komentis = "UPDATE schet SET `prodlens`='1' WHERE ns='".$_POST['nsold']."'";
                    mysql_query($komentis) or die(mysql_error($link));
                }
			        $komenti = "UPDATE schet SET `prodlens`='1' WHERE ns='".$_POST['nsold']."'";
		            mysql_query($komenti) or die(mysql_error($link));
			}
		}
		$agent=htmlspecialchars($_POST["agent"]);
		$ogrn = "SELECT * FROM ogrn WHERE inn ='".$_GET['inn']."' AND kpp ='".$_GET['kpp']."' ";
		$rogrn = mysql_query($ogrn);
		$pogrn = mysql_fetch_array($rogrn);
		$inn = $pogrn['inn'];
		$kpp = $pogrn['kpp'];
		$naim = $pogrn['naim'];
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
			`shetold`,
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
			`tell`,
			`url`,
			`agent`,
			`Lagent`,
			`sos_opis`,
			`kol_opis`,
		    `nds`
			) VALUES (
            '".$schetNs."',
			'$_POST[nsold]',
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
			'$_POST[priceks]',
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
			'$_POST[tel]',
			'$_POST[url]',
			'$agent',
			'$checkl',
			'$_POST[sos_opis]',
			'$_POST[kol_opis]',
            $nds
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
        $messaeLog = ' Пользователь ' . $userdata['users_id'] . ' GET[rand] ' . $_GET['rand'] . ' $shetrand ' . $shetrand . ' $_POST[shetrand] ' . $_POST['shetrand']
            . ' kolichschet ' . $sujmdfhsd . ' $zs ' . $zs . ' $_POST[submitsch] ' . $_POST['submitsch'] . ' $_POST[dog] '
            . $_POST['dog'];
        error_log(date('Y-m-d H:i:s') . $messaeLog . PHP_EOL, 3, 'log/voovi.log');
		echo '<script type="text/javascript">'; 
		echo 'window.location.href="/kartklient.php?id='.$_GET['id'].'";'; 
		echo '</script>';
	}
    else{
		$aktivn = "INSERT INTO `aktivn` (`data`,`deistvie`,`users`,`gmd`,`tip`) VALUES ('". date("d.m.Y; H:i:s") ."','Новый счет','".$userdata['users_id']."','".date("Ymd")."','')";
		mysql_query($aktivn) or die(mysql_error($link));

		$qe = "SELECT * FROM dogovor ORDER BY nomer DESC LIMIT 1";
		$re = mysql_query($qe);
		$pe = mysql_fetch_array($re);
		$ze = $pe['nomer']+ 1;

		$qrand = "SELECT sortir FROM `schet` ORDER BY sortir DESC LIMIT 1";
		$resultrand = mysql_query($qrand);
		$personrand = mysql_fetch_array($resultrand);
							
		echo $personrand['sortir'];
		$var = $personrand['sortir'] + 1;

		$ogrn = "SELECT * FROM ogrn WHERE id =".$_GET['id'];
		$rogrn = mysql_query($ogrn);
		$pogrn = mysql_fetch_array($rogrn);
		$inn = $pogrn['inn'];
		$kpp = $pogrn['kpp'];
		$naim = $pogrn['naim'];

		if($_POST['dog'] == 0){
		$u = "INSERT INTO `dogovor` (
		`god`,
		`filial`,
		`otdel`,
		`nomer`,
		`ogrn`
		) VALUES (
		'".date("Y")."',
		'".$userdata['filial']."',
		'".$userdata['otdel']."',
		'".$ze."',
		'".$_GET['id']."'
		)";
		mysql_query($u) or die(mysql_error($link));
		}
		$qazaza = mysql_query(voovi_tarif_schet_query($_GET['parent'], isset($_GET['rand']) ? $_GET['rand'] : ''));
		while($rowazaza = mysql_fetch_array($qazaza)) {
		if($_POST[$rowazaza['id']] == 1){
			
		if(!empty($_POST['checkl']))
		{
			$checkl=1;
		}
		else
		{
			$checkl=0;
		}
		if($_GET['parent']==319||$_GET['parent']==400||$_GET['parent']==391)
		{
			$sos_opis=$_POST['sos_opis'];
			$kol_opis=$_POST['kol_opis'];
		}
		else
		{
			$sos_opis=0;
			$kol_opis=0;
		}
		if($_POST['nsold']!=""&&$_POST['nsold']!="0")
		{
		if($_POST['priceks']!=""&&$_POST['priceks']!="0")
			{
					$koment = "UPDATE schet SET `prodlens`='1' WHERE ns='".$_POST['nsold']."'";
		            mysql_query($koment) or die(mysql_error($link));
			}
        if($_POST['aallsumm']!=""&&$_POST['aallsumm']!="0")
			{
                if($_POST['skidka']=="100")
                {
                    $komentis = "UPDATE schet SET `prodlens`='1' WHERE ns='".$_POST['nsold']."'";
                    mysql_query($komentis) or die(mysql_error($link));
                }
			$komenti = "UPDATE schet SET `prodlenks`='1' WHERE ns='".$_POST['nsold']."'";
		            mysql_query($komenti) or die(mysql_error($link));
			}
		}
		$pkvo = $rowazaza;
        $agent=htmlspecialchars($_POST["agent"]);
			$azaza = "INSERT INTO `schet` (
            `ns`,
			`shetold`,
			`gen`,
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
		`kvogorod`,
		`goroddd`,
		`prodlen`,
		`skidka`,
		`price`,
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
		`tell`,
		`url`,
		`agent`,
		`Lagent`,
		`sos_opis`,
		`kol_opis`,
		`nds`
			) VALUES (
            '".$schetNs."',
			'$_POST[nsold]',
			'".$pkvo['gen']."',
		'$_GET[id]',
		'".$var."',
		'".$userdata['otdel']."',
		'".$userdata['filial']."',
		'".date("y")."',
		'".$ze."',
		'".$zs."',
		'".$sujmdfhsd."',
		'$_POST[nomerschetks]',
		'".$_POST["input".$rowazaza['id']]."',
		'".$rowazaza['id']."',
		'$_POST[kvogorod]',
		'$_POST[goroddd]',
		'0',
		'$_POST[skidka]',
		'$_POST[aallsumm]',
		'$_POST[priceks]',
		'".$_GET['ogrn']."',
		'".$naim."',
		'".$inn."',
		'".$kpp."',
		'$_GET[parent]',
		'".$userdata['users_id']."',
		'".date("d.m.y в H:i")."',
		'".date("d")."',
		'".date("m")."',
		'".date("Y")."',
		'".date("H:i")."',
		'$_POST[shetrand]',
		'$_POST[dog]',
		'$_POST[tel]',
		'$_POST[url]',
		'$agent',
		'$checkl',
		'$_POST[sos_opis]',
        '$_POST[kol_opis]',
        $nds
		)";
		mysql_query($azaza) or die(mysql_error($link));


		}
		}

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
        $messaeLog = ' Пользователь ' . $userdata['users_id'] . ' GET[rand] ' . $_GET['rand'] . ' $shetrand ' . $shetrand . ' $_POST[shetrand] ' . $_POST['shetrand']
            . ' kolichschet ' . $sujmdfhsd . ' $zs ' . $zs . ' $_POST[submitsch] ' . $_POST['submitsch'] . ' $_POST[dog] '
            . $_POST['dog'];
        error_log(date('Y-m-d H:i:s') . $messaeLog . PHP_EOL, 3, 'log/voovi.log');
        echo '<script type="text/javascript">';
        echo 'window.location.href="/kartklient.php?id='.$_GET['id'].'";';
        echo '</script>';

		$mamba = voovi_tarif_schet_query($_GET['parent'], isset($_GET['rand']) ? $_GET['rand'] : '');
		$rmamba = mysql_query($mamba);
		$pmamba = mysql_fetch_array($rmamba);
		$pkvo = $pmamba;
		$agent=htmlspecialchars($_POST["agent"]);
		if($_POST[$pmamba['id']] != 1){
		if(!empty($_POST['goroddd'])){
			if(!empty($_POST['checkl']))
		{
			$checkl=1;
		}
		    else
		{
			$checkl=0;
		}
			if($_GET['parent']==319||$_GET['parent']==400||$_GET['parent']==391)
		{
			$sos_opis=$_POST['sos_opis'];
			$kol_opis=$_POST['kol_opis'];
		}
		    else
		{
			$sos_opis=0;
			$kol_opis=0;
		}	
		if($_POST['nsold']!=""&&$_POST['nsold']!="0")
		{
			if($_POST['priceks']!=""&&$_POST['priceks']!="0")
			{
					$koment = "UPDATE schet SET `prodlens`='1' WHERE ns='".$_POST['nsold']."'";
		            mysql_query($koment) or die(mysql_error($link));
			}
			if($_POST['aallsumm']!=""&&$_POST['aallsumm']!="0")
			{
                if($_POST['skidka']=="100")
                {
                    $komentis = "UPDATE schet SET `prodlens`='1' WHERE ns='".$_POST['nsold']."'";
                    mysql_query($komentis) or die(mysql_error($link));
                }
			$komenti = "UPDATE schet SET `prodlenks`='1' WHERE ns='".$_POST['nsold']."'";
		            mysql_query($komenti) or die(mysql_error($link));
			}
		}
		//------------------------------------------------------------------------
			$azazai = "INSERT INTO `schet` (
            `ns`,
			`shetold`,
			`gen`,
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
		`kvogorod`,
		`goroddd`,
		`prodlen`,
		`skidka`,
		`price`,
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
		`tell`,
		`url`,
		`agent`,
		`Lagent`,
		`sos_opis`,
		`kol_opis`,
		`nds`
			) VALUES (
            '".$schetNs."',
			'$_POST[nsold]',
			'".$pkvo['gen']."',
		'$_GET[id]',
		'".$var."',
		'".$userdata['otdel']."',
		'".$userdata['filial']."',
		'".date("y")."',
		'".$ze."',
		'".$zs."',
		'".$sujmdfhsd."',
		'$_POST[nomerschetks]',
		'".$_POST["input".$rowazaza['id']]."',
		'".$rowazaza['id']."',
		'$_POST[kvogorod]',
		'$_POST[goroddd]',
		'0',
		'$_POST[skidka]',
		'$_POST[aallsumm]',
		'$_POST[priceks]',
		'".$_GET['ogrn']."',
		'".$naim."',
		'".$inn."',
		'".$kpp."',
		'$_GET[parent]',
		'".$userdata['users_id']."',
		'".date("d.m.y в H:i")."',
		'".date("d")."',
		'".date("m")."',
		'".date("Y")."',
		'".date("H:i")."',
		'$_POST[shetrand]',
		'$_POST[dog]',
		'$_POST[tel]',
		'$_POST[url]',
		'$agent',
		'$chekl',
		'$_POST[sos_opis]',
	    '$_POST[kol_opis]',
        $nds
		)";
		mysql_query($azazai) or die(mysql_error($link));

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
            $messaeLog = ' Пользователь ' . $userdata['users_id'] . ' GET[rand] ' . $_GET['rand'] . ' $shetrand ' . $shetrand . ' $_POST[shetrand] ' . $_POST['shetrand']
                . ' kolichschet ' . $sujmdfhsd . ' $zs ' . $zs . ' $_POST[submitsch] ' . $_POST['submitsch'] . ' $_POST[dog] '
                . $_POST['dog'];
            error_log(date('Y-m-d H:i:s') . $messaeLog . PHP_EOL, 3, 'log/voovi.log');
            echo '<script type="text/javascript">';
            echo 'window.location.href="/kartklient.php?id='.$_GET['id'].'";';
            echo '</script>';
		//---------------------------------------------------------------------------
		}
		}



	}
        if(isset($_POST['dobprod']))
        {
        $dop_yslygi = $_POST['dobprod'];
        $count = count($dop_yslygi); //это количество элементов массива, для цикла.
        }
		if($count != 0)
        {
				for ($i=0; $i < $count; $i++)
        {
					$azazal = "INSERT INTO `schet` (
		
		`dobprod`,
		`kto`
		) VALUES (
		'$dop_yslygi[$i]',
		'".$userdata['users_id']."',
		)";
		mysql_query($azazal) or die(mysql_error($link));
        }
        }
	}
}


if(isset($_POST['submitsave'])){

    $shetrand = $pestar['rand'];
    $sujmdfhsd = $pestar['kolichschet'];
    $schetNs = $pestar2['ns'];
    if ($schetNs == '' || $schetNs == '0') {
        $schetNs = $pestar['god'].$pestar['kto'].$pestar['otdel'].$pestar['kolichschet'];
    }
    $_POST['shetrand'] = $shetrand;

	$aktivn = "INSERT INTO `aktivn` (`data`,`deistvie`,`users`,`gmd`,`tip`) VALUES ('". date("d.m.Y; H:i:s") ."','Счет изменен','".$userdata['users_id']."','".date("Ymd")."','')";
mysql_query($aktivn) or die(mysql_error($link));

if($pestar2['del']=='1'){
	mysql_query("DELETE FROM `schet` WHERE  `del` =  '0' AND rand ='".$_GET['rand']."'");

}
if($pestar2['del']=='0'){
	mysql_query("UPDATE `schet` SET  `del` =  '1' WHERE rand ='".$_GET['rand']."'");
        mysql_query("DELETE FROM `av` WHERE   rand ='".$_GET['rand']."'");
}
if($_GET['tip']==12 && $_POST['nomerschetks']!="")
    {
        /*echo'<script>alert('.$_POST['dobprod'].')</script>';*/
        if($_POST['av']==0)
        {
            $pagent=$_POST['nomerschetks']."L";
            $sagent=$_POST['nomerschetks'];
            $chisrol=2;
            if($_POST['sht']!="0"&&$_POST['sht']!="")
            {
                $dagent=$_POST['nomerschetks'];
                $shtsert=$_POST['sht'];
                $chisrol=3;
            }
            $dop_yslygi = $_POST['dobusl'];
            $count = count($dop_yslygi); //это количество элементов массива, для цикла.
            for ($i=0; $i < $count; $i++)
            {
                $azaza = "INSERT INTO `av` (
			`os_prod`,
			`dob_usl`,
			`pagent`,
			`sagent`,
			`dagent`,
			`shtsert`,
			`chisrol`,
			`rand`,
			`schet`,
			`summschet`,
			`kto`
		) VALUES (
		'$_GET[parent]',
		'$dop_yslygi[$i]',
		'$pagent',
		'$sagent',
		'$dagent',
		'$shtsert',
		'$chisrol',
		'$_POST[shetrand]',
		'$_POST[nomerschetks]',
		'$_POST[priceks]',
		'".$userdata['users_id']."'
		)";
                mysql_query($azaza) or die(mysql_error($link));
            }
        }
        if($_POST['av']==1)
        {
            $sagent=$_POST['nomerschetks'];
            $chisrol=1;
            if($_POST['sht']!="0"&&$_POST['sht']!="")
            {
                $dagent=$_POST['nomerschetks'];
                $shtsert=$_POST['sht'];
                $chisrol=2;
            }
            $dop_yslygi = $_POST['dobusl'];
            $count = count($dop_yslygi); //это количество элементов массива, для цикла.
            for ($i=0; $i < $count; $i++)
            {
                $azaza = "INSERT INTO `av` (
			`os_prod`,
			`dob_usl`,
			`pagent`,
			`sagent`,
			`dagent`,
			`shtsert`,
			`chisrol`,
			`rand`,
			`schet`,
			`summschet`,
			`kto`
		) VALUES (
		'$_GET[parent]',
		'$dop_yslygi[$i]',
		'$pagent',
		'$sagent',
		'$dagent',
		'$shtsert',
		'$chisrol',
		'$_POST[shetrand]',
		'$_POST[nomerschetks]',
		'$_POST[priceks]',
		'".$userdata['users_id']."'
		)";
                mysql_query($azaza) or die(mysql_error($link));
            }
        }
        if($_POST['av']==2)
        {
            if($_POST['sht']!="0"&&$_POST['sht']!="")
            {
                $dagent=$_POST['nomerschetks'];
                $shtsert=$_POST['sht'];
                $chisrol=1;
            }
            $dop_yslygi = $_POST['dobusl'];
            $count = count($dop_yslygi); //это количество элементов массива, для цикла.
            for ($i=0; $i < $count; $i++)
            {
                $azaza = "INSERT INTO `av` (
			`os_prod`,
			`dob_usl`,
			`pagent`,
			`sagent`,
			`dagent`,
			`shtsert`,
			`chisrol`,
			`rand`,
			`schet`,
			`summschet`,
			`kto`
		) VALUES (
		'$_GET[parent]',
		'$dop_yslygi[$i]',
		'$pagent',
		'$sagent',
		'$dagent',
		'$shtsert',
		'$chisrol',
		'$_POST[shetrand]',
		'$_POST[nomerschetks]',
		'$_POST[priceks]',
		'".$userdata['users_id']."'
		)";
                mysql_query($azaza) or die(mysql_error($link));
            }
        }
        if($_POST['av']=="3")
        {
            $pagent="Без АВ";
            $sagent="Без АВ";
            $chisrol=2;
            if($_POST['sht']!="0"&&$_POST['sht']!="")
            {
                $dagent="Без АВ";
                $shtsert=$_POST['sht'];
                $chisrol=3;
            }
            $dop_yslygi = $_POST['dobusl'];
            $count = count($dop_yslygi); //это количество элементов массива, для цикла.
            for ($i=0; $i < $count; $i++)
            {
                $azaza = "INSERT INTO `av` (
			`os_prod`,
			`dob_usl`,
			`pagent`,
			`sagent`,
			`dagent`,
			`shtsert`,
			`chisrol`,
			`rand`,
			`schet`,
			`summschet`,
			`kto`
		) VALUES (
		'$_GET[parent]',
		'$dop_yslygi[$i]',
		'$pagent',
		'$sagent',
		'$dagent',
		'$shtsert',
		'$chisrol',
		'$_POST[shetrand]',
		'$_POST[nomerschetks]',
		'$_POST[priceks]',
		'".$userdata['users_id']."'
		)";
                mysql_query($azaza) or die(mysql_error($link));
            }
        }
        $dop_prod = $_POST['dobprod'];
        $count = count($dop_prod);
        $avdob = $_POST['avdob'];
        $shtsertdd=$_POST['shtpr'];
        if($count!=0)
        {
            for ($i=0; $i < $count; $i++)
            {
                if($avdob[$i]==0)
                {
                    $dobprodschet=$_POST['nomerschetks']."dob";
                    $pagentd=$_POST['nomerschetks']."L";
                    $sagentd=$_POST['nomerschetks'];
                    $chisroldob=2;
                    if($shtsertdd[$i]!="0"&&$shtsertdd[$i]!="")
                    {
                        $dagentd=$_POST['nomerschetks'];
                        $shtsertd=$shtsertdd[$i];
                        $chisroldob=3;
                    }
                    $dop_yslygi = $_POST['dobuslpr'];
                    //это количество элементов массива, для цикла.

                    $azaza = "INSERT INTO `av` (
			`os_prod`,
			`dob_prod`,
		`dobprodschet`,
			`pagent`,
			`sagent`,
			`dagent`,
			`shtsert`,
			`chisrol`,
			`rand`,
			`schet`,
			`summschet`,
			`kto`
		) VALUES (
		'$_GET[parent]',
		'$dop_prod[$i]',
		'$dobprodschet',
		'$pagentd',
		'$sagentd',
		'$dagentd',
		'$shtsertd',
		'$chisroldob',
		'$_POST[shetrand]',
		'$_POST[nomerschetks]',
		'$_POST[priceks]',
		'".$userdata['users_id']."'
		)";
                    mysql_query($azaza) or die(mysql_error($link));
                    $pagentd="";
                    $sagentd="";
                    $chisroldob="";
                    $dagentd="";
                }
                if($avdob[$i]==1)
                {
                    $dobprodschet=$_POST['nomerschetks']."dob";
                    $sagentd=$_POST['nomerschetks'];
                    $chisroldob=1;
                    if($shtsertdd[$i]!="0"&&$shtsertdd[$i]!="")
                    {
                        $dagentd=$_POST['nomerschetks'];
                        $shtsertd=$shtsertdd[$i];
                        $chisroldob=2;
                    }
                    $azaza = "INSERT INTO `av` (
			`os_prod`,
			`dob_prod`,
		`dobprodschet`,
			`pagent`,
			`sagent`,
			`dagent`,
			`shtsert`,
			`chisrol`,
			`rand`,
			`schet`,
			`summschet`,
			`kto`
		) VALUES (
		'$_GET[parent]',
		'$dop_prod[$i]',
		'$dobprodschet',
		'$pagentd',
		'$sagentd',
		'$dagentd',
		'$shtsertd',
		'$chisroldob',
		'$_POST[shetrand]',
		'$_POST[nomerschetks]',
		'$_POST[priceks]',
		'".$userdata['users_id']."'
		)";
                    mysql_query($azaza) or die(mysql_error($link));
                    $pagentd="";
                    $sagentd="";
                    $chisroldob="";
                    $dagentd="";
                }
                if($avdob[$i]==2)
                {
                    $chisroldob=0;
                    if($shtsertdd[$i]!="0"&&$shtsertdd[$i]!="")
                    {
                        $dagentd=$_POST['nomerschetks'];
                        $shtsertd=$shtsertdd[$i];
                        $chisroldob=1;
                    }
                    $dobprodschet=$_POST['nomerschetks']."dob";
                    $azaza = "INSERT INTO `av` (
			`os_prod`,
			`dob_prod`,
		`dobprodschet`,
			`pagent`,
			`sagent`,
			`dagent`,
			`shtsert`,
			`chisrol`,
			`rand`,
			`schet`,
			`summschet`,
			`kto`
		) VALUES (
		'$_GET[parent]',
		'$dop_prod[$i]',
		'$dobprodschet',
		'$pagentd',
		'$sagentd',
		'$dagentd',
		'$shtsertd',
		'$chisroldob',
		'$_POST[shetrand]',
		'$_POST[nomerschetks]',
		'$_POST[priceks]',
		'".$userdata['users_id']."'
		)";
                    mysql_query($azaza) or die(mysql_error($link));
                    $pagentd="";
                    $sagentd="";
                    $chisroldob="";
                    $dagentd="";
                }
                if($avdob[$i]=="3")
                {
                    $dobprodschet=$_POST['nomerschetks']."dob";
                    $pagentd="Без АВ";
                    $sagentd="Без АВ";
                    $chisroldob=2;
                    if($shtsertdd[$i]!="0"&&$shtsertdd[$i]!="")
                    {
                        $dagentd="Без АВ";
                        $shtsert=$shtsertdd[$i];
                        $chisroldob=3;
                    }
                    $azaza = "INSERT INTO `av` (
			`os_prod`,
			`dob_prod`,
		`dobprodschet`,
			`pagent`,
			`sagent`,
			`dagent`,
			`shtsert`,
			`chisrol`,
			`rand`,
			`schet`,
			`summschet`,
			`kto`
		) VALUES (
		'$_GET[parent]',
		'$dop_prod[$i]',
		'$dobprodschet',
		'$pagentd',
		'$sagentd',
		'$dagentd',
		'$shtsertd',
		'$chisroldob',
		'$_POST[shetrand]',
		'$_POST[nomerschetks]',
		'$_POST[priceks]',
		'".$userdata['users_id']."'
		)";
                    mysql_query($azaza) or die(mysql_error($link));
                    $pagentd="";
                    $sagentd="";
                    $chisroldob="";
                    $dagentd="";
                }

            }



            /* $dop_yslygi = $_POST['dobuslpr'];
             $count = count($dop_yslygi); //это количество элементов массива, для цикла.
                     for ($i=0; $i < $count; $i++)
             {
                         $azaza = "INSERT INTO `av` (


             `dob_usl`,
             `kto`
             ) VALUES (

             '$dop_yslygi[$i]',
             '".$userdata['users_id']."'
             )";
             mysql_query($azaza) or die(mysql_error($link));
             }*/
        }
    }


$ogrn = "SELECT * FROM ogrn WHERE id =".$_GET['id'];
$rogrn = mysql_query($ogrn);
$pogrn = mysql_fetch_array($rogrn);
$inn = $pogrn['inn'];
$kpp = $pogrn['kpp'];
$naim = $pogrn['naim'];
if($_POST['nsold']!=""&&$_POST['nsold']!="0")
		{
			if($_POST['priceks']!=""&&$_POST['priceks']!="0")
			{
					$koment = "UPDATE schet SET `prodlens`='1' WHERE ns='".$_POST['nsold']."'";
		            mysql_query($koment) or die(mysql_error($link));
			}
			if($_POST['aallsumm']!=""&&$_POST['aallsumm']!="0")
			{
			$komenti = "UPDATE schet SET `prodlenks`='1' WHERE ns='".$_POST['nsold']."'";
		            mysql_query($komenti) or die(mysql_error($link));
			}
		}
$qazaza = mysql_query(voovi_tarif_schet_query($_GET['parent'], isset($_GET['rand']) ? $_GET['rand'] : ''));
while($rowazaza = mysql_fetch_array($qazaza)) {
if($_POST[$rowazaza['id']] == 1){
	$agent=htmlspecialchars($_POST["agent"]);
$pkvo = $rowazaza;
	$azaza = "INSERT INTO `schet` (
    `ns`,
	`shetold`,
	`gen`,
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
`kvogorod`,
`goroddd`,
`prodlen`,
`skidka`,
`price`,
`priceks`,
`ogrn`,
`name`,
`inn`,
`kpp`,
`produkt`,
`kto`,
`data`,
`koment`,
`d`,
`m`,
`y`,
`time`,
`rand`,
`oferta`,
`tell`,
`url`,
`agent`,
`nds`
	) VALUES (
    '".$schetNs."',
	'$_POST[nsold]',
	'".$pkvo['gen']."',
'$_GET[id]',
'".$var."',
'".$userdata['otdel']."',
'".$userdata['filial']."',
'".$pestar['god']."',
'".$pestar['nomerdog']."',
'".$pestar['nomerschet']."',
'".$pestar['kolichschet']."',
'$_POST[nomerschetks]',
'".$_POST["input".$rowazaza['id']]."',
'".$rowazaza['id']."',
'$_POST[kvogorod]',
'$_POST[goroddd]',
'0',
'$_POST[skidka]',
'$_POST[aallsumm]',
'$_POST[priceks]',
'".$_GET['ogrn']."',
'".$naim."',
'".$inn."',
'".$kpp."',
'$_GET[parent]',
'".$userdata['users_id']."',
'".$pestar['data']."',
'".$pestar['koment']."',
'".$pestar['d']."',
'".$pestar['m']."',
'".$pestar['y']."',
'".$pestar['time']."',
'$_POST[shetrand]',
'".$pestar['time']."',
'$_POST[tel]',
'$_POST[url]',
'$agent',
$nds
)";
mysql_query($azaza) or die(mysql_error($link));
}}
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
    $messaeLog = ' Пользователь ' . $userdata['users_id'] . ' GET[rand] ' . $_GET['rand'] . ' $shetrand ' . $shetrand . ' $_POST[shetrand] ' . $_POST['shetrand']
        . ' kolichschet ' . $sujmdfhsd . ' $zs ' . $zs . ' $_POST[submitsch] ' . $_POST['submitsch'] . ' $_POST[dog] '
        . $_POST['dog'];
    error_log(date('Y-m-d H:i:s') . ' ' . $messaeLog . PHP_EOL, 3, 'log/voovi.log');
echo '<script type="text/javascript">';
echo 'window.location.href="/kartklient.php?id='.$_GET['id'].'";';
echo '</script>';


}

$depo = "SELECT * FROM schet WHERE del = '0' AND rand ='".$_GET['rand']."'";
$rdepo = mysql_query($depo);
$pdepo = mysql_fetch_array($rdepo);

$newschetOldSchetOptions = array();
$newschetOldSchetQuery = mysql_query(
    "SELECT s.ns, s.nomerschet, s.nomerschetks, s.data, s.priceks, s.name, s.rand
     FROM schet s
     INNER JOIN (
         SELECT MAX(id) AS max_id
         FROM schet
         WHERE del = '0'
           AND idkli = '".$newschetClientId."'
           AND produkt = '".$newschetProductId."'
           AND ns IS NOT NULL
           AND ns != ''
         GROUP BY ns
     ) last_schet ON last_schet.max_id = s.id
     ORDER BY s.id DESC
     LIMIT 20"
);
if ($newschetOldSchetQuery) {
    while ($newschetOldSchet = mysql_fetch_assoc($newschetOldSchetQuery)) {
        $newschetOldSchetOptions[] = $newschetOldSchet;
    }
}

?>




<form method="post" id="schetForm">

<div class="newschet-mode-row">
<label for="idprodkschet">Режим счета</label>
<select id="idprodkschet" class="form-control">
<option value="2">Продление</option>
<option value="1">Новый</option>
</select>
</div>
<div id="oldns" style="display:none;">
    <div class="newschet-old-input-row">
        <div class="newschet-old-input">
            <label for="nsold">Предыдущий счет</label>
            <input id="nsold" name="nsold" class="form-control" type="text" list="newschetOldSchetList" autocomplete="off" value="<?php echo isset($_GET['oldns']) ? newschet_h($_GET['oldns']) : ''; ?>" placeholder="Введите номер или выберите из подсказок" />
            <datalist id="newschetOldSchetList">
                <?php foreach ($newschetOldSchetOptions as $newschetOldSchet) { ?>
                    <option value="<?php echo newschet_h($newschetOldSchet['ns']); ?>"><?php echo newschet_h(trim($newschetOldSchet['nomerschetks'].' '.$newschetOldSchet['data'].' '.$newschetOldSchet['priceks'])); ?></option>
                <?php } ?>
            </datalist>
        </div>
        <div id="oldschet" class="newschet-old-status"></div>
    </div>
    <?php if (count($newschetOldSchetOptions) > 0) { ?>
        <div class="newschet-old-suggestions">
            <?php foreach ($newschetOldSchetOptions as $newschetOldSchet) { ?>
                <button type="button" class="newschet-old-option" data-ns="<?php echo newschet_h($newschetOldSchet['ns']); ?>">
                    № <?php echo newschet_h($newschetOldSchet['ns']); ?>
                    <span><?php echo newschet_h(trim($newschetOldSchet['nomerschetks'].' '.$newschetOldSchet['data'])); ?></span>
                </button>
            <?php } ?>
        </div>
    <?php } ?>
</div>
<div id="prodkschet" style="display:none;">



<table id="tab" class="table">
<thead>
<tr>
<th style="width:1px;"></th>
<th style="padding: 5px;">Продукты </th>
  <th  style="width: 120px; padding: 5px;">Цена 1 шт.</th>
  <th  style="width: 10px; padding: 5px;">Кол-во</th>
  <th  style="width: 10px; padding: 5px;">Сумма</th>
</tr>
</thead>
<?php
$int = 1;
$query = mysql_query(voovi_tarif_schet_query($_GET['parent'], isset($_GET['rand']) ? $_GET['rand'] : ''));
while($row = mysql_fetch_array($query)) {
echo '<tr class="newschet-tarif-row"><td class="newschet-tarif-check">
<input id="check'.$row['id'].'" name="'.$row['id'].'" type="checkbox" value="1" onclick="validate'.$row['id'].'()"';

if(isset($_GET['rand'])){
$dopschet = "SELECT * FROM schet WHERE del = '0' AND rand ='".$_GET['rand']."' AND prod = '".$row['id']."'";
$rdopschet = mysql_query($dopschet);
$pdopschet = mysql_fetch_array($rdopschet);
if($pdopschet['prod'] == $row['id']){
echo 'checked';
}
}

echo'>';
echo '</td>';
echo '<td class="newschet-tarif-name">';
echo $row['name'];
echo '</td>';
echo '<td class="newschet-tarif-price">';
$vowels = array(" "," ");
$price = str_replace($vowels, "", $row['price']);
echo $price;

echo '</td>';
echo '<td class="newschet-tarif-qty">';
echo '<input id="input'.$row['id'].'" name="input'.$row['id'].'" class="newschet-qty-input" type="number" min="0" step="1" inputmode="numeric" value="';
if(isset($_GET['rand'])){
$dopschet = "SELECT * FROM schet WHERE del = '0' AND rand ='".$_GET['rand']."' AND prod = '".$row['id']."'";
$rdopschet = mysql_query($dopschet);
$pdopschet = mysql_fetch_array($rdopschet);
if ($pdopschet['kvo'] > 0){
echo $pdopschet['kvo'];
}else{
echo '0';
}
}else{
echo '0';
}

echo'">';
echo '</td>';
echo '<td class="newschet-tarif-sum">';
echo '<p id="summ'.$row['id'].'" name="summ'.$row['id'].'" class="newschet-total-sum">';
if(isset($_GET['rand'])){
$dopschet = "SELECT * FROM schet WHERE del = '0' AND rand ='".$_GET['rand']."' AND prod = '".$row['id']."'";
$rdopschet = mysql_query($dopschet);
$pdopschet = mysql_fetch_array($rdopschet);

echo $pdopschet['kvo']*$price;

}else{
echo '0';
}
echo'</p>
<script >
$(function() {
$("input[id=input'.$row['id'].']").keyup(function input'.$row['id'].'(){
  if($("#input'.$row['id'].'").val() > 0){
	$("#check'.$row['id'].'").prop("checked", true);
  }else{
	$("#check'.$row['id'].'").attr("checked", false);
  }

price = '.$price.' * $("#input'.$row['id'].'").val();
document.getElementById("summ'.$row['id'].'").innerHTML = '.price.';
});

$( "#input'.$row['id'].'" ).click(function() {
  $( "#input'.$row['id'].'" ).keyup();
  if($("#input'.$row['id'].'").val() == 0){
	$("input[id=check'.$row['id'].']").removeAttr("checked");
  }if($("#input'.$row['id'].'").val() > 0){
	$("#check'.$row['id'].'").prop("checked", true);
  }
});
});
function validate'.$row['id'].'(){
if (check'.$row['id'].'.checked == 1){
$("#input'.$row['id'].'").val("1");
price = '.$price.' * $("#input'.$row['id'].'").val();
document.getElementById("summ'.$row['id'].'").innerHTML = '.price.';
} else {
$("#input'.$row['id'].'").val("0"); 
price = '.$price.' * $("#input'.$row['id'].'").val();
document.getElementById("summ'.$row['id'].'").innerHTML = '.price.';
}
}
</script>'; 
echo '</td></tr>';
}
?>
</table>

<table class="table newschet-total-table">
<tr  style="display:none"><td style="width:20px;">
</td>
<td style="padding: 5px;">
Выезд
</td>
<td style="width: 120px; padding: 5px;">
<select id="gorod" onchange="gorod()" type="text" name="gorod" style="font-size: 15px;">
<?php 
if($pdepo['goroddd'] > 0){
if(isset($_GET['rand'])){ echo '<option value="'.$pdepo['goroddd'].'">';
if($pdepo['goroddd'] == 450){
	echo 'Пятигорск';
}if($pdepo['goroddd'] == 750){
	echo 'КМВ';
}if($pdepo['goroddd'] == 1500){
	echo 'Георгиевск';
}if($pdepo['goroddd'] == 23){
	echo 'Километр';
}if($pdepo['goroddd'] == 162534){
	echo 'Связанная';
}
echo'</option>';
}
}
?>
<option value="0" style="background: #DDD;">Без выезда</option>
<option value="450">Пятигорск</option>
<option value="750">КМВ</option>
<option value="1500">Георгиевск</option>
<option value="23">Километр</option>
<option value="162534">Связанная</option>
</select>
</td>



<td style="width: 57px;">
<input id="kmgorod" type="text" value="" style="width: 50px; display: none;">
<input id="kvogorod" name="kvogorod" type="text" min="0" max="100" step="1" value="1" style="width: 50px;">
</td>
<td style="width: 5px;">
<p id="gorodd">0</p>
<input type="text" style="display: none;" name="goroddd" id="goroddd">
</td>
</tr>


<tr class="newschet-total-row"><td style="width:1px;">
</td>
<td style="padding: 5px;">
<strong class="newschet-total-label">Итого:</strong>
</td>
<td class="newschet-discount-cell">
<span>Скидка</span>
</td>
<td  style="width: 58px; ">
<span class="newschet-discount-wrap"><input id="skidka" name="skidka" class="newschet-discount-input" type="number" min="0" max="100" step="1" value="0"><span>%</span></span>
</td>
<td style="width: 50px;">
<p id="allsumm" class="newschet-total-sum"></p>
<input type="text" style="display: none;" name="aallsumm" id="aallsumm">
</td></tr>
</table>

<table class="table">
<?php
if ($person['tel'] == 1) {
echo '
<tr><td style="width:150px;">
<span class="glyphicon glyphicon-earphone" aria-hidden="true"></span> Номер телефона:
</td>
<td style="padding: 5px;">
<input id="tel" name="tel" class="form-control" type="text" value="';
if(isset($_GET['rand'])){ echo $pdepo['tell'];}
echo'" style="width:100%;">
</td>
</tr>';
}
?>
<?if ($_GET['parent']!=319){?>
<tr><td style="width:20px;">
<span class="glyphicon glyphicon-link" aria-hidden="true"></span> Ссылка
</td>
<td style="padding: 5px;">
<input id="url" name="url" class="form-control" type="text" value="<?php if(isset($_GET['rand'])){ echo $pdepo['url'];}?>" style="width:100%;">
</td>
</tr>
<tr><td style="width:1px;">
<span class="glyphicon glyphicon-ruble" aria-hidden="true"></span> Сумма:
</td>
<td  style="padding: 5px;">
<input type="text" class="form-control" name="priceks" id="priceks"  value="<?php if(isset($_GET['rand'])){ echo $pdepo['priceks'];}?>">
</td></tr>
<tr><td style="width:1px;">
<span class="glyphicon glyphicon-tag" aria-hidden="true"></span> Номер счета:
</td>
<td  style="padding: 5px;">
<input type="text" class="form-control" name="nomerschetks" id="nomerschetks"  value="<?php if(isset($_GET['rand'])){ echo $pdepo['nomerschetks'];}?>">
</td></tr>
<tr><td style="width:1px;">
<span class="glyphicon glyphicon-user" aria-hidden="true"></span> Агент:
</td>
<td  style="padding: 5px;">
<select name="agent" class="form-control">
  <option value="0" selected>Выберите агента</option>   
  <?$r=mysql_query("SELECT * from agent ");
   while($res = mysql_fetch_assoc($r)) :?>	  
  <option value="<?php echo $res['id'];?>"><?php echo htmlspecialchars($res['name']);?></option>
    <?php endwhile; ?>
  </select>
</td></tr>
<?}?>

<?if (in_array($_GET['parent'], [319, 400, 391, 426, 428, 427, 434, 435])) {?>
<div style="
    padding-top: 20px;
    font-size: 12pt;
">
<b style="font-size: 15px;">Количество строк в описе:</b>
<input name="kol_opis" type="text" style="width:50px;" value=''>
    <br><b style="font-size: 15px;">Кто составил опись:</b>
    <select name="sos_opis" class="htext-add">
    <option  value='4113' selected>Коневец Татьяна</option>
         <option value="4113">Коневец Татьяна</option>;
</div>
<?}?>

</table>
<script >
setInterval(gorod, 500)
function gorod(){
var gorr = $("#gorod").val();
var kvogorod = $("#kvogorod").val();
var kmgorod = $("#kmgorod").val();



if (gorr == 23) {
$(function() {
$("input[id='kmgorod']").keyup(function kmgorod(){
document.getElementById("gorodd").innerHTML = gorr * $("#kmgorod").val()*$("#kvogorod").val() ;
$("#goroddd").val(gorr * $("#kmgorod").val());
});
});
document.getElementById("gorodd").innerHTML = gorr * kmgorod*$("#kvogorod").val() ;
$("#goroddd").val(gorr * kmgorod);
document.getElementById("kmgorod").style.display="block";
} else if (gorr == 162534) {
document.getElementById("gorodd").innerHTML = gorr - 162534;
$("#goroddd").val(gorr);
} else {
document.getElementById("gorodd").innerHTML = gorr*$("#kvogorod").val() ;
$("#goroddd").val(gorr);
document.getElementById("kmgorod").style.display="none";
}


}
setInterval(alsum, 500)
function alsum(){
    Decimal.config({ precision: 30 });
<?php $query3 = mysql_query(voovi_tarif_schet_query($_GET['parent'], isset($_GET['rand']) ? $_GET['rand'] : '')); while($row3 = mysql_fetch_array($query3)) { echo 'price'.$row3['id'].' = parseFloat(document.getElementById("summ'.$row3['id'].'").innerHTML);
'; } ?>
var allsumm = <?php $query1 = mysql_query(voovi_tarif_schet_query($_GET['parent'], isset($_GET['rand']) ? $_GET['rand'] : '')); while($row1 = mysql_fetch_array($query1)) { echo "price",$row1['id']," + "; } ?> parseFloat(document.getElementById("gorodd").innerHTML);
skidka = $("#skidka").val();
sskidka = (new Decimal(skidka)).dividedBy(100).times(new Decimal(allsumm));
if ($("#gorod").val() == 162534){
document.getElementById('allsumm').innerHTML = Decimal(allsumm).minus(Decimal(sskidka));
$("#aallsumm").val(Decimal(allsumm).minus(Decimal(sskidka)));
}else{
document.getElementById('allsumm').innerHTML = Decimal(allsumm).minus(Decimal(sskidka));
$("#aallsumm").val(Decimal(allsumm).minus(Decimal(sskidka)));
}
}
</script>
<?if ($_GET['tip']==12){?>
<div class="av newschet-av" id="agent">
<div class="form-control newschet-av-panel newschet-av-row" style="height: auto;float: left;">
<label class="newschet-av-product"><span>ОП</span><strong><?php
$id = (int)$_GET['parent']; // приводим к int
$person = mysql_fetch_assoc(mysql_query("SELECT * FROM `produkti` WHERE id = $id"));
echo newschet_h($person['name']);
?></strong></label>
<div class="newschet-av-field newschet-av-type" style="
    width: 25%;
    height: 50px;
    float: left;
">
<label style="
  width: 30%;
    text-align: left;
	padding-top:7px;
"> Тип оп&nbsp;&nbsp;&nbsp;&nbsp;</label>
<select style="
    width: 53%;
    font-size: 15pt;
    margin-top: 7px;
    padding-top: 4px;
    padding-bottom: 4px;margin-left: 40px;" id="av" name="av">
<option value="-1"selected></option>
<option value="0">Новый</option>
<option value="1">Продление</option>
<option value="2">Удл.личности от контура</option>
<option value="3">Ускор.удл.личности</option>
<option value="4">Бесплатно</option>
</select>
</div>
<div class="newschet-av-field newschet-av-services" style="
    width: 35%;
    height: auto;
    float: left;
	display:none;
" id="dobusl">
<label style="
  width: 50%;
    text-align: left;
	padding-top:7px;
">Услуги в счете ОП&nbsp;&nbsp;&nbsp;&nbsp;</label>
 <div style="
    width: 50%;
    font-size: 15pt;

    padding-top: 4px;
    padding-bottom: 4px;float: left;" id="dobusll">
<select style="
    width: 100%;
    font-size: 15pt;
    margin-top: 7px;
    padding-top: 4px;
    padding-bottom: 4px;" name="dobusl[]" id="dobuslll" >
	<option value="0"selected></option>
 <?$r=mysql_query("SELECT * from dobusl ");
   while($res = mysql_fetch_assoc($r)) :?>	  
  <option value="<?php echo $res['id'];?>"><?php echo htmlspecialchars($res['dobuslnaim']);?></option>
    <?php endwhile; ?>
</select>
</div>
</div>
<div class="newschet-av-field newschet-av-sht" style="
    
    width: 15%;
    height: 50px;
    float: left;
    margin-left: 0px;
    padding-left: 10px;
	display:none;

" id="shtsert">
<label style="
  
  width: 60%;
  text-align: left;
  padding-top:7px;

">ШТ. эцп&nbsp;&nbsp;&nbsp;&nbsp;</label>
<input class="form-control" style="width:50px;float: left;margin-top: 10px;font-size: 15pt;
    text-align: center;" id="sht" name="sht"></input>
</div>
</div>

<div class="form-control newschet-av-panel" style="height: auto;float: left;" id="dobp">
<div class="newschet-dp-row" style="
    height: auto;
    float: left;
    width: 100%;
" id="dobpp">
<div class="newschet-av-field newschet-dp-product" style="
    width: 25%;
    height: auto;
	float: left;
" id="dobpr">
<button type="button" class="newschet-add-dp-button" onclick="newschetAddDpRow(this)">+ дп</button>
<label style="
    width: 40%;
    padding-top: 5px;
">дп</label>
<div class="newschet-dp-select-wrap">
<select style="
    width: 53%;
    margin-left: 10px;
    margin-top: 8px;
    font-size: 15pt;
    padding-top: 4px;
    padding-bottom: 4px;
" name="dobprod[]" id="dobprod" class="dobprod">
<option value="0"selected></option>
<?$r=mysql_query("SELECT * from dobprod");
   while($res = mysql_fetch_assoc($r)) :?>	  
  <option value="<?php echo $res['id'];?>"><?php echo htmlspecialchars($res['dobprodnaim']);?></option>
    <?php endwhile; ?>
</select>
</div>
</div>
<div class="newschet-av-field newschet-dp-type" style="
    width: 25%;
    height: 50px;
    float: left;
	display:none;
" id="dobppr" class="dobppr">
<label style="
  
  width: 39%;
  text-align: left;
  padding-top:7px;

">Тип дп&nbsp;&nbsp;&nbsp;&nbsp;</label>
<select style="
  width: 53%;
    font-size: 15pt;
    margin-top: 7px;
    padding-top: 4px;
    padding-bottom: 4px;
    margin-left: 6px;" id="avdob" name="avdob[]" class="avdob">
<option value="-1"selected></option>
<option value="0">Новый</option>
<option value="1">Продление</option>
<option value="2">Удл.личности от контура</option>
<option value="3">Ускор.удл.личности</option>
<option value="4">Бесплатно</option>
</select>
</div>
<div class="newschet-av-field newschet-av-services newschet-dp-services" style="
 width: 35%; height: auto; float: left; display: none;" id="dobuslpr">
<label style="
  width: 50%;
    text-align: left;
	padding-top:7px;
">Услуги в счете ДП&nbsp;&nbsp;&nbsp;&nbsp;</label>
 <div style="
    width: 50%;
    font-size: 15pt;

    padding-top: 4px;
    padding-bottom: 4px;float: left;" id="dobuslprl">
<select style="
    width: 100%;
    font-size: 15pt;
    margin-top: 7px;
    padding-top: 4px;
    padding-bottom: 4px;" name="dobuslpr[]" id="dobusllpr">
	<option value="-1" selected></option>
 <?$r=mysql_query("SELECT * from dobusl ");
   while($res = mysql_fetch_assoc($r)) :?>	  
  <option value="<?php echo $res['id'];?>"><?php echo htmlspecialchars($res['dobuslnaim']);?></option>
    <?php endwhile; ?>
</select>
</div>
</div>
<div class="newschet-av-field newschet-dp-sht" style="
    
    width: 15%;
    height: 50px;
    float: left;
    margin-left: 0px;
    padding-left: 10px;
	display:none;

" id="shtsertpr">
<label style="
  
  width: 60%;
  text-align: left;
  padding-top:7px;

">ШТ. эцп&nbsp;&nbsp;&nbsp;&nbsp;</label>
<input class="form-control" style="width:50px;float: left;margin-top: 10px;font-size: 15pt;
    text-align: center;" id="shtpr" name="shtpr[]" value="0"></input>
</div>
</div>
</div>
</div>
<?}?>
<?php 


 
//echo '<div>
//<br>
//<table class="table">
//<tr>
//<td colspan="2">
//<select id="dog" class="form-control" type="text" name="dog" style="font-size: 15px;">';
//if(!isset($_GET['rand'])){
//
//}else{
//	echo'<option value="'.$pdepo['oferta'].'" style="background: #ccc;">';
//	if($pdepo['oferta'] == 1){
//		echo 'Договор оферты';
//	}if($pdepo['oferta'] == 2){
//		echo 'Контур';
//	}if($pdepo['oferta'] == 0){
//		echo 'Новый договор';
//	}
//	echo'</option>';
//}
//echo'<option value="1">Договор оферты</option>';
///*<option value="2">Контур</option>*/
//echo'<option value="0">Новый договор</option>';
//$query1 = mysql_query("SELECT * from dogovor WHERE ogrn = $_GET[ogrn]");
//while($row1 = mysql_fetch_array($query1)) {
//echo "<option value='".$row1['god'].$row1['filial'].$row1['otdel'].$row1['nomer']."'>Договор №: ".$row1['god'].$row1['filial'].$row1['otdel'].$row1['nomer']."</option>";
//}
//if(isset($_GET['rand'])){
//}else{
//echo '<div class="col-md-4" style="padding-bottom: 10px;">';
//include 'docmyogrn.php';
//echo '</div>';
//}
//echo '</select>
//</td>
//</tr>
//</table>
//</div>';

if(!isset($_GET['rand'])){ 
echo '
<input type="text" style="display:none;" name="shetrand" id="shetrand" value="'.$shetrand.'"><br>
<input type="submit" name="submitsch" value="Создать" class="btn btn-success col-md-12" /><br>';
}else{
echo '
<input type="text" style="display:none;" name="shetrand" id="shetrand" value="'.$shetrand.'"><br>
<input type="submit" name="submitsave" value="Сохранить" class="btn btn-success col-md-12" /><br>';
}
?>


</form>
</div>




<script>
(function () {
    function newschetShow(block) {
        $(block).removeClass("newschet-is-hidden").show();
    }

    function newschetHide(block) {
        $(block).addClass("newschet-is-hidden").hide();
    }

    function newschetSetMode() {
        var mode = $("#idprodkschet").val();
        if (mode === "1") {
            $("#prodkschet").show();
            $("#oldns").hide();
        } else {
            $("#oldns").show();
            if ($.trim($("#nsold").val()) === "") {
                $("#prodkschet").hide();
            }
        }
    }

    function newschetSetOldResult(message, ns) {
        var text = $.trim(message);
        var notFound = "Данный номер счета не найден в системе";
        var wrongProduct = "Выбранный продукт не указан в счете №'" + ns + "'";
        var status = $("#oldschet");

        if (text === notFound || text === wrongProduct) {
            status.text(text).css("color", "#c9413b");
            $("#prodkschet").hide();
            return;
        }

        status.text(text || "Счет подходит для продления, можно выбрать тарифы ниже.").css("color", "#16803a");
        $("#prodkschet").show();
    }

    function newschetCheckOldSchet() {
        var ns = $.trim($("#nsold").val());
        if (ns === "") {
            $("#oldschet").text("");
            $("#prodkschet").hide();
            return;
        }

        $.ajax({
            type: "GET",
            url: "oldschet.php",
            data: "nsold=" + encodeURIComponent(ns) + "&idkli=<?php echo intval($_GET['id']); ?>&parent=<?php echo intval($_GET['parent']); ?>",
            success: function (msg) {
                newschetSetOldResult(msg, ns);
            }
        });
    }

    function newschetRefreshTarifRows() {
        $("#tab .newschet-tarif-row").each(function () {
            var row = $(this);
            var checked = row.find('input[type="checkbox"]').prop("checked");
            var qty = parseFloat(row.find(".newschet-qty-input").val()) || 0;
            row.toggleClass("is-selected", checked || qty > 0);
        });
    }

    function newschetDpRow(element) {
        return $(element).closest(".newschet-dp-row, #dobpp");
    }

    function newschetDpValue(row, selector) {
        var control = $(row).find(selector).first();
        return control.length ? control.val() : "";
    }

    function newschetSetDpProductState(row) {
        row = $(row);
        var productValue = newschetDpValue(row, 'select[name="dobprod[]"]');
        var typeBlock = row.find("#dobppr").first();
        var serviceBlock = row.find("#dobuslpr").first();
        var shtBlock = row.find("#shtsertpr").first();

        if (productValue && productValue !== "0" && productValue !== "-1") {
            newschetShow(typeBlock);
        } else {
            newschetHide(typeBlock);
            newschetHide(serviceBlock);
            newschetHide(shtBlock);
        }
    }

    function newschetSetDpTypeState(row) {
        row = $(row);
        var productValue = newschetDpValue(row, 'select[name="dobprod[]"]');
        var typeValue = newschetDpValue(row, 'select[name="avdob[]"]');
        var serviceBlock = row.find("#dobuslpr").first();
        var shtBlock = row.find("#shtsertpr").first();

        if (!productValue || productValue === "0" || productValue === "-1" || typeValue === "4" || typeValue === "-1" || typeValue === "") {
            newschetHide(serviceBlock);
            newschetHide(shtBlock);
            return;
        }

        if (typeValue === "0" || typeValue === "1") {
            newschetShow(serviceBlock);
        } else {
            newschetHide(serviceBlock);
        }
        newschetShow(shtBlock);
    }

    function newschetResetDpRow(row) {
        row = $(row);
        row.find('select[name="dobprod[]"]').first().val("0");
        row.find('select[name="avdob[]"]').first().val("-1");
        row.find('input[name="shtpr[]"]').first().val("0");
        row.find('select[name="dobuslpr[]"]').not(":first").remove();
        row.find('select[name="dobuslpr[]"]').first().val("-1");
        newschetSetDpProductState(row);
        newschetSetDpTypeState(row);
    }

    function newschetIsEmptyServiceValue(value) {
        return value === "" || value === "0" || value === "-1" || typeof value === "undefined";
    }

    function newschetNormalizeServiceSelects(holder, selector, emptyValue) {
        holder = $(holder);
        var selects = holder.find(selector);
        var emptySelects;
        var newSelect;

        if (!selects.length) {
            return;
        }

        emptySelects = selects.filter(function () {
            return newschetIsEmptyServiceValue($(this).val());
        });

        if (emptySelects.length > 1) {
            emptySelects.slice(1).remove();
        }

        selects = holder.find(selector);
        emptySelects = selects.filter(function () {
            return newschetIsEmptyServiceValue($(this).val());
        });

        if (!emptySelects.length) {
            newSelect = selects.first().clone(false, false);
            newSelect.val(emptyValue);
            holder.append(newSelect);
        }
    }

    function newschetPrepareServiceHolderForSubmit(holder, selector) {
        holder = $(holder);
        var selects = holder.find(selector);
        var hasSelected = false;

        selects.prop("disabled", false);
        selects.each(function () {
            if (!newschetIsEmptyServiceValue($(this).val())) {
                hasSelected = true;
            }
        });

        if (hasSelected) {
            selects.filter(function () {
                return newschetIsEmptyServiceValue($(this).val());
            }).prop("disabled", true);
        }
    }

    window.newschetPrepareServicesForSubmit = function () {
        newschetPrepareServiceHolderForSubmit($("#dobusll"), 'select[name="dobusl[]"]');
        $(".newschet-dp-row, #dobpp").each(function () {
            newschetPrepareServiceHolderForSubmit($(this).find("#dobuslprl").first(), 'select[name="dobuslpr[]"]');
        });
    };

    window.newschetAddDpRow = function (button) {
        var sourceRow = newschetDpRow(button);
        if (!sourceRow.length) {
            sourceRow = $(".newschet-dp-row, #dobpp").last();
        }
        if (!sourceRow.length) {
            return;
        }

        var newRow = sourceRow.clone(false, false);
        newschetResetDpRow(newRow);
        sourceRow.closest("#dobp").append(newRow);
        newRow.find('select[name="dobprod[]"]').first().focus();
    };

    function newschetSetMainAvState() {
        var value = $("#av").val();
        if (value === "0" || value === "1") {
            newschetShow($("#dobusl"));
            newschetShow($("#shtsert"));
        } else if (value === "2" || value === "3") {
            newschetHide($("#dobusl"));
            newschetShow($("#shtsert"));
        } else {
            newschetHide($("#dobusl"));
            newschetHide($("#shtsert"));
        }
    }

    function newschetShowAgentBlock() {
        $("#agent").show();
    }

    $(document).ready(function () {
        var oldCheckTimer = null;

        newschetSetMode();
        newschetRefreshTarifRows();

        $("#idprodkschet").on("change", function () {
            newschetSetMode();
            if ($(this).val() === "2") {
                newschetCheckOldSchet();
            }
        });

        $("#nsold").on("input", function () {
            window.clearTimeout(oldCheckTimer);
            oldCheckTimer = window.setTimeout(newschetCheckOldSchet, 350);
        }).on("change", newschetCheckOldSchet);

        $(".newschet-old-option").on("click", function () {
            $("#nsold").val($(this).data("ns"));
            newschetCheckOldSchet();
        });

        $(document).on("keyup change click", "#tab input", newschetRefreshTarifRows);
        $(document).on("input change", ".newschet-qty-input", function () {
            $(this).trigger("keyup");
        });

        if ($.trim($("#nsold").val()) !== "") {
            newschetCheckOldSchet();
        }

        if (<?php echo intval($_GET['tip']); ?> === 12) {
            newschetShowAgentBlock();
            newschetSetMainAvState();
            $(".newschet-dp-row, #dobpp").each(function () {
                newschetSetDpProductState(this);
                newschetSetDpTypeState(this);
            });

            $("#av").on("change", newschetSetMainAvState);
            $("#dobusl").on("change", 'select[name="dobusl[]"]', function () {
                newschetNormalizeServiceSelects($("#dobusll"), 'select[name="dobusl[]"]', "0");
            });
            $("body").on("change", ".dobprod", function () {
                var row = newschetDpRow(this);
                newschetSetDpProductState(row);
                newschetSetDpTypeState(row);
            });
            $("body").on("change", ".avdob", function () {
                newschetSetDpTypeState(newschetDpRow(this));
            });
            $("body").on("change", 'select[name="dobuslpr[]"]', function () {
                var row = newschetDpRow(this);
                newschetNormalizeServiceSelects(row.find("#dobuslprl").first(), 'select[name="dobuslpr[]"]', "-1");
            });
        }
    });
})();
</script>

<?php
$messaeLog = ' Пользователь ' . $userdata['users_id'] . ' GET[rand] ' . $_GET['rand'] . ' $shetrand ' . $shetrand . ' $_POST[shetrand] ' . $_POST['shetrand']
    . ' kolichschet ' . $sujmdfhsd . ' $zs ' . $zs . ' $_POST[submitsch] ' . $_POST['submitsch'] . ' $_POST[dog] '
    . $_POST['dog'];
error_log(date('Y-m-d H:i:s') . ' ' . $messaeLog . PHP_EOL, 3, 'log/voovi.log');
?>

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

            if (window.newschetPrepareServicesForSubmit) {
                window.newschetPrepareServicesForSubmit();
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
