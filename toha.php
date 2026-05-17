<?php
  # Подключаем конфиг
  include 'conf.php';
  include 'invoice_action.php';

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
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "https://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="https://www.w3.org/1999/xhtml" xml:lang="ru" lang="ru">
<head>
<title></title>
<meta http-equiv="content-type" content="text/html" charset="utf-8" />
<link href="css/bootstrap.min.css" rel="stylesheet">
<!--<link rel="stylesheet" type="text/css" href="css/normalize.css" />
<link rel="stylesheet" type="text/css" href="css/demo.css" />
<link rel="stylesheet" type="text/css" href="css/component.css" /> -->


		<!--<script src="js/jquery.ba-throttle-debounce.min.js"></script>
		<script src="js/jquery.stickyheader.js"></script>-->
</head>
<body>
<?php
# шапка
include 'header.php';
?>

<div class="container" style="margin-top: 60px;">
<div class="row">
<div class="col-md-12">
<div id="scrollArea" class="clusterize-scroll">

 

<?php
$qrand = "SELECT sortir FROM `schet` ORDER BY sortir DESC";
					$resultrand = mysql_query($qrand);
					$personrand = mysql_fetch_array($resultrand);


$var = $personrand['sortir'] + 1;

$id = $_POST['id'];
if(isset($_POST['deletemarked'])){
	if($userdata['users_id'] != 20){
		if (empty($id) || $id == 0){
			echo 'Ошибка'.$id;
		}else{
			$impid = implode(", ",$id);
			$Qdelete = mysql_query("DELETE FROM schet WHERE del = '0' AND rand IN (".$impid.")") or die ("error in delete");
			if(isset($Qdelete))
			{
				echo "Успешно удален счет";
			}
		}
	}else{
		echo '<div class="alert alert-danger" role="alert"> <strong>Опасно!</strong> Вы не ответственный человек!!!</div>';
	}
}

if(isset($_POST['ingroup'])){
	if (empty($id) || $id == 0){
		echo 'Ошибка'.$id;
	}else{
		$impid = implode(", ",$id);
		$Qdelete = mysql_query("UPDATE `schet` SET `gr` =  '".rand(0,255).rand(0,255).rand(200,255)."', `groupi` =  'rgb(".rand(0,255).", ".rand(0,255).", ".rand(200,255).")', `sortir` =  '".$var."'  WHERE rand IN (".$impid.")") or die ("error in delete");
		if(isset($Qdelete))
		{
			echo "Успешно  ".$person."";
		}
	}
}

if(isset($_POST['ungroup'])){
	if (empty($id) || $id == 0){
		echo 'Ошибка'.$id;
	}else{
		$impid = implode(", ",$id);
		$Qdelete = mysql_query("UPDATE `schet` SET `gr` =  '0', `groupi` =  '' WHERE rand IN (".$impid.")") or die ("error in delete");
		if(isset($Qdelete))
		{
			echo "Успешно  ".$person."";
		}
	}
}

if(isset($_POST['doljen'])){
	if (empty($id) || $id == 0){
		echo 'Ошибка'.$id;
	}else{
		$impid = implode(", ",$id);
		$q47 = "SELECT * FROM schet WHERE del = '0' AND rand =".$impid;
		$result47 = mysql_query($q47);
		$person47 = mysql_fetch_array($result47);
		if($person47['doljen'] == 0){
			$Qdelete = mysql_query("UPDATE `schet` SET `doljen` =  '1', `sortir` =  '".$var."' WHERE rand IN (".$impid.")") or die ("error in delete");
			if(isset($Qdelete)){
				echo "Успешно  ".$person."";
			}
		}if($person47['doljen'] == 1){
			$Qdelete = mysql_query("UPDATE `schet` SET `doljen` =  '0' WHERE rand IN (".$impid.")") or die ("error in delete");
			if(isset($Qdelete)){
				echo "Успешно  ".$person."";
			}
		}
	}
}

