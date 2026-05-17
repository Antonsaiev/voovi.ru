<form action="" method="POST">
 
<p>
<label><b>Название документа:</b><br /> </label>
    <input name="docname" />
</p>
 
<p>
<label><b>Ваше имя:</b><br /> </label>
    <input name="myself" />
</p>
 
<p>
<label><b>Ваш текст:</b><br /> </label>
    <textarea name="text" rows="10" cols="25" tabindex="2"></textarea>
</p>
<input type="submit" value="Создать документ!" />
 
</form>
 
 
 
 
<?PHP
 
if (isset($_POST['docname']) && !empty($_POST['docname'])) $docname = $_POST['docname'];
if (isset($_POST['myself'])  && !empty($_POST['myself']))  $myself  = $_POST['myself'];
if (isset($_POST['text'])    && !empty($_POST['text']))    $text    = $_POST['text'];
 
if (isset($docname) && isset($myself) && isset($text)) {
 
 
    header('Content-type: application/vnd.ms-word');
    header('Content-Disposition: attachment;Filename=' . $docname . '.doc');
 
    echo '<html>';
    echo '<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">';
    echo '<body>';
    echo 'Автор: ' . $myself;
    echo '<br>' . $text . '</b>';
    echo '</body>';
    echo '</html>';
 
}
 
?>