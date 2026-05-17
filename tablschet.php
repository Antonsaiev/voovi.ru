<?php
# подключаем конфиг
include 'conf.php';  
session_start();
	/*$ogrn=$_SESSION['ogrn'];
	$tips=$_SESSION['tip'];
	*/
	$ogrn=$_COOKIE['ogrn'];
	$tips=$_COOKIE['tip'];
	$dss=$_SESSION['ds'];
$dff=$_SESSION['df'];

	if($ogrn!="0")
	{
	$udosrpod = "SELECT uslugi.id,uslugi.name,users_access.users from uslugi left join users_access on uslugi.id=users_access.uslugi where users_access.users='".$_GET['id']."' and uslugi.id='".$ogrn."'";
	$udosresultrpod = mysql_query($udosrpod);
	$udospersonrpod = mysql_fetch_array($udosresultrpod);
	$idogrn=$udospersonrpod['id'];
	$nameogrn=$udospersonrpod['name'];
	}
	else
	{
		$idogrn="0";
		$nameogrn="Все организации";
	}
	
?>
<div class="modal-shadowkube" id="modal-shadowkube"></div>
<div class="kube" id="kube">
<img src="/img/image-preloader-252.gif">
</div>

<div class="by amt" style="
 width: 100%;
        margin-top: 35px;
    padding-left: 0px;
	margin-bottom: 10px;
">

<div class='statdate' style="width:390px;float: left;">
<select class='form-control' id="getOrg">
<option value =<?echo $idogrn;?> selected><? echo $nameogrn;?></option>
<option value="0">Все организации</option>
<?$rep=mysql_query("SELECT uslugi.id,uslugi.name,users_access.users from uslugi left join users_access on uslugi.id=users_access.uslugi where users_access.users='".$_GET['id']."' and uslugi.del!='1'");
   while($resep = mysql_fetch_assoc($rep)) :?>
   <option value ="<?echo $resep['id'];?>"><?echo $resep['name'];?></option>
   <?php endwhile; ?>
</select>
</div>
<div class='statdate' style="width:150px;float: left;">
<input class='form-control firstDate' id="datestart" type="date" value="<?echo $_COOKIE['datas'];?>">
</div>
<div class='statdate' style="width:150px;float: left;">
<input class='form-control secondDate' id="datefinish" type="date" value="<?echo $_COOKIE['dataf'];?>">
</div>
<div class='statdate' style="width:400px;float: left;">
<input class="check"id="scales"name="scales" type="checkbox" checked="" style="height: 25px;
    width: 25px;
    margin-top: 5px;
    padding-top: 15px;
    margin-right: 5px;">
<label style="position: relative;font-size: 14pt;color: #d3d3d3;font-weight: normal;bottom: 5px;" id="scalesl"for="scales">Показать текущий месяц</label>
</div>
</div>
<div class="by amt" style="
   width: 100%;
    padding-left: 0px;
">
<div style="
	border-left: 1px solid #d3d3d3" class="tipschet">
<select class='form-control' id="tip" style="
    height: 53px;
	border: none;
">
<option value="<?echo $tips;?>" selected><?if($tips=="2"){echo "Продление";}if($tips=="1"){echo "Новый";}?></option>
<option value="1">Новый</option>
<option value="2">Продлениe</option>
</select>
</div>
<div id="tablschetog">

</div>
</div>
<div id="tablosn" style="width:100%; float:left;margin-top: 10px;">
</div>

<script>
    ogrn=document.getElementById('getOrg').value;
	tip=document.getElementById('tip').value;
	datastart=document.getElementsByClassName("firstDate")[0].value;
	datafinish=document.getElementsByClassName("secondDate")[0].value;
	
$.ajax({
				type: "GET",
				url: "tablschetog.php",
				data: "users=<?echo $_GET['id'];?>&ogrn=<?echo $ogrn;?>&tip="+tip+"&datas=<?echo $dss;?>&dataf=<?echo $dff;?>",
				success: function(html){
					 $("#tablschetog").html(html);
				}
			});
			var chkBox = document.querySelector('#scales');
 if(chkBox.checked==true)
 {
	document.getElementById('scalesl').style.color='#536570';
	
 }
 else
 {
	document.getElementById('scalesl').style.color='#d3d3d3'; 
 }
