<div >
<?php 

echo '';
$lis = "SELECT * FROM klient WHERE id =".$schet['lico'];
					$resultlis = mysql_query($lis);
					$personlis = mysql_fetch_array($resultlis);

echo'<p></p> 
<a onclick="konttakt()" style="
    right: 0;
    font-size: 13px;
    margin-top: -25px;
    cursor: pointer;
    color: #fff;
    position: absolute;
">Выбрать из списка <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a>

  


	';
	
		
					if(empty($schet['lico'])){
						echo '<h4 id="spisecho">Выберите из списка...</h4>';
					}
					echo '
					
					<select name="kontakti" class="form-control"  id="kontakti" onchange="konTakti(this.value)" style="';
					
					if(!empty($schet['lico'])){
						echo 'display:none;';
					}
					
					echo '">'; 
					echo '<option  value="0"></option>';
					$query2 = mysql_query("SELECT * from klient_ogrn WHERE idkli = '".$_GET['kli']."' ORDER BY id DESC");
					while($row2 = mysql_fetch_array($query2)) {
						$query3 = mysql_query("SELECT * from klient WHERE id = '".$row2['klient']."' ORDER BY id DESC");
						while($row3 = mysql_fetch_array($query3)) {
							echo '<option  value="'.$row3['id'].'">';
							echo $row3['fio']," (",$row3['dol'],":",$row3['tel'],")";
							echo '</option>';
						}
					}
					echo '<option  value="0"></option>';
					echo '</select>';	
					
					
					
				
	if(empty($schet['lico'])){
						echo '<h4 id="redecho">Введите данные нового клиента...</h4>';
	}
	echo '<div id="konactinfo"></div>
	
	
	<div class="form-group">
		<label class="col-sm-3 control-label">ФИО:</label>';
		 
			if(empty($schet['lico'])){
				echo '<div class="col-sm-9" id="fio"><input class="form-control col-90" id="name" type="text" placeholder="Петров Петр Петрович" aria-describedby="basic-addon1"></div>';
			}else{
				echo '<div class="col-sm-9" id="fio"><p style="font-size: 12pt;" class="form-control-static">'.$personlis['fio'].'</p></div>';
			}
		
	echo '</div>';
	echo '<div class="form-group">';
		
		
			if(empty($personlis['tel'])){
                echo '<label class="col-sm-3 control-label">Тел.:</label>
					<div class="col-sm-9" id="tel"><p class="form-control-static"style="color: #FFF;background-color: #1590d2; padding: 4px;float: left;font-size: 14pt;">'.$old1.' ('.$str05['name'].".  Время: ".$H.":".$i.') </p>
					<span style="padding: 8px; margin-top: -1px;" class="hidee btn btn-success glyphicon glyphicon-earphone"  id="callt"aria-hidden="true"></span>
					<span style="padding: 8px; margin-top: -1px; margin-left: -4px;" class="hidee btn btn-info glyphicon glyphicon-envelope" aria-hidden="true"></span>
					<span style="padding: 8px; margin-top: -1px; margin-left: -4px;" class="hidee btn btn-danger glyphicon glyphicon-trash" aria-hidden="true"></span>
					<a onclick="addbq()"><span style="padding: 8px; margin-top: -1px; margin-left: -4px;" class="hidee btn btn-warning glyphicon glyphicon-plus" aria-hidden="true"></span></a></div>';
			}else{
				echo '';
				$vowels = array("+", "(", ")", " ", "-", ",", "", "а", "б", "в", "г", "д", "е", "ё", "ж", "з", "и", "й", "к", "л", "м", "н", "о", "п", "р", "с", "т", "у", "ф", "х", "ц", "ч", "ш", "щ", "ъ", "ы", "ь", "э", "ю", "я", "А", "Б", "В", "Г", "Д", "Е", "Ё", "Ж", "З", "И", "Й", "К", "Л", "М", "Н", "О", "П", "Р", "С", "Т", "У", "Ф", "Х", "Ц", "Ч", "Ш", "Щ", "Ъ", "Ы", "Ь", "Э", "Ю", "Я");
				$old = str_replace($vowels, "", $personlis['tel']);
				$old1=substr($old,0,1)." (".substr($old,1,3).") ".substr($old,4,3)."-".substr($old,7,2)."-".substr($old,9,2);
				$str = str_replace($vowels, "", $personlis['tel']);
				$str2 = mb_strlen($str,'UTF-8')."\n";
				$alltele[]=$old;
				if($str2 == 11){
					$str00 = substr($str, 0, 1);
					$str01 = substr($str, 1, 2);
					$str02 = substr($str, 1, 3);
					$str03 = substr($str, 4, 10);
					$str04 = mysql_fetch_array(mysql_query("SELECT * FROM `mtt_codes` WHERE `def` ='".$str02."' AND `from` <= '".$str03."' AND `to` >= '".$str03."'"));
					$str05 = mysql_fetch_array(mysql_query("SELECT * FROM `mtt_regions` WHERE `id` ='".$str04['region']."'"));
					$s = $str05['tip'];  
					$i = gmdate('i', time() + 3*3600);
					$H = gmdate('H', time() + 3*3600) + $str05['zch'] - 3;
					echo '<label class="col-sm-3 control-label">Тел.:</label>
					<div class="col-sm-9" id="tel"><p ><div id="tele" class="form-control-static"style="color: #FFF;background-color: #1590d2; padding: 4px;float: left;font-size: 14pt;">'.$old1.' ('.$str05['name'].".  Время: ".$H.":".$i.')</div> </p>
					<span style="padding: 8px; margin-top: -1px;" class="hidee btn btn-success glyphicon glyphicon-earphone"  id="callt"aria-hidden="true"></span>
					<span style="padding: 8px; margin-top: -1px; margin-left: -4px;" class="hidee btn btn-info glyphicon glyphicon-envelope" aria-hidden="true"></span>
					<span style="padding: 8px; margin-top: -1px; margin-left: -4px;" class="hidee btn btn-danger glyphicon glyphicon-trash" aria-hidden="true"></span>
					<a onclick="addbq()"><span style="padding: 8px; margin-top: -1px; margin-left: -4px;" class="hidee btn btn-warning glyphicon glyphicon-plus" aria-hidden="true"></span></a></div>';
				
					
				}
				echo '';
			}
