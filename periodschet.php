<?
include 'conf.php'; 

setcookie('orgn',$_GET['orgn'],time()+60*60*24*30, "/", VOOVI_COOKIE_DOMAIN);
if($_COOKIE['y']=="")
{
	$yearc="0";
}
if($_COOKIE['y']!="")
{
	$yearc=$_COOKIE['y'];
}	
if($_GET['orgn']=="0")
{
 $allogrn='';	
}
else
{
	$allogrn="uslugi.id='".$_GET['orgn']."' and ";	
}
//$arYears = range(2019, date('Y')+1);
//
//for($i=0;$i<count($arYears);$i++)
//{
//echo $arYears[$i];
///*for ($m=1; $m <= 12; $m++) {
//          echo '<li>'.$_monthsList[$m].'</li>';
//        }*/
//}
?>
<style>
.period-table-scroll {
    cursor: grab;
    -webkit-overflow-scrolling: touch;
}
.period-table-scroll.is-dragging {
    cursor: grabbing;
    user-select: none;
}
</style>
<div class="by amt" style="float: left;
 width: 100%;
 padding-left: 0px;
 margin-bottom: 10px;
">
<?
$arYears = range(2018, date('Y'));
$m = date(F);
$_monthsList = array(
"1"=>"Январь","2"=>"Февраль","3"=>"Март",
"4"=>"Апрель","5"=>"Май", "6"=>"Июнь",
"7"=>"Июль","8"=>"Август","9"=>"Сентябрь",
"10"=>"Октябрь","11"=>"Ноябрь","12"=>"Декабрь");
for($i=0;$i<count($arYears);$i++)
{?>
<div class="by amt" style="float: left;
 width: 100%;
 padding-left: 0px;
">
<div class='tipschet' style="width:390px;float: left;border-left: 1px solid #d3d3d3;padding-top: 20px;
    padding-right: 12px;">
<ul class='procschet'>
<li id="periody<?echo $arYears[$i];?>"><?echo $arYears[$i]." год процесс";?></li>
</ul>
</div>
          <?$r = mysql_query("SELECT schet.rand, COUNT(*) as count FROM `schet` left join produkti on schet.produkt=produkti.id left join uslugi on produkti.parent=uslugi.id WHERE $allogrn schet.del!='1'and schet.y='".$arYears[$i]."' and schet.otk!='1' and schet.cher!='1' group by schet.rand");
    $res = mysql_num_rows($r) ;
	$ri = mysql_query("SELECT schet.*, COUNT(*) as count FROM `schet` left join produkti on schet.produkt=produkti.id left join uslugi on produkti.parent=uslugi.id WHERE $allogrn schet.del!='1'and schet.y=$arYears[$i] and schet.akt='1' and schet.otk='0' and schet.cher='0'group by schet.rand");
    $resi = mysql_num_rows($ri) ;

	if($res==$resi)
	{
		echo'<div class="tipschet" style="width:180px;float: left;background-color:#6FCF97;padding-top: 15px;font-size: 14pt;">';
		  echo "Закрыт ";
	      echo "100%";
		  echo '</div>';
	}
	if($res!=$resi)
	{

		$proc=($resi*100)/$res;

        if($proc>100) {
            $proc=100;

        }
		if($proc>"0"&&$proc<"25")
		{
			$color="background-color:#FFFFFF";
		}
		if($proc>"25"&&$proc<"50")
		{
			$color="background-color:#85D6D1";
		}
		if($proc>"50"&&$proc<"75")
		{
			$color="background-color:#FFF850";
		}
		if($proc>"75"&&$proc<"100")
		{
			$color="background-color:#FFB366";
		}
		if($proc=="100")
		{
			$color="background-color:#85D6A7";
		}
        if($proc>"100")
        {
            $color="background-color:#85D6A7";
        }
		  echo'<div class="tipschet" style="width:180px;float: left;padding-top: 15px;'.$color.';font-size: 14pt;">';
		  echo "Закрыт ";
	      echo trim(substr($proc,0,3),'.')."%";
		  echo '</div>';
	}
		  ?>
		  
<?
if($arYears[$i]==date('Y'))
	{
		$colmonf=date('n');
	}
	if($arYears[$i]!=date('Y'))
	{
		$colmonf=12;
	}
for ($m=1; $m <= $colmonf; $m++) { ?>
          <?
		  if($m<10)
		  {
			  $month="0".$m;
		  }
		  else
		  {
			$month=$m;  
		  }
		  $rm = mysql_query("SELECT schet.rand, COUNT(*) as count FROM `schet` left join produkti on schet.produkt=produkti.id left join uslugi on produkti.parent=uslugi.id WHERE $allogrn schet.del!='1'and schet.y='".$arYears[$i]."' and schet.m='".$month."' and schet.otk!='1' and schet.cher!='1' group by schet.rand");
    $resm = mysql_num_rows($rm) ;
	$rim = mysql_query("SELECT schet.rand, COUNT(*) as count FROM `schet` left join produkti on schet.produkt=produkti.id left join uslugi on produkti.parent=uslugi.id WHERE $allogrn schet.del!='1'and schet.y='".$arYears[$i]."' and schet.m='".$month."' and schet.akt='1' group by schet.rand");
    $resim = mysql_num_rows($rim) ;
	if($resm==$resim)
	{
		echo'<div class="tipschet" style="width:120px;float: left;background-color:#6FCF97;padding-top: 5px;font-size: 14pt;">';
		   echo '<p>'.$_monthsList[$m].'</p>';
	      echo " 100%";
		  echo '</div>';
	}
	if($resm!=$resim)
	{
		$proci=($resim*100)/$resm;
        if($proci>100) {
            $proci=100;

        }
		if($proci>"0"&&$proci<"25")
		{
			$color="background-color:#FFFFFF";
		}
		if($proci>"25"&&$proci<"50")
		{
			$color="background-color:#85D6D1";
		}
		if($proci>"50"&&$proci<"75")
		{
			$color="background-color:#FFF850";
		}
		if($proci>"75"&&$proci<"100")
		{
			$color="background-color:#FFB366";
		}
		if($proci=="100")
		{
			$color="background-color:#85D6A7";
		}
        if($proci>"100")
        {
            $color="background-color:#85D6A7";
        }
		echo'<div class="tipschet" style="width:120px;float: left;padding-top: 5px;'.$color.';font-size: 14pt;">';
		  echo '<p>'.$_monthsList[$m].'</p>';
	      echo " ".round($proci, 1, PHP_ROUND_HALF_ODD)."%";
		  echo '</div>';
	}


		 
		  ?>
        <?}?>
		<div class="period-table-scroll" id="periodtabl<?echo $arYears[$i];?>" style="display:none;float: left;clear: both;width: 100%;min-width: 100%;grid-column: 1 / -1;overflow-x: auto;box-sizing: border-box;">
		</div>
		<script>
		/*var year=<?echo $yearc;?>;
		document.getElementById('modal-shadowkube').style.display="block";
 document.getElementById('kube').style.display="block";
		if(year!="0")
		{

		 
     document.getElementById('periodtabl<?echo $yearc;?>').style.display="block";
	 $.ajax({
				type: "GET",
				url: "periodtabl.php",
				data: "id=<?echo $_GET['id'];?>&orgn=<?echo $_GET['orgn'];?>&y="+year+"",
				success: function(html){
					 $("#periodtabl<?echo $yearc;?>").html(html);
					 document.getElementById('modal-shadowkube').style.display="none";	
                     document.getElementById('kube').style.display="none";
				}
			});
		}
		else
		{
				 document.getElementById('modal-shadowkube').style.display="none";	
                     document.getElementById('kube').style.display="none";
		}*/
 $('#periody<?echo $arYears[$i];?>').click(function () {
	  
	 if( document.getElementById('periodtabl<?echo $arYears[$i];?>').style.display=="none")
	 {
		 document.getElementById('modal-shadowkube').style.display="block";
 document.getElementById('kube').style.display="block";
     document.getElementById('periodtabl<?echo $arYears[$i];?>').style.display="block";
	 $.ajax({
				type: "GET",
				url: "periodtabl.php",
				data: "id=<?echo $_GET['id'];?>&orgn=<?echo $_GET['orgn'];?>&y=<?echo $arYears[$i];?>",
				success: function(html){
					 $("#periodtabl<?echo $arYears[$i];?>").html(html);
                     if (window.initPeriodTableDragScroll) {
                         window.initPeriodTableDragScroll();
                     }
					 document.getElementById('modal-shadowkube').style.display="none";	
                     document.getElementById('kube').style.display="none";
				}
			});
     }
	 else
	 {
		document.getElementById('periodtabl<?echo $arYears[$i];?>').style.display="none"; 
	 }
    });
</script>
</div>
<?}
?>

