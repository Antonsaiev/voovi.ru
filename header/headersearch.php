
<div style="  margin-top: 5px;
  margin-right: 7px;">
<form method="post">
<input class="serchtext" id="searchs" type="search" style="width: 250px;" name="serchtext" placeholder="Введите Название или ИНН"  AUTOCOMPLETE="off" > 

<p class="btn btn-danger btn-xs"  id="schetsubmit" style="margin-bottom: 5px; border-radius: 0; height: 24px;margin-top: 3px;margin-right: -4px;border:none;    line-height: 23px; display:none;"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></p>

<button class="btn btn-primary btn-xs" type="submit" name="serchsubmit" style="margin-bottom: 5px; border-bottom-left-radius: 0; border-top-left-radius: 0;height: 24px;margin-top: 3px;border:none;    line-height: 24px;"><span class="glyphicon glyphicon-search" aria-hidden="true"></span></button>




<div id="serchog"></div> 




</form>
<?php 
if(isset($_POST['serchsubmit'])){
echo'<script type="text/javascript">
   document.location.href = "/admin_dela.php?naim=&inn='.$_POST['serchtext'].'&kpp=&ogrn=&email=";
</script>';
}
?>
</div>




  