echo '		
	</div><div id="addbsq">
	</div>';
	
	echo '<div class="form-group">
		<label class="col-sm-3 control-label">Должность:</label>';
		 
			if(empty($personlis['dol'])){
				echo '<div class="col-sm-9" id="dol">
<select id="dolrr" class="form-control col-90">
  <option></option>
  <option>Сотрудник</option>
  <option>Заместитель</option>
  <option>Секретарь</option>
  <option>Бухгалтер</option>
  <option>Программист</option>
  <option>Юрист</option>
  <option>Другой</option>
</select></div>';
			}else{
				echo '<div class="col-sm-9" id="dol"><p style="font-size: 12pt" class="form-control-static">'.$personlis['dol'].'</p></div>';
			}
		
	echo '</div>';
	echo '<div class="form-group">
		<label class="col-sm-3 control-label">Е-mail:</label>';
		 
			if(empty($personlis['email'])){
				echo '<div class="col-sm-9" id="email"><input class="form-control col-90" id="email" type="text" placeholder="Петров Петр Петрович" aria-describedby="basic-addon1"></div>';
			}else{
				echo '<div class="col-sm-9" id="email"><p style="font-size: 12pt" class="form-control-static">'.$personlis['email'].'</p></div>';
			}
		
	echo '</div>';
	echo '
	<div class="form-group">
		<label class="col-sm-3 control-label">Пол:</label>';
		
			if(empty($personlis['pol'])){
				echo '<div class="col-sm-9"><div class="input-group">
<div id="fullname-gender">
<label class="sgt-granular_label" style="font-size: 13px; font-weight: normal;">
<input type="radio" name="pol" id="polrr" value="1" class="inline">
Мужской
</label>
<label class="sgt-granular_label" style="
font-size: 13px;
font-weight: normal;
">
<input type="radio" name="pol" id="fullname-gender-female" value="2" class="inline">
Женский
</label>
</div>
</div></div>';
			}else{
				if($personlis['pol'] == 1){
					echo '<div class="col-sm-9"><p style="font-size: 12pt"class="form-control-static">Мужской</p></div>';
				}else{
					echo '<div class="col-sm-9"><p style="font-size: 12pt"class="form-control-static">Женский</p></div>';
					
				}
			}
