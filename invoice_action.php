<?php

function checkError($id) {  //Проверка на возможность отгрузить счет или вернуть с отгрузки
    $impid = implode(", ", $id);
    $q47 = "SELECT schet.id, schet.ns, schet.idkli, schet.rand, schet.sortir, schet.del, schet.tipprod, schet.akt, schet.akt_date, schet.dataprod, schet.datasert, schet.status, schet.name, schet.produkt, schet.inn, schet.kpp, schet.oplachenks, schet.priceks, produkti.parent FROM schet INNER JOIN produkti ON produkti.id = schet.produkt WHERE schet.del = '0' AND schet.rand IN (" . $impid . ") GROUP BY schet.rand";
    $result47 = mysql_query($q47);

    $errorMessages = "";

    while ($row = mysql_fetch_array($result47, MYSQL_ASSOC)) {

        $array_return['sql_query'][] = $row;
        if ($row["parent"] == '24') {
            if ($row["priceks"] == '' || $row["priceks"] == '0') {
                $errorMessages .= "Cчет " . $row["ns"] . " должен содержать сумму расхода, заполните. ";
                $array_return['error'] = true;
            }
        } elseif ($row["oplachenks"] == '0') {
            $errorMessages .= "Cчет " . $row["ns"] . " неоплачен - действия с отгрузкой запрещены. ";
            $array_return['error'] = true;
        }
        elseif ($row["akt"] == 0) {
            if (empty($row['tipprod'])) {
                $errorMessages .= "Для счета " . $row["ns"] . " укажите тип продления. ";
                $array_return['error'] = true;
            }
            elseif ($row['tipprod'] == "Сер/Пос" && (empty($row['dataprod']) || empty($row['datasert']))  ) {
                $errorMessages .= "Для счета " . $row["ns"] . " укажите даты окончания сертификата или поставки. ";
                $array_return['error'] = true;
            }
            elseif ($row['tipprod'] == "Поставка" && empty($row['datasert'])) {
                $errorMessages .= "Для счета " . $row["ns"] . " укажите дату окончания поставки. ";
                $array_return['error'] = true;
            }
            elseif ($row['tipprod'] == "Сертификат" && empty($row['dataprod'])) {
                $errorMessages .= "Для счета " . $row["ns"] . " укажите дату окончания сертификата. ";
                $array_return['error'] = true;
            }

        }
        elseif ($row["akt"] == 1 && date('ym') != $row["akt_date"]) {
            $errorMessages .= "Cчет " . $row["ns"] . " был отгружен в прошлом периоде. Невозможно убрать из отгрузки. ";
            $array_return['error'] = true;
        }

    }

    $array_return['error_message'] = $errorMessages;
    return $array_return;
}

function updateSchet($id) {
if (empty($id) || $id == 0) {
    //echo "<script>console.log('счета не переданы');</script>";
return 'Ошибка' . $id;
}
else {
    $array_data = checkError($id);
    if ($array_data['error']) { // Если хоть в одном счете запрет на отгрузку
        $error_message = $array_data["error_message"];
        //echo "<script>console.log($error_message);</script>";
        return '<script>alert("' . $error_message . '");</script>';
    }
    else { // Все переданные счета могут быть отгружены
        $result = $array_data["sql_query"];
        foreach($result as $value){
            if ($value['akt'] == 0) {  // Отгружаем счет
                handleAktZero($value);
            }
            elseif ($value['akt'] == 1) { // Убираем счет из отгрузки
                handleAktOne($value);
            }
        }
        return '<script>alert("Все действия по отгрузке выполнены");</script>';
    }
}
}

