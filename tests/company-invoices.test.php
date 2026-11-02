<?php
date_default_timezone_set('Europe/Moscow');
require dirname(__DIR__).'/company_invoice_filters.php';
function company_test($condition, $message) { if (!$condition) throw new RuntimeException($message); }
company_test(company_invoice_period('', '') === array(date('Y-m-01'), date('Y-m-t')), 'Missing dates');
company_test(company_invoice_period('2026-02-30','2026-02-31') === array(date('Y-m-01'),date('Y-m-t')), 'Invalid calendar date');
$row = array('dataprod'=>'2027-01-15','datasert'=>'2026-12-31','prodlens'=>'0','prodlenks'=>'0');
company_test(count(company_pending_parts($row,'2026-12-20','2027-02-10')) === 2, 'Cross-year/middle month');
$row['prodlenks']='1';
company_test(company_pending_parts($row,'2026-12-20','2027-02-10') === array('Поставка'=>'2027-01-15'), 'Partial renewal');
company_test(!company_pending_parts($row,'2027-01-16','2027-02-01'), 'Exact day boundaries');
$db = getenv('DB_NAME');
company_test(preg_match('/^voovi_schet_identity_test(?:_[a-z0-9_]+)?$/D', $db), 'Scratch DB only');
mysql_connect(getenv('DB_HOST'), getenv('DB_LOGIN'), getenv('DB_PASSWORD'));
mysql_select_db($db);
mysql_query('SET NAMES utf8');
function company_query($sql) { $result=mysql_query($sql); company_test($result !== false,mysql_error()); return $result; }
// Temporary fixture tables shadow only this connection's scratch tables.
company_query("CREATE TEMPORARY TABLE schet(id INT,akt CHAR(1) DEFAULT '0',rand VARCHAR(50),ns VARCHAR(50),produkt INT,`del` CHAR(1),cher CHAR(1),otk CHAR(1),tipprod VARCHAR(50),prodlens CHAR(1),prodlenks CHAR(1),dataprod DATE,datasert DATE,shetold VARCHAR(50),prichotk INT,price VARCHAR(50),priceks VARCHAR(50),y VARCHAR(4),m VARCHAR(2),d VARCHAR(2))");
company_query('CREATE TEMPORARY TABLE produkti(id INT,parent INT)');
company_query('CREATE TEMPORARY TABLE prichotk(id INT,value VARCHAR(50),col VARCHAR(50))');
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
$where=company_pending_renewals_sql('2026-12-20','2027-02-10');
$rows=company_query('SELECT rand FROM schet WHERE '.$where.' GROUP BY rand');
company_test(mysql_num_rows($rows)===2,'Pending SQL excludes partial or counts closed invoice');
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