echo '		

<a onclick="fadress()" class="btn btn-success" id="newkli" style="display:none;float:right;">Создать клиента</a>
	</div>';
?>
<div id="robot" style="display: none;"> 
<p><h4>Контактное лицо <select class="">
  <option>Сотрудник</option>
  <option>Заместитель</option>
  <option>Секретарь</option>
  <option>Бухгалтер</option>
  <option>Программист</option>
  <option>Юрист</option>
  <option>Другой</option>
</select>

<div onclick="hide()" style="float: right;font-size: 13px;margin-top: 7px;  cursor: pointer;">Закрыть</div></h4> </p>
<div class="form-group">
<label class="col-sm-3 control-label">ФИО:</label> 
<div class="col-sm-9"><input class="form-control col-90" type="text" placeholder="Петров Петр Петрович" aria-describedby="basic-addon1"></div>
</div><div style="margin-top: 6px;"></div>
	<div class="form-group">
		<label class="col-sm-3 control-label">Тел:</label>
		<div class="col-sm-9">
					<div class="input-group col-90">
						<select id="str44" class="form-control" style="float: left;width: 30%;">
						<option value="str0">Рабочий</option>
						<option value="str1">Раб.прямой</option>
						<option value="str2">Мобильный</option>
						<option value="str3">Факс</option>
						<option value="str4">Городской</option>
						<option value="str5">Другй</option>
						</select>
							<input class="form-control" style="float: left;width: 70%;" type="tel" required pattern="[0-9_\-]{10}" placeholder="+7(___) ___ __ __" id="iuser_phone3" title="Формат: (096) 999 99 99">
						<span class="input-group-addon" id="basic-addon2">
							<a onclick="addb()"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span></a>
						</span>
					</div>
				</div>
	</div>
	<div id="addbs">
	</div>
	<div class="form-group"><label class="col-sm-3 control-label">E-mail:</label><div class="col-sm-9"><input class="form-control col-90" type="email" placeholder="" aria-describedby="basic-addon1" ></div>
	<div style="margin-top: 6px;"></div>
	<div class="form-group"><label class="col-sm-3 control-label">Пол:</label><div class="col-sm-9"><input class="form-control col-90" type="text" placeholder="" aria-describedby="basic-addon1" ></div>
</div>
</div>
</div>


</div>
<script>
		


function show(){
document.getElementById("robot").style.display="block";
}
function hide(){
document.getElementById("robot").style.display="none";
}   
 
