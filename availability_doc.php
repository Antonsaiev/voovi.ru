<?php
include 'conf.php';
include 'invoice_action.php';
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



// docs - наличие документов  contract - наличие закрывающих
$currentTime = $_POST['currentTime'];  // текущее время в формате ISO
$dateTime = new DateTime($currentTime, new DateTimeZone('UTC'));
// Установим временную зону, если нужно
$dateTime->setTimezone(new DateTimeZone('Europe/Moscow'));
// Выведем дату и время в удобочитаемом формате
$dateTimeStr = $dateTime->format(' H:i d.m.Y');
$fName = mb_substr($userdata['f_name'],0,1,'UTF-8');
$whois = $fName. '. ' . $userdata['l_name'];
$rand = $_POST['rand'];
if ($_POST['action']  === 'docs') {
    $checkboxStatus = ($_POST['checkboxStatus'] === 'true' || $_POST['checkboxStatus'] === true) ? 1 : 0;
    if ($checkboxStatus == 1) {
        $upst = updateStatusDocs($rand, 1, $userdata['users_id'], $currentTime);
        echo "$dateTimeStr $whois";
    }
    elseif ($checkboxStatus == 0){
        $upst = updateStatusDocs($rand, 0, $userdata['users_id'], $currentTime);
        echo "";
    }


}
elseif ($_POST['action']  === 'contract') {
    $checkboxStatus = $_POST['checkboxStatus'];
    $upst = updateStatusContract($rand, $checkboxStatus, $userdata['users_id'], $currentTime);
    echo "$dateTimeStr $whois";
}
elseif ($_POST['action']  === 'tn') {
    $checkboxStatus = $_POST['checkboxStatus'];
    $upst = updateStatusTN($rand, $checkboxStatus, $userdata['users_id'], $currentTime);
    echo "$dateTimeStr $whois";
}
elseif ($_POST['action']  === 'dogovor') {
    $checkboxStatus = $_POST['checkboxStatus'];
    $upst = updateStatusDogovor($rand, $checkboxStatus, $userdata['users_id'], $currentTime);
    echo "$dateTimeStr $whois";
}
elseif ($_POST['action']  === 'tn_date') {
    $checkboxStatus = $_POST['checkboxStatus'];
    $upst = updateDateTN($rand, $checkboxStatus, $userdata['users_id'], $currentTime);
    echo "$dateTimeStr $whois";
}
elseif ($_POST['action'] === 'need_diadok') {
    $checkboxStatus = ($_POST['checkboxStatus'] === 'true' || $_POST['checkboxStatus'] === true) ? 1 : 0;

    // Функция обновления — должна быть реализована в invoice_action.php
    $upst = updateStatusElectronicallySigned($rand, $checkboxStatus, $userdata['users_id'], $currentTime);

    // Если хочешь отображать, кто и когда поменял:
    echo "$dateTimeStr $whois";
}

?>