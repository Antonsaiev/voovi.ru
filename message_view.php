<?php
ob_start();
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
	<title>Чат</title>
	<meta http-equiv="content-type" content="text/html" charset="utf-8" />
	<meta http-equiv="Content-Style-Type" content="text/css" />
	<link href="/css/bootstrap.min.css" rel="stylesheet">
</head>
<body style="font-family: verdana;">


<?php
# подключаем конфиг
include 'header.php';  
?>

<div style="width: 98%;
margin: 0 auto; margin-top: 60px;">

</a>
<div  style="margin-top: 6px;"></div>

<div class="panel panel-default"  style="
    margin-bottom: 5px;
">
    <?php
        $result = mysql_query ("
            SELECT 
                `dialog_messages`.`id` as `message_id`,
                `dialog_messages`.`dialog_id` as `dialog_id`,
                `dialog_messages`.`owner_id` as `owner_id`,
                `dialog_messages`.`time` as `time`,
                `dialog_messages`.`message` as `message`,
                `dialog_messages`.`view_flag` as `view_flag`,
                `users`.`users_login` as `login`,
                `users`.`img` as `photo`
            FROM `users`, `dialog_messages`
            WHERE (
                `dialog_messages`.`id` = '".$_GET['id']."' and 
                `users`.`users_id` = `dialog_messages`.`owner_id`
            )
            ORDER BY `time` DESC;
        ");

        if (mysql_num_rows ($result) < 1) {
            exit ("Ошибка. Сообщение не найдено");
        } else {
            mysql_data_seek($result, 0);
            $message = mysql_fetch_array ($result);

            $message = (object) $message;
        }

        if (isset ($_GET['action']) and $_GET['action'] == 'remove') {
            $result = mysql_query ("DELETE FROM `dialog_messages` WHERE (`id` = '".intval($_GET['id'])."')");
            
            if ($result) {
                header ("location: /dia.php?action=open&user_id=" . $_GET['user_id']);
            }
            exit ();
        }
    ?>
<div class="panel-heading" style="padding: 0px;">
    <table style = "width: 100%;">
        <tr>
            <td style = "background-image:url('/img/<?=$message->photo?>'); border-radius: 4px; background-repeat: no-repeat; height: 50px; background-size: cover; width: 50px; cursor: pointer;" onclick = "window.location = '/profile.php?id=<?=$message->owner_id?>';"></td>
            <td style = "padding-left: 10px;">
                <a href = "/profile.php?id=<?=$message->owner_id?>"><?=$message->login?></a> <br />
                <a href = "./message_view.php?id=<?=$message->message_id?>" style = "color: #ccc;"><?=date("d.m.Y в H:i:s", $message->time)?></a>
            </td>
        </tr>
    </table>
</div>
<div class="panel-body" style="padding-bottom: 5px 10px;">
    <?=$message->message?>
</div>

</div>
<a class=" btn btn-danger" style="width: 100%; border-radius: 0px; padding-top: 0px;padding-bottom: 0px;height: 22px;width: 100%; font-size: 14px;" href = "?id=<?=$_GET['id']?>&action=remove&user_id=<?=$_GET['user_id']?>">Удалить сообщение</a>
<div style="margin-top: 6px;"></div>
</div>
<?php
# подключаем конфиг
include 'niz.php';  
?>
</body>
</html>
