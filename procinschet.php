<?php
# подключаем конфиг
include 'conf.php'; 

$orgn=$_COOKIE['orgn'];

	if($orgn!="0")
	{
	$udosrpod = "SELECT uslugi.id,uslugi.name,users_access.users from uslugi left join users_access on uslugi.id=users_access.uslugi where users_access.users='".$_GET['id']."' and uslugi.id='".$orgn."'";
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
<select class='form-control' id="getogr">
<option value =<?echo $idogrn;?> selected><? echo $nameogrn;?></option>
<option value="0">Все организации</option>
<?$rep=mysql_query("SELECT uslugi.id,uslugi.name,users_access.users from uslugi left join users_access on uslugi.id=users_access.uslugi where users_access.users='".$_GET['id']."' and uslugi.del!='1'");
   while($resep = mysql_fetch_assoc($rep)) :?>
   <option value ="<?echo $resep['id'];?>"><?echo $resep['name'];?></option>
   <?php endwhile; ?>
</select>
</div>
<div class='statdate' style="width:400px;float: left;">
<input class="check"id="scales"name="scales" type="checkbox"style="height: 25px;
    width: 25px;
    margin-top: 5px;
    padding-top: 15px;
    margin-right: 5px;">
<label style="position: relative;font-size: 14pt;color: #d3d3d3;font-weight: normal;bottom: 5px;" id="scalesl"for="scales">Наложить хронологический след</label>
</div>
</div>
<div class="by amt" style="
 width: 100%;
        margin-top: 35px;
    padding-left: 0px;
	margin-bottom: 10px;
">

<div class='statdate' style="width:390px;float: left;">
<ul class='form-control procschet'>
<li id="period">Период процесс</li>
</ul>
</div>
<div id="periodschet">

</div>
</div>
<script>
/*ogrn=document.getElementById('getogr').value;
if(ogrn!="")
{
		 ogrn=document.getElementById('getogr').value;
	 document.getElementById('modal-shadowkube').style.display="block";
 document.getElementById('kube').style.display="block";
$.ajax({
				type: "GET",
				url: "periodschet.php",
				data: "users=<?echo $_GET['id'];?>&orgn="+ogrn+"",
				success: function(html){
					 $("#periodschet").html(html);	
					  document.getElementById('modal-shadowkube').style.display="none";	
                     document.getElementById('kube').style.display="none";
				}
			});
}*/
 $('#period').click(function () {
	 ogrn=document.getElementById('getogr').value;
	 document.getElementById('modal-shadowkube').style.display="block";
 document.getElementById('kube').style.display="block";
$.ajax({
				type: "GET",
				url: "periodschet.php",
				data: "users=<?echo $_GET['id'];?>&orgn="+ogrn+"",
				success: function(html){
					 $("#periodschet").html(html);
					 document.getElementById('modal-shadowkube').style.display="none";	
 document.getElementById('kube').style.display="none";	
				}
			});
    });
	var tool = document.getElementById('period');

tool.addEventListener('click', () => {
  tool.classList.toggle('tool');
})
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.bundle.min.js"></script>