if(isset($_POST['doljenop'])){
	if (empty($id) || $id == 0){
		echo 'Ошибка'.$id;
	}else{
		$impid = implode(", ",$id);
		$q47 = "SELECT * FROM schet WHERE del = '0' AND rand =".$impid;
		$result47 = mysql_query($q47);
		$person47 = mysql_fetch_array($result47);
		if($person47['doljenop'] == 0){
			$Qdelete = mysql_query("UPDATE `schet` SET `doljenop` =  '1', `sortir` =  '".$var."' WHERE rand IN (".$impid.")") or die ("error in delete");
			if(isset($Qdelete)){
				echo "Успешно  ".$person."";
			}
		}if($person47['doljenop'] == 1){
			$Qdelete = mysql_query("UPDATE `schet` SET `doljenop` =  '0' WHERE rand IN (".$impid.")") or die ("error in delete");
			if(isset($Qdelete)){
				echo "Успешно  ".$person."";
			}
		}
	}
}

if(isset($_POST['proveren'])){
	if (empty($id) || $id == 0){
		echo 'Ошибка'.$id;
	}else{
		$impid = implode(", ",$id);
		$q47 = "SELECT * FROM schet WHERE del = '0' AND rand =".$impid;
		$result47 = mysql_query($q47);
		$person47 = mysql_fetch_array($result47);
		if($person47['gotov'] == 0){
			$Qdelete = mysql_query("UPDATE `schet` SET `gotov` =  '1', `sortir` =  '".$var."' WHERE rand IN (".$impid.")") or die ("error in delete");
			if(isset($Qdelete)){
				echo "Успешно  ".$person."";
			}
		}if($person47['gotov'] == 1){
			$Qdelete = mysql_query("UPDATE `schet` SET `gotov` =  '0' WHERE rand IN (".$impid.")") or die ("error in delete");
			if(isset($Qdelete)){
				echo "Успешно  ".$person."";
			}
		}
	}
}
$message = $_POST['oplachen'];
if(isset($_POST['oplachen'])){
	if (empty($id) || $id == 0){
		echo 'Ошибка'.$id;
	}else{
		$dateop=date("Y-m-d");
		$impid = implode(", ",$id);
		$q47 = "SELECT * FROM schet WHERE del = '0' AND rand =".$impid;
		$result47 = mysql_query($q47);
		$person47 = mysql_fetch_array($result47);
		if($person47['oplachenks'] == 0){
			$Qdelete = mysql_query("UPDATE `schet` SET `oplachenks` =  '1' ,`date_op`='".$dateop."',`sortir` =  '".$var."' WHERE rand IN (".$impid.")");
			if(isset($Qdelete)){
				echo "Успешно  ".$person."";
			}
		}if($person47['oplachenks'] == 1){
			$Qdelete = mysql_query("UPDATE `schet` SET `oplachenks` =  '0',`date_op`='' WHERE rand IN (".$impid.")") or die ("error in delete");
			if(isset($Qdelete)){
				echo "Успешно  ".$person."";
			}
		}
	}
}

if(isset($_POST['akt'])){
    echo updateSchet($id);

}

