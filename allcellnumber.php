<?php
# подключаем конфиг
include 'conf.php';

$q = "SELECT * FROM ogrn WHERE id =$_GET[id]";
$result = mysql_query($q);
$person = mysql_fetch_array($result);





if($person['rand'] == 0){
    mysql_query("UPDATE ogrn SET `rand`='".rand(100000000,999999999).date('dmY')."' WHERE `id`=$_GET[id]");
}
$ogrnq = "SELECT * FROM ogrn";
$ogrnresult = mysql_query($ogrnq);
$ogrn = mysql_fetch_array($ogrnresult);

$qq = "SELECT * from tekkli WHERE idkli = '".$_GET['id']."'";
$resultt = mysql_query($qq);
$personn = mysql_fetch_array($resultt);

$qqq = mysql_query("SELECT DISTINCT nomerschet,otdel,filial,god,nomerdog,data,produkt,price,priceks,kto,rand FROM schet WHERE del = '0' AND idkli = '".$_GET['id']."'");
$perq = mysql_fetch_array($qqq);



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
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "https://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="https://www.w3.org/1999/xhtml" xml:lang="ru" lang="ru">
<head>
    <title></title>
    <meta http-equiv="content-type" content="text/html" charset="utf-8" />
    <meta http-equiv="Content-Style-Type" content="text/css" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <script type="text/javascript"src="js/script.js" defer></script>
    <link rel="shortcut icon" href="/favicon.ico">

</head>
<body>

    <div style="display: flex;position: fixed;background: white;z-index: 99999999;" class="col-sm-12" >
        <div id="allt" class="allcalltablezag">
            <label >Все звонки</label>
        </div>
        <div id="alltp" class="allcalltablezag">
            <label>Привязаные</label>
        </div>
        <div  id="allnp"class="allcalltablezag">
            <label>Не привязаные</label>
        </div>
    </div>
    <table class="table col-sm-12" style="position: fixed;top:50px;z-index: 99999999;">
        <thead>
        <tr>
            <th style="width: 40px;text-align: center;padding-bottom: 5px"">№</th>
            <th style="width: 145px;text-align: center;padding-bottom: 5px"">Вид звонка</th>
            <th style="width: 195px;text-align: center;padding-bottom: 5px"">Кто звонил</th>
            <th style="width: 60px;text-align: center;">С какого номера</th>
            <th style="width: 165px;text-align: center;padding-bottom: 5px"">Кому звонили</th>
            <th style="width: 495px;text-align: center;padding-bottom: 5px"">Звонок</th>
            <th style="width: 265px;text-align: center;padding-bottom: 5px"">Дата звонка</th>
            <th style="width: 420px;text-align: center;padding-bottom: 5px"">Организация</th>
            <th style="text-align: center;padding-bottom: 5px">Номер счета</th>
        </tr>
        </thead>
    </table>
    <div id="content" style="position: relative;top: 80px;">

    </div>
<script>
    $(document).ready(function() {
        $.ajax({
            url: "allcall.php",
            cache: false,
            data: "id=<?echo $userdata['users_id'];?>&idkli=<?echo $_GET["lico"];?>&type=",
            success: function (html) {
                $("#content").html(html);
                document.getElementById("allt").style.color = 'grey';
                document.getElementById("alltp").style.color = '#d3d3d3';
                document.getElementById("allnp").style.color = '#d3d3d3';
            }
        })
    });
    $('#allt').click(function(){
        $.ajax({
            url: "allcall.php",
            cache: false,
            data: "id=<?echo $userdata['users_id'];?>&idkli=<?echo $_GET["lico"];?>&type=",
            success: function(html){
                $("#content").html(html);
                document.getElementById("allt").style.color='grey';
                document.getElementById("alltp").style.color='#d3d3d3';
                document.getElementById("allnp").style.color='#d3d3d3';
            }
        });
    });

    $('#alltp').click(function(){
        $.ajax({
            url: "allcallp.php",
            cache: false,
            data: "id=<?echo $userdata['users_id'];?>&idkli=<?echo $_GET["lico"];?>&type=",
            success: function(html){
                $("#content").html(html);
                document.getElementById("allt").style.color='#d3d3d3';
                document.getElementById("alltp").style.color='grey';
                document.getElementById("allnp").style.color='#d3d3d3';
            }
        });
    });
    $('#allnp').click(function(){
        $.ajax({
            url: "allcallnp.php",
            cache: false,
            data: "id=<?echo $userdata['users_id'];?>&idkli=<?echo $_GET["lico"];?>&type=",
            success: function(html){
                $("#content").html(html);
                document.getElementById("allt").style.color='#d3d3d3';
                document.getElementById("alltp").style.color='#d3d3d3';
                document.getElementById("allnp").style.color='grey';
            }
        });
    });
</script>
</body>
</html>
