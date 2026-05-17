<?php
# Подключаем конфиг 
include 'conf.php'; 
$q = "SELECT * FROM ogrn WHERE id =$_GET[kli]";
		$result = mysql_query($q);
		$person = mysql_fetch_array($result);

$qq = "SELECT * from tekkli WHERE inn = '".$person['inn']."' AND kpp = '".$person['kpp']."'";
		$resultt = mysql_query($qq);
		$personn = mysql_fetch_array($resultt);

?>
<meta http-equiv="content-type" content="text/html" charset="utf-8" />
<script src="js/script.js"></script>
<strong><h4 style="margin-top: 10px; font-weight: bold; border-bottom: 1px #333 solid;">Выберите документы в наличии</h4></strong>
<br>
<span style="float: left; margin-right: 4px;">
	 <div style="background: #f80707; width: 15px; height: 15px; float: left; margin-right: 4px;"></div> - Нет документов  
	 </span>
	 
	 <span style="float: left; margin-right: 4px;">
	 <div style="background: #3FA043; width: 15px; height: 15px; float: left; margin-right: 4px;"></div> - Оригинал
	 </span> 
	 
	 <span style="float: left; margin-right: 4px;">
	 <div style="background: #f6d008; width: 15px; height: 15px; float: left; margin-right: 4px;"></div> - Копия
	 </span>

<table style="width: 100%; background: #fff;"  class="table table-hover tableq tabli">
<?php

if (strlen($_GET['inn']) == 12){
$tip = 1;
} else {
$tip = 2;
}
$re1 = mysql_query("SELECT count(*) from proddoc WHERE produkt = '$_GET[parent]'  AND tip = '".$tip."'");
$cl1 = mysql_result($re1, 0);
$re2 = mysql_query("SELECT count(*) from proddoc WHERE produkt = '$_GET[parent]'  AND tip = '3'");
$cl2 = mysql_result($re2, 0);
$cl3 = $cl2 + $cl1;
$red = mysql_query("SELECT count(*) from dokstamp WHERE ogrn ='".$_GET['kli']."' AND schet = '".$_GET['rand']."'");
$clad = mysql_result($red, 0);
echo "<br>";
if($cl3 == $clad){
}


echo '<thead>
<tr>
<th>Документы</th>
<th>О</th>
<th>К</th>
</tr>
</thead>';

