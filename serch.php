  <link rel="stylesheet" href="module/serch/serch.css">
  <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <script src="https://code.jquery.com/ui/1.12.0/jquery-ui.js"></script>
  <script>
  $( function() {
    var availableTags = [
      <?php
	  $query = mysql_query("SELECT * FROM `class_okp` WHERE `isset`='1' AND `node_count` = '0'");	
		while($row = mysql_fetch_array($query)) {
			echo "'".$row['name']."',"; 
		}
	  ?>
    ];
    $( "#tags" ).autocomplete({
    minChars: 2, // Минимальная длина запроса для срабатывания автозаполнения
    delimiter: /(,|;)\s*/, // Разделитель для нескольких запросов, символ или регулярное выражение
    maxHeight: 400, // Максимальная высота списка подсказок, в пикселях
    width: 300, // Ширина списка
    zIndex: 9999, // z-index списка
    deferRequestBy: 99, // Задержка запроса (мсек), на случай, если мы не хотим слать миллион запросов, пока пользователь печатает. Я обычно ставлю 300.
    params: { country: 'Yes'}, // Дополнительные параметры
    onSelect: function(data, value){ }, // Callback функция, срабатывающая на выбор одного из предложенных вариантов,
    source: availableTags // Список вариантов для локального автозаполнения
    });
  } );
  </script>
<?
echo '<div class="block-serch">';
echo '<form class="">';
echo '<div class="container">';
echo '<div class="form-serch">';
    echo '
<dl class="dl-horizontal">
  <dt><span class="glyphicon glyphicon-search " aria-hidden="true"></span> Ключевое слово</dt>
  <dd>
	<div class="input-group col-md-12">';
      echo '
		<div class="ui-widget">
		  <input id="tags"  ng-model="query" type="text" class="form-control-100" placeholder="Введите название или категорию...">
		</div>';
    echo '</div><!-- /input-group -->
	</dd>  
	</dl>
	<dl class="dl-horizontal">
<dt><span class="glyphicon glyphicon-map-marker" aria-hidden="true"></span> <span class="none767px">Регион</span></dt>
  <dd>
<select class="form-control-100" id="vud">
                                                                <option value="0">Выбрать регион</option>
                                                            </select>
	</dd>
</dl>';
echo '<button class="btn btn-warning serch-bottom" type="button">Найти</button>';
echo '</div> <button type="button" class="btn btn-link float-right serch-btn-rash">Показать расширенный поиск</button>';
echo '</div>';

echo '</form>';

echo '</div>';
echo '<br>';
echo '<br>';

echo '<div class="container">
  <div class="row">
    <div class="col-md-12">
      <!--Body content-->';

/* ------------  Вывод из таблицы всех данных если i = '1' и соединить все похожие gr 
---------------  сортировать списох по id по убыванию. Разбить на страницы,
---------------  задать лимит по сколько в 1 странице показать ------------ */
$num = 999;
$page = $_GET['page'];
$result00 = mysql_query("SELECT COUNT(*) FROM data_prod");
$temp = mysql_fetch_array($result00);
$posts = $temp[0];
$total = (($posts - 1) / $num) + 1;
$total =  intval($total);
$page = intval($page);
if(empty($page) or $page < 0) $page = 1;
if($page > $total) $page = $total;
$start = $page * $num - $num;
// Проверяем нужны ли стрелки назад
if ($page != 1) $pervpage = '<a href=?page=1>Первая</a> | <a href=?page='. ($page - 1) .'>Предыдущая</a> | ';
// Проверяем нужны ли стрелки вперед
if ($page != $total) $nextpage = ' | <a href=?page='. ($page + 1) .'>Следующая</a> | <a href=?page=' .$total. '>Последняя</a>';
// Находим две ближайшие станицы с обоих краев, если они есть
if($page - 5 > 0) $page5left = ' <a href=?page='. ($page - 5) .'>'. ($page - 5) .'</a> | ';
if($page - 4 > 0) $page4left = ' <a href=?page='. ($page - 4) .'>'. ($page - 4) .'</a> | ';
if($page - 3 > 0) $page3left = ' <a href=?page='. ($page - 3) .'>'. ($page - 3) .'</a> | ';
if($page - 2 > 0) $page2left = ' <a href=?page='. ($page - 2) .'>'. ($page - 2) .'</a> | ';
if($page - 1 > 0) $page1left = '<a href=?page='. ($page - 1) .'>'. ($page - 1) .'</a> | ';
if($page + 5 <= $total) $page5right = ' | <a href=?page='. ($page + 5) .'>'. ($page + 5) .'</a>';
if($page + 4 <= $total) $page4right = ' | <a href=?page='. ($page + 4) .'>'. ($page + 4) .'</a>';
if($page + 3 <= $total) $page3right = ' | <a href=?page='. ($page + 3) .'>'. ($page + 3) .'</a>';
if($page + 2 <= $total) $page2right = ' | <a href=?page='. ($page + 2) .'>'. ($page + 2) .'</a>';
if($page + 1 <= $total) $page1right = ' | <a href=?page='. ($page + 1) .'>'. ($page + 1) .'</a>';