if(isset($_POST['otk'])){
	if (empty($id) || $id == 0){
		echo 'Ошибка'.$id;
	}else{
		$impid = implode(", ",$id);
		$q47 = "SELECT * FROM schet WHERE del = '0' AND rand =".$impid;
		$result47 = mysql_query($q47);
		$person47 = mysql_fetch_array($result47);
		if($person47['otk'] == 0){
			$Qdelete = mysql_query("UPDATE `schet` SET `otk` =  '1', `sortir` =  '".$var."' WHERE rand IN (".$impid.")") or die ("error in delete");
			if(isset($Qdelete)){
				echo "Успешно  ".$person."";
			}
		}if($person47['otk'] == 1){
			$Qdelete = mysql_query("UPDATE `schet` SET `otk` =  '0' WHERE rand IN (".$impid.")") or die ("error in delete");
			if(isset($Qdelete)){
				echo "Успешно  ".$person."";
			}
		}
	}
}
if(isset($_POST['cher'])){
	if (empty($id) || $id == 0){
		echo 'Ошибка'.$id;
	}else{
		$impid = implode(", ",$id);
		$q47 = "SELECT * FROM schet WHERE del = '0' AND rand =".$impid;
		$result47 = mysql_query($q47);
		$person47 = mysql_fetch_array($result47);
		if($person47['cher'] == 0){
			$Qdelete = mysql_query("UPDATE `schet` SET `cher` =  '1', `sortir` =  '".$var."' WHERE rand IN (".$impid.")") or die ("error in delete");
			if(isset($Qdelete)){
				echo "Успешно  ".$person."";
			}
		}if($person47['cher'] == 1){
			$Qdelete = mysql_query("UPDATE `schet` SET `cher` =  '0' WHERE rand IN (".$impid.")") or die ("error in delete");
			if(isset($Qdelete)){
				echo "Успешно  ".$person."";
			}
		}
	}
}
if(isset($_POST['sertifikat'])){

    if (empty($id) || $id == 0){
        echo 'Ошибка'.$id;
    }else{
        $impid = implode(", ",$id);

        $q47 = "SELECT * FROM schet WHERE del = '0' AND rand =".$impid;
        $result47 = mysql_query($q47);
        $person47 = mysql_fetch_array($result47);
        if($person47['ust_sert'] == 0){
            $Qdelete = mysql_query("UPDATE `schet` SET `ust_sert` =  '1', `sortir` =  '".$var."' WHERE rand IN (".$impid.")") or die ("error in delete");
            if(isset($Qdelete)){
                echo "Успешно  ".$person."";
            }
        }
        if($person47['ust_sert'] == 1){

            $Qdelete = mysql_query("UPDATE `schet` SET `ust_sert` =  '0' WHERE rand IN (".$impid.")") or die ("error in delete");
            if(isset($Qdelete)){
                echo "Успешно  ".$person."";
            }
        }
    }
}

/*Контур*/
if(isset($_POST['kross'])){
    if (empty($id) || $id == 0){
        echo 'Ошибка'.$id;
    }else{
        $impid = implode(", ",$id);
        $q47 = "SELECT * FROM schet WHERE del = '0' AND rand =".$impid;
        $result47 = mysql_query($q47);
        $person47 = mysql_fetch_array($result47);
        if($person47['krossprod'] == 0){
            $Qdelete = mysql_query("UPDATE `schet` SET `krossprod` =  '1' , `sortir` =  '".$var."'WHERE rand IN (".$impid.")") or die ("error in delete");
            if(isset($Qdelete)){
                echo "Успешно  ".$person."";
            }
        }if($person47['krossprod'] == ""){
            $Qdelete = mysql_query("UPDATE `schet` SET `krossprod` =  '0' WHERE rand IN (".$impid.")") or die ("error in delete");
            if(isset($Qdelete)){
                echo "Успешно  ".$person."";
            }
        }
    }
}
if(isset($_POST['prodplus'])){
    if (empty($id) || $id == 0){
        echo 'Ошибка'.$id;
    }else{

        $impid = implode(", ",$id);

        $q47 = "SELECT * FROM schet WHERE del = '0' AND rand =".$impid;
        $result47 = mysql_query($q47);
        $person47 = mysql_fetch_array($result47);
        if($person47['prodplus'] == 0){
            $Qdelete = mysql_query("UPDATE `schet` SET `prodplus` =  '1', `sortir` =  '".$var."' WHERE rand IN (".$impid.")") or die ("error in delete");
            if(isset($Qdelete)){
                echo "Успешно  ".$person."";
            }
        }if($person47['prodplus'] == ""){
            $Qdeletet = mysql_query("UPDATE `schet` SET `prodplus` =  '0', `sortir` =  '".$var."'  WHERE rand IN (".$impid.")") or die ("error in delete");
            if(isset($Qdeletet)){
                echo "Успешно  ".$person."";
            }
        }
    }
}
if(isset($_POST['incoming'])){
    if (empty($id) || $id == 0){
        echo 'Ошибка'.$id;
    }else{
        $impid = implode(", ",$id);
        $q47 = "SELECT * FROM schet WHERE del = '0' AND rand =".$impid;
        $result47 = mysql_query($q47);
        $person47 = mysql_fetch_array($result47);
        if($person47['incoming'] == 0){
            $Qdelete = mysql_query("UPDATE `schet` SET `incoming` =  '1', `sortir` =  '".$var."' WHERE rand IN (".$impid.")") or die ("error in delete");
            if(isset($Qdelete)){
                echo "Успешно  ".$person."";
            }
        }if($person47['incoming'] == ""){
            $Qdelete = mysql_query("UPDATE `schet` SET `incoming` =  '0' WHERE rand IN (".$impid.")") or die ("error in delete");
            if(isset($Qdelete)){
                echo "Успешно  ".$person."";
            }
        }
    }
}
/*-----------*/
if(isset($_POST['postprod'])){
	if (empty($id) || $id == 0){
		echo 'Ошибка'.$id;
	}else{
		$impid = implode(", ",$id);
		$q47 = "SELECT * FROM schet WHERE del = '0' AND rand =".$impid;
		$result47 = mysql_query($q47);
		$person47 = mysql_fetch_array($result47);
		if($person47['postprod'] == 0){
			$Qdelete = mysql_query("UPDATE `schet` SET `postprod` =  '1', `sortir` =  '".$var."' WHERE rand IN (".$impid.")") or die ("error in delete");
			if(isset($Qdelete)){
				echo "Успешно  ".$person."";
			}
		}if($person47['postprod'] == 1){
			$Qdelete = mysql_query("UPDATE `schet` SET `postprod` =  '0' WHERE rand IN (".$impid.")") or die ("error in delete");
			if(isset($Qdelete)){
				echo "Успешно  ".$person."";
			}
		}
	}
}

