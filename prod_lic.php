<?php 
# Подключаем конфиг 
include 'confmex.php';
?> 

<!DOCTYPE html>
<html itemscope="" itemtype="http://schema.org/WebPage" lang="ru">
<head>
	<title></title>
	<meta http-equiv="content-type" content="text/html" charset="utf-8" />
	<meta http-equiv="Content-Style-Type" content="text/css" />
	<meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="css/bootstrap.min.css" rel="stylesheet">
<link rel="shortcut icon" href="/favicon.ico">
</head>
<body>
<?php 
$log=htmlspecialchars($_POST['login']);
$date_lic=htmlspecialchars($_POST['new_date_lic']);
$sql=mysql_query("UPDATE `users` SET date_licen='$date_lic' WHERE users_login='$log'");
echo '<head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" /></head>
        <body></body>';
    header("Location: ".$_SERVER['HTTP_REFERER']);
?>
</body>
</html>