chkBox.onchange = function(){
    document.getElementById('tablosn').innerHTML = "";
 if(chkBox.checked==true)
 {
	document.getElementById('scalesl').style.color='#536570';
	document.getElementById('datestart').removeAttribute('disabled');
	document.getElementById('datefinish').removeAttribute('disabled');
 }
 else
 {
	 document.getElementById('modal-shadowkube').style.display="block";
     document.getElementById('kube').style.display="block";
	 	var date = new Date(); 
y = date.getFullYear();
 m = date.getMonth();
 var firstDay = new Date(y, m, 2);
var lastDay = new Date(y, m + 1, 1);
    document.getElementById('datestart').valueAsDate = firstDay;
         document.getElementById('datefinish').valueAsDate = lastDay;
	document.getElementById('scalesl').style.color='#d3d3d3'; 
	document.getElementById('datestart').setAttribute("disabled", "disabled");
	document.getElementById('datefinish').setAttribute("disabled", "disabled");
		ogrn=document.getElementById('getOrg').value;
	tip=document.getElementById('tip').value;
	datastart=document.getElementsByClassName("firstDate")[0].value;
	datafinish=document.getElementsByClassName("secondDate")[0].value;
	$.ajax({
				type: "GET",
				url: "tablschetog.php",
				data: "users=<?echo $_GET['id'];?>&ogrn="+ogrn+"&tip="+tip+"&datas="+datastart+"&dataf="+datafinish+"&chenge=1",
				success: function(html){
					 $("#tablschetog").html(html);
					 document.getElementById('modal-shadowkube').style.display="none";
                    document.getElementById('kube').style.display="none";
				}
			});
 }
};
var date = new Date(); 
y = date.getFullYear();
 m = date.getMonth();
 var  mon = date.getMonth();
 var firstDay = new Date(y, m, 2);
var lastDay = new Date(y, m + 1, 1);
    if(document.getElementById('datestart').value=="") {
        document.getElementById('datestart').valueAsDate = firstDay;
    }
    if(document.getElementById('datefinish').value=="") {
        document.getElementById('datefinish').valueAsDate = lastDay;
    }
  /*  var moni=<?echo $_COOKIE['manfs'];?>;
if(moni!=(mon+1))
{
    document.getElementById('datestart').valueAsDate = firstDay;
    document.getElementById('datefinish').valueAsDate = lastDay;
}*/
$( "#getOrg" ).change(function () {
	ogrn=document.getElementById('getOrg').value;
	tip=document.getElementById('tip').value;
	datastart=document.getElementsByClassName("firstDate")[0].value;
	datafinish=document.getElementsByClassName("secondDate")[0].value;
			$.ajax({
				type: "GET",
				url: "tablschetog.php",
				data: "users=<?echo $_GET['id'];?>&ogrn="+ogrn+"&tip="+tip+"&datas="+datastart+"&dataf="+datafinish+"",
				success: function(html){
					 $("#tablschetog").html(html);
				}
			});
		});
		$( "#tip" ).change(function () {
		datastart=document.getElementsByClassName("firstDate")[0].value;
	datafinish=document.getElementsByClassName("secondDate")[0].value;
	ogrn=document.getElementById('getOrg').value;
	tip=document.getElementById('tip').value;
			$.ajax({
				type: "GET",
				url: "tablschetog.php",
				data: "users=<?echo $_GET['id'];?>&ogrn="+ogrn+"&tip="+tip+"&datas="+datastart+"&dataf="+datafinish+"",
				success: function(html){
					 $("#tablschetog").html(html);
				}
			});
           /* $.ajax({
				type: "GET",
				url: "tablschetosn.php",
				data: "users=<?echo $_GET['id'];?>&ogr="+ogrn+"&tip="+tip+"&datas="+datastart+"&dataf="+datafinish+"",
				success: function(html){
					 $("#tablosn").html(html);
				}
			});*/
		});

</script>
<script>

$( "#datestart" ).change(function () {
	document.getElementById('modal-shadowkube').style.display="block";
    document.getElementById('kube').style.display="block";
		ogrn=document.getElementById('getOrg').value;
	tip=document.getElementById('tip').value;
	datastart=document.getElementsByClassName("firstDate")[0].value;
	datafinish=document.getElementsByClassName("secondDate")[0].value;
    document.getElementById('tablosn').innerHTML = "";
	$.ajax({
				type: "GET",
				url: "tablschetog.php",
				data: "users=<?echo $_GET['id'];?>&ogrn="+ogrn+"&tip="+tip+"&datas="+datastart+"&dataf="+datafinish+"&chenge=1",
				success: function(html){
					 $("#tablschetog").html(html);
					  document.getElementById('modal-shadowkube').style.display="none";
                    document.getElementById('kube').style.display="none";
				}
			});
		});
</script>
<script>
$( "#datefinish" ).change(function () {
	document.getElementById('modal-shadowkube').style.display="block";
    document.getElementById('kube').style.display="block";
		ogrn=document.getElementById('getOrg').value;
	tip=document.getElementById('tip').value;
	datastart=document.getElementsByClassName("firstDate")[0].value;
	datafinish=document.getElementsByClassName("secondDate")[0].value;
    document.getElementById('tablosn').innerHTML = "";
	$.ajax({
				type: "GET",
				url: "tablschetog.php",
				data: "users=<?echo $_GET['id'];?>&ogrn="+ogrn+"&tip="+tip+"&datas="+datastart+"&dataf="+datafinish+"&chenge=1",
				success: function(html){
					 $("#tablschetog").html(html);
					 document.getElementById('modal-shadowkube').style.display="none";
                    document.getElementById('kube').style.display="none";
				}
			});
		});
</script>
<script src="https://mottie.github.io/tablesorter/js/jquery.tablesorter.js"></script>