function handleAktZero($oneSchet) {  //Отгрузка счета

    //echo "<script>console.log('" . json_encode($oneSchet['tipprod']) . "');</script>";

    $qrand = "SELECT sortir FROM `schet` ORDER BY sortir DESC";
    $resultrand = mysql_query($qrand);
    $personrand = mysql_fetch_array($resultrand);
    $var = $personrand['sortir'] + 1;

    $Qotgruz = mysql_query("UPDATE `schet` SET `akt` = '1', `akt_date` = '" . date('ym') . "', `sortir` = '" . $var . "' WHERE del = '0' AND rand = '" . $oneSchet['rand'] . "'");
    //echo '<script>console.log("'.$oneSchet['tipprod'].'");</script>';

    if ($oneSchet['tipprod'] == "Нет") {

        if (isset($Qotgruz)) {
            return 'Успешно ' . $oneSchet['name'] . '<script>alert("Отправлен в архив без продления");</script>';
        }
        else {
            return '<script>alert("Ошибка отправления в архив");</script>';
        }
    }

    elseif ($oneSchet['tipprod'] == "Сер/Пос") { // Продление с отправкой данных в коллцентр
        insertToCallCenter('Сертификат', $oneSchet, 'dataprod');
        insertToCallCenter('Поставка', $oneSchet, 'datasert');
        if (isset($Qotgruz)) {
            return 'Успешно ' . $oneSchet['name'] . '<script>alert("Отправлен в архив");</script>';
        }

        else {
            return '<script>alert("Ошибка отправления в архив");</script>';
        }
    }
    elseif ($oneSchet['tipprod'] == "Поставка") { // Продление с отправкой данных в коллцентр
        insertToCallCenter('Поставка', $oneSchet, 'datasert');
        if (isset($Qotgruz)) {
            return 'Успешно ' . $oneSchet['name'] . '<script>alert("Отправлен в архив");</script>';
        }

        else {
            return '<script>alert("Ошибка отправления в архив");</script>';
        }
    }
    elseif ($oneSchet['tipprod'] == "Сертификат") { // Продление с отправкой данных в коллцентр
        insertToCallCenter('Сертификат', $oneSchet, 'dataprod');
        if (isset($Qotgruz)) {
            return 'Успешно ' . $oneSchet['name'] . '<script>alert("Отправлен в архив");</script>';
        }

        else {
            return '<script>alert("Ошибка отправления в архив");</script>';
        }
    }

}

function handleAktOne($oneSchet) {  //Возврат из  отгрузки счета
$Qdelete = mysql_query("UPDATE `schet` SET `akt` = '0', `akt_date` = '' WHERE del = '0' AND rand IN (" . $oneSchet['rand'] . ")") or die("error in delete");
deleteFromCallCenter($oneSchet['ns']); // удаляется из коллцентра продление
return 'Успешно ' . $oneSchet['name'];
}

function insertToCallCenter($type, $array_data, $dateField) {  // При отгрузке отправка данных в колл центр
$dateProd = str_replace("-", "", $array_data[$dateField]);
$callCentrIns = "INSERT INTO `call_center`(`date`, `4`,`6`,`9`, `10`, `otk`,`ns`,`idogrn`) VALUES ('" . $dateProd . "','" . $array_data['produkt'] . "','" . $type . "','" . $array_data['inn'] . "','" . $array_data['kpp'] . "','0','" . $array_data['ns'] . "','" . $array_data['idkli'] . "')";
    $error_message = mysql_query($callCentrIns) or die(mysql_error());
    //echo "<script>console.log($error_message);</script>";
}

function deleteFromCallCenter($ns) {
    $callCentrDel = "DELETE FROM `call_center` WHERE ns='" . $ns . "'";
    mysql_query($callCentrDel) or die(mysql_error());
}

function getStatus($rand) {
    $qda = "SELECT * FROM `document_availability` WHERE rand = '" . $rand . "'";
    $resultqda = mysql_query($qda);

    if (mysql_num_rows($resultqda) > 0) {
        // данные вернулись, можно работать с ними
        $dataresultqda = mysql_fetch_array($resultqda);
        // ваш код для работы с данными
        return $dataresultqda;
    } else {
        $status = "INSERT INTO `document_availability`(`rand`) VALUES ('" . $rand . "')";
        $message = mysql_query($status) or die(mysql_error());
        $qda2 = "SELECT * FROM `document_availability` WHERE rand = '" . $rand . "'";
        return mysql_fetch_array(mysql_query($qda2));
    }
}

function getStatusAkt($rand) {
    $status = getStatus($rand);
    $statakt = $status['akt_text'];
    return $statakt;
}