function addb()
{
 var a = document.getElementById("addbs");
 var o = document.createElement("div");
 var b = document.createElement("label");
 var c = document.createTextNode("Тел:");
 var d = document.createElement("div");
 var j = document.createElement("div");
 var e = document.createElement("select");
 var f = document.createElement("input");
 var h = document.createElement("span");
 var is = document.createElement("span");
 a.appendChild(o);
 o.className = "form-group";
 
 for (var i = 0; i < 9999; i++) {
  o.id = ''+i;
}
 o.appendChild(b);
 b.className = "col-sm-3 control-label";
 b.appendChild(c);
 o.appendChild(d);
 d.className = "col-sm-9";
 d.appendChild(j);
 j.appendChild(h);
 h.appendChild(is);
 h.className = "input-group-addon";
 is.className = "glyphicon glyphicon-trash";
 is.id = "glyphicon glyphicon-plus";
 j.className = "input-group col-90";
 j.appendChild(e);
 e.className = "form-control";
 e.style.float="left";
 e.style.width="30%";
 e.options[0] = new Option("Рабочий", "str0");
 e.options[1] = new Option("Раб.прямой", "str1");
 e.options[2] = new Option("Мобильный", "str2");
 e.options[3] = new Option("Факс", "str3");
 e.options[4] = new Option("Городской", "str4");
 e.options[5] = new Option("Другой", "str5");
 j.appendChild(f);
 f.className = "form-control rfield";
 f.type = "tel";
 f.style.float="left";
 f.style.width="70%";

function randomInteger(min, max) {
  var rand = min + Math.random() * (max - min)
  rand = Math.round(rand);
  return rand;
}
var s = new Date();
var azazatoha = randomInteger(10, 99) +""+ s.getSeconds();
 f.id += "user_phone" + azazatoha;
 e.id += "euserphone" + azazatoha;
	 f.pattern = "[0-9_\\-]{10}";
 f.required;
 
(function($){
	jQuery.fn.exists = function() {
	   return jQuery(this).length;
	}
$(function() {
if(!is_mobile()){
for (var i = 0; i < 9999; i++) {
			if($('#user_phone' + i).exists()){
			$('#user_phone' + i).each(function(){
			  $(this).mask("9(999) 999-99-99");
			});
			$('#user_phone' + i)
			  .addClass('rfield')
			  .removeAttr('required')
			  .removeAttr('pattern')
			  .removeAttr('title')
			  .attr({'placeholder':'8(123) 456-78-90'});
			}
}
		$(document).ready(function(){
		for (var i = 0; i < 9999; i++) {
			$("#euserphone"+i).click(function(){
			
				if(document.getElementById('euserphone'+i).value == "str4" || document.getElementById('euserphone').value == "str3"){
				
					if($('#user_phone' + i).exists()){
					$('#user_phone' + i).each(function(){
					  $(this).mask("9(9999) 99-99-99");
					});
					$('#user_phone' + i)
					  .addClass('rfield')
					  .removeAttr('required')
					  .removeAttr('pattern')
					  .removeAttr('title')
					  .attr({'placeholder':'8(1234) 56-78-90'});
				  }
				  
				}else{
					if($('#user_phone' + i).exists()){
					$('#user_phone' + i).each(function(){
					  $(this).mask("9(999) 999-99-99");
					});
					$('#user_phone' + i)
					  .addClass('rfield')
					  .removeAttr('required')
					  .removeAttr('pattern')
					  .removeAttr('title')
					  .attr({'placeholder':'8(123) 456-78-90'});
				  }
				  }
			
			});
		}
		});
	  
	}
});
})( jQuery );



}


    //////////////////////////////////////////////////////////////////////////////////////////////////////////

