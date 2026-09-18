<?php
# подключаем конфиг
include 'conf.php'; 

$organizations = array();
$savedOrgId = isset($_COOKIE['orgn']) ? intval($_COOKIE['orgn']) : 0;
$selectedOrgId = 0;
$defaultOrgId = 0;
$defaultOrgPriority = 0;
$userId = isset($_GET['id']) ? intval($_GET['id']) : 0;
$organizationQuery = mysql_query("SELECT DISTINCT uslugi.id, uslugi.name
    FROM uslugi
    INNER JOIN users_access ON users_access.uslugi = uslugi.id
    WHERE users_access.users = '".$userId."' AND uslugi.del != '1'
    ORDER BY uslugi.name, uslugi.id");

if ($organizationQuery) {
    while ($organization = mysql_fetch_assoc($organizationQuery)) {
        $organizations[] = $organization;
        if (intval($organization['id']) === $savedOrgId) {
            $selectedOrgId = $savedOrgId;
        }

        // The active company is ИЦ "SAVOIR"; other SAVOIR services are separate organizations.
        $normalizedName = preg_replace('/[\s\p{Z}"\'«»“”„]+/u', '', $organization['name']);
        $priority = 0;
        if (preg_match('/^ИЦSAVOIR$/iu', $normalizedName)) {
            $priority = 2;
        } elseif (strcasecmp($normalizedName, 'SAVOIR') === 0) {
            $priority = 1;
        }
        if ($priority > $defaultOrgPriority) {
            $defaultOrgId = intval($organization['id']);
            $defaultOrgPriority = $priority;
        }
    }
}
if ($defaultOrgId > 0) {
    $selectedOrgId = $defaultOrgId;
}
?>
<?php include_once 'voovi_spinner.php'; ?>
<div class="by amt" style="
 width: 100%;
        margin-top: 35px;
    padding-left: 0px;
	margin-bottom: 10px;
">

<div class='statdate' style="width:390px;float: left;">
<select class='form-control' id="getogr">
<option value="0"<?php if ($selectedOrgId === 0) { echo ' selected'; } ?>>Все организации</option>
<?php foreach ($organizations as $organization): ?>
   <option value="<?php echo intval($organization['id']); ?>"<?php if (intval($organization['id']) === $selectedOrgId) { echo ' selected'; } ?>><?php echo htmlspecialchars($organization['name'], ENT_QUOTES, 'UTF-8'); ?></option>
<?php endforeach; ?>
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
