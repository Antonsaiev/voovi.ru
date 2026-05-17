<?
define ('DB_HOST', 'localhost');
define ('DB_LOGIN', 'shoes');
define ('DB_PASSWORD', 'ShoesOpt');
define ('DB_NAME', 'shoes');
$link =mysql_connect(DB_HOST, DB_LOGIN, DB_PASSWORD) or die ("MySQL Error: " . mysql_error());
mysql_query("set names utf8") or die ("<br>Invalid query: " . mysql_error());
mysql_select_db(DB_NAME) or die ("<br>Invalid query: " . mysql_error());
$id=$_POST['id_t'];
$del=$_POST['id_del'];
$ar=$_POST['id_ar'];
$valuename=$_POST['id_values'];
$edit=$_POST['id_edit'];
$value=$_POST['id_value'];
$cop=$_POST['id_cop'];
$exel=$_POST['id_excel'];
$mov= $_POST['id_mov'];
$num= $_POST['id_num'];
$vid= $_POST['id_vid'];
$pol=$_POST['id_pol'];
$art=$_POST['id_art'];
$razm=$_POST['id_razm'];
$count=$_POST['id_count'];
$brend=$_POST['id_brend'];
$mat_verh=$_POST['id_mat_verh'];
$mat_pod=$_POST['id_mat_pod'];
$mat_niz=$_POST['id_mat_niz'];
$tnved=$_POST['id_tnved'];
$color=$_POST['id_color'];
$contry=$_POST['id_contry'];
$idkm=$_POST['id_idkm'];
$dobkm=$_POST['id_dobkm'];
if($ar=='1')
{
session_start();
$_SESSION['del']=$del;
}
else
{
	session_start();
	$_SESSION['del']='0';
}
$r = mysql_query("SELECT count(number) FROM product"); $res = mysql_fetch_array($r) ;

/*$r = mysql_query("SELECT * FROM kind_of_shoes where name='$vid'");
 while($res = mysql_fetch_assoc($r))
 {
    $lico = "UPDATE `product`SET `kind`='". $res['code']."' where `id`=8";
    mysql_query($lico) or die(mysql_error($links));
 }
*/
if ($edit=='1') {
    $clientdata = mysql_fetch_assoc(mysql_query("SELECT * FROM product WHERE id = '$id' LIMIT 1"));
    if ($clientdata['number'] !== $num) {
        $sql = mysql_query("UPDATE `product` SET `number`='$num'  WHERE id = '$id'");
    }

    if ($clientdata['kind'] !== $vid) {
        $sql = mysql_query("UPDATE `product` SET `kind`='$vid'  WHERE id = '$id'");
    }
    if ($clientdata['pol'] !== $pol) {
        $sql = mysql_query("UPDATE `product` SET `pol`='$pol'  WHERE id = '$id'");
    }
    if ($clientdata['art'] !== $art) {
        $sql = mysql_query("UPDATE `product` SET `art`='$art'  WHERE id = '$id'");
    }
    if ($clientdata['size'] !== $razm) {
        $sql = mysql_query("UPDATE `product` SET `size`='$razm'  WHERE id = '$id'");
    }
    if ($clientdata['brand'] !== $brend) {
        $sql = mysql_query("UPDATE `product` SET `brand`='$brend'  WHERE id = '$id'");
    }
    if ($clientdata['upper_material'] !== $mat_verh) {
        $sql = mysql_query("UPDATE `product` SET `upper_material`='$mat_verh'  WHERE id = '$id'");
    }
    if ($clientdata['lining_material'] !== $mat_pod) {
        $sql = mysql_query("UPDATE `product` SET `lining_material`='$mat_pod'  WHERE id = '$id'");
    }
    if ($clientdata['bottom_material'] !== $mat_niz) {
        $sql = mysql_query("UPDATE `product` SET `bottom_material`='$mat_niz'  WHERE id = '$id'");
    }
    if ($clientdata['color'] !== $color) {
        $sql = mysql_query("UPDATE `product` SET `color`='$color'  WHERE id = '$id'");
    }
    if ($clientdata['tnved'] !== $tnved) {
        $sql = mysql_query("UPDATE `product` SET `tnved`='$tnved'  WHERE id = '$id'");
    }
    if ($clientdata['country'] !== $contry) {
        $sql = mysql_query("UPDATE `product` SET `country`='$contry'  WHERE id = '$id'");
    }
    if ($clientdata['count'] !== $count) {
        $sql = mysql_query("UPDATE `product` SET `count`='$count'  WHERE id = '$id'");
    }
}
if ($mov=='1')
{
	 $data_schet = date("d.m.Y");
    $num=$res[0];
    $mov=$num+1;
    $lico = "insert into `product`(number,publication_date) values ('$mov','$data_schet')";
    mysql_query($lico) or die(mysql_error($links));
}
if ($del=='1')
{
    $clientdata = mysql_fetch_assoc(mysql_query("SELECT * FROM product WHERE id = '$id' LIMIT 1"));
    if($clientdata['del']!==$del)
    {
        $sql=mysql_query("UPDATE `product` SET `del`='$del'  WHERE id = '$id'");
    }
}
if ($cop=='1')
{
    $lico = "CREATE TEMPORARY TABLE foo AS SELECT * FROM product WHERE id = '$id'";
    $lico1 = "UPDATE foo SET id=NULL";
    $lico2 = "INSERT INTO product SELECT * FROM foo";
    $lico3 = "DROP TABLE foo";

    mysql_query($lico) or die(mysql_error($links));
    mysql_query($lico1) or die(mysql_error($links));
    mysql_query($lico2) or die(mysql_error($links));
    mysql_query($lico3) or die(mysql_error($links));
}
if ($dobkm=='1')
{
	$licos = "insert into `km`(code_km) values ('$idkm')";
    mysql_query($licos) or die(mysql_error($links));
}
$uploads_dir = 'opis/upload';
   if ( 0 < $_FILES['file']['error'] ) {
        echo 'Error: ' . $_FILES['file']['error'] . '<br>';
    }
    else {
        move_uploaded_file($_FILES['file']['tmp_name'],$_FILES['file']['name']);
    }