function addbq()
{
 var a = document.getElementById("addbsq");
 var o = document.createElement("div");
 var b = document.createElement("label");
 var c = document.createTextNode("Тел:");
 var d = document.createElement("div");
 var j = document.createElement("div");
 var e = document.createElement("select");
 var f = document.createElement("input");
 var h = document.createElement("span");
 var z = document.createElement("span");
 var is = document.createElement("span");
 var id = document.createElement("span");
 a.appendChild(o);
 o.className = "form-group";
 o.appendChild(b);
 b.className = "col-sm-3 control-label";
 b.appendChild(c);
 o.appendChild(d);
 d.className = "col-sm-9";
 d.appendChild(j);
 j.appendChild(h);
 h.appendChild(is);
 h.className = "input-group-addon";
 is.className = "glyphicon glyphicon-trash";
 j.className = "input-group col-90";
 j.appendChild(e);
 e.className = "form-control";
 e.style.float="left";
 e.style.width="30%";
 e.options[0] = new Option("Рабочий", "str0");
 e.options[1] = new Option("Раб.прямой", "str1");
 e.options[2] = new Option("Мобильный", "str2");
 e.options[3] = new Option("Факс", "str3");
 e.options[4] = new Option("Городской", "str4");
 e.options[5] = new Option("Другой", "str5");
 j.appendChild(f);
 f.className = "form-control rfield";
 f.type = "tel";
 f.style.float="left";
 f.style.width="70%";
 j.appendChild(z);
 z.appendChild(id);
 z.className = "input-group-addon";
 id.className = "glyphicon glyphicon-ok ";

function randomInteger(min, max) {
  var rand = min + Math.random() * (max - min)
  rand = Math.round(rand);
  return rand;
}
var s = new Date();
var azazatoha = randomInteger(10, 99) +""+ s.getSeconds();
 f.id += "user_phone" + azazatoha;
 e.id += "euserphone" + azazatoha;
	 f.pattern = "[0-9_\\-]{10}";
 f.required;
 
 is.id = "trasha"+azazatoha;
 o.id = "trashdiv"+azazatoha;
$("#trasha"+azazatoha).click(function() {
  document.getElementById("trashdiv"+azazatoha).style.display="none";
});
 
(function($){
	jQuery.fn.exists = function() {
	   return jQuery(this).length;
	}
$(function() {
if(!is_mobile()){
for (var i = 0; i < 9999; i++) {
			if($('#user_phone' + i).exists()){
			$('#user_phone' + i).each(function(){
			  $(this).mask("9(999) 999-99-99");
			});
			$('#user_phone' + i)
			  .addClass('rfield')
			  .removeAttr('required')
			  .removeAttr('pattern')
			  .removeAttr('title')
			  .attr({'placeholder':'8(123) 456-78-90'});
			}
}
		$(document).ready(function(){
		for (var i = 0; i < 9999; i++) {
			$("#euserphone"+i).click(function(){
			
				if(document.getElementById('euserphone'+i).value == "str4" || document.getElementById('euserphone').value == "str3"){
				
					if($('#user_phone' + i).exists()){
					$('#user_phone' + i).each(function(){
					  $(this).mask("9(9999) 99-99-99");
					});
					$('#user_phone' + i)
					  .addClass('rfield')
					  .removeAttr('required')
					  .removeAttr('pattern')
					  .removeAttr('title')
					  .attr({'placeholder':'8(1234) 56-78-90'});
				  }
				  
				}else{
					if($('#user_phone' + i).exists()){
					$('#user_phone' + i).each(function(){
					  $(this).mask("9(999) 999-99-99");
					});
					$('#user_phone' + i)
					  .addClass('rfield')
					  .removeAttr('required')
					  .removeAttr('pattern')
					  .removeAttr('title')
					  .attr({'placeholder':'8(123) 456-78-90'});
				  }
				  }
			
			});
		}
		});
	  
	}
});
})( jQuery );



}


    //////////////////////////////////////////////////////////////////////////////////////////////////////////
	// 	<div class="form-group">                                                                            //
	//	<label class="col-sm-3 control-label">Тел:</label>                                                  //
	//	<?php                                                                                               //
	//		if(empty($klient['27'])){                                                                       //
	//			echo '<div class="col-sm-9">                                                                //
	//				<div class="input-group col-90">                                                        //
	//					<select  class="form-control" style="float: left;width: 30%;">                      //
	//						<option>Рабочий</option>                                                        //
	//						<option>Раб.прямой</option>                                                     //
	//						<option>Мобильный</option>                                                      //
	//						<option>Факс</option>                                                           //
	//						<option>Городской</option>                                                      //
	//						<option>Другой</option>                                                         //
	//					</select>                                                                           //
	//					<input class="form-control" style="float: left;width: 70%;" type="tel" required     //
	//					pattern="[0-9_\-]{10}" placeholder="+7(___) ___ __ __" id="user_phone3"              //
	//                  title="Формат: (096) 999 99 99">                                                    //
	//					<span class="input-group-addon" id="basic-addon2"><a onclick="addb()">              //
	//                  <span class="glyphicon glyphicon-plus" aria-hidden="true"></span></a></span>        //
	//				</div>                                                                                  //
	//			</div>';                                                                                    //
	//		}else{                                                                                          //
	//			echo '<div class="col-sm-9"><p class="form-control-static">'.$klient['27'].'</p></div>';    //
	//		}                                                                                               //
	//	?>                                                                                                  //
	//</div>                                                                                                //
	//////////////////////////////////////////////////////////////////////////////////////////////////////////
	
	
	
	(function( $ ){
	
	//// ---> Проверка на существование элемента на странице
	jQuery.fn.exists = function() {
	   return jQuery(this).length;
	}
	
	//	Phone Mask
$(function() {
if(!is_mobile()){
 for (var i = 0; i < 9999; i++) {
      if($('#iuser_phone' + i).exists()){
        $('#iuser_phone' + i).each(function(){
          $(this).mask("9(999) 999-99-99");
        });
        $('#iuser_phone' + i)
          .addClass('rfield')
          .removeAttr('required')
          .removeAttr('pattern')
          .removeAttr('title')
          .attr({'placeholder':'8(123) 456-78-90'});
      }
      }
 $(document).ready(function()
{
 $("#str44").click(function()
{
 if(document.getElementById('str44').value == "str4" || document.getElementById('str44').value == "str3"){
 for (var i = 0; i < 9999; i++) {
      if($('#iuser_phone' + i).exists()){
        $('#iuser_phone' + i).each(function(){
          $(this).mask("9(9999) 99-99-99");
        });
        $('#iuser_phone' + i)
          .addClass('rfield')
          .removeAttr('required')
          .removeAttr('pattern')
          .removeAttr('title')
          .attr({'placeholder':'8(1234) 56-78-90'});
      }
      }
      }else{
	  for (var i = 0; i < 9999; i++) {
	        if($('#iuser_phone' + i).exists()){
        $('#iuser_phone' + i).each(function(){
          $(this).mask("9(999) 999-99-99");
        });
        $('#iuser_phone' + i)
          .addClass('rfield')
          .removeAttr('required')
          .removeAttr('pattern')
          .removeAttr('title')
          .attr({'placeholder':'8(123) 456-78-90'});
      }
	  }
	  }
	  });
	  });
    
} 
	});

})( jQuery );


	
</script><div  id="addkont" style="display: none;float: right;font-size: 13px;margin-top: 7px;  cursor: pointer;" onclick="show()">Добавить ещё</div>
<?php

