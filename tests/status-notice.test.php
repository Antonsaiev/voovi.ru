<?php
require dirname(__DIR__).'/send_mail.php';
function check_notice($v,$why){if(!$v)throw new RuntimeException($why);}
$dir=sys_get_temp_dir().'/voovi-notice-test-'.uniqid();mkdir($dir);
$calls=0;$send=function($to,$subject,$body,$headers)use(&$calls){$calls++;return true;};
check_notice(voovi_status_notice('same','22','65','rand1',$dir,$send,1000),'first');
check_notice(!voovi_status_notice('same','22','65','rand1',$dir,$send,1001),'repeat');
check_notice($calls===1,'sent twice');
check_notice(voovi_status_notice('same','22','65','rand2',$dir,$send,1001),'other invoice same number');
check_notice(voovi_status_notice('same','23','65','rand1',$dir,$send,1001),'changed conflict');
check_notice(!voovi_status_notice('same','65','65','rand1',$dir,$send,1001),'equal');
$fail=function()use(&$calls){$calls++;return false;};
check_notice(!voovi_status_notice('bad','22','65','rand3',$dir,$fail,1000),'failed');$before=$calls;
check_notice(!voovi_status_notice('bad','22','65','rand3',$dir,$fail,1001),'failed throttle');check_notice($calls===$before,'failure spam');
check_notice(voovi_status_notice('bad','22','65','rand3',$dir,$send,4601),'retry after hour');
// Другой запрос держит блокировку: отправка запрещена.
$key=hash('sha256',json_encode(array('parallel','22','65')));
$lock=fopen($dir.'/'.$key.'.lock','c+');flock($lock,LOCK_EX);
check_notice(!voovi_status_notice('same','22','65','parallel',$dir,$send,1000),'parallel claim');
flock($lock,LOCK_UN);fclose($lock);
// Повреждённый журнал не должен разрешать повтор неизвестной отправки.
file_put_contents($dir.'/'.$key.'.json','broken');
check_notice(!voovi_status_notice('same','22','65','parallel',$dir,$send,1000),'corrupt state');
foreach(glob($dir.'/*') as $file)unlink($file);rmdir($dir);
echo "Status notice: once per invoice/conflict, independent rand, unchanged status and failure throttle — PASS\n";
