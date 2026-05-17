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
	<script src="/jquery.min.js"></script>
<script src="//cdn.ckeditor.com/4.4.3/full/ckeditor.js"></script>
</head>
<body>


<?php
# подключаем конфиг
include 'header.php';  
?>
<div  style="margin-top: 60px;"></div>
<div class="container">





<?php
	/* Powered by mr.molodoy */
	/* Author email: vitaly.molodoy@gmail.com */

	class Dialog {
		var $dialog_id = 0;
		var $dialog_exist = false;

		var $owner_id = 0;
		var $user_id = 0;

		// Конструктор класса Dialog
		// Инициализация необходимых для работы класса параметров
		function Dialog ( $user_id = 0 ) {
			$user_id = intval ( $user_id );
			if ($user_id < 1 or (empty ($_COOKIE['id']) or intval ($_COOKIE['id']) < 1)) {
				return false;
			} else {
				$this->user_id = $user_id;
				$this->owner_id = intval ($_COOKIE['id']);
				$dialog_id = $this->exist();

				if ($dialog_id === false) {
					// Диалог еще не был создан
					$this->dialog_exist = false;
				} else {
					$this->dialog_id = intval ( $dialog_id );
					$this->dialog_exist = true;
				}
				
			}
		}

		// Отправка сообщения в диалог
		function send_message ( $message ) {
			if ($this->dialog_exist === true and !empty($message)) {
				$result = mysql_query ("
					INSERT INTO `dialog_messages` ( `dialog_id`, `owner_id`, `time`, `view_flag`, `message` ) 
					VALUES (
						'" . $this->dialog_id . "', 
						'" . intval ($this->owner_id) . "',
						'" . time() . "', 
						'0', 
						'".$message."'
					);
				");
				return ($result)? true : false;
			} else { return false; }
		}

		// Получение списка сообщений в диалоге между двумя пользователями
		function get_messages () {
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
						`dialog_messages`.`dialog_id` = '".$this->dialog_id."' and 
						`users`.`users_id` = `dialog_messages`.`owner_id`
					)
				ORDER BY `time` DESC
				LIMIT 0, 10;
				");

			if (!$result) {
				return false;
			} else {
				if (mysql_num_rows ($result) < 1) {
					return false;
				} else {
					$messages = array ();
					while ($message = mysql_fetch_array ( $result )) {
						$messages[] = $message;
					}

					mysql_query ("
						UPDATE `dialog_messages` 
						SET `view_flag` = 1
						WHERE (
							`owner_id` = '".$this->user_id."' and 
							`dialog_id` = '".$this->dialog_id."' and 
							`view_flag` = 0
						);
					");
					return $messages;
				}
			}
		}

		// Проверка существования диалога между двумя пользователями
		function exist () {
			$result = mysql_query ("
					SELECT `id` 
					FROM `dialogs`
					WHERE ((  `user1` = '" . $this->owner_id . "' 	and 
							  `user2` = '" . $this->user_id . "'  ) or 
							( `user1` = '" . $this->user_id . "'  	and 
							  `user2` = '" . $this->owner_id . "' ));
				");
			$count = mysql_num_rows ($result);
			if ($count > 0) {
				mysql_data_seek($result, 0);
				$dialog = mysql_fetch_array( $result );

				return intval ($dialog['id']);
			} else {
				return false;
			}
		}

		// Удаление диалога
		function remove () {

		}
	}

	class Dialogs {
		var $owner_id = 0;

		// Конструктор класса
		function __construct () {
			$this->owner_id = intval ($_COOKIE['id']);
		}

		function count () {
			$result = mysql_query ("
				SELECT `id` 
				FROM `dialogs`
				WHERE (
						(	`user1` = '" . $this->owner_id . "') or 
						(	`user2` = '" . $this->owner_id . "'));
			");
			return intval (mysql_num_rows( $result ));
		}

		// Получение списка диалогов
		function get () {
			$result = mysql_query ("
				SELECT 
					`dialogs`.`id` as `dialog_id`,
					`dialogs`.`user1` as `user1`,
					`dialogs`.`user2` as `user2`
				FROM `dialogs`
				WHERE (
						(	`user1` = '" . $this->owner_id . "') or 
						(	`user2` = '" . $this->owner_id . "'));
			");
			if (mysql_num_rows($result) < 1) {
				return false;
			} else {
				$dialogs = array ();
				while ($dialog = mysql_fetch_array( $result )) {
					$dialogs[] = $dialog;
				}
				// Возвращаем список диалогов в виде массива
				return $dialogs;
			}
		}

		// Создание нового диалога
		function open ($user_id = 0) {
			$dialog = new Dialog ($user_id);
			if ($dialog === false) { 
				return false;
			} else {
				if ($dialog->dialog_exist === true) {
					// Если диалог между инициатором беседы и 
					// выбраным пользователем был установлен  ранее
					// Возвращаем идентификатор диалога из базы данных
					return $dialog->dialog_id;
				} else {
					// Если диалог не был создан ранее - создаем его
					$result = mysql_query ("
						INSERT INTO `dialogs` ( `user1`, `user2` ) 
						VALUES (
							'" . $this->owner_id . "', 
							'" . intval ($user_id) . "'
						);
					");
					if (!$result) {
						return false;
					} else {
						// Если диалог успешно создан - возвращаем его id
						return mysql_insert_id ();
					}
				}
			}
		}
	}

	

?>
<style type="text/css">
.red-button {
	margin-top: 5px;
	font-weight: 700;
	color: #fff;
	border-radius: 4px;
	margin-right: 1%;
	width: 48%;
	border: 1px solid;
	background: -webkit-linear-gradient(#D3726F, #E72E29);
	background-color: #D15451;
	-webkit-box-shadow: inset 0 1px 1px #FA9898;
	border-color: #FFC7C7 #CC5050 #FA1919;
	display: inline-block;
	margin-left: 3px;
	cursor: pointer;
	text-align: center;
	padding: 5px 10px;
}
a.red-button {
	text-decoration: none;
	color: #fff;
}
a.red-button:hover {
	text-decoration: none;
	color: #fff;
}
</style>
<?php if (!isset ($_GET['action'])) { ?>
	<!-- Список диалогов -->
	<div class = "panel panel-default">
		<div class = "panel-heading">Список диалогов</div>
		<div style="
    padding: 5px;
" class = "panel-body">
			<?php
				$dialogs = new Dialogs;
				$total_dialogs = $dialogs->count();

				if ($total_dialogs > 0) {
					// Получаем список диалогов если таковые имеются
					$dialog_items = $dialogs->get();
					$dialog_new_items = array ();
					foreach ($dialog_items as $dialog) {

						if ($dialog['user1'] == $_COOKIE['id']) {
							$dialog['uid'] = $dialog['user2'];
						} else {
							$dialog['uid'] = $dialog['user1'];
						}

						$result = mysql_query ("SELECT `img`, `users_id`, `users_login`, `f_name`, `l_name` FROM `users` WHERE (`users_id` = '".$dialog['uid']."');");
						mysql_data_seek($result, 0);
						$dialog['user'] = mysql_fetch_array ($result);

						unset ($result);

						$result = mysql_query ("SELECT * 
							FROM 
								`dialog_messages`, 
								`users` 
							WHERE (
								`dialog_messages`.`dialog_id` = '".$dialog['dialog_id']."' and 
								`users`.`users_id` = `dialog_messages`.`owner_id`
							) ORDER BY `dialog_messages`.`time` DESC LIMIT 0,1;");

						if (mysql_num_rows ($result) > 0) {
							$dialog['is_messages'] = true;
							mysql_data_seek($result, 0);
							$dialog['messages'] = mysql_fetch_array ($result);	

						} else {
							$dialog['is_messages'] = false;
						}
						$dialog_new_items[$dialog['messages']['time']] = $dialog;
					}
					krsort($dialog_new_items);

					foreach ($dialog_new_items as $dialog) {
						$dialog = $dialog;

						$uid = $dialog['uid'];
						$data_user = $dialog['user'];
						$is_messages = $dialog['is_messages'];
						$data_message = $dialog['messages'];
						
						if ($is_messages === true) {
						?>
							<div class = "panel panel-default" style =  "cursor: pointer; margin-bottom: 5px;" onclick = "window.location = '?action=open&user_id=<?=$uid?>';">
								<?php if ($data_message['owner_id'] != $_COOKIE['id'] and $data_message['view_flag'] == 0 and $is_messages === true) { ?>
									<div  style="padding: 0px; " class = "bg-danger">
								<?php } else { ?>
									<div style="
    padding: 0px;
" class = "bg-success">
								<?php } ?>
									<table style = "width: 100%;">
										<tr>
											<td style = "background-image:url('/img/<?=$data_user['img']?>'); background-repeat: no-repeat;  background-size: cover; width: 50px;
border-radius: 3px;
height: 50px;
background-position: center;"></td>
											<td style = "padding-left: 15px; vertical-align: top;">
												<?=$data_user['f_name'].' '.$data_user['l_name']?> <br />
												<?php if ($is_messages) { ?>
													<span style = "color: #777; font-size: 11px;">
														Последнее сообщение от: <a href = "/profile.php?id=<?=$data_message['owner_id']?>" style = "font-size: 10px;
color: #777;"><?=$data_message['f_name'].' '.$data_message['l_name']?></a>
													</span>
													<div style = "
														<?php 
															if ($data_message['owner_id'] == $_COOKIE['id'] and $data_message['view_flag'] < 1) {
																print 'background-color: #f5f5f5; border-radius: 5px; border: 1px solid #ddd;';
															}
														?> padding: 5px 10px; margin-bottom: 5px; font-size: 11px; border-left: 5px solid #ddd ;
													">
														<?=$data_message['message']?> <br />
														<span style = "color: #777; font-size: 11px;"><?=date("d.m.Y в H:i:s", $data_message['time'])?></span>
													</div>
												<?php } else { ?>
													<div style = "font-size: 11px; color: #aaa;">Сообщений еще нет</div>
												<?php } ?>
											</td>
										</tr>
									</table>
								</div>
							</div></a>
						<?php
						}
					}
				} else {
					// если созданных диалогов еще нет
					?>
						Диалогов пока нет. <br />
						<i style = "color: #666; font-size: 11px;">Выберите пользователя и начните с ним беседу.</i>
					<?php
				}
			?>
		</div>			
	</div>

<?php } else { ?>
	<?php if ($_GET['action'] == 'open') {
		if (intval ($_GET['user_id']) == $_COOKIE['id']) {
			?>
				<div class="alert alert-danger">Нельзя писать самому себе.</div>
				<a href = "?">Вернутся к диалогам</a> <br />
			<?php
			exit();
		}

		if (empty($_GET['user_id'])) {
			?>
				<div class="alert alert-danger">Ошибка. Не выбран пользователь.</div>
				<a href = "?">Вернутся к диалогам</a> <br />
			<?php
			exit();
		}

		/*
			Открытие диалога по идентификатору собеседника
		*/
			// Получаем идентификатор собеседника
			$user_id = intval ($_GET['user_id']);

			// Открываем диалог с собеседником
			$dialogs = new Dialogs;
			$dialog_id = $dialogs->open( $user_id );

			if (intval($dialog_id) > 0 and ($dialog_id !== false)) {
				// Инициализируем диалог
				$dialog = new Dialog( $user_id );
				?>
					<?php if (isset($_GET['message']) and $_GET['message'] == "success") { ?>
						<div class="alert alert-success" style="   margin-bottom: 5px; padding: 2px 5px;">Успешно отправлено.</div>
					<?php } ?>
					
					<div class = "panel panel-default" style="   margin-bottom: 10px;">
						<div class = "panel-body" style="  padding: 5px;">
							<form action = "?action=sendmessage&user_id=<?=$user_id?>" method = "POST" style = "text-align: center;">
								<textarea id='editor' name = "message" onkeypress="if(event.keyCode==10||(event.ctrlKey && event.keyCode==13))koment.click();" class = "form-control" style="margin-bottom: 0px; height: 36px;" placeholder = "Введите текст сообщения"></textarea>
								
								<center>
									<input id="koment" type="submit" value="Отправить"  style=" float: left; width: 65%;float: left;  width: 65%;  margin-top: 5px;  color: #fff;  border-radius: 4px;  border: 1px solid;  background-color: #E47C79;  display: inline-block;  cursor: pointer;  text-align: center;  padding: 2px 5px;" />
									<a href = "?action=open&user_id=<?=$_GET['user_id']?>&rand=<?=rand(1111,9999)?>"  style = "width: 34%; float: right;    margin-top: 5px;  color: #fff;  border-radius: 4px;  border: 1px solid;  background-color: #E47C79;  display: inline-block;  cursor: pointer;  text-align: center;  padding: 2px 5px;">
										Обновить
									</a>
								</center>
							</form>
						</div>
					</div>
					<?php $messages = $dialog->get_messages(); ?>
					<?php if ($messages === false) { ?>
						<div class="alert alert-danger">В диалоге нет ниодного сообщения.</div>
					<?php } else { ?>
						<div id = "messages">
							<script type="text/javascript">
									<?php $total = mysql_num_rows(mysql_query("SELECT * FROM `dialog_messages` WHERE (`dialog_id` = '".$dialog_id."');")); ?>
									window.total_messages = <?=$total?>;
							</script>
						<?php foreach ($messages as $message) { ?>
							<?php $message = (object) $message; ?>
							<div class = "panel panel-default" style="margin-bottom: 5px;">
								<?php if (($message->view_flag < 1)) { ?>
									<div class = "panel-heading" style="padding: 0px;" data-messageid = "<?=$message->message_id?>">
										<?php } else { ?> 
									<div class = "panel-body" style="padding: 0px;" data-messageid = "<?=$message->message_id?>">
								<?php } ?>
									<table style = "width: 100%;">
										<tr>
											<td style = "width: 50px; height: 50px;" onclick = "window.location = '/profile.php?id=<?=$message->owner_id?>';">
												<div style = "background-image:url('/img/<?=$message->photo?>'); border-radius: 4px; background-repeat: no-repeat; height: 50px; background-size: cover; width: 50px; cursor: pointer;"></div>
											</td>
											<td style = "padding-left: 10px;">
												<?=htmlspecialchars_decode($message->message)?>
												<div style = "color: #aaa; font-size: 11px; margin-top: 5px;">
												  <a href = "/profile.php?id=<?=$message->owner_id?>" style = "color: #aaa;"><?=$message->login?></a>
						<?php 
						if($userdata['users_id'] == 1){
						echo '<a href = "./message_view.php?id='.$message->message_id.'&user_id='.$_GET['user_id'].'">';
						}
						?>
													<?=date("d.m.Y в H:i:s", $message->time)?><?php 
						if($userdata['users_id'] == 1){
						echo '</a>';
						}
						?>
													<?php if ($message->view_flag < 1 and $message->owner_id == $_COOKIE['id']) { ?>
														<span style = "text-decoration: underline;">Не прочитано</span>
													<?php } ?>
												</div>
											</td>
										</tr>
									</table>
								</div>
							</div>
						<?php } ?>
					</div>
					<?php } ?>
					<center>
						<?php if ($total > 10) { ?>
							<a href = "javascript:show_next();" id = "show_next" style = "width: 100%; margin-top: 5px;  color: #4C6B8F; display: inline-block;  border-radius: 4px;  border: none;  background-color: #E4E8ED; cursor: pointer;  text-align: center;  padding: 6px;">
								Предыдущие сообщениея
							</a> <br />
						<?php } ?>
						<?php 
						if($userdata['users_id'] == 1){
						echo '<a href = "/dialog_clear.php?id='.$dialog_id.'">Очистить диалог</a>';
						}
						?>
						
					</center>
				<?php
			} else {
				// В случае непредвиденной ошибки
				?>
					<div class="alert alert-danger" style="
    padding-bottom: 2px;
    padding-top: 2px;
    padding-left: 5px;
    padding-right: 5px;
	margin-bottom: 5px;
">Произошла непредвиденная ошибка</div>
				<?php
			}
	} else if ($_GET['action'] == 'sendmessage') {
		/*
			Отправка сообщения в диалог
		*/
			// Получаем идентификатор собеседника
			$user_id = intval ($_GET['user_id']);

			// Инициализируем диалог
			$dialog = new Dialog ( $user_id );

			$status_error = false;
			if ($dialog === false) { $status_error = true; }

			$message = trim ($_POST['message']);
			if (empty ($message)) {
				?>
					<div class="alert alert-danger" style="
    padding-bottom: 2px;
    padding-top: 2px;
    padding-left: 5px;
    padding-right: 5px;
	margin-bottom: 5px;
">Сообщение не может быть пустым.</div>
					<a href = "?action=open&user_id=<?=$user_id?>" style="width: 100%;       color: #fff;  border-radius: 4px;  border: 1px solid;  background-color: #E47C79;  display: inline-block;  cursor: pointer;  text-align: center;  padding: 2px 5px;margin-bottom: 5px;">Вернитесь и повторите попытку</a>
				<?php
			} else {
				// Добавляем сообщение
				if ($dialog->send_message (htmlspecialchars_decode($message)) === false or $status_error) {
					// В случае возникновения непредвиденной ошибки
					?>
						<div class="alert alert-danger" style="
    padding-bottom: 2px;
    padding-top: 2px;
    padding-left: 5px;
    padding-right: 5px;
	margin-bottom: 5px;
">Произошла непредвиденная ошибка</div>
						<a href = "?action=open&user_id=<?=$user_id?>">Вернитесь и повторите попытку</a>
					<?php
				} else {
					header("location: ?action=open&user_id=".$user_id."&message=success"); exit();
				}
			}
	} ?>
<?php } ?>
<script type="text/javascript">
	function show_next () {
		var messages = $("#messages");
		var message_id = $("div[data-messageid]:last").attr('data-messageid');
		$.get("/dialog_get_messages.php?dialog_id=<?=$dialog_id?>&message_id=" + message_id, function (data) {
			messages.append(data);
		});

		if (window.total_messages <= ($("div[data-messageid]").length + 10)) { 
			$("#show_next").fadeOut("fast");
		}
	}

	function get_messages () {
		
		var message_id = $("div[data-messageid]:first").attr('data-messageid');
		$.get("/get_new_messages.php?dialog_id=<?=$dialog_id?>&message_id=" + message_id, function (data) {
			$("#messages").prepend(data);
			get_messages ();
		});
	}
	get_messages ();
</script>
</div> <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
 <script src="js/bootstrap.min.js"></script>
</body>
</html>