function getStatusDocs($rand) {
    $status = getStatus($rand);
    $statbool = $status['entitling_docs'];
    if ($statbool == 0) {
        return [0, ''];
    }
    else {
        $quser = mysql_query("SELECT * FROM `users` WHERE users_id = '" . $status['user_entitling_docs'] . "'");
        $resuluser = mysql_fetch_array($quser);

        $dateTime = new DateTime($status['datetime_entitling_docs'], new DateTimeZone('UTC'));
        // Установим временную зону, если нужно
        $dateTime->setTimezone(new DateTimeZone('Europe/Moscow'));
        // Выведем дату и время в удобочитаемом формате
        $dateTimeStr = $dateTime->format(' H:i d.m.Y');

        $fName = mb_substr($resuluser['f_name'],0,1,'UTF-8');
        $whois = $fName. '. ' . $resuluser['l_name'];
        $textreturn = "$dateTimeStr $whois";
        return [1, $textreturn];
    }
}

function getStatusContract($rand) {
    $status = getStatus($rand);
    $statselect = $status['closing_docs'];
    $userId = $status['user_closing_docs'];
    if (empty($userId)) {
        return [$statselect, ''];
    }
    else {
        $quser = mysql_query("SELECT * FROM `users` WHERE users_id = '" . $status['user_closing_docs'] . "'");
        $resuluser = mysql_fetch_array($quser);

        $dateTime = new DateTime($status['datetime_closing_docs'], new DateTimeZone('UTC'));
        // Установим временную зону, если нужно
        $dateTime->setTimezone(new DateTimeZone('Europe/Moscow'));
        // Выведем дату и время в удобочитаемом формате
        $dateTimeStr = $dateTime->format(' H:i d.m.Y');

        $fName = mb_substr($resuluser['f_name'],0,1,'UTF-8');
        $whois = $fName. '. ' . $resuluser['l_name'];
        $textreturn = "$dateTimeStr $whois";
        return [$statselect, $textreturn];
    }
}


function updateStatusDocs($rand, $status, $user, $datetime) {
    $getStat = getStatus($rand);
    if ($status == 1) {
    $newStatus = mysql_query("UPDATE `document_availability` SET `entitling_docs` ='" . $status . "', `user_entitling_docs` = '" . $user . "', `datetime_entitling_docs` = '" . $datetime . "' WHERE rand = '" . $rand . "'") or die(mysql_error());
    } else {
        $newStatus = mysql_query("UPDATE `document_availability` SET `entitling_docs` ='" . $status . "', `user_entitling_docs` = NULL, `datetime_entitling_docs` = NULL WHERE rand = '" . $rand . "'") or die(mysql_error());
    }
    return json_encode($newStatus);

}

function updateStatusContract($rand, $status, $user, $datetime) {
    $getStat = getStatus($rand);
    $newStatus = mysql_query("UPDATE `document_availability` SET `closing_docs` ='" . $status . "', `user_closing_docs` = '" . $user . "', `datetime_closing_docs` = '" . $datetime . "' WHERE rand = '" . $rand . "'") or die(mysql_error());
    return json_encode($newStatus);

}

function updateStatusTN($rand, $status, $user, $datetime) {
    $getStat = getStatus($rand);
    $newStatus = mysql_query("UPDATE `document_availability` SET `tn` ='" . $status . "', `user_closing_docs` = '" . $user . "', `datetime_closing_docs` = '" . $datetime . "' WHERE rand = '" . $rand . "'") or die(mysql_error());
    return json_encode($newStatus);
};

function updateDateTN($rand, $date, $user, $datetime) {

    $getStat = getStatus($rand);
    if ($date !== '') {$newDate = mysql_query("UPDATE `document_availability` SET `date_tn` ='" . $date . "', `user_closing_docs` = '" . $user . "', `datetime_closing_docs` = '" . $datetime . "' WHERE rand = '" . $rand . "'") or die(mysql_error());}
    else {$newDate = mysql_query("UPDATE `document_availability` SET `date_tn` = NULL, `user_closing_docs` = '" . $user . "', `datetime_closing_docs` = '" . $datetime . "' WHERE rand = '" . $rand . "'") or die(mysql_error());}
    return json_encode($newDate);
};