$query3 = mysql_query("SELECT * from doki inner join dokstamp on dokstamp.doki=doki.id and dokstamp.schet='".$_GET['rand']."' order by doki.id asc");
while($row3 = mysql_fetch_assoc($query3))
		{
            echo '<tr  id="'.$row3['id'].'" >';
            if ($row3['status']=='0')
            {
                if($row3['origin']=='0' && $row3['coopi']=='0' )//нет оригенала нет копии
                {
                    echo '<td class="danger" >' . $row3['name'];
                    echo '</td>';
                    echo '<td id="' . $row3['id'] . '" style="width: 20px"; class=" danger" >';
                    echo '<span onclick="ktp'.$row3['id'].'()" id="ktp'.$row3['id'].'" class="glyphicon glyphicon-remove " aria-hidden="true"></span>
<script>
    function ktp'.$row3['id'].'() {
        $.ajax({
            type:"get",
            url:"izm_sos_or.php",
            data:"tip=1doc&prod='.$row3['id'].'&kli='.$_GET['kli'].'&rand='.$_GET['rand'].'&doki='.$row3['doki'].'&ori='.$row3['origin'].'&cop='.$row3['coopi'].'",//параметры запроса
            success: function(){location.reload()}
        });

    }';
                    echo'</script>';
                    echo '</td>';
                    echo '<td id="' . $row3['id'] . '" style="width: 20px"; class="danger" ">';
                    echo '<span onclick="ktp1'.$row3['id'].'()" id="ktp1'.$row3['id'].'" class="glyphicon glyphicon-remove " aria-hidden="true"></span>
<script>
    function ktp1'.$row3['id'].'() {
        $.ajax({
            type:"get",
            url:"izm_sos_cop.php",
            data:"tip=1doc&prod='.$row3['id'].'&kli='.$_GET['kli'].'&rand='.$_GET['rand'].'&doki='.$row3['doki'].'&ori='.$row3['origin'].'&cop='.$row3['coopi'].'",//параметры запроса
            success: function(){location.reload()}
        });

    }';
                    echo'</script>';
                    echo '</td>';
                }
                if($row3['origin']=='1' && $row3['coopi']=='0' )////есть оригенал нет копии
                {
                    echo '<td class="success" >' . $row3['name'];
                    echo '</td>';
                    echo '<td  id="' . $row3['id'] . '" style="width: 20px"; class=" success">';
                    echo '<span onclick="ktp'.$row3['id'].'()" id="ktp'.$row3['id'].'" class="glyphicon glyphicon-ok " aria-hidden="true"></span>
<script>
    function ktp'.$row3['id'].'() {
        $.ajax({
            type:"get",
            url:"izm_sos_or.php",
            data:"tip=1doc&prod='.$row3['id'].'&kli='.$_GET['kli'].'&rand='.$_GET['rand'].'&doki='.$row3['doki'].'&ori='.$row3['origin'].'&cop='.$row3['coopi'].'",//параметры запроса
            success: function(){location.reload()}
        });

    }';
                    echo'</script>';
                    echo '</td>';
                    echo '<td id="' . $row3['id'] . '" style="width: 20px"; class="danger" ">';
                    echo '<span onclick="ktp1'.$row3['id'].'()" id="ktp1'.$row3['id'].'" class="glyphicon glyphicon-remove " aria-hidden="true"></span>
<script>
    function ktp1'.$row3['id'].'() {
        $.ajax({
            type:"get",
            url:"izm_sos_cop.php",
            data:"tip=1doc&prod='.$row3['id'].'&kli='.$_GET['kli'].'&rand='.$_GET['rand'].'&doki='.$row3['doki'].'&ori='.$row3['origin'].'&cop='.$row3['coopi'].'",//параметры запроса
            success: function(){location.reload()}
        });

    }';
                    echo'</script>';
                    echo '</td>';
                }
                if( $row3['origin']=='1' && $row3['coopi']=='1')//есть оригенал есть копия
                {
                    echo '<td class="success" >' . $row3['name'];
                    echo '</td>';
                    echo '<td id="' . $row3['id'] . '" style="width: 20px"; class="success" ">';
                    echo '<span onclick="ktp'.$row3['id'].'()" id="ktp'.$row3['id'].'" class="glyphicon glyphicon-ok " aria-hidden="true"></span>
<script>
    function ktp'.$row3['id'].'() {
        $.ajax({
            type:"get",
            url:"izm_sos_or.php",
            data:"tip=1doc&prod='.$row3['id'].'&kli='.$_GET['kli'].'&rand='.$_GET['rand'].'&doki='.$row3['doki'].'&ori='.$row3['origin'].'&cop='.$row3['coopi'].'",//параметры запроса
            success: function(){location.reload()}
        });

    }';
                    echo'</script>';
                    echo '</td>';
                    echo '<td id="' . $row3['id'] . '" style="width: 20px"; class="success" ">';
                    echo '<span onclick="ktp1'.$row3['id'].'()" id="ktp1'.$row3['id'].'" class="glyphicon glyphicon-ok " aria-hidden="true"></span>
<script>
    function ktp1'.$row3['id'].'() {
        $.ajax({
            type:"get",
            url:"izm_sos_cop.php",
            data:"tip=1doc&prod='.$row3['id'].'&kli='.$_GET['kli'].'&rand='.$_GET['rand'].'&doki='.$row3['doki'].'&ori='.$row3['origin'].'&cop='.$row3['coopi'].'",//параметры запроса
            success: function(){location.reload()}
        });

    }';
                    echo'</script>';
                    echo '</td>';
                }
                if($row3['origin']=='0' && $row3['coopi']=='1' )//нет оригенала есть копия
                {
                    echo '<td class="warning" >' . $row3['name'];
                    echo '</td>';
                    echo '<td id="' . $row3['id'] . '" style="width: 20px"; class=" danger" >';
                    echo '<span onclick="ktp'.$row3['id'].'()" id="ktp'.$row3['id'].'" class="glyphicon glyphicon-remove " aria-hidden="true"></span>
<script>
    function ktp'.$row3['id'].'() {
        $.ajax({
            type:"get",
            url:"izm_sos_or.php",
            data:"tip=1doc&prod='.$row3['id'].'&kli='.$_GET['kli'].'&rand='.$_GET['rand'].'&doki='.$row3['doki'].'&ori='.$row3['origin'].'&cop='.$row3['coopi'].'",//параметры запроса
            success: function(){location.reload()}
        });

    }';
                    echo'</script>';
                    echo '</td>';
                    echo '<td id="' . $row3['id'] . '" style="width: 20px"; class="warning" ">';
                    echo '<span onclick="ktp1'.$row3['id'].'()" id="ktp1'.$row3['id'].'" class="glyphicon glyphicon-ok " aria-hidden="true"></span>
<script>
    function ktp1'.$row3['id'].'() {
        $.ajax({
            type:"get",
            url:"izm_sos_cop.php",
            data:"tip=1doc&prod='.$row3['id'].'&kli='.$_GET['kli'].'&rand='.$_GET['rand'].'&doki='.$row3['doki'].'&ori='.$row3['origin'].'&cop='.$row3['coopi'].'",//параметры запроса
            success: function(){location.reload()}
        });

    }';
                    echo'</script>';
                    echo '</td>';
                }
            }

            /* $assa = mysql_result(mysql_query("SELECT count(*) from dokstamp WHERE status ='0' AND ogrn ='".$_GET['kli']."' AND doki  =".$row2['id']), 0);
             $assas = mysql_result(mysql_query("SELECT count(*) from dokstamp WHERE status ='1' AND ogrn ='".$_GET['kli']."' AND doki  =".$row2['id']), 0);
             $assass = mysql_result(mysql_query("SELECT count(*) from dokstamp WHERE status ='2' AND ogrn ='".$_GET['kli']."' AND doki  =".$row2['id']), 0);
             if ($assa <0){echo '<td id="st'.$row2['id'].'" class="warning">';} else if ($assas < 0 || $assass > 0) {echo '<td id="st'.$row2['id'].'" class="success">';}else{echo '<td id="st'.$row2['id'].'" class="danger">';}

             echo $row2['name'];
             echo '

             <script>

             </script>
             </td>';


             /*if (mysql_result(mysql_query("SELECT count(*) from dokstamp WHERE status ='1' AND ogrn ='".$_GET['kli']."' AND doki  =".$row2['id']), 0) > 0) {
                 echo '<td style="width: 20px;" id="kt'.$row2['id'].'" class="success">';
                 echo '<span onclick="ktp'.$row2['id'].'()" id="ktp'.$row2['id'].'" class="glyphicon glyphicon-ok" aria-hidden="true"></span>
                 <script>function ktp'.$row2['id'].'(){$.ajax({type: "GET",url: "pusya.php",data: "tip=1doc&prod='.$row2['id'].'&kli='.$_GET['kli'].'&rand='.$_GET['rand'].'&doc=1",
                 success: function(){document.getElementById("ktp'.$row2['id'].'").className = "glyphicon glyphicon-remove";document.getElementById("kt'.$row2['id'].'").className = "danger";
                 if($("#kt'.$row2['id'].'").hasClass("danger") == true && $("#ktt'.$row2['id'].'").hasClass("danger") == true){document.getElementById("st'.$row2['id'].'").className = "danger";}
                 }});}</script>';
                 echo '</td>';
             }else{
                 echo '<td style="width: 20px;" id="kt'.$row2['id'].'" class="danger">';
                 echo '<span onclick="ktp'.$row2['id'].'()" id="ktp'.$row2['id'].'" class="glyphicon glyphicon-remove" aria-hidden="true"></span>
                 <script>function ktp'.$row2['id'].'(){$.ajax({type: "GET",url: "pusya.php",data: "tip=2doc&prod='.$row2['id'].'&kli='.$_GET['kli'].'&rand='.$_GET['rand'].'&doc=1",
                 success: function(){document.getElementById("ktp'.$row2['id'].'").className = "glyphicon glyphicon-ok";document.getElementById("kt'.$row2['id'].'").className = "success";
                 if($("#kt'.$row2['id'].'").hasClass("success") == true || $("#ktt'.$row2['id'].'").hasClass("success") == true){document.getElementById("st'.$row2['id'].'").className = "success";}
                 }});}</script>';
                 echo '</td>';
             }
             if (mysql_result(mysql_query("SELECT count(*) from dokstamp WHERE status ='2' AND ogrn ='".$_GET['kli']."' AND doki  =".$row2['id']), 0) > 0) {
                 echo '<td style="width: 20px;" id="ktt'.$row2['id'].'" class="success">';
                 echo '<span onclick="ktpu'.$row2['id'].'()" id="ktpu'.$row2['id'].'" class="glyphicon glyphicon-ok" aria-hidden="true"></span>
                 <script>function ktpu'.$row2['id'].'(){$.ajax({type: "GET",url: "pusya.php",data: "tip=1doc&prod='.$row2['id'].'&kli='.$_GET['kli'].'&rand='.$_GET['rand'].'&doc=2",
                 success: function(){document.getElementById("ktpu'.$row2['id'].'").className = "glyphicon glyphicon-remove";document.getElementById("ktt'.$row2['id'].'").className = "danger";
                 if($("#kt'.$row2['id'].'").hasClass("danger") == true && $("#ktt'.$row2['id'].'").hasClass("danger") == true){document.getElementById("st'.$row2['id'].'").className = "danger";}
                 }});}</script>';
                 echo '</td>';
             }else{
                 echo '<td style="width: 20px;" id="ktt'.$row2['id'].'" class="danger">';
                 echo '<span onclick="ktpu'.$row2['id'].'()" id="ktpu'.$row2['id'].'" class="glyphicon glyphicon-remove" aria-hidden="true"></span>
                 <script>function ktpu'.$row2['id'].'(){$.ajax({type: "GET",url: "pusya.php",data: "tip=2doc&prod='.$row2['id'].'&kli='.$_GET['kli'].'&rand='.$_GET['rand'].'&doc=2",
                 success: function(){document.getElementById("ktpu'.$row2['id'].'").className = "glyphicon glyphicon-ok";document.getElementById("ktt'.$row2['id'].'").className = "success";
                 if($("#kt'.$row2['id'].'").hasClass("success") == true || $("#ktt'.$row2['id'].'").hasClass("success") == true){document.getElementById("st'.$row2['id'].'").className = "success";}
                 }});}</script>';
                 echo '</td>';
             }

             if (mysql_result(mysql_query("SELECT count(*) from dokstamp WHERE status ='0' AND ogrn ='".$_GET['kli']."' AND doki  =".$row2['id']), 0) > 0) {
                 echo '<td style="width: 20px;" id="kta'.$row2['id'].'" class="success">';
                 echo '<span onclick="ktpa'.$row2['id'].'()" id="ktpa'.$row2['id'].'" class="glyphicon glyphicon-plus-sign" aria-hidden="true"></span>
                 <script>function ktpa'.$row2['id'].'(){$.ajax({type: "GET",url: "pusya.php",data: "tip=1doc&prod='.$row2['id'].'&kli='.$_GET['kli'].'&rand='.$_GET['rand'].'&doc=0",
                 success: function(){document.getElementById("ktpa'.$row2['id'].'").className = "glyphicon glyphicon-trash";document.getElementById("kta'.$row2['id'].'").className = "danger";
                 if($("#kta'.$row2['id'].'").hasClass("success") == true){document.getElementById("st'.$row2['id'].'").className = "warning";}else{
                     if($("#kt'.$row2['id'].'").hasClass("danger") == true && $("#ktt'.$row2['id'].'").hasClass("danger") == true){document.getElementById("st'.$row2['id'].'").className = "danger";}
                 }
                 }});}</script>';
                 echo '</td>';
             }else{
                 echo '<td style="width: 20px;" id="kta'.$row2['id'].'" class="danger">';
                 echo '<span onclick="ktpa'.$row2['id'].'()" id="ktpa'.$row2['id'].'" class="glyphicon glyphicon-trash" aria-hidden="true"></span>
                 <script>function ktpa'.$row2['id'].'(){$.ajax({type: "GET",url: "pusya.php",data: "tip=2doc&prod='.$row2['id'].'&kli='.$_GET['kli'].'&rand='.$_GET['rand'].'&doc=0",
                 success: function(){document.getElementById("ktpa'.$row2['id'].'").className = "glyphicon glyphicon-plus-sign";document.getElementById("kta'.$row2['id'].'").className = "success";
                 if($("#kta'.$row2['id'].'").hasClass("success") == true){document.getElementById("st'.$row2['id'].'").className = "warning";}else{
                     if($("#kt'.$row2['id'].'").hasClass("success") == true || $("#ktt'.$row2['id'].'").hasClass("success") == true){document.getElementById("st'.$row2['id'].'").className = "success";}
                 }
                 }});}</script>';
                 echo '</td>';
             }*/
		
		
		
		
		echo '</tr>';

}
?>
</table>

<?php 
if(isset($_POST['submit'])){
$u = "INSERT INTO `doki` (`name`,`tip`) VALUES ('$_POST[name]','$_POST[tip]')";
mysql_query($u) or die(mysql_error($link));
header("Location: /kartklient.php?id=".$_POST['id']);
}
?>

