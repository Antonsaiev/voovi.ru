<?php
// Проверяем прежние условия отгрузки без запросов к документу Контура.
identity_test_query('CREATE TABLE produkti(id INT PRIMARY KEY,parent INT) ENGINE=InnoDB');
identity_test_query('INSERT INTO produkti VALUES(1,12)');
$guardRand = mysql_real_escape_string($source['rand']);
identity_test_query("UPDATE schet SET akt='0',oplachenks='1',produkt='1',nomerschetks='N-ready',priceks='0',
    url='https://other-provider.example/invoice',tipprod='Нет' WHERE rand='".$guardRand."'");
$_POST = array(); $_GET = array();
require_once dirname(__DIR__).'/invoice_action.php';
$guard = function() use ($guardRand) { return checkError(array("'".$guardRand."'")); };
$result=$guard();
identity_test_require(empty($result['error']), 'Ссылка другого поставщика запретила прежнюю отгрузку');
identity_test_query("UPDATE schet SET url='https://billy-partners.kontur.ru/billinfo/unknown' WHERE rand='".$guardRand."'");
identity_test_require(empty($guard()['error']), 'Отсутствующая связь Контура запретила прежнюю отгрузку');
identity_test_query("INSERT INTO bills_voovi_links(schet_rand,BillId) VALUES ('".$guardRand."',NULL)");
identity_test_require(empty($guard()['error']), 'Ожидающая связь запретила прежнюю отгрузку');
identity_test_query("UPDATE schet SET oplachenks='0' WHERE rand='".$guardRand."'");
$result=$guard();
identity_test_require(!empty($result['error']) && strpos($result['error_message'],'неоплачен') !== false,
    'Изменено прежнее ограничение действий по локально неоплаченному счёту');
identity_test_query("UPDATE schet SET oplachenks='1',akt='1',akt_date='".date('ym', strtotime('first day of last month'))."' WHERE rand='".$guardRand."'");
$result=$guard();
identity_test_require(!empty($result['error']) && strpos($result['error_message'],'прошлом периоде') !== false,
    'Изменено прежнее ограничение возврата отгрузки прошлого периода');

identity_test_query('DROP TABLE IF EXISTS call_center');
identity_test_query("CREATE TABLE call_center (id INT AUTO_INCREMENT PRIMARY KEY, rand VARCHAR(366) DEFAULT '',
    ns VARCHAR(366),idogrn INT,`4` VARCHAR(366)) ENGINE=InnoDB");
identity_test_query("UPDATE schet SET akt='1',akt_date='".date('ym')."' WHERE rand='".$guardRand."'");
$number=identity_test_scalar("SELECT ns FROM schet WHERE rand='".$guardRand."' LIMIT 1");
$numberSql=mysql_real_escape_string($number);
$other = identity_test_scalar("SELECT rand FROM schet WHERE rand<>'".$guardRand."' LIMIT 1");
$otherSql=mysql_real_escape_string($other);
identity_test_query("UPDATE schet SET ns='".$numberSql."',idkli='1',produkt='1' WHERE rand='".$otherSql."'");
identity_test_query("INSERT INTO call_center(rand,ns,idogrn,`4`) VALUES ('".$guardRand."','".$numberSql."',1,'1'),
    ('".$otherSql."','".$numberSql."',1,'1'),('','".$numberSql."',1,'1')");
$invoice=mysql_fetch_assoc(identity_test_query("SELECT * FROM schet WHERE rand='".$guardRand."' LIMIT 1"));
handleAktOne($invoice);
identity_test_require((int)identity_test_scalar("SELECT COUNT(*) FROM call_center WHERE rand='".$otherSql."'")===1,
    'Возврат одного счёта удалил продление другого с таким же номером');
identity_test_require((int)identity_test_scalar("SELECT COUNT(*) FROM call_center WHERE rand=''")===1,
    'Удалено историческое продление с неоднозначной принадлежностью');
identity_test_require((int)identity_test_scalar("SELECT COUNT(*) FROM call_center WHERE rand='".$guardRand."'")===0,
    'Возврат не удалил продление выбранного rand');
identity_test_require(voovi_schet_find_rand($number,'1')===null, 'Неоднозначный номер выбрал случайный rand');
identity_test_query("UPDATE schet SET ns='other-number' WHERE rand='".$otherSql."'");
identity_test_require(voovi_schet_find_rand($number,'1')===$source['rand'], 'Однозначный номер не найден');
deleteFromCallCenter($invoice);
identity_test_require((int)identity_test_scalar("SELECT COUNT(*) FROM call_center WHERE rand=''")===0,
    'Не удалено однозначное историческое продление');

// Ручное продление номера изменяет только счёт выбранного клиента, даже при совпавших ns.
identity_test_query('ALTER TABLE call_center ADD COLUMN `date` VARCHAR(36), ADD COLUMN `6` VARCHAR(36),
    ADD COLUMN `9` VARCHAR(36), ADD COLUMN `10` VARCHAR(36), ADD COLUMN otk VARCHAR(36)');
identity_test_query('DROP TABLE IF EXISTS kol_prodlen');
identity_test_query('CREATE TABLE kol_prodlen (id INT AUTO_INCREMENT PRIMARY KEY, ns VARCHAR(366),kol VARCHAR(36),
    tip VARCHAR(36),date_prodleni VARCHAR(36),randkol VARCHAR(366),status VARCHAR(36)) ENGINE=InnoDB');
identity_test_query("UPDATE schet SET ns='".$numberSql."',idkli='2',dataprod='2027-01-01' WHERE rand='".$otherSql."'");
identity_test_query("UPDATE schet SET dataprod='2027-01-01' WHERE rand='".$guardRand."'");
$manualSource=file_get_contents(dirname(__DIR__).'/aaadate.php');
$manualStart=strpos($manualSource,"if(isset(\$_POST['go']))");
$manualEnd=strpos($manualSource,'?>',$manualStart);
$manualCode=substr($manualSource,$manualStart,$manualEnd-$manualStart);
$_GET=array('kli'=>'1','inn'=>'fixture','kpp'=>'');
$_POST=array('go'=>'1','nsold'=>$number,'date'=>'2028-03-04','produkti'=>'1','tip'=>'Сертификат',
    'status'=>'0','shtprod'=>'1');
$shetrand='manual-renewal-fixture';
eval($manualCode);
identity_test_require(identity_test_scalar("SELECT dataprod FROM schet WHERE rand='".$guardRand."' AND `del`='0' LIMIT 1")==='2028-03-04',
    'Ручное продление не изменило выбранную группу');
identity_test_require(identity_test_scalar("SELECT dataprod FROM schet WHERE rand='".$otherSql."' LIMIT 1")==='2027-01-01',
    'Ручное продление по номеру изменило чужую группу');
$_POST['nsold']='unknown-number';
eval($manualCode);
identity_test_require((int)identity_test_scalar("SELECT COUNT(*) FROM call_center WHERE ns='unknown-number' AND rand=''")===1,
    'Ручное продление без найденного счёта ошибочно запрещено');
echo "Legacy shipment: local flags, independent Kontur links and scoped renewals — OK\n";