function updateStatusDogovor($rand, $status, $user, $datetime) {
    $getStat = getStatus($rand);
    $newStatus = mysql_query("UPDATE `document_availability` SET `dogovor_tovar` ='" . $status . "', `user_closing_docs` = '" . $user . "', `datetime_closing_docs` = '" . $datetime . "' WHERE rand = '" . $rand . "'") or die(mysql_error());
    return json_encode($newStatus);
};

function updateStatusElectronicallySigned($rand, $status) {
    $getStat = getStatus($rand); // чтобы строка точно существовала
    $status = (int)$status ? 1 : 0;

    $newStatus = mysql_query(
        "UPDATE `document_availability`
         SET `is_electronically_signed` = '" . $status . "'
         WHERE rand = '" . $rand . "'"
    ) or die(mysql_error());

    return json_encode($newStatus);
}

function getStatusTN($rand) {
    $status = getStatus($rand);
    $statselect = $status['tn'];
    $userId = $status['user_closing_docs'];
    if (empty($userId)) {
        return [$statselect, ''];
    }
    else {
        $quser = mysql_query("SELECT * FROM `users` WHERE users_id = '" . $status['user_closing_docs'] . "'");
        $resuluser = mysql_fetch_array($quser);

        $dateTime = new DateTime($status['datetime_closing_docs'], new DateTimeZone('UTC'));
        // Установим временную зону, если нужно
        $dateTime->setTimezone(new DateTimeZone('Europe/Moscow'));
        // Выведем дату и время в удобочитаемом формате
        $dateTimeStr = $dateTime->format(' H:i d.m.Y');

        $fName = mb_substr($resuluser['f_name'],0,1,'UTF-8');
        $whois = $fName. '. ' . $resuluser['l_name'];
        $textreturn = "$dateTimeStr $whois";
        return [$statselect, $textreturn];
    }
};

function getStatusDateTN($rand) {
    $status = getStatus($rand);
    $dateTN = $status['date_tn'];
    $userId = $status['user_closing_docs'];
    if (empty($userId)) {
        return [$dateTN, ''];
    }
    else {
        $quser = mysql_query("SELECT * FROM `users` WHERE users_id = '" . $status['user_closing_docs'] . "'");
        $resuluser = mysql_fetch_array($quser);

        $dateTime = new DateTime($status['datetime_closing_docs'], new DateTimeZone('UTC'));
        // Установим временную зону, если нужно
        $dateTime->setTimezone(new DateTimeZone('Europe/Moscow'));
        // Выведем дату и время в удобочитаемом формате
        $dateTimeStr = $dateTime->format(' H:i d.m.Y');

        $fName = mb_substr($resuluser['f_name'],0,1,'UTF-8');
        $whois = $fName. '. ' . $resuluser['l_name'];
        $textreturn = "$dateTimeStr $whois";
        return [$dateTN, $textreturn];
    }
};

function getStatusDogovor($rand) {
    $status = getStatus($rand);
    $statselect = $status['dogovor_tovar'];
    $userId = $status['user_closing_docs'];
    if (empty($userId)) {
        return [$statselect, ''];
    }
    else {
        $quser = mysql_query("SELECT * FROM `users` WHERE users_id = '" . $status['user_closing_docs'] . "'");
        $resuluser = mysql_fetch_array($quser);

        $dateTime = new DateTime($status['datetime_closing_docs'], new DateTimeZone('UTC'));
        // Установим временную зону, если нужно
        $dateTime->setTimezone(new DateTimeZone('Europe/Moscow'));
        // Выведем дату и время в удобочитаемом формате
        $dateTimeStr = $dateTime->format(' H:i d.m.Y');

        $fName = mb_substr($resuluser['f_name'],0,1,'UTF-8');
        $whois = $fName. '. ' . $resuluser['l_name'];
        $textreturn = "$dateTimeStr $whois";
        return [$statselect, $textreturn];
    }
};

function getDateDocuments($rand)