// Вывод меню если страниц больше одной
if ($total > 1){
	Error_Reporting(E_ALL & ~E_NOTICE);
	echo "<div class=\"pstrnav\">";
	echo $pervpage.$page5left.$page4left.$page3left.$page2left.$page1left.'<b>'.$page.'</b>'.$page1right.$page2right.$page3right.$page4right.$page5right.$nextpage;
	echo "</div>";
}

$query = mysql_query("SELECT * FROM data_prod ORDER BY id DESC LIMIT $start, $num");	
while($row = mysql_fetch_array($query)) {
$mysql_prod_okp = mysql_query("SELECT * FROM `class_okp` WHERE `code` LIKE '$row[prod_okp]'");
$row_prod_okp = mysql_fetch_array($mysql_prod_okp);
$mysql_data_ogrn = mysql_query("SELECT * FROM `data_ogrn` WHERE `id` = '$row[prod_ogrn]'");
$row_data_ogrn = mysql_fetch_array($mysql_data_ogrn);
$mysql_class_obiom = mysql_query("SELECT * FROM `class_obiom` WHERE `id` = '$row[obiom]'");
$row_class_obiom = mysql_fetch_array($mysql_class_obiom);
$mysql_class_tara = mysql_query("SELECT * FROM `class_tara` WHERE `id` = '$row[obiom]'");
$row_class_tara = mysql_fetch_array($mysql_class_tara);

	echo ' <div class="phones">
        <div class="panel panel-default" ng-repeat="phone in phones | filter:query" >
		  <div class="panel-body row">
		  <div class="col-md-6" style="white-space: nowrap;"><a class="text-warning cursor-pointer"> <span class="glyphicon glyphicon-star star" aria-hidden="true"></span></a> '.$row_prod_okp['name'].' "'.$row['brend'].'"</div>
		  <div class="col-md-1 text-right">'.$row['price'].'.00 <span class="glyphicon glyphicon-ruble" aria-hidden="true" style="font-size: 11px;"></span></div>
		  <div class="col-md-1 text-right">'.$row['emko'].' '.$row_class_obiom['name'].'</div>
		  <div class="col-md-1 text-right">'.$row['vupak'].' шт.</div>
		  <div class="col-md-1 text-right">'.$row_class_tara['name'].'</div>
		  <div class="col-md-2 text-right">';
		    $y = mb_substr(strstr($row['date'],"2"),0,4,'UTF-8'); 
			$m = mb_substr(strstr($row['date'],"2"),5,2,'UTF-8');
			$d = mb_substr(strstr($row['date'],"2"),8,2,'UTF-8');
			echo $d; 
			echo '.'.$m;
			echo '.'.$y.'</b>';
		  echo'</div>
           
		   
		  </div>
		  
          <div class="panel-footer">
		  '.$row_data_ogrn['value'].'
		<f6>'.$row_data_ogrn['data_address_value'].' <a><span class="glyphicon glyphicon-map-marker" aria-hidden="true" ></span> На карте</a></f6> 
		  </div> 
        </div>
      </div>';
}

// Вывод меню если страниц больше одной
if ($total > 1){
	Error_Reporting(E_ALL & ~E_NOTICE);
	echo "<div class=\"pstrnav\">";
	echo $pervpage.$page5left.$page4left.$page3left.$page2left.$page1left.'<b>'.$page.'</b>'.$page1right.$page2right.$page3right.$page4right.$page5right.$nextpage;
	echo "</div>";
}
// ----------------------------------------  Конец ---------------------------------------- //


    echo '</div>
  </div>
</div>';
?>