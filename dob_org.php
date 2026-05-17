<?php
# Подключаем конфиг 
include 'conf.php'; 

# проверка авторизации
if (isset($_COOKIE['id']) and isset($_COOKIE['hash'])) 
{    
    $userdata = mysql_fetch_assoc(mysql_query("SELECT * FROM users WHERE users_id = '".intval($_COOKIE['id'])."' LIMIT 1"));

    if(($userdata['users_hash'] !== $_COOKIE['hash']) or ($userdata['users_id'] !== $_COOKIE['id']) ) 
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

<?php session_start();
if(isset($_GET['exit'])) {
session_destroy(); 
#redirect
header('Location: http://xxx.xxx');
exit;
}
?>
<html>
<head>
	<title></title>
	<meta http-equiv="content-type" content="text/html" charset="utf-8" />
	<meta http-equiv="Content-Style-Type" content="text/css" />
	<meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="css/bootstrap.min.css" rel="stylesheet">
	<link href="css/style.css" rel="stylesheet">
<link rel="shortcut icon" href="/favicon.ico">
</head>
 <body>
 <div class="container">
 <div class="row">
 <div class="col-md-4"> </div>
 <div class="col-md-4 reg-gm">
 
  <div class="col-md-4"></div>
  </div>
  <div class="content">
    <div class="top-reg-gm zag">Добавление/удаление доступа к услугам</div>
   <div class="top-reg-gm zag1"> </div>
   <form method="POST" action="" class="vib">
   <select class="vib vibor"  onchange="if (this.selectedIndex) this.form.submit()" name="sort">
  <option selected> Выберите сотрудника</option>
  <?php $r = mysql_query("SELECT * FROM users"); while($res = mysql_fetch_assoc($r))  : ?>
  <option value="<?php echo $res['users_id']?>"><?php echo htmlspecialchars($res['f_name']) .'&nbsp'. htmlspecialchars($res['l_name']);?></option>
    <?php endwhile; ?>
  </select>
  </form>
  
 <div class=" form_dob">
 <span class="glyphicon glyphicon-th-list text_zag" aria-hidden="true"><p class="zaglav">&nbspАктуальные услуги</p></span>
 <form method="POST" action="/org_dob.php">
 <ul class="port" name="usl">
<?php
$id=htmlspecialchars($_POST["sort"]);
session_start();
$_SESSION['a'] = $id;
session_write_close();
$result = mysql_query("SELECT users_access.uslugi, uslugi.name FROM `users_access` INNER join uslugi on uslugi.id=users_access.uslugi and users_access.users='".$id."'");
while($rez = mysql_fetch_array($result)):?>
<li value="<?php echo $rez['uslugi']?>" name="usl"><?php echo htmlspecialchars($rez['name']);?></li>
<?php  endwhile; ?>
</ul>
</form>
 </div>
 <div class="form_dob">
 <span class="glyphicon glyphicon-th-list text_zag" aria-hidden="true"><p class="zaglav">&nbspДоступные услуги</p></span>
 <ul class="port">
<?php
$result = mysql_query("SELECT * FROM uslugi where  del='0'");
while($rez = mysql_fetch_array($result)):?>
<li value="<?php echo $rez['id']?>"><?php echo htmlspecialchars($rez['name']);?>
<?php endwhile; ?>
</ul>
 </div>
   <form method="POST" action="/org_dob.php" class="usl">
   <select class="vib vibor"  name="dob">
  <option selected> Выберите услугу для добавления</option>
  <?php
   $r = mysql_query("SELECT * FROM uslugi where  del='0'"); while($res = mysql_fetch_assoc($r))  : ?>
  <option value="<?php echo $res['id']?>"><?php echo htmlspecialchars($res['name']);?></option>
    <?php endwhile; ?>
  </select>
  <button class="button">Добавить</button>
  </form>
<form method="POST" action="/udl_usl.php" class="usl">
   <select class="vib vibor" name="udl">
  <option selected> Выберите услугу для удаления</option>
  <?php
    $id=htmlspecialchars($_POST["sort"]);
   session_start();
   $_SESSION['b'] = $id;
   session_write_close();    
  $r = mysql_query("SELECT users_access.uslugi, uslugi.name FROM `users_access` INNER join uslugi on uslugi.id=users_access.uslugi and users_access.users='".$id."'");
  while($res = mysql_fetch_assoc($r))  : ?>
  <option value="<?php echo $res['uslugi']?>"><?php echo htmlspecialchars($res['name']);?></option>
    <?php endwhile; ?>
  </select>
  <button class="button">Удалить</button>
  </form>
 </div>

  </div>
  </div>
  <script src="js/bootstrap.min.js"></script>
  </body>
  </html>