{
    $ddq = "SELECT * FROM `document_data` WHERE rand = '" . $rand . "'";
    $resultddq = mysql_query($ddq);

    if (mysql_num_rows($resultddq) > 0) {
        $dataresultdd = mysql_fetch_array($resultddq);
        return $dataresultdd;
    } else {

        $dschet = "SELECT schet.d, schet.m, schet.y, ogrn.budjet_ogrn FROM schet INNER JOIN ogrn ON schet.idkli=ogrn.id WHERE del = '0' AND schet.rand = (" . $rand . ") GROUP BY schet.rand";
        $resultdschet = mysql_fetch_array(mysql_query($dschet));
        $date_schet_old = $resultdschet['y'].'-'.$resultdschet['m'].'-'.$resultdschet['d'];
        error_log($date_schet_old . PHP_EOL, 3, 'log/voovi.log');
        if ((int)$resultdschet['y'] >= 2024) {
            $newDate = "INSERT INTO `document_data`(`rand`, `d_bill`, `d_contract`, `d_specification`, `d_act`, `d_invoice_advance`, `d_upd`, `d_tn`) VALUES ('" . $rand . "','" . $date_schet_old . "','" . $date_schet_old . "','" . $date_schet_old . "','" . $date_schet_old . "','" . $date_schet_old . "','" . $date_schet_old . "','" . $date_schet_old . "')";
        } else {
            $typeContract = 'dogovor';
            if ($resultdschet['budjet_ogrn'] === '1') {$typeContract = 'contract';}
            $newDate = "INSERT INTO `document_data`(`rand`, `d_bill`, `d_contract`, `d_specification`, `d_act`, `d_invoice_advance`, `d_upd`, `d_tn`, `type_contract`) VALUES ('" . $rand . "','" . $date_schet_old . "','" . $date_schet_old . "','" . $date_schet_old . "','" . $date_schet_old . "','" . $date_schet_old . "','" . $date_schet_old . "','" . $date_schet_old . "','" . $typeContract . "')";
        }
        $message = mysql_query($newDate) or die(mysql_error());
        $dd = "SELECT * FROM `document_data` WHERE rand = '" . $rand . "'";
        return mysql_fetch_array(mysql_query($dd));
    }
}

function getStatusElectronicallySigned($rand) {
    $status = getStatus($rand);
    $flag = (int)$status['is_electronically_signed']; // 0/1
    return $flag;
}


function updateDocuments($rand, $d_bill=null, $d_contract=null, $d_specification=null, $d_act=null, $d_tn=null, $d_upd=null, $d_invoice_advance=null, $type_contract=null)
{
    if ($d_bill) {$newDocuments = mysql_query("UPDATE `document_data` SET `d_bill` ='" . $d_bill . "' WHERE rand = '" . $rand . "'") or die(mysql_error());}
    if ($d_contract) {$newDocuments = mysql_query("UPDATE `document_data` SET `d_contract` ='" . $d_contract . "' WHERE rand = '" . $rand . "'") or die(mysql_error());}
    if ($d_specification) {$newDocuments = mysql_query("UPDATE `document_data` SET `d_specification` ='" . $d_specification . "' WHERE rand = '" . $rand . "'") or die(mysql_error());}
    if ($d_act) {$newDocuments = mysql_query("UPDATE `document_data` SET `d_act` ='" . $d_act . "' WHERE rand = '" . $rand . "'") or die(mysql_error());}
    if ($d_invoice_advance) {$newDocuments = mysql_query("UPDATE `document_data` SET `d_invoice_advance` ='" . $d_invoice_advance . "' WHERE rand = '" . $rand . "'") or die(mysql_error());}
    if ($d_upd) {$newDocuments = mysql_query("UPDATE `document_data` SET `d_upd` ='" . $d_upd . "' WHERE rand = '" . $rand . "'") or die(mysql_error());}
    if ($d_tn) {$newDocuments = mysql_query("UPDATE `document_data` SET `d_tn` ='" . $d_tn . "' WHERE rand = '" . $rand . "'") or die(mysql_error());}
    if ($type_contract) {$newDocuments = mysql_query("UPDATE `document_data` SET `type_contract` ='" . $type_contract . "' WHERE rand = '" . $rand . "'") or die(mysql_error());}
    return json_encode($newDocuments);
}

$typeContract = $_POST['typeContract'];
$rand = $_POST['rand'];
if($rand and $typeContract){
include 'conf.php';
//echo '<script>console.log("'.$rand, $typeContract.'")</script>';
$ret = updateDocuments($rand, null, null, null, null, null, null, null, $typeContract);
//echo '<script>console.log("'.$ret.'")</script>';
}
?>


