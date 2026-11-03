<?php
require dirname(__DIR__).'/invoice_renewal.php';
foreach (array(
    array('Нет','','',true,'Нет'),
    array('Поставка','2026-01-01','',true,'Поставка'),
    array('','2026-01-01','2026-02-01',false,'Сер/Пос'),
    array('','2026-01-01','',false,'Сертификат'),
    array('','','2026-02-01',true,'Поставка'),
    array('','','',true,'Сертификат'),
    array('','0000-00-00','0',false,'Поставка'),
) as $case) {
    $row=array('tipprod'=>$case[0],'dataprod'=>$case[1],'datasert'=>$case[2]);
    if (voovi_invoice_renewal_type($row,$case[3])!==$case[4]) throw new RuntimeException('Wrong renewal default');
    if ($row['tipprod']!==$case[0]) throw new RuntimeException('Stored type modified');
}
echo "Renewal defaults: saved type, existing dates, generation flag, empty dates — OK\n";
// Проверяем действительный фрагмент pusya.php на временной таблице отдельной тестовой базы.
if (getenv('DB_HOST')) {
    mysql_connect(getenv('DB_HOST'),getenv('DB_LOGIN'),getenv('DB_PASSWORD')) or die('No DB');
    if (!mysql_select_db('voovi_invoice_manager_test')) die('Isolated test DB required');
    mysql_query('SET NAMES utf8');
    mysql_query('CREATE TEMPORARY TABLE schet (rand VARCHAR(40),tipprod VARCHAR(50),dataprod VARCHAR(40),datasert VARCHAR(40),del INT DEFAULT 0,ns VARCHAR(40))');
    mysql_query("INSERT INTO schet (rand,tipprod,dataprod,datasert,ns) VALUES ('111','Нет','','','TEST')");
    mysql_query('CREATE TEMPORARY TABLE kol_prodlen (ns VARCHAR(40),kol INT,tip VARCHAR(50),date_prodleni VARCHAR(40),randkol VARCHAR(40),status VARCHAR(20))');
    $source=file_get_contents(dirname(__DIR__).'/pusya.php');
    $start=strpos($source,"\$renewalTypeSql = '';");
    $end=strpos($source,"if(\$_GET['tip'] == 'datacar')",$start);
    $_GET=array('tip'=>'datasert','tel'=>'2027-11-04','rand'=>'111');
    eval(substr($source,$start,$end-$start));
    if (mysql_result(mysql_query("SELECT tipprod FROM schet WHERE rand='111'"),0)!=='Нет') throw new RuntimeException('Legacy form changed renewal type');
    $_GET['renewal_type']='Поставка';
    eval(substr($source,$start,$end-$start));
    $row=mysql_fetch_assoc(mysql_query("SELECT tipprod,datasert FROM schet WHERE rand='111'"));
    if ($row['tipprod']!=='Поставка' || $row['datasert']!=='2027-11-04') throw new RuntimeException('Date and default not saved together');
    echo "Renewal date save: default persisted, legacy form preserved — OK\n";
}