$loadfile = $_FILES['file']['name']; // получаем имя загруженного файла
require_once $_SERVER['DOCUMENT_ROOT']."/Classes/PHPExcel/IOFactory.php"; // подключаем класс для доступа к файлу
$objPHPExcel = PHPExcel_IOFactory::load($_SERVER['DOCUMENT_ROOT']."/opis/".$loadfile);

$objPHPExcel->setActiveSheetIndex(0);
$aSheet = $objPHPExcel->getActiveSheet();
  $highestRow = $aSheet->getHighestRow(); // получаем количество строк
  $highestColumn = $aSheet->getHighestColumn();
   for ($row = 7; $row <= $highestRow; ++ $row) // обходим все строки
  {
    $cell1 = $aSheet->getCellByColumnAndRow(0, $row );
	$cell2 = $aSheet->getCellByColumnAndRow(9, $row);
    $cell3 = $aSheet->getCellByColumnAndRow(13, $row);	//артикул
	$cell4 = $aSheet->getCellByColumnAndRow(3, $row);
	$cell5 = $aSheet->getCellByColumnAndRow(14, $row);
	$cell6= $aSheet->getCellByColumnAndRow(6, $row);
	$cell7 = $aSheet->getCellByColumnAndRow(10, $row);
	$cell8 = $aSheet->getCellByColumnAndRow(11, $row);
	$cell9 = $aSheet->getCellByColumnAndRow(12, $row);
	$cell10 = $aSheet->getCellByColumnAndRow(15, $row);
	$cell11 = $aSheet->getCellByColumnAndRow(8, $row);
	$cell12 = $aSheet->getCellByColumnAndRow(4, $row);
	
// читаем значение ячейки
   $value1 = $cell1->getValue();
   $value2 = $cell2->getValue();
   $res=substr($value2, 0, 11); 
   $value3 = $cell3->getValue();
   $value4 = $cell4->getValue();
   $value5 = $cell5->getValue();
   $value6 = $cell6->getValue();
   $res2=str_replace('"',"",$value6);
   $value7 = $cell7->getValue();
   $value8 = $cell8->getValue();
   $value9 = $cell9->getValue();
   $value10 = $cell10->getValue();
   $str=strpos($value10, ">");
   $res3=substr($value10, 0, $str+1);
   $value11 = $cell11->getValue();
   $value12 = $cell12->getValue();
   $res4=substr($value11, 0, 4); 
	$res1=substr($value5, 0, 11); 
	$count++;
    $sql = "INSERT INTO `product` (number,kind,art,publication_date,color,size,brand,upper_material,lining_material,bottom_material,count,tnved,country) VALUES('$count','$res','$value4','$value12','$value3','$res1','$res2','$value7','$value8','$value9','$value1','$res3','$res4')";
    $query = mysql_query($sql) or die('Ошибка чтения записи: '.mysql_error());
  }
mysql_close($link);



?>