$render_toha_search_in_top_nav = true;
include 'serchtoha.php';
?>
<script>
  var h_hght = 40; // высота шапки
  var h_mrg = 40; // отступ когда шапка уже не видна
  var userdata_id = <?php echo json_encode($userdata['users_id']); ?>;
  // var checkedValues = []; // массив для хранения выбранных значений
  $('#mySelect').val("");
  $(function(){
   $(window).scroll(function(){
  var top = $(this).scrollTop();
  var elem = $('#top_nav');
  if (top+h_mrg < h_hght) {
   elem.css('top', (h_hght-top));
  } else {
   elem.css('top', h_mrg);
  }
});
  });
  
  
  var count = 0;
$(function() {
    var $schetCheckboxes = $('input[type=checkbox][name="id[]"]');
    count = $schetCheckboxes.filter(':checked').length;
    displayCount();

    $schetCheckboxes.bind('click' , function(e, a) {

        // var value = $(this).val(); // значение текущего checkbox
        // var labelFor = "#date" + value;
        // console.log($(labelFor).parent().hide())
         if (this.checked) {

              count += a ? -1 : 1;
             // checkedValues.push(value); // добавляем значение в массив
         } else {
              count += a ? 1 : -1;
             // var index = checkedValues.indexOf(value);
             // if (index > -1) {
             //     checkedValues.splice(index, 1); // удаляем значение из массива
             //     }
         }
         displayCount();
    });
    $('#invert').click(function(e) {
         $schetCheckboxes.trigger('click', true)
    });
	$('#deleteol').click(function() {    
        $('#count').text(count); 
		count = 0;
        // checkedValues = []; // очищаем массив выбранных значений
		document.getElementById("countdisplay").style.display="none";
    });
    $('#mySelect').change(function() {
        var selectedValue = $(this).val(); // Получение выбранного значения
        // console.log("Выбранное значение: " + selectedValue);
        // Очистка массива
        checkedValues = [];

        // Проход по всем checkbox и проверка их состояния
        $schetCheckboxes.each(function() {
            if (this.checked) {
                var value = $(this).val();
                checkedValues.push(value);

            }
        });

        // Проход по всем значениям в массиве checkedValues
        for (var i = 0; i < checkedValues.length; i++) {
            var value = checkedValues[i];

            $.ajax({
                type: "GET",
                async: false, // Сделать запрос синхронным
                url: "getstatusschet.php",
                data: "status=" + selectedValue + "&schet=" + value + "&kto=" + userdata_id + "",
                success: function(msg) {
                    var labelFor = "#date" + value;
                    $(labelFor).parent().hide(); // скрываем родительский элемент
                }
            });

        }

        // Очищаем все чекбоксы
        $schetCheckboxes.prop('checked', false);

        $('#count').text(count);
        count = 0;
        document.getElementById("countdisplay").style.display="none";
        document.getElementById("mySelect").style.display="none";
        // Теперь в массиве checkedValues у вас все выбранные значения checkbox
        // console.log(checkedValues);
        checkedValues = []; // Очистка массива
        $(this).val(""); // default_value - это значение по умолчанию для вашего <select>
    });
	
});
  function displayCount() {
      $('#count').text(count);
      if(count == 0){
          document.getElementById("countdisplay").style.display="none";
          document.getElementById("mySelect").style.display="none";  // Скрываем select
      } else {
          document.getElementById("countdisplay").style.display="block";
          document.getElementById("mySelect").style.display="block";  // Показываем select
      }
      if (typeof syncTohaTableOffset === 'function') {
          syncTohaTableOffset();
      }
  }

	