?>
<script>
		$("#name").keyup(function() {
			document.getElementById("newkli").style.display="block";
		});
		$("#newkli").click(function() {
					var namer = $( "#name" ).val();
					var telwr = $( "#user_phone2" ).val();
					var email = $( "#email" ).val();
					var dolrr = $( "#dolrr" ).val();
					var polrr = $( "#polrr" ).val();
				$.ajax({
					type: "GET",
					url: "pusya.php",
					data: "tip=addkontakt&kli=<?php echo $_GET['kli']; ?>&ogrn=<?php echo $_GET['ogrn']; ?>&fio=" + namer + "&tel=" + telwr + "&email=" + email + "&dol=" + dolrr + "&pol=" + polrr + "",
					success: function(msg){
					}
				});
		});
		
		function konttakt(){
			document.getElementById("kontakti").style.display="block";
			document.getElementById("addkont").style.display="block";
		}
		function konTakti(str) {
			if (str=="0") {
				$.ajax({
					type: "GET",
					url: "pusya.php",
					data: "lico="+str+"&tip=konttakt&rand=<?php echo $_GET['rand']; ?>",
					success: function(msg){
						var obj = jQuery.parseJSON(msg);
						$("#fio").html(obj.fio);
						$("#tele").html(obj.tel);
						$("#dol").html(obj.dol);
						$("#email").html(obj.email);
						// //document.getElementById("redecho").style.display="none";
						// document.getElementById("spisecho").style.display="none";
						// document.getElementById("kontakti").style.display="none";
						// document.getElementById("addkont").style.display="none";
					}
				});
			} else {
				$.ajax({
					type: "GET",
					url: "pusya.php",
					data: "lico="+str+"&tip=konttakt&rand=<?php echo $_GET['rand']; ?>",
					success: function(msg){
						var obj = jQuery.parseJSON(msg);
						$("#fio").html(obj.fio);
						$("#tele").html(obj.tel);
						$("#dol").html(obj.dol);
						$("#email").html(obj.email);
						//document.getElementById("redecho").style.display="none";
						// document.getElementById("spisecho").style.display="none";
						// document.getElementById("kontakti").style.display="none";
						// document.getElementById("addkont").style.display="none";
					 }
				});
			}
		}


	</script>
