<?php
define ('DB_HOST', 'localhost');
define ('DB_LOGIN', 'shoes');  
define ('DB_PASSWORD', 'ShoesOpt');
define ('DB_NAME', 'shoes');
session_start();
$del=$_SESSION['del'];
if($del=='')
{
	$del='0';
}
mysql_connect(DB_HOST, DB_LOGIN, DB_PASSWORD) or die ("MySQL Error: " . mysql_error());
mysql_query("set names utf8") or die ("<br>Invalid query: " . mysql_error()); 
mysql_select_db(DB_NAME) or die ("<br>Invalid query: " . mysql_error());
?> 
 <?php $rr = mysql_query("SELECT product.id,product.number,product.kind,product.pol,product.art,product.size,product.country,product.brand,product.upper_material,product.lining_material,product.bottom_material,product.color,product.tnved,product.count,country_of_origin.country,country_of_origin.code_country,kind_of_shoes.code_kind,kind_of_shoes.kind,size.sieof,size.code_size,tnved.tnved,tnved.code_tnved FROM product inner join country_of_origin on product.country=country_of_origin.code_country inner join kind_of_shoes on product.kind=kind_of_shoes.code_kind inner join size on product.size=size.code_size inner join tnved on product.tnved=tnved.code_tnved and product.del='$del' ORDER by product.id asc "); while($res1 = mysql_fetch_assoc($rr))  : ?>
  <tr id="<?echo $res1['id'];?>" >
<td >
    <input class="form-control input-reg-gm input-lg" id="number<?echo $res1['id'];?>"value="<?echo $res1['number'];?>"/>
</td>
  <td>
  <form method="POST" action="" class="">
  <select class="form-control input-sm"name="sort"id="vid<?echo $res1['id'];?>" >
   <option selected="" value="<?php echo $res1['code_kind']?>"><?echo $res1['kind'];?></option>
  <?php $r = mysql_query("SELECT * FROM kind_of_shoes"); while($res = mysql_fetch_assoc($r))  : ?>
  <option   value="<?php echo $res['code_kind']?>"><?php echo htmlspecialchars($res['kind']);?></option>
  <?php endwhile; ?>
  </select>
  </form>
  </td>
  <td><input class="form-control input-reg-gm input-lg" id="pol<?echo $res1['id'];?>" value="<?echo $res1['pol'];?>"/></td>
  <td><input class="form-control input-reg-gm input-lg" id="art<?echo $res1['id'];?>" value="<?echo $res1['art'];?>"/></td>
  <td>
  <form method="POST" action="" class="">
  <select class="form-control input-sm"name="sort" id="razm<?echo $res1['id'];?>">
   <option selected="" value="<?php echo $res1['code_size']?>"><?echo $res1['sieof'];?></option>
  <?php $r = mysql_query("SELECT * FROM size"); while($res = mysql_fetch_assoc($r))  : ?>
  <option value="<?php echo $res['code_size']?>"><?php echo htmlspecialchars($res['sieof']);?></option>
  <?php endwhile; ?>
  </select>
  </form>
  </td>
      <td><input class="form-control input-reg-gm input-lg" id="brend<?echo $res1['id'];?>" value="<?echo $res1['brand'];?>"/></td>
  <td><input class="form-control input-reg-gm input-lg" id="mat_verh<?echo $res1['id'];?>" value="<?echo $res1['upper_material'];?>"/></td>
  <td><input class="form-control input-reg-gm input-lg" id="mat_pod<?echo $res1['id'];?>" value="<?echo $res1['lining_material'];?>"/></td>
  <td><input class="form-control input-reg-gm input-lg" id="mat_niz<?echo $res1['id'];?>" value="<?echo $res1['bottom_material'];?>"/></td>
      <td><input class="form-control input-reg-gm input-lg" id="color<?echo $res1['id'];?>" value="<?echo $res1['color'];?>"/></td>
  <td>
      <form method="POST" action="" class="">
          <select class="form-control input-sm"name="sort" id="tnved<?echo $res1['id'];?>">
              <option selected="" value="<?php echo $res1['code_tnved']?>"><?echo $res1['tnved'];?></option>
              <?php $r = mysql_query("SELECT * FROM tnved"); while($res = mysql_fetch_assoc($r))  : ?>
                  <option value="<?php echo $res['code_tnved']?>"><?php echo htmlspecialchars($res['tnved']);?></option>
              <?php endwhile; ?>
          </select>
      </form>
  </td>
      <td>
          <form method="POST" action="" class="">
              <select class="form-control input-sm"name="sort"id="contry<?echo $res1['id'];?>">
                  <option selected="" value="<?php echo $res1['code_country']?>"><?echo $res1['country'];?></option>
                  <?php $r = mysql_query("SELECT * FROM country_of_origin"); while($res = mysql_fetch_assoc($r))  : ?>
                      <option   value="<?php echo $res['code_country']?>"><?php echo htmlspecialchars($res['country']);?></option>
                  <?php endwhile; ?>
              </select>
          </form>
      </td>
  <td><input class="form-control input-reg-gm input-lg" id="count<?echo $res1['id'];?>" value="<?echo $res1['count'];?>"/></td>
  <td><label>Получить GTIN</label></td>
  <td><label>Получить КМ</label></td>
  <td class="row text-center">
<i id="<?echo $res1['id'];?>" class="fas fa-edit" onClick="e(this);"></i>
<i id="<?echo $res1['id'];?>" class="far fa-copy" onClick="co(this);"></i>
<i id="<?echo $res1['id'];?>" class="far fa-trash-alt"onClick="tr(this);"></i>
</td>
  </tr>
  <?php endwhile; ?>
