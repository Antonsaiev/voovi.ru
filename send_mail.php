<?php

function sendErrorStatusSchet($ns, $statusSchet, $statusJoinTable) {  //Отправка письма - ошибка статуса счета (расхождение статусов)
    $tema = "Ошибка проставление статуса счету: " . $ns;
    $to = "infosavoir@yandex.ru";
    $subject = $tema;
    $charset = "utf-8";
    $headerss = "Content-type: text/html; charset=$charset\r\n";
    $headerss .= "MIME-Version: 1.0\r\n";
    $headerss .= "Date: " . date('D, d M Y h:i:s O') . "\r\n";
    $headerss .= "From:it.savoir<it.savoir@yandex.ru>\r\n";
    $headerss .= "Reply-To:it.savoir@yandex.ru\r\n";
    $msg =" 
    <html>
    <head>
    </head>
    <body>
        <p> Счет номер " . $ns . "</p>
        <p> Имеет статус: " . $statusJoinTable . "</p>
        <p> Но статус в строке счета отличается: " . $statusSchet . "</p>
    </body>
    </html>
    " . " Это сообщение сформировано автоматически.";
    mail($to, $subject, $msg, $headerss);
}

?>
