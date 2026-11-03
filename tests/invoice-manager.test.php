<?php
date_default_timezone_set('Europe/Moscow');
// Отдельная локальная база: рабочие начисления и счета не изменяются.
mysql_connect(getenv('DB_HOST'), getenv('DB_LOGIN'), getenv('DB_PASSWORD')) or die('No DB');
if (!mysql_select_db('voovi_invoice_manager_test')) die('Create isolated test database first');
mysql_query('SET NAMES utf8');
foreach (array('schet','users','bills','bills_voovi_links','aktivn') as $table) mysql_query('DROP TABLE IF EXISTS '.$table);
mysql_query('CREATE TABLE users (users_id INT PRIMARY KEY, f_name VARCHAR(100), l_name VARCHAR(100), o_name VARCHAR(100), del_users INT DEFAULT 0, uvl INT DEFAULT 0, tip INT DEFAULT 0) ENGINE=InnoDB');
mysql_query('CREATE TABLE schet (id INT PRIMARY KEY, rand VARCHAR(40), ns VARCHAR(40), kto INT, del INT DEFAULT 0) ENGINE=InnoDB');
mysql_query('CREATE TABLE bills (BillId VARCHAR(40) PRIMARY KEY, created_by INT NULL, status VARCHAR(20)) ENGINE=InnoDB');
mysql_query('CREATE TABLE bills_voovi_links (id INT PRIMARY KEY, schet_rand VARCHAR(40), BillId VARCHAR(40)) ENGINE=InnoDB');
mysql_query('CREATE TABLE aktivn (data VARCHAR(40), deistvie TEXT, users INT) ENGINE=InnoDB');
mysql_query("INSERT INTO users (users_id,f_name,l_name,o_name) VALUES (3,'Антон','Саиев',''),(4,'Алексей','Никитенко','')");
mysql_query("INSERT INTO schet VALUES (1,'111','26A1',3,0),(2,'111','26A1',3,0),(3,'222','26A2',3,0)");
mysql_query("INSERT INTO bills VALUES ('bill-one',3,'created')");
mysql_query("INSERT INTO bills_voovi_links VALUES (1,'111','bill-one'),(2,'222','bill-one')");
require dirname(__DIR__).'/invoice_manager.php';
function check_manager($condition,$message) { if (!$condition) throw new RuntimeException($message); }
$admin=array('users_id'=>3,'adm'=>'1');
check_manager(!voovi_invoice_manager_allowed(array('adm'=>0,'otvetstven'=>1)), 'Ответственный не должен получить права руководителя');
$state=voovi_invoice_manager_state('111');
check_manager(count($state['related'])===1, 'Должен быть показан второй связанный счёт');
try { voovi_invoice_manager_change('111',4,array('users_id'=>3,'adm'=>0),$state['version']); throw new RuntimeException('Нет проверки прав'); } catch (VooviSchetException $e) {}
try { voovi_invoice_manager_change('111',4,$admin,$state['version']); throw new RuntimeException('Общий документ требует подтверждения'); } catch (VooviSchetException $e) {}
voovi_invoice_manager_change('111',4,$admin,$state['version'],true);
check_manager(mysql_result(mysql_query("SELECT COUNT(*) FROM schet WHERE rand='111' AND kto=4 AND ns='26A1'"),0)==2,'Менеджер меняется во всех строках, номер сохраняется');
check_manager(mysql_result(mysql_query("SELECT created_by FROM bills WHERE BillId='bill-one'"),0)==4,'Локальный менеджер Контура не обновлён');
check_manager(mysql_result(mysql_query("SELECT kto FROM schet WHERE rand='222'"),0)==3,'Другой счет Voovi не должен изменяться');
voovi_invoice_manager_change('111',4,$admin,$state['version'],true);
check_manager(mysql_result(mysql_query('SELECT COUNT(*) FROM aktivn'),0)==1,'Повторное подтверждение не должно дублировать запись');
try { voovi_invoice_manager_change('111',3,$admin,$state['version'],true); throw new RuntimeException('Нет защиты от устаревшей формы'); } catch (VooviSchetException $e) {}
mysql_query("UPDATE bills SET created_by=3");
$state=voovi_invoice_manager_state('222');
mysql_query("CREATE TRIGGER reject_change BEFORE INSERT ON aktivn FOR EACH ROW SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT='Test log failure'") or die(mysql_error());
try { voovi_invoice_manager_change('222',4,$admin,$state['version'],true); throw new RuntimeException('Ожидалась ошибка журнала'); } catch (VooviSchetException $e) {}
check_manager(mysql_result(mysql_query("SELECT kto FROM schet WHERE rand='222'"),0)==3,'Ошибка журнала должна откатить смену менеджера');
check_manager(mysql_result(mysql_query("SELECT created_by FROM bills WHERE BillId='bill-one'"),0)==3,'Ошибка журнала должна откатить менеджера Контура');
echo "Invoice manager: role, shared links, rows, stable number, retry, stale form, rollback — OK\n";
