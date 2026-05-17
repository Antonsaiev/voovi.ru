<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>Результат загрузки файла</title>
  	<meta http-equiv="content-type" content="text/html" charset="utf-8" />
	<meta http-equiv="Content-Style-Type" content="text/css" />
	<meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="../css/bootstrap.min.css" rel="stylesheet">
	<link rel="shortcut icon" href="/favicon.ico">
</head>
<body>
<?php
error_reporting(E_ALL); // Выключаем показ ошибок. Чтобы их видеть - вместо 0 поставьте E_ALL
include('../conf.php');
$max_file_size = 9; // Максимальный размер файла в МегаБайтах
if($_POST['update']=='OK'){
    // СТАРТ Загрузка файла на сервер
    if($_FILES["filename"]["size"] > $max_file_size*1024*1024){
        echo 'Размер файла превышает '.$max_file_size.' Мб!';
        include('form_file_load.php');
        exit;
    }
    if(copy($_FILES["filename"]["tmp_name"],$path.$_FILES["filename"]["name"])){
        echo("Файл "."<b>".$_FILES["filename"]["name"]."</b>"." успешно загружен!<br>");
    }
    else{
        echo 'Ошибка загрузки файла<br>';
        include('form_file_load.php');
        exit;
    }

    //СТАРТ Считывание из файла Excel и запись в БД
    require_once "Excel/reader.php";
    $data = new Spreadsheet_Excel_Reader();
    $data->setOutputEncoding("UTF8"); //Кодировка выходных данных
    $data->read($_FILES["filename"]["name"]);
    echo '<table class="table">';
    for ($i=1; $i<=$data->sheets[0]["numRows"]; $i++){
        $cell1 = addslashes(trim($data->sheets[0]["cells"][$i][1]));
        $cell2 = addslashes(trim($data->sheets[0]["cells"][$i][2]));
        $cell3 = addslashes(trim($data->sheets[0]["cells"][$i][3]));
        $cell4 = addslashes(trim($data->sheets[0]["cells"][$i][4]));
        $cell5 = addslashes(trim($data->sheets[0]["cells"][$i][5]));
        $cell6 = addslashes(trim($data->sheets[0]["cells"][$i][6]));
		$cell7 = addslashes(trim($data->sheets[0]["cells"][$i][7]));
		$cell8 = addslashes(trim($data->sheets[0]["cells"][$i][8]));
		$cell9 = addslashes(trim($data->sheets[0]["cells"][$i][9]));
		$cell10 = addslashes(trim($data->sheets[0]["cells"][$i][10]));
		$cell11 = addslashes(trim($data->sheets[0]["cells"][$i][11]));
		$cell12 = addslashes(trim($data->sheets[0]["cells"][$i][12]));
		$cell13 = addslashes(trim($data->sheets[0]["cells"][$i][13]));
		$cell14 = addslashes(trim($data->sheets[0]["cells"][$i][14]));
		$cell15 = addslashes(trim($data->sheets[0]["cells"][$i][15]));
		$cell16 = addslashes(trim($data->sheets[0]["cells"][$i][16]));
		$cell17 = addslashes(trim($data->sheets[0]["cells"][$i][17]));
		$cell18 = addslashes(trim($data->sheets[0]["cells"][$i][18]));
        
        /*$ins="INSERT INTO `mail_to`
		(`naim`, `inn`, `kpp`, `email`, `e_mail`, `picmo`, `dat_fns`, `fns`, `statuc`, `dat_sav`, `rand`)
		VALUES 
		('$cell1','$cell2','$cell3','$cell4','$cell5','$cell6','$cell7','$cell8','$cell9','$cell10','".rand(100000000000, 999999999999).rand(100, 999)."')";
        $query = mysql_query($ins);*/
$lgenerac = "SELECT * FROM `schet` WHERE del = '0' AND nomerschetks LIKE '%".$cell4."%' GROUP BY rand";
$rgenerac = mysql_query($lgenerac);
$pgenerac = mysql_fetch_array($rgenerac);
		echo '<tr ';
		if($pgenerac['akt'] == 0){
			echo ' class="alert alert-danger" role="alert"';
		}else{
			echo ' class="alert alert-success" role="alert"';
		}
		echo '>';
		echo '<td>'.$cell1.'</td>';
		echo '<td>'.$cell2.'</td>';
		echo '<td>'.$cell3.'</td>';
		echo '<td ';
		if(!empty($pgenerac['akt_date'])){
			if($pgenerac['akt_date'] != $_GET['date']){
			}
		}
		if($pgenerac['akt_date'] == $_GET['date']){
			echo ' class="alert alert-success" role="alert"';
		}
		
		if(!empty($pgenerac['akt_date'])){
			$mes = date('ym') - $pgenerac['akt_date'];
			if($mes == 1){
				echo ' class="alert alert-success" role="alert"';
				//$koment = "UPDATE schet SET `ksisset`='1' WHERE nomerschetks LIKE '%".$cell4."%'";
				//mysql_query($koment) or die(mysql_error($link));
			}if($mes == 2){
				echo ' class="alert alert-info" role="alert"';
				//$koment = "UPDATE schet SET `ksisset`='3' WHERE nomerschetks LIKE '%".$cell4."%'";
				//mysql_query($koment) or die(mysql_error($link));
			}if($mes == 0){
				echo ' class="alert alert-warning" role="alert"';
				//$koment = "UPDATE schet SET `ksisset`='2' WHERE nomerschetks LIKE '%".$cell4."%'";
				//mysql_query($koment) or die(mysql_error($link));
			}
		}
		
		echo '>';
		if($pgenerac['akt'] == 0){
			echo 'NO';
		}
		if(!empty($pgenerac['akt_date'])){
			$mes = date('ym') - $pgenerac['akt_date'];
			if($mes == 1){
				echo 'ОК';
				//$koment = "UPDATE schet SET `ksisset`='1' WHERE nomerschetks LIKE '%".$cell4."%'";
				//mysql_query($koment) or die(mysql_error($link));
			}if($mes == 2){
				echo 'OLD';
				//$koment = "UPDATE schet SET `ksisset`='3' WHERE nomerschetks LIKE '%".$cell4."%'";
				//mysql_query($koment) or die(mysql_error($link));
			}if($mes == 0){
				echo 'NEXT';
				//$koment = "UPDATE schet SET `ksisset`='2' WHERE nomerschetks LIKE '%".$cell4."%'";
				//mysql_query($koment) or die(mysql_error($link));
			}
		}
		echo '</td>';
		$qdsafesd = mysql_query("SELECT COUNT(*) FROM schet WHERE del = '0' AND nomerschetks LIKE '%".$cell4."%' GROUP BY rand");
		$pedfsbfedb = mysql_result($qdsafesd, 0);
		echo '<td ';
		if($pedfsbfedb <= 0){
			echo ' class="alert alert-danger" role="alert"';
		}else{
			echo ' class="alert alert-success" role="alert"';
		}
		echo '>';
		if($pedfsbfedb <= 0){
			echo 'Счета нет';
		}else{
			echo 'Счет есть';
		}
		echo '</td>';
		echo '<td>'.$pgenerac['akt_date'].'</td>';
		echo '<td>'.$cell4.'</td>';
		echo '<td>'.$cell5.'</td>';
		echo '<td>'.$cell6.'</td>';
		echo '<td>'.$cell7.'</td>';
		echo '<td>'.$cell8.'</td>';
		echo '<td>'.$cell9.'</td>';
		echo '<td>'.$cell10.'</td>';
		echo '<td>'.$cell11.'</td>';
		echo '<td>'.$cell12.'</td>';
		echo '<td>'.$cell13.'</td>';
		echo '<td>'.$cell14.'</td>';
		echo '<td>'.$cell15.'</td>';
		echo '<td>'.$cell16.'</td>';
		echo '<td>'.$cell17.'</td>';
		echo '<td>'.$cell18.'</td>';
		$koment = "UPDATE schet SET `priceksisset`='".$cell18."' WHERE nomerschetks LIKE '%".$cell4."%'";
		mysql_query($koment) or die(mysql_error($link));

		echo '</tr>';
    } 
	echo '</table>';
}
else{
    include('form_file_load.php');
	error_reporting(E_ALL);
}
?>
</body>
</html>