<html>
<head>
	<title></title>
	<meta http-equiv="content-type" content="text/html" charset="utf-8" />
    <link href="css/bootstrap.min.css" rel="stylesheet">
	<link rel="stylesheet" href="editor.css"></head>
	<script type="text/javascript" src="ckeditor/ckeditor.js"></script>
	<script type="text/javascript" src="AjexFileManager/ajex.js"></script>
</head>
<body>
<div class="container" style="margin-top: 60px;">
<div class="row">
<div class="col-md-12">


<?php
function show_form()
{
?>
<form action="" method=post enctype="multipart/form-data">
<div class="input-group">
              <span class="input-group-addon">email*</span>
              <input class="form-control" type="text" name="email" size="40">
</div>
<div class="input-group">
              <span class="input-group-addon">Teма</span>
              <input class="form-control" type="text" name="title" size="40" value="Отправте свою отчетность на infosavoir@yandex.ru">
</div>
<div class="input-group">
              <span class="input-group-addon">Сообщение</span>
              <textarea rows="10" name="mess" cols="30">
								<div style="font-size: 15px; color: rgb(0, 0, 0); font-family: arial, sans-serif;">Добрый день!</div>

	<div style="font-size: 15px; color: rgb(0, 0, 0); font-family: arial, sans-serif;">&nbsp;</div>

	<div style="font-size: 15px; color: rgb(0, 0, 0); font-family: arial, sans-serif;">Отправте свою отчетность на&nbsp;<a href="https://e.mail.ru/compose/?mailto=mailto%3ainfosavoir@yandex.ru" rel=" noopener noreferrer" style="color: rgb(0, 0, 0);" target="_blank">infosavoir@yandex.ru</a></div>

	<div style="font-size: 15px; color: rgb(0, 0, 0); font-family: arial, sans-serif;">укажите организацию и телефон и вид оплаты наличными или безналичным</div>

	<div style="font-size: 15px; color: rgb(0, 0, 0); font-family: arial, sans-serif;">декларации отправят и свами свяжутся по телефону который Вы указали.</div>

	<div style="font-size: 15px; color: rgb(0, 0, 0); font-family: arial, sans-serif;">Все протоколы Вы получите электронно на Вашу электронную почту.</div>

	<div style="font-size: 15px; color: rgb(0, 0, 0); font-family: arial, sans-serif;">&nbsp;</div>

	<div style="font-size: 15px; color: rgb(0, 0, 0); font-family: arial, sans-serif;">С уважением,</div>

	<div style="font-size: 15px; color: rgb(0, 0, 0); font-family: arial, sans-serif;">Никитенко Иван Федорович</div>

	<div style="font-size: 15px; color: rgb(0, 0, 0); font-family: arial, sans-serif;"><span style="color:rgb(0, 0, 0); font-family:arial,sans-serif; font-size:15px">Тел. 8 (905) 415-39-08</span></div>
</textarea>
</div>
<div class="input-group">
			  <script  type="text/javascript">
var ckeditor = CKEDITOR.replace( "mess" );
AjexFileManager.init({returnTo: "mess", editor: ckeditor});
</script>
              <span class="input-group-addon">Файл</span>
              <input class="form-control" name="attachfile" type="file" size="28">
</div>
<div>
              <input class="btn btn-success" type="submit" value="Отправить" name="submit">
</div>
</form>
* Помечены поля, которые необходимо заполнить
<?
}

function complete_mail() {
        // $_POST['title'] содержит данные из поля "Тема", trim() - убираем все лишние пробелы и переносы строк, htmlspecialchars() - преобразует специальные символы в HTML сущности, будем считать для того, чтобы простейшие попытки взломать наш сайт обломались, ну и  substr($_POST['title'], 0, 1000) - урезаем текст до 1000 символов. Для переменных $_POST['mess'], $_POST['name'], $_POST['tel'], $_POST['email'] все аналогично
        $_POST['title'] =  substr(htmlspecialchars(trim($_POST['title'])), 0, 1000);
        $_POST['name'] =  substr(htmlspecialchars(trim($_POST['name'])), 0, 30);
        $_POST['tel'] =  substr(htmlspecialchars(trim($_POST['tel'])), 0, 30);
        $_POST['email'] =  substr(htmlspecialchars(trim($_POST['email'])), 0, 999999999);
        // если неправильно заполнено поле email - показываем ошибку 1
        if(!preg_match("/[0-9a-z_]+@[0-9a-z_^\.]+\.[a-z]{2,3}/i", $_POST['email']))
             output_err(1);
        // обратите внимание, теперь мы можем писать красивые письма, с помощью html тегов ;-)
        $mess = $_POST['mess'];

        // подключаем файл класса для отправки почты
        require 'class/class.phpmailer.php';
        $mail = new PHPMailer();
				$mail->CharSet = 'UTF-8';
        $mail->From = 'infosavoir@yandex.ru';      // от кого
        $mail->FromName = 'SAVOIR';   // от кого
        $mail->AddAddress ($_POST['email']); // кому - адрес, Имя
        $mail->IsHTML(true);        // выставляем формат письма HTML
        $mail->Subject = $_POST['title'];  // тема письма

        // если был файл, то прикрепляем его к письму
        if(isset($_FILES['attachfile'])) {
                 if($_FILES['attachfile']['error'] == 0){
                    $mail->AddAttachment($_FILES['attachfile']['tmp_name'], $_FILES['attachfile']['name']);
                 }
        }
        $mail->Body = $mess;

        // отправляем наше письмо
        if (!$mail->Send()) die ('Mailer Error: '.$mail->ErrorInfo);
        echo 'Спасибо! Ваше письмо отправлено.';
}

function output_err($num)
{
    $err[1] = 'ОШИБКА! Неверно введен e-mail.';
    echo '<p>'.$err[$num].'</p>';
    show_form();
    exit();
}

if (!empty($_POST['submit'])) complete_mail();
else show_form();
?>

<?php
//var_dump(mail("gmrx@mail.ru","Test","Test"));
?>
</div>
</div>
</div>









    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
</body>
</html>
