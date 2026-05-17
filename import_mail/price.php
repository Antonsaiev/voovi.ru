<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>Результат загрузки файла</title>
</head>
<body>
<?php
error_reporting(E_ALL); // Выключаем показ ошибок. Чтобы их видеть - вместо 0 поставьте E_ALL
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
    $data->setOutputEncoding("UTF8"); //Кодировка выходных данных
    $data->read($_FILES["filename"]["name"]);
    
    for ($i=2; $i<=$data->sheets[0]["numRows"]; $i++){
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
        
        $ins="INSERT INTO `mail_to`
		(`naim`, `inn`, `kpp`, `email`, `e_mail`, `picmo`, `dat_fns`, `fns`, `statuc`, `dat_sav`, `rand`)
		VALUES 
		('$cell1','$cell2','$cell3','$cell4','$cell5','$cell6','$cell7','$cell8','$cell9','$cell10','".rand(100000000000, 999999999999).rand(100, 999)."')";
        $query = mysql_query($ins);
        if(!$query){
            die('Ошибочка');
			error_reporting(E_ALL);
        }
    } 
}
else{
    include('form_file_load.php');
	error_reporting(E_ALL);
}
?>
</body>
</html>