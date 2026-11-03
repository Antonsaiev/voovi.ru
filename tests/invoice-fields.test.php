<?php
date_default_timezone_set('Europe/Moscow');
mysql_connect(getenv('DB_HOST'),getenv('DB_LOGIN'),getenv('DB_PASSWORD')) or die('No DB');
if (!mysql_select_db('voovi_invoice_manager_test')) die('Isolated test DB required');
mysql_query('SET NAMES utf8');
mysql_query('DROP TABLE IF EXISTS schet');
mysql_query('DROP TABLE IF EXISTS klient_ogrn');
mysql_query('CREATE TABLE schet (id INT PRIMARY KEY, rand VARCHAR(40), idkli INT, vladelec VARCHAR(40), fadress VARCHAR(1366), del INT DEFAULT 0) ENGINE=InnoDB');
mysql_query('CREATE TABLE klient_ogrn (klient INT, idkli INT) ENGINE=InnoDB');
mysql_query("INSERT INTO schet VALUES (1,'111',5,'0','old',0),(2,'111',5,'0','old',0)");
mysql_query('INSERT INTO klient_ogrn VALUES (12,5),(13,6)');
require dirname(__DIR__).'/invoice_fields_storage.php';
function field_check($condition,$message) { if (!$condition) throw new RuntimeException($message); }
voovi_invoice_field_save('111','address','first',array('old'));
voovi_invoice_field_save('111','address','final',array('old','first'));
try { voovi_invoice_field_save('111','address','first',array('old')); throw new RuntimeException('Old save overwrote final'); } catch(VooviSchetException $e) {}
field_check(mysql_result(mysql_query("SELECT COUNT(*) FROM schet WHERE fadress='final'"),0)==2,'Save all rows');
voovi_invoice_field_save('111','owner','12',array('0'));
try { voovi_invoice_field_save('111','owner','13',array('12'));throw new RuntimeException('Foreign owner accepted'); } catch(VooviSchetException $e) {}
voovi_invoice_field_save('111','address','',array('final'));
field_check(mysql_result(mysql_query("SELECT COUNT(*) FROM schet WHERE fadress='' AND vladelec='12'"),0)==2,'Clear address without changing owner');
voovi_invoice_field_save('111','address','',array('final'));
echo "Invoice fields: all rows, owner relation, stale writes, closing request, clearing, retry — OK\n";