</div>
<script>
(function() {
    if (!window.initPeriodTableDragScroll) {
        window.initPeriodTableDragScroll = function() {
            function isInteractive(node) {
                while (node && node !== document && node.nodeType === 1) {
                    var tag = node.tagName ? node.tagName.toLowerCase() : '';
                    if (tag === 'a' || tag === 'input' || tag === 'textarea' || tag === 'select' || tag === 'button' || tag === 'label') {
                        return true;
                    }
                    node = node.parentNode;
                }
                return false;
            }

            var scrolls = document.querySelectorAll('.period-table-scroll');
            for (var i = 0; i < scrolls.length; i++) {
                (function(scroll) {
                    if (scroll.getAttribute('data-drag-scroll-ready') === '1') {
                        return;
                    }
                    scroll.setAttribute('data-drag-scroll-ready', '1');

                    var isDown = false;
                    var wasDragged = false;
                    var startX = 0;
                    var scrollLeft = 0;

                    scroll.addEventListener('mousedown', function(e) {
                        if (e.button !== 0 || isInteractive(e.target)) {
                            return;
                        }
                        isDown = true;
                        wasDragged = false;
                        startX = e.pageX;
                        scrollLeft = scroll.scrollLeft;
                        scroll.className += ' is-dragging';
                    });

                    function stopDrag() {
                        isDown = false;
                        scroll.className = scroll.className.replace(' is-dragging', '');
                    }

                    scroll.addEventListener('mouseleave', stopDrag);
                    scroll.addEventListener('mouseup', stopDrag);
                    scroll.addEventListener('mousemove', function(e) {
                        if (!isDown) {
                            return;
                        }
                        var walk = e.pageX - startX;
                        if (Math.abs(walk) > 3) {
                            wasDragged = true;
                            e.preventDefault();
                            scroll.scrollLeft = scrollLeft - walk;
                        }
                    });
                    scroll.addEventListener('click', function(e) {
                        if (!wasDragged) {
                            return;
                        }
                        e.preventDefault();
                        e.stopPropagation();
                        wasDragged = false;
                    }, true);
                })(scrolls[i]);
            }
        };
    }

    window.initPeriodTableDragScroll();
})();
</script>
