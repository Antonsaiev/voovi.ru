<?
include 'phpqrcode/qrlib.php';

$qr=$_GET['naimenovanie'];
$nch=$_GET['nch'];
$chkor=$_GET['chkor'];
$bik=$_GET['bik'];
$bank=$_GET['bank'];
$innpolu=$_GET['innpolu'];
$ns=$_GET['ns'];
$uridadress=$_GET['uridadress'];
$price=$_GET['price'];

QRcode::png('ST00012|Name='.$qr.'|PersonalAcc='.$nch.'|BankName='.$bank.'|BIC='.$bik.'|CorrespAcc='.$chkor.'|LastName=.|FirstName=.|MiddleName=.|Purpose=Счет-оферта'.$ns.'|PayeeINN='.$innpolu.'|PayerAddress='.$uridadress.'|Sum=',false,'H',2,1);
/*QRcode::png('ST00012|
Name='.$qr.'|
PersonalAcc='.$nch.'|
BankName='.$bank.'|
BIC='.$bik.'|
CorrespAcc='.$chkor.'|
Sum='.$price.'|
Purpose=Счет-оферта'.$ns.'|
PayeeINN='.$innpolu.'|
LastName=.|
FirstName=.|
MiddleName=.|
PayerAddress='.$uridadress.'
',false,'H',2,1);*/
?>
