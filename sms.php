<?php
 
require_once "1000sms.php";
 
$email = "gmrx@mail.ru";
$password = "Ve0UNc";
$phone = "79097565645";
$phones = array("79097565645", "79097565645");
$dlr_url = "http://example.com/test.php?state=%d&phone=%p";
$dlr_mask = 35;
 
 
// Пример 1 - если необходимо отправить одно SMS сообщение, можно совместить
// аутентификацию с отправкой сообщения.
//--------------------------------------------------------------------

// Отправляется SMS сообщение, указывается дополнительный параметр - имя
// отправителя (полный список дополнительных параметров метода push_msg
// можно найти в описании API, например, test=1 - режим отладки).
 
var_dump(
smsapi_push_msg_nologin($email, $password, $phone, "Hello world =)!", array("sender_name"=>"user"))
);
 
echo "OK\n";
 
?>