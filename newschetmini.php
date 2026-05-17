<?php
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


<?php
					$qrand = "SELECT * FROM `schet` WHERE rand = ".$_GET['rand']." ORDER BY id";
					$resultrand = mysql_query($qrand);
					$personrand = mysql_fetch_array($resultrand);
					$qrands = "SELECT * FROM `tarif` WHERE id = ".$personrand['prod'];
					$resultrands = mysql_query($qrands);
					$personrands = mysql_fetch_array($resultrands);
				?>



					<?php
$num = 999;
						$page = $_GET['page'];
						$result00 = mysql_query("SELECT COUNT(*) FROM produkti WHERE parent = '".$_GET['parent']."'  AND del = '0'");
						$temp = mysql_fetch_array($result00);
						$posts = $temp[0];
						$total = (($posts - 1) / $num) + 1;
						$total =  intval($total);
						$page = intval($page);
						if(empty($page) or $page < 0) $page = 1;
						if($page > $total) $page = $total;
						$start = $page * $num - $num;
						$var = round($temp[0] / 2);
						
						
						
						echo '<div class="" style="width:250px; padding 2px;   float: left; font-size: 17px; ">';
						$query = mysql_query("SELECT * from produkti WHERE parent = '".$_GET['parent']."'  AND del = '0' ORDER BY name LIMIT $start, $var");	
							while($row = mysql_fetch_array($query)) {
								echo '<a href="/newschet.php?id='.$_GET['id'].'&parent='.$row['id'].'&ogrn='.$_GET['ogrn'].'&inn='.$_GET['inn'].'&kpp='.$_GET['kpp'].'&tip='.$_GET['parent'].'&head='.$_GET['head'];
								if(isset($_GET['rand'])){
								echo '&rand='.$_GET['rand'];
								}
								echo'">';
									echo '<div class="col-md-hover" style=" padding 2px;';
									if($personrands['parent'] == $row['id'] || $_GET['tarif'] == $row['id']){echo 'background:#E4F7E0;';}
									if(isset($_GET['dop'.$row['id']])){echo 'background:#FCFFCF;';}
									echo '">';
									echo '<div style="float:left;
										background-image: url('."'".'/img/product_icons_20x20.png'."'".');
										background-repeat: no-repeat;
										background-position: 0 -300px;
										width: 20px;
										height: 20px;"></div>
										<div >'.$row['name'].'</div>';   
									echo '</div>';
								echo '</a>';
						} 
						echo '</div>';

						echo '<div class="" style="width:250px; padding 2px;   float: left; font-size: 17px; ">';
						$query2 = mysql_query("SELECT * from produkti WHERE parent = '".$_GET['parent']."'  AND del = '0' ORDER BY name LIMIT $var, $posts");	
							while($row = mysql_fetch_array($query2)) {
								echo '<a href="/newschet.php?id='.$_GET['id'].'&parent='.$row['id'].'&ogrn='.$_GET['ogrn'].'&inn='.$_GET['inn'].'&kpp='.$_GET['kpp'].'&tip='.$_GET['parent'].'&head='.$_GET['head'];
								if(isset($_GET['rand'])){
								echo '&rand='.$_GET['rand'];
								}
								echo'">';
									echo '<div class="col-md-hover" style=" padding 2px;';if($personrands['parent'] == $row['id'] || $_GET['tarif'] == $row['id']){echo 'background:#E4F7E0;';}
									echo '">';
									echo '<div style="float:left;
										background-image: url('."'".'/img/product_icons_20x20.png'."'".');
										background-repeat: no-repeat;
										background-position: 0 -300px;
										width: 20px;
										height: 20px;"></div>
										<div >'.$row['name'].'</div>';   
									echo '</div>';
								echo '</a>';
						} 
						echo '</div>';
					?>
				</table>

