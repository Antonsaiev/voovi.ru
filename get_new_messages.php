<?php
ob_start();
set_time_limit(13);
# подключаем конфиг
include 'conf.php';  

# проверка авторизации
if (isset($_COOKIE['id']) and isset($_COOKIE['hash'])) {    
    $userdata = mysql_fetch_assoc(mysql_query("SELECT * FROM users WHERE users_id = '".intval($_COOKIE['id'])."' LIMIT 1"));
    if(($userdata['users_hash'] !== $_COOKIE['hash']) or ($userdata['users_id'] !== $_COOKIE['id'])) { 
        setcookie('id', '', time() - 60*24*30*12, '/'); 
        setcookie('hash', '', time() - 60*24*30*12, '/');
        setcookie('errors', '1', time() + 60*24*30*12, '/'); 
        header('Location: index.php'); exit();
    } 
} else { 
  setcookie('errors', '2', time() + 60*24*30*12, '/');
  header('Location: index.php'); exit();
}

function get_new_message ($circle = 0) {
    if ($circle < 10) {

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
                `dialog_messages`.`dialog_id` = '".$_GET['dialog_id']."' and 
                `dialog_messages`.`id` > '".$_GET['message_id']."' and 
                `users`.`users_id` = `dialog_messages`.`owner_id`
            ) LIMIT 0, 1;
        ");

        if (mysql_num_rows ($result) < 1) {
            $circle++;
            sleep(1);

            get_new_message ($circle);
            
        } else {
            mysql_data_seek($result, 0);
            $message = mysql_fetch_array($result);

            $message = (object) $message;

            ?>
                <div class = "panel panel-default" style="margin-bottom: 5px;">
                    <div class = "panel-body" style="padding: 0px;"  data-messageid = "<?=$message->message_id?>">
                    <table style = "width: 100%;">
                        <tr>
                            <td style = "width: 50px; height: 50px;" onclick = "window.location = '/profile.php?id=<?=$message->owner_id?>';">
                                <div style = "background-image:url('/img/<?=$message->photo?>'); border-radius: 4px; background-repeat: no-repeat; height: 50px; background-size: cover; width: 50px; cursor: pointer;"></div>
                            </td>
                             <td style = "padding-left: 10px;">
                                <?=$message->message?>
                                <div style = "color: #aaa; font-size: 11px; margin-top: 5px;">
                                    <a href = "/profile.php?id=<?=$message->owner_id?>" style = "color: #aaa;"><?=$message->login?></a>
                                     <a href = "./message_view.php?id=<?=$message->message_id?>&user_id=<?=$_GET['user_id']?>"><?=date("d.m.Y в H:i:s", $message->time)?></a>
                                    <?php if ($message->view_flag < 1 and $message->owner_id == $_COOKIE['id']) { ?>
                                        <span style = "text-decoration: underline;">Не прочитано</span>
                                       <?php } ?>
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>
                </div>
            <?php
            return true;
        }
    } else {
        ?><i></i><?php
        return false;
    }
}


session_write_close();
get_new_message (0);

?>