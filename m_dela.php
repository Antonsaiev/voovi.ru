<?php
# подключаем конфиг
include 'conf.php';  

# проверка авторизации
if (isset($_COOKIE['id']) and isset($_COOKIE['hash'])) 
{    
    $userdata = mysql_fetch_assoc(mysql_query("SELECT * FROM users WHERE users_id = '".intval($_COOKIE['id'])."' LIMIT 1"));

    if(($userdata['users_hash'] !== $_COOKIE['hash']) or ($userdata['users_id'] !== $_COOKIE['id'])) 
    { 
        setcookie('id', '', time() - 60*24*30*12, '/'); 
        setcookie('hash', '', time() - 60*24*30*12, '/');
    setcookie('errors', '1', time() + 60*24*30*12, '/'); 
    header('Location: index.php'); exit();
    } 
} 
else 
{ 
  setcookie('errors', '2', time() + 60*24*30*12, '/');
  header('Location: index.php'); exit();
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ru" lang="ru">
<head>
	<title></title>
	<meta http-equiv="content-type" content="text/html" charset="utf-8" />
	<meta http-equiv="Content-Style-Type" content="text/css" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="css/bootstrap.min.css" rel="stylesheet">
	<link href="blog.css" rel="stylesheet">
</head>
<body>
<?php
# шапка
include 'header.php';  
?>
<div class="container" style="margin-top: 60px;">
<div class="row">

<div class="col-md-10">

<div class="bs-example">
   
<h3 style="border-bottom: 1px #333 solid;">Мои клиенты</h3>

	<table class="table table-striped">
	<thead>
        <tr>
		<th>Принята</th>
		  <th>ФИО</th>
          <th>Телефон</th>
		  <th>Этап</th>
		  <th>Тип сделки</th>
            <th><span class="glyphicon glyphicon-envelope"></span></th>
          <th><span class="glyphicon glyphicon-time"></span></th>
		  <th><span class="glyphicon glyphicon-user"></span></th>
		  <th><span class="glyphicon glyphicon-pencil"></span></th>
        </tr>
    </thead>
					<?php
						$query = mysql_query("SELECT * from klient WHERE man = '".intval($_COOKIE['id'])."' ORDER BY id DESC");	
							while($row = mysql_fetch_array($query)) {
								echo '<tr style="background: ;"><td>';
								echo $row['dr'],"&nbsp";
								echo $row['mr'],"&nbsp";
								echo $row['gr'];
								echo '</td>';
								echo '<td>';
								echo $row['fname'],"&nbsp";
								echo $row['lname'],"&nbsp";
								echo $row['mname'];
								echo '</td>';
								echo '<td>';
								echo $row['tel'];
								echo '</td>';
								echo '<td>';
								if ($row['status'] == 1) {
								echo "Первичный контакт";
								} if ($row['status'] == 2) {
								echo "Переговоры";
								} if ($row['status'] == 3) {
								echo "Принимают решение";
								} if ($row['status'] == 4) {
								echo "Согласование договора";
								} if ($row['status'] == 0) {
								echo "В архив (без слелки)";
								}
								echo '</td>';
								echo '<td>';
								if ($row['operac'] == 1) {
								echo "Аренда";
								} if ($row['operac'] == 2) {
								echo "Купля";
								} if ($row['operac'] == 3) {
								echo "Продажа";
								} if ($row['operac'] == 4) {
								echo "Обмен";
								}
								echo '</td>';
                                echo '<td>';
								echo '<a title="Написать сообщение" href="./new_sms.php?id=' .$row['id']. '"><span class="glyphicon glyphicon-envelope"></span></a>';
								echo '</td>';
								echo '<td>';
								echo '<a title="Новое напоминание" href="./napomni.php?id=' .$row['id']. '"><span class="glyphicon glyphicon-time"></span></a>';
								echo '</td>';
								echo '<td>';
								echo '<a title="Карточка клиента" href="./kartklient.php?id=' .$row['id']. '"><span class="glyphicon glyphicon-user"></span></a>';
								echo '</td>';
								echo '<td>';
								echo '<a title="Редактировать" href="./m_kli.php?id=' .$row['id']. '"><span class="glyphicon glyphicon-pencil"></span></a>';
								echo '</td></tr>';
						}
					?>
				</table>
</div>
</div>
<?php
# левая колонка сайта
include 'left_sitebar.php';  
?>
</div>
</div>			
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
</body>
</html>