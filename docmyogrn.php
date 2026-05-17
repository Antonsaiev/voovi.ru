<strong><h4 style="margin-top: 10px; font-weight: bold; border-bottom: 1px #333 solid;">Выберите документы в наличии</h4></strong>
<form action="<?php $_SERVER['PHP_SELF']?>" method="post">
<table style="width: 100%; background: #fff;"  class="table tabli">
<?php
$query3 = mysql_query("SELECT * from proddoc WHERE produkt = '$_GET[parent]'");	
while($row3 = mysql_fetch_array($query3)) {
$query2 = mysql_query("SELECT * from doki WHERE id =".$row3['document']);	
while($row2 = mysql_fetch_array($query2)) {
echo '<tr class="highlight_row"';
			$resultq = mysql_query("SELECT count(*) from erte WHERE  produkt = '$_GET[parent]' AND document  =".$row2['id']);
			$classq = mysql_result($resultq, 0);
				if ($classq > 0) { 
					echo 'class="highlight_success"';
				}
echo '>';
echo '<td style="width: 1px;">';
echo '<input type="checkbox" value="'.$row2['id'].'"';
if ($classq < 0) { 
echo 'checked';
}echo'>';
echo '</td>';
echo '<td>';
echo $row2['name'];
echo '</td>';
echo '</tr>';
}
}
?>
</table>
</form>
 <script>
$(document).ready(function () {
    $('.tabli tr').click(function (event) {
        if (event.target.type !== 'checkbox') {
            $(':checkbox', this).trigger('click');
        }
    });

    $(".tabli tr input[type='checkbox']").change(function (e) {
        if ($(this).is(":checked")) {
            $(this).closest('tr').addClass("highlight_success").removeClass("highlights_row");
			$.ajax({
			   type: "GET",
			   url: "prodd.php",
			   data: "id=1&schet=<?php echo $shetrand; ?>&ogrn=<?php echo $_GET['id']; ?>&doki="+$(this).closest("input[type='checkbox']").val(),
			   success: function(msg){
			   }
			});
        } else {
            $(this).closest('tr').removeClass("highlight_success").addClass("highlights_row");
			$.ajax({
			   type: "GET",
			   url: "prodd.php",
			   data: "id=0&schet=<?php echo $shetrand; ?>&ogrn=<?php echo $_GET['id']; ?>&doki="+$(this).closest("input[type='checkbox']").val(),
			   success: function(msg){
				alert(msg);
			   }
			});
        }
    });
});
 </script>