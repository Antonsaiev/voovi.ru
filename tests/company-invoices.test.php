<?php
date_default_timezone_set('Europe/Moscow');
require dirname(__DIR__).'/company_invoice_filters.php';
function company_test($condition, $message) { if (!$condition) throw new RuntimeException($message); }
company_test(company_invoice_period('', '') === array(date('Y-m-01'), date('Y-m-t')), 'Missing dates');
company_test(company_invoice_period('2026-02-30','2026-02-31') === array(date('Y-m-01'),date('Y-m-t')), 'Invalid calendar date');
$row = array('tipprod'=>'Сер/Пос','dataprod'=>'2027-01-15','datasert'=>'2026-12-31','prodlens'=>'0','prodlenks'=>'0');
company_test(count(company_pending_parts($row,'2026-12-20','2027-02-10')) === 2, 'Cross-year/middle month');
$row['prodlenks']='1';
company_test(!company_pending_parts($row,'2026-12-20','2027-02-10'), 'Either flag closes combined invoice');
$row['prodlenks']='0';
company_test(!company_pending_parts($row,'2027-01-16','2027-02-01'), 'Exact day boundaries');
$db = getenv('DB_NAME');
company_test(preg_match('/^voovi_schet_identity_test(?:_[a-z0-9_]+)?$/D', $db), 'Scratch DB only');
mysql_connect(getenv('DB_HOST'), getenv('DB_LOGIN'), getenv('DB_PASSWORD'));
mysql_select_db($db);
mysql_query('SET NAMES utf8');
function company_query($sql) { $result=mysql_query($sql); company_test($result !== false,mysql_error()); return $result; }
// Fixture tables are created only in the separately guarded disposable database.
company_query('DROP TABLE IF EXISTS schet');
company_query("CREATE TABLE schet(id INT,idkli VARCHAR(50) DEFAULT 'client',dateotkaz DATE DEFAULT NULL,akt CHAR(1) DEFAULT '0',rand VARCHAR(50),ns VARCHAR(50),produkt INT,`del` CHAR(1),cher CHAR(1),otk CHAR(1),tipprod VARCHAR(50),prodlens CHAR(1),prodlenks CHAR(1),dataprod DATE,datasert DATE,shetold VARCHAR(50),prichotk INT,price VARCHAR(50),priceks VARCHAR(50),y VARCHAR(4),m VARCHAR(2),d VARCHAR(2))");
company_query('DROP TABLE IF EXISTS produkti');
company_query('CREATE TABLE produkti(id INT,parent INT)');
company_query('DROP TABLE IF EXISTS prichotk');
company_query('CREATE TABLE prichotk(id INT,value VARCHAR(50),col VARCHAR(50))');
company_query("INSERT INTO produkti VALUES(1,12),(2,10)");
company_query("INSERT INTO prichotk VALUES(1,'reason','#aaa'),(2,'empty','#bbb')");
company_query("INSERT INTO schet(id,rand,ns,produkt,`del`,cher,otk,tipprod,prodlens,prodlenks,dataprod,datasert,shetold,prichotk,price,priceks,y,m,d) VALUES
 (1,'both','same',1,'0','0','0','Сер/Пос','0','0','2027-01-15','2026-12-31','','0','100','30','2026','12','31'),
 (2,'partial','same',1,'0','0','0','Сер/Пос','0','1','2027-01-15','2026-12-31','older','0','200','40','2026','12','31'),
 (3,'done','done',1,'0','0','0','Сер/Пос','1','1','2027-01-15','2026-12-31','','0','300','50','2026','12','31'),
 (4,'refusal','r',1,'0','1','0','Поставка','0','0','2027-01-15','2026-12-31','old','1','150','50','2026','12','31'),
 (5,'refusal','r',1,'0','1','0','Поставка','0','0','2027-01-15','2026-12-31','old','1','150','50','2026','12','31'),
 (6,'refusal-other','r2',2,'0','1','0','Поставка','0','0','2027-01-15','2026-12-31','old','1','900','90','2026','12','31'),
 (7,'deleted','r3',1,'1','1','0','Поставка','0','0','2027-01-15','2026-12-31','old','1','900','90','2026','12','31')");
$single = array('tipprod'=>'Поставка','prodlens'=>'1','prodlenks'=>'0','dataprod'=>'2027-01-10','datasert'=>'2026-12-25');
company_test(!company_pending_parts($single,'2026-12-20','2027-02-10'), 'Either flag closes single supply');
$where=company_pending_renewals_sql('2026-12-20','2027-02-10');
$rows=company_query('SELECT rand FROM schet WHERE '.$where.' GROUP BY rand');
company_test(mysql_num_rows($rows)===1,'Pending SQL includes partially or fully renewed invoice');
$allWhere=company_open_renewals_sql('2026-12-20','2027-02-10');
$allRows=company_query('SELECT rand FROM schet WHERE '.$allWhere.' GROUP BY rand');
company_test(mysql_num_rows($allRows)===2,'Open + pending union duplicates or loses invoices');
$stats=company_query(company_refusal_statistics_sql('12','2','2026-12-01','2026-12-31'));
$reason=mysql_fetch_assoc($stats);
company_test((int)$reason['coun']===1 && (float)$reason['kolsum']===150.0 && (float)$reason['kolsumka']===50.0, 'Multi-line / deleted / other org refusal totals');
$empty=mysql_fetch_assoc($stats);
company_test((int)$empty['coun']===0 && (float)$empty['kolsum']===0.0,'Empty category');
$all=mysql_fetch_assoc(company_query(company_refusal_statistics_sql('0','2','2026-12-01','2026-12-31')));
company_test((int)$all['coun']===2 && (float)$all['kolsum']===1050.0,'All organizations totals');
echo "Company invoices: exact periods, cross-year and partial renewals, invoice-level refusal sums — OK\n";

// The source must disappear as soon as a real next bill or refusal exists.
function company_pending_ids() {
    $where=company_pending_renewals_sql('2026-12-20','2027-02-10');
    $query=company_query('SELECT rand FROM schet WHERE '.$where.' GROUP BY rand ORDER BY rand');
    $ids=array(); while ($row=mysql_fetch_assoc($query)) $ids[]=$row['rand'];
    return $ids;
}
foreach (array('Поставка','Сертификат','Сер/Пос') as $type) {
    foreach (array(array('0','0'),array('1','0'),array('0','1'),array('1','1')) as $flags) {
        company_query("UPDATE schet SET tipprod='".$type."',prodlens='".$flags[0]."',prodlenks='".$flags[1]."' WHERE rand='both'");
        $expected=$flags===array('0','0') ? array('both') : array();
        company_test(company_pending_ids()===$expected, 'Either-flag SQL rule: '.$type.' '.implode('/',$flags));
        $display=array('tipprod'=>$type,'dataprod'=>'2027-01-15','datasert'=>'2026-12-31','prodlens'=>$flags[0],'prodlenks'=>$flags[1]);
        company_test((bool)company_pending_parts($display,'2026-12-20','2027-02-10')===(bool)$expected,
            'Either-flag display rule: '.$type.' '.implode('/',$flags));
    }
}
company_query("UPDATE schet SET tipprod='Сер/Пос',prodlens='0',prodlenks='0' WHERE rand='both'");
company_query("UPDATE schet SET ns=rand");
$insertNext = "INSERT INTO schet(id,rand,ns,idkli,produkt,`del`,cher,otk,tipprod,shetold,y,m,d) VALUES ";
company_query($insertNext."(10,'next','next','client',1,'0','0','0','','both','2028','01','01')");
company_test(company_pending_ids() === array(), 'Issued unpaid/future bill did not exclude source');
company_query("UPDATE schet SET cher='1',prichotk=1 WHERE rand='next'");
company_test(company_pending_ids() === array(), 'Refusal clone did not exclude source');
company_query("UPDATE schet SET `del`='1' WHERE rand='next'");
company_test(company_pending_ids() === array('both'), 'Deleted next bill still excludes source');
company_query("UPDATE schet SET `del`='0',otk='1',cher='0' WHERE rand='next'");
company_test(company_pending_ids() === array('both'), 'Draft next bill excludes source');
company_query("UPDATE schet SET otk='0',idkli='different-client' WHERE rand='next'");
company_test(company_pending_ids() === array('both'), 'Other client with same number excludes source');
company_query("UPDATE schet SET dateotkaz='2026-12-21' WHERE rand='both'");
company_test(company_pending_ids() === array(), 'Recorded refusal on source not excluded');
echo "Company renewal eligibility: issued bills, refusals, deleted/draft bills and client identity — OK\n";
