<?php
include 'conf.php';

// Функция для вывода ошибки и остановки скрипта
function respondWithError($message)
{
    echo json_encode(["success" => false, "error" => $message]);
    exit; // Останавливаем выполнение скрипта после вывода ошибки
}

$result4 = mysql_query("SELECT count(*) from schet_status WHERE schet='$_GET[schet]' AND status='$_GET[status]' AND kto='$_GET[kto]'");
$result = mysql_result($result4, 0);

$koment = "INSERT INTO `schet_status` (`schet`, `status`, `kto`, data) VALUES ('$_GET[schet]', '$_GET[status]', '$_GET[kto]', '" . date('d.m.Y; H:i') . "');";
//mysql_query($koment) or die(mysql_error($link));
if (!mysql_query($koment)) {
    respondWithError("Ошибка в запросе koment: " . mysql_error());
}

$qrand = "SELECT sortir FROM `schet` ORDER BY sortir DESC";
$resultrand = mysql_query($qrand);
if (!$resultrand) {
    respondWithError("Ошибка в запросе qrand: " . mysql_error());
}
$personrand = mysql_fetch_array($resultrand);

echo $personrand['sortir'];
$var = $personrand['sortir'] + 1;

$komentq = "UPDATE `schet` SET `status`='$_GET[status]', `sortir` =  '" . $var . "' WHERE `rand`='$_GET[schet]'";
//mysql_query($komentq) or die(mysql_error($linkq));
if (!mysql_query($komentq)) {
    respondWithError("Ошибка в запросе komentq: " . mysql_error());
}

//if ($_GET['status'] == '22') {
//    $komentq = "UPDATE `schet` SET `akt` =  '1',`akt_date` =  '" . date('ym') . "' WHERE rand = '$_GET[schet]'";
//    mysql_query($komentq) or die(mysql_error($linkq));
//}
//elseif ($_GET['status'] == '23') {
//    $komentq = "UPDATE `schet` SET `otk` =  '1' WHERE rand = '$_GET[schet]'";
////    mysql_query($komentq) or die(mysql_error($linkq));
//    if (!mysql_query($komentq)) {
//        respondWithError("Ошибка в запросе komentq для статуса 23: " . mysql_error());
//    }
//}
//else {
//    $komentq = "UPDATE `schet` SET `akt` =  '0',`akt_date` =  '' WHERE rand = '$_GET[schet]'";
////    mysql_query($komentq) or die(mysql_error($linkq));
//    if (!mysql_query($komentq)) {
//        respondWithError("Ошибка в запросе komentq для статуса по умолчанию: " . mysql_error());
//    }
//}

if (!mysql_query("UPDATE `schet` SET `akt` =  '0',`akt_date` =  '' WHERE rand = '$_GET[schet]'")) {
    respondWithError("Ошибка в запросе для отмены отгрузки: " . mysql_error());
}

$status_schet_query = mysql_query("SELECT `schet`.ns, `schet`.status, `schet_status`.`status` as status_join_table FROM `schet` LEFT JOIN `schet_status` ON `schet`.rand=`schet_status`.`schet` AND `schet_status`.id = ( SELECT MAX(id) FROM `schet_status` WHERE `schet_status`.schet = `schet`.rand ) WHERE `schet`.rand = '$_GET[schet]' GROUP BY `schet`.rand ");
$status_schet = mysql_fetch_array($status_schet_query);

$status_schet_json = json_encode($status_schet);
echo "\nСтатус строки счета: " . $status_schet['status'] . "\nСтатус из актуального статуса: " . $status_schet['status_join_table'] . "\n\n";

if ($status_schet['status'] !== $status_schet['status_join_table']) {
    include 'send_mail.php';
    sendErrorStatusSchet($status_schet['ns'], $status_schet['status'], $status_schet['status_join_table']);
}

echo $_GET['status'];

?>