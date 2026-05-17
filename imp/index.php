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
$rand = date('YmdHis');
echo $rand;
error_reporting(0); // Выключаем показ ошибок. Чтобы их видеть - вместо 0 поставьте E_ALL
include('db_conn.php');
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
    $data->setOutputEncoding("UTF8");
    $data->read($_FILES["filename"]["name"]);
    echo '<table class="table table-bordered table-striped">';
		$asd .='<thead>';
			$asd .='<tr>';
				$asd .='<td>№</td>';
				for ($i=1; $i<=15; $i++){
				$asd .= '<td><select id="tdh'.$i.'" class="form-control xs" style="font-size: 11px;padding: 2px;margin: 0;height: 22px;">
					<option value="1"';if($i == 1){$asd .= 'selected';}$asd .='>ВАЖНЫЙ - 1 иначе 0</option>
					<option value="2"';if($i == 2){$asd .= 'selected';}$asd .='>ПРОДЛЕНИЕ 0 . НОВЫЙ 1</option>
					<option value="3"';if($i == 3){$asd .= 'selected';}$asd .='>Наименование организации</option>
					<option value="4"';if($i == 4){$asd .= 'selected';}$asd .='>ИНН</option>
					<option value="5"';if($i == 5){$asd .= 'selected';}$asd .='>КПП</option>
					<option value="6"';if($i == 6){$asd .= 'selected';}$asd .='>Доп. Продажи</option>
					<option value="7"';if($i == 7){$asd .= 'selected';}$asd .='>Менеджер</option>
					<option value="8"';if($i == 8){$asd .= 'selected';}$asd .='>Продукт</option>
					<option value="9"';if($i == 9){$asd .= 'selected';}$asd .='>Тариф</option>
					<option value="10"';if($i == 10){$asd .= 'selected';}$asd .='>Получен из\Тип продления</option>
					<option value="11"';if($i == 11){$asd .= 'selected';}$asd .='>Окончание поставки</option>
					<option value="12"';if($i == 12){$asd .= 'selected';}$asd .='>Внимание</option>
					<option value="13"';if($i == 13){$asd .= 'selected';}$asd .='>Комментарий 1 \ из пакета услуг</option>
					<option value="14"';if($i == 14){$asd .= 'selected';}$asd .='>Комментарий 2 \ из клиента</option>
					<option value="15"';if($i == 15){$asd .= 'selected';}$asd .='>Акция для клиента</option>
					</select>
				</td>';
				}
			$asd .='</tr>';
		$asd .='</thead>';	
		echo $asd;
	$ii = 1;
		for ($i=2; $i<=$data->sheets[0]["numRows"]; $i++){
			$cell1 = addslashes(trim($data->sheets[0]["cells"][$i][1])); // ВАЖНЫЙ - 1 иначе 0
			$cell2 = addslashes(trim($data->sheets[0]["cells"][$i][2])); // ПРОДЛЕНИЕ 0 . НОВЫЙ 1
			$cell8 = addslashes(trim($data->sheets[0]["cells"][$i][3])); // Наименование организации
			$cell9 = addslashes(trim($data->sheets[0]["cells"][$i][4])); // ИНН
			$cell10 = addslashes(trim($data->sheets[0]["cells"][$i][5])); // КПП
			$cell11 = addslashes(trim($data->sheets[0]["cells"][$i][6])); // Доп. Продажи
			$cell3 = addslashes(trim($data->sheets[0]["cells"][$i][7])); // Менеджер
			$cell4 = addslashes(trim($data->sheets[0]["cells"][$i][8])); // Продукт
			$cell5 = addslashes(trim($data->sheets[0]["cells"][$i][9])); // Тариф
			$cell6 = addslashes(trim($data->sheets[0]["cells"][$i][10])); // Получен из\Тип продления
			$cell7 = addslashes(trim($data->sheets[0]["cells"][$i][11])); // Окончание поставки
			$cell12 = addslashes(trim($data->sheets[0]["cells"][$i][12])); // Внимание
			$cell13 = addslashes(trim($data->sheets[0]["cells"][$i][13])); // Комментарий 1 \ из пакета услуг
			$cell14 = addslashes(trim($data->sheets[0]["cells"][$i][14])); // Комментарий 2 \ из клиента
			$cell15 = addslashes(trim($data->sheets[0]["cells"][$i][15])); // Акция для клиента
			echo'<tr>';
			echo'<td>'.$ii++.'</td>';
			echo'<td>'.$cell1.'</td>';
			echo'<td>'.$cell2.'</td>';
			echo'<td>'.$cell8.'</td>';
			echo'<td>';
			if(strlen($cell9) != 10 && strlen($cell9) != 12){
				$inn = '0'.$cell9;
			}else{
				$inn = $cell9;
			}
			echo $inn;
			echo '</td>';
			echo'<td>';
			if(strlen($inn) == 10){
				if(strlen($cell10) != 9){
					$kpp = '0'.$cell10;
				}else{
					$kpp = $cell10;
				}
			}else{
					$kpp = "";
			}
			echo $kpp;
			echo '</td>';
			echo'<td>'.$cell11.'</td>';
			echo'<td>'.$cell3.'</td>';
			echo'<td>'.$cell4.'</td>';
			echo'<td>'.$cell5.'</td>';
			echo'<td>'.$cell6.'</td>';
			echo'<td>'.substr($cell7, 0, 2).'.'.substr($cell7, 3, 2).'.'.substr($cell7, 6, 4).'</td>';
			echo'<td>'.$cell12.'</td>';
			echo'<td>'.$cell13.'</td>';
			echo'<td>'.$cell14.'</td>';
			echo'<td>'.$cell15.'</td>';
			echo'</tr>';
			
			  $ins="INSERT INTO `call_center`
				 (`rand`, `date`, `1`, `2`, `3`, `4`, `5`, `6`, `7`, `8`, `9`, `10`, `11`, `12`, `13`, `14`, `15`) 
			 VALUES
				 ('".$rand."','".substr($cell7, 6, 4).substr($cell7, 3, 2).substr($cell7, 0, 2)."','$cell1','$cell2','$cell3','$cell4','$cell5','$cell6','".substr($cell7, 6, 4).substr($cell7, 3, 2).substr($cell7, 0, 2)."','$cell8','$inn','$kpp','$cell11','$cell12','$cell13','$cell14','$cell15')";
			 $query = mysql_query($ins); 
		}
	echo'<table>';
}
else{
    include('form_file_load.php');
	error_reporting(E_ALL);
}
?>
</body>
</html>