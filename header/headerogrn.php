
			
			
		<li class="dropdown">
          <a href="#" class="navbar-brand dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
		  <span class="glyphicon glyphicon-file" aria-hidden="true"></span> <?php 	$udosrpod = "SELECT * from uslugi  WHERE id = '".$userdata['inogrn']."'";
	$udosresultrpod = mysql_query($udosrpod);
	$udospersonrpod = mysql_fetch_array($udosresultrpod);
	echo $udospersonrpod['name'];?>
		  <span class="caret"></span></a>
          <ul class="dropdown-menu">
		  <li>
<label style="
    background: #3b6a98;
    color: #fff;
    font-weight: bold;
    text-align: center;
"><input class="none" type="radio" name="radioogrn" onchange="startacp890975656(this.value)" <?php if(89097565645 == $userdata['inogrn']){echo 'checked';}?>>ВСЕ ОРГАНИЗАЦИИ</label>
	<script>
		function startacp890975656(str) {
			$.ajax({
				type: "GET",
				url: "pusya.php",
				data: "vlad="+str+"&tip=ogrnus&rand=89097565645",
				success: function(msg){
					window.location.href="<?php echo $_SERVER['REQUEST_URI'];?>";
				}
			});
		}
	</script></li>
           <?php
		$query214 = mysql_query("SELECT * from users_access  WHERE users = '".$userdata['users_id']."'");	
		while($row214 = mysql_fetch_array($query214)) {
		$query32 = mysql_query("SELECT * from uslugi  WHERE del = '0' AND id = '".$row214['uslugi']."' ORDER BY name ");	
		while($row32 = mysql_fetch_array($query32)) {

    echo "<li>";
	echo '<label><input class="none" type="radio" name="radioogrn" onchange="startacp'.$row32['id'].'(this.value)" ';
	if($row32['id'] == $userdata['inogrn']){
		echo 'checked';
	}
	echo'>';
	echo $row32['name'];
	if($row32['kkt'] != 0){
		//echo ' <a class="offkass" href="/check.php"><span class="glyphicon glyphicon-log-out" aria-hidden="true"></span></a>';
	}
	echo'</label> 
	<script> 
		function startacp'.$row32['id'].'(str) {
			$.ajax({
				type: "GET",
				url: "pusya.php",
				data: "vlad="+str+"&tip=ogrnus&rand='.$row32['id'].'",
				success: function(msg){
					window.location.href="'.$_SERVER['REQUEST_URI'].'";
				}
			});
		}
	</script>';
	echo '</li>';
  }
  }
  ?>
          </ul>
        </li>
		
		
		
            <li>
			
			<?php 
			
			/*echo '<a style="background: #f0ad4e; color: #fff;" href="#" data-toggle="dropdown" role="button" aria-expanded="false" title="Закрыть смену">	
				<span class="glyphicon glyphicon-log-out" aria-hidden="true"></span>
			</a>';
			*/
			?>
			
			</li>