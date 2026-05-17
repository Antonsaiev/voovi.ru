<?php
define ('DB_HOST', 'localhost');
define ('DB_LOGIN', 'shoes');  
define ('DB_PASSWORD', 'ShoesOpt');
define ('DB_NAME', 'shoes');
mysql_connect(DB_HOST, DB_LOGIN, DB_PASSWORD) or die ("MySQL Error: " . mysql_error());
mysql_query("set names utf8") or die ("<br>Invalid query: " . mysql_error()); 
mysql_select_db(DB_NAME) or die ("<br>Invalid query: " . mysql_error());
?>  
  <tr>
<td></td>
  <td>
  <form method="POST" action="" class="">
  <select class="form-control input-sm"name="sort">
   <option selected=""></option>
  <?php $r = mysql_query("SELECT * FROM kind_of_shoes"); while($res = mysql_fetch_assoc($r))  : ?>
  <option value="<?php echo $res['code']?>"><?php echo htmlspecialchars($res['name']);?></option>
  <?php endwhile; ?>
  </select>
  </form>
  </td>
  <td></td>
  <td></td>
  <td>
  <form method="POST" action="" class="">
  <select class="form-control input-sm"name="sort">
   <option selected=""></option>
  <?php $r = mysql_query("SELECT * FROM size"); while($res = mysql_fetch_assoc($r))  : ?>
  <option value="<?php echo $res['code']?>"><?php echo htmlspecialchars($res['sieof']);?></option>
  <?php endwhile; ?>
  </select>
  </form>
  </td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
  <td>
<div class="row text-center">
<i class="fas fa-edit"></i>
<i class="far fa-copy"></i>
<i class="far fa-trash-alt"></i>
</div>
</td>
  </tr>