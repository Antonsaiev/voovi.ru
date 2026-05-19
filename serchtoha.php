
<?php if (empty($render_toha_search_in_top_nav)) { ?>
<form action="<?php echo $_SERVER['REQUEST_URI'];?>" method="GET" name="form" style="
position: fixed;
top: 45px;
z-index: 10;
right: 95px;
background: #424242;
color: #FFF;
border-radius: 4px;
height: 28px;
padding: 1px 8px;
padding-left: 1px;
border: 1px solid #545454;
">

<input type="text" id="texts" name="inn" style="
    border: 2px solid #868686;
    background: #666666;
	border-top-left-radius: 3px;
    border-bottom-left-radius: 3px;
	width: 200px;" placeholder=Введите Название, № или ИНН">
<input type="submit" value="Поиск" style="
    background: #333;
    margin-right: -7px;
    padding: 4px;
    border: 1px solid #666;
    border-top-right-radius: 3px;
    border-bottom-right-radius: 3px;
"><br><br>
</form>
<?php } ?>
<div id="details">
</div>
<form class="toha-action-form" action="<?php $_SERVER['PHP_SELF']?>" method="post" >
<?php if (empty($render_toha_search_in_top_nav)) { ?>
<table class="table rowclick" id="rowclick2" <?php
if (isset($_GET['inn'])){
	echo 'style="margin-top: 30px;"';
}
 ?>>

<?php
if(isset($_GET['akt'])){
$getakt="akt = '$_GET[akt]'";
}else{
$getakt="akt != '1'";
}
$name1 = $_GET['name'];
$inn1 = $_GET['inn'];
$groupi1 = $_GET['groupi'];
if (isset($inn1)) {
$search_name= mysql_query("SELECT DISTINCT ns,
kolichschet,
d,
m,
y,
nomerschet,
nomerschetks,
ogrn,
prodlen,
generac,
name,
lico,
rand,
otdel,
filial,
god,
nomerdog,
data,
produkt,
install,
price,
kto,
inn,
kpp,
idkli,
goroddd,
otk,
koment,
oplachen,
oplachenks,
doljen,
gotov,
akt,
url, 
groupi,
gr  FROM `schet` 
WHERE CONCAT(inn,' ',name,' ',ns) LIKE '%$inn1%' AND `gr` LIKE '%$groupi1%' AND del ='0'");
if (mysql_num_rows($search_name) != 0) {
echo'	<thead>
<tr>
<th style="width: 1px;"><span class="glyphicon glyphicon-ok" aria-hidden="true"></span></th>
<th style="width: 1px;"><span class="glyphicon glyphicon-random" aria-hidden="true"></span></th>
<th style="width: 1px;">№</th>
<th style="width: 1px;">Дата</th>
<th style="width: 1px;">ИНН</th>
<th style="width: 1px;">КПП</th>
<th style="width: 180px; text-align: center;">Название</th>
<th style="width: 90px; text-align: center;">Продукт</th>
<th >Комментарии</th>
<th style="width: 1px;"><span class="glyphicon glyphicon-star"></span></th>
<th style="width: 1px;"><span class="glyphicon glyphicon-ruble" aria-hidden="true"></span></th>
<th style="width: 1px;"><span class="glyphicon glyphicon-open-file" aria-hidden="true"></span></th>
<th style="width: 8px; text-align: center;">Контур</span></th>
<th style="width: 8px; text-align: center;">SAVOIR</th>
<th style="width: 80px; text-align: center;">Услуга</th>
<th style="width: 80px; text-align: center;">Выставил</th>
<th style="width: 1px;">Исполнитель</th>
<th style="width: 1px;">Открыть</th>
</tr>
    </thead>';
while ($row = mysql_fetch_assoc($search_name)) {
	include 'inctoha.php';
}} else {
echo "Ничего не найдено";
}
} 
?>
</table>
<?php } ?>