</script>



<div id="top_nav" style="padding:8px 0px 3px 8px ;background: rgb(51, 51, 51);">
<input class="btn btn-info btn-xs" type="submit" name="ingroup" value="Сгруппировать" style="margin-bottom: 5px;float: left; border-bottom-right-radius: 0; border-top-right-radius: 0;" />
<input class="btn btn-info btn-xs" type="submit" name="ungroup" value="Разгруппировать" style="margin-right: 12px;margin-bottom: 5px;float: left; border-bottom-left-radius: 0; border-top-left-radius: 0;" />
<input  class="btn btn-danger btn-xs" type="submit" name="doljen" value="Должен" style="margin-right: 12px;margin-bottom: 5px;float: left;" />
<input  class="btn btn-danger btn-xs" type="submit" name="doljenop" value="Должен оплатить" style="margin-right: 12px;margin-bottom: 5px;float: left;" />
<?php if($userdata['otvetstven']==1){
echo '
<input  class="btn btn-warning btn-xs" type="submit" name="oplachen" value="Оплата" style="margin-right: 12px;margin-bottom: 5px;float: left;" />
<input  class="btn btn-successs btn-xs" type="submit" name="proveren" value="Готово" style="margin-right: 12px;margin-bottom: 5px;float: left;" />
<input class="btn btn-success btn-xs" type="submit" name="akt" value="В архив" style="margin-right: 12px;margin-bottom: 5px;float: left;" />
<input class="btn btn-success btn-xs" type="submit" name="sertifikat" value="Установка сертификата" style="margin-right: 12px;margin-bottom: 5px;float: left;" />

';}
?>
<!--<input class="btn btn-gavno btn-xs" type="submit" name="cher" value="Оказались" style="margin-right: 12px;margin-bottom: 5px;float: left;" />!-->
<!--<input class="btn btn-gavno btn-xs" type="submit" name="otk" value="В черновики" style="margin-right: 12px;margin-bottom: 5px;float: left;" />!-->
    <input class="btn btn-xs" type="submit" name="kross" value="Кросы" style="background: #fff;margin-bottom: 5px;" />
    <input class="btn btn-xs" type="submit" name="prodplus" value="Проделние +" style="background: #fff;margin-bottom: 5px;" />
    <input class="btn btn-xs" type="submit" name="incoming" value="Входящие" style="background: #fff;margin-bottom: 5px;" />
    <input class="btn btn-default btn-xs" type="reset" id="deleteol" value="Убрать отмеченые" style="margin-right: 12px;margin-bottom: 5px;float: left;" />
<!--    --><?php //if($userdata['otvetstven']==1){echo '<input onclick=\'return confirm("Для удаления счета нажмите OK!");\' class="btn btn-primary btn-xs" type="submit" name="deletemarked" value="Удалить" style="margin-bottom: 5px;" />';}?>
    <!-- Общий div для элементов count и select -->
    <div id="countAndSelect" style="display: flex; align-items: center;">
        <p id="countdisplay" style="color:#fff; display:none;">Выбранно: <b style="font-size:15px;" id="count"></b></p>
        <!-- Добавленный элемент select -->
        <select id="mySelect" style="display: none; margin-left: 10px;">
            <option value="" disabled selected hidden>Выберите для смены статуса счетов</option>  <!-- Подсказывающая опция -->
            <!-- PHP часть для загрузки опций из SQL -->
            <?php

            $sql = "SELECT * FROM status WHERE del = 0 and uslugi =".$userdata['inogrn']; //
            $m_q_result = mysql_query($sql);

            if (!empty($m_q_result)) {
                while($row = mysql_fetch_array($m_q_result)) {
                    echo "<option value='" . $row['id'] . "'>" . $row['name'] . "</option>";
                }
            } else {
                echo "<option value=''>Нет данных</option>";
            }
            ?>
        </select>
    </div>
    <div class="toha-top-search">
        <input type="text" id="texts" class="toha-top-search-input" value="<?php echo htmlspecialchars(isset($_GET['inn']) ? $_GET['inn'] : '', ENT_QUOTES, 'UTF-8'); ?>" placeholder="Введите название, N или ИНН">
        <button type="button" id="tohaTopSearchButton" class="btn btn-xs toha-top-search-button">Поиск</button>
    </div>
</div>
<script type="text/javascript">
    function runTohaTopSearch() {
        var searchValue = $.trim($('#texts').val());
        var currentParams = window.location.search ? window.location.search.substring(1).split('&') : [];
        var nextParams = [];
        for (var i = 0; i < currentParams.length; i++) {
            if (!currentParams[i]) {
                continue;
            }
            var key = currentParams[i].split('=')[0];
            if (decodeURIComponent(key) !== 'inn') {
                nextParams.push(currentParams[i]);
            }
        }
        if (searchValue !== '') {
            nextParams.push('inn=' + encodeURIComponent(searchValue));
        }
        window.location.href = window.location.pathname + (nextParams.length ? '?' + nextParams.join('&') : '');
    }

    $(function() {
        syncTohaTableOffset();
        $(window).resize(function() {
            syncTohaTableOffset();
        });
        $('#tohaTopSearchButton').click(function() {
            runTohaTopSearch();
        });
        $('#texts').keydown(function(event) {
            if (event.which === 13) {
                event.preventDefault();
                runTohaTopSearch();
            }
        });
        setTimeout(syncTohaTableOffset, 250);
    });

    function syncTohaTableOffset() {
        var topNavHeight = $('#top_nav').outerHeight() || 0;
        document.documentElement.style.setProperty('--toha-top-nav-offset', (topNavHeight + 18) + 'px');
    }
</script>
<div id="status" class="toha-status-line"></div>
<?php 

if(isset($_GET['groupi'])){
if($_GET['groupi']==0){
$groupi="";
}else{
$groupi="gr != '$_GET[groupi]'";
}}else{
$groupi="inn != '$_GET[inn]'";
}
if(isset($_GET['akt'])){
$getakt=" AND schet.akt = '$_GET[akt]'";
}else{
$getakt=" AND schet.akt != '1'";
}

if(isset($_GET['akt_date'])){
$akt_date="AND schet.akt_date = '$_GET[akt_date]'";
}else{
$akt_date="";
}
if(isset($_GET['gen_date'])){
$gen_date="AND schet.gen_date = '$_GET[gen_date]'";
}else{
$gen_date="";
}
if (isset($_GET['oplachenks'])){
$oplachenks = "AND (schet.oplachenks = '1' OR schet.oplachen = '1') AND schet.gotov = '0'  AND schet.akt = '0'   AND schet.doki = '$_GET[oplachenks]' ";
}
if (isset($_GET['oplachen'])){
$oplachen = "AND schet.oplachenks = '1' AND schet.status = ''";
}
if(isset($_GET['gotov'])){
$gotov="AND schet.gotov = '$_GET[gotov]'";
}else{
$gotov="";
}
if($_GET['goroddd']== '1' ){
$goroddd="AND goroddd > '0'";
}
if($_GET['goroddd']== '0' ){
$goroddd="AND (goroddd = '0' OR goroddd = '')";
}
if(isset($_GET['neoplachen'])){
$neoplachen=" AND oplachenks != '1' AND oplachen != '1' AND gotov = '0'";
}else{
$neoplachen="";
}
if(isset($_GET['turbo'])){
$turbo="AND turbo = '$_GET[turbo]'";
}else{
$turbo="";
}
if(isset($_GET['status'])){
$status="AND schet.status = '$_GET[status]'";
}else{
$status="";
}
if(isset($_GET['moy'])){
$moy="AND schet.kto = '".$userdata['users_id']."'";
}else{
$moy="";
}
if(isset($_GET['postavka'])){
$postavka="AND nomerdog = '$_GET[postavka]'";
}else{
$postavka="";
}
if(isset($_GET['m'])){
$postavka="AND schet.m = '$_GET[m]' AND schet.y = '$_GET[y]'";
}else{
$postavka="";
}
if(isset($_GET['doljen'])){
$doljen="AND schet.doljen = '1'";
$getakt="";
}else{
$doljen="";
}
if(isset($_GET['doljenop'])){
$doljenop="AND schet.doljenop = '1'";
$getakt="";
}else{
$doljenop="";
}
if($_GET['generac'] == '0'){
$generac="AND schet.generac = '0'";
}if($_GET['generac'] == '1'){
$generac="AND schet.generac != '0'";
}if(!isset($_GET['generac'])){
$generac="";
}if($_GET['generac'] > 9999){
$generac="AND schet.generac = '$_GET[generac]'";
}
if(isset($_GET['otk'])){
$otk="AND schet.otk = '$_GET[otk]'";
}else{
$otk="AND schet.otk = '0'";
}
if(isset($_GET['cher'])){
$cher="AND schet.cher = '$_GET[cher]'";
}else{
$cher="AND schet.cher = '0'";
}
if(isset($_GET['sertifikat'])){
    $ust_sert="AND schet.ust_sert = '$_GET[sertifikat]' ";
    $getakt="";
}else{
    $ust_sert="";
}
/*Контур*/
if(isset($_GET['krossprod'])){
    $krossprod="AND schet.krossprod = '$_GET[krossprod]'";
    $getakt="";
}else{
    $krossprod="";
}
if(isset($_GET['prodplus'])){
    $prodplus="AND schet.prodplus = '$_GET[prodplus]'";
    $getakt="";
}else{
    $prodplus="";
}
if(isset($_GET['incoming'])){
    $incoming="AND schet.incoming = '$_GET[incoming]'";
    $getakt="";
}else{
    $incoming="";
}
/*---*/
if(isset($_GET['postprod'])){
$postprod="AND schet.postprod = '1'";
$getakt="";
}else{
$postprod="";
}
if(isset($_GET['y'])){
$y="AND y = '$_GET[y]'";
}else{
$y=""; 
}
if(isset($_GET['m'])){
$m="AND m = '$_GET[m]'";
}else{
$m=""; 
}
if(isset($_GET['d'])){
$d="AND d = '$_GET[d]'";
}else{
$d=""; 
}

if(isset($_GET['noprice'])){
$noprice="AND noprice = '$_GET[noprice]'";
}

?>
<div class="toha-table-shell">
<table class="table tablehover rowclick toha-schet-table" id="rowclick2">
<thead>
<tr>
<th style="width: 1px;"><span class="glyphicon glyphicon-ok" aria-hidden="true"></span></th>
<th style="width: 1px;"><span class="glyphicon glyphicon-random" aria-hidden="true"></span></th>
<th style="width: 1px;">№</th>
<th style="width: 1px;">Дата</th>
<th style="width: 1px;">ИНН</th>
<th style="width: 1px;">КПП</th>
<th style="width: 180px; text-align: center;">Название</th>
<th style="width: 90px; text-align: center;">Продукт</th>
<th >Комментарии</th>
<th style="width: 1px;"><span class="glyphicon glyphicon-star"></span></th>
<th style="width: 1px;"><span class="glyphicon glyphicon-ruble" aria-hidden="true"></span></th>
<!--<th style="width: 1px;"><span class="glyphicon glyphicon-open-file" aria-hidden="true"></span></th>!-->
<th style="width: 8px; text-align: center;">Контур</span></th>
<th style="width: 8px; text-align: center;">S</th>
<th style="width: 80px; text-align: center;">Услуга</th>
<th style="width: 80px; text-align: center;">Выставил</th>
<th style="width: 80px; text-align: center;">Агент</th>
<th style="width: 1px;">Исполнитель</th>
<th style="width: 1px;"></th>
<th style="width: 1px;">Дата продления</th>
</tr>
</thead>
<tbody id="contentArea" class="clusterize-content">
<?php $iz = 1;
$query = mysql_query("SELECT DISTINCT 

schet.ns,schet.status,kolichschet,d,m,y,nomerschet,otl3,nomerschetks,ogrn,prodlen,generac,name,lico,doljenop,rand,otdel,filial,god,nomerdog,schet.data,produkt,price,schet.kto,inn,kpp,idkli,goroddd,akt_date,otk,cher,ust_sert,krossprod,
prodplus,incoming,postprod,koment,oplachen,
oplachenks,priceks,doljen,gotov,akt,url,groupi,install,gr,agent,data_napom,b.data

FROM schet 
LEFT JOIN (
    SELECT c.*
    FROM schet_status c
    INNER JOIN (
        SELECT schet_status.schet, MAX(id) AS max_id
        FROM schet_status
        GROUP BY schet_status.schet
    ) d ON c.schet = d.schet AND c.id = d.max_id
) b ON schet.rand = b.schet

WHERE del = '0' AND $groupi $getakt $turbo $gotov $status $goroddd $akt_date $gen_date $postavka $generac $oplachenks $doljenop $oplachen $neoplachen $doljen $otk $cher $ust_sert $krossprod $prodplus $incoming $postprod $moy $y $m $d ORDER BY STR_TO_DATE(b.data, '%d.%m.%Y; %H:%i'), CAST(CONCAT(y, '-', m, '-', d) AS DATE) asc ");

while($row = mysql_fetch_array($query)) {
//    echo "<script>console.log('Row:', " . json_encode($row) . ");</script>";

		$udosrpod = "SELECT * FROM produkti WHERE id =".$row['produkt'];
	$udosresultrpod = mysql_query($udosrpod);
	$udospersonrpod = mysql_fetch_array($udosresultrpod);
	
if($userdata['inogrn'] != 89097565645){
	if($udospersonrpod['parent'] == $userdata['inogrn']) {
		include 'inctoha.php'; 
	}
}else{
	$udos = mysql_query("SELECT * FROM users_access WHERE users = '".$userdata['users_id']."' AND uslugi = '".$udospersonrpod['parent']."'");
	while($udostup = mysql_fetch_array($udos)) {
		include 'inctoha.php'; 
	}
}
	 

}
?>
</tbody>
</table>
</div>
</form>
<div class="modal-shadow"></div>
<div class="modal-window">
  <div class="close">X</div>
    <form class="contact_form" action="izm_date.php" method="POST">
	  <p>
	  <label for="schet">Счет№</label>
      <input type="text" name="schet" id="menafis" placeholder="" required>
	</p>   
	<p>
	  <label for="date">Новая дата</label>
      <input type="date" name="date" id="date" placeholder="Выберите дату" required>
	</p>
	<p>
	<p>
	  <button>Изменить дату</button>
	</p>
	</form>
</div>

</div>
</div>

<div class="col-md-12">
<?php
# подвал
include 'footer.php';
?>
<br>
</div>
</div>
</div><script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>

</body>
</html>
