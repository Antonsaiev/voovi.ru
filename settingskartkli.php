<?php
# подключаем конфиг
include 'conf.php';


$q = "SELECT * FROM ogrn WHERE id =$_GET[id]";
		$result = mysql_query($q);
		$person = mysql_fetch_array($result);

$qq = "SELECT * from tekkli WHERE inn = '".$person['inn']."' AND kpp = '".$person['kpp']."'";
		$resultt = mysql_query($qq);
		$personn = mysql_fetch_array($resultt);
		
$qqq = mysql_query("SELECT DISTINCT nomerschet,otdel,filial,god,nomerdog,data,produkt,price,priceks,kto,rand FROM schet WHERE del = '0' AND ogrn = '".$person['ogrn']."'");
$perq = mysql_fetch_array($qqq);



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
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ru" lang="ru">
<head>
	<title></title>
	<meta http-equiv="content-type" content="text/html" charset="utf-8" />
	<meta http-equiv="Content-Style-Type" content="text/css" />
	<meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="chosen/chosen.css" rel="stylesheet">
    <link rel="shortcut icon" href="/favicon.ico">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
<script src="chosen/chosen.jquery.js"></script>
<style>
    .chosen-container {
        border-radius: 0;
        font-size: 16px; /* Размер шрифта */
        min-width: 200px !important;
    }

    .chosen-results {
        max-height: 100px; /* Максимальная высота списка */
        overflow-y: auto; /* Полоса прокрутки */
    }

    .chosen-container .chosen-drop {
        border-bottom: 0;
        border-top: 1px solid #aaa;
        top: auto;
        bottom: 40px;
    }

    /* Убираем закругление у поля поиска внутри выпадающего списка */
    .chosen-container .chosen-search input[type="text"] {
        border-radius: 0px !important;
    }
    .chosen-container-single .chosen-single {
        height: 40px; /* или другая желаемая высота */
        padding-top: 0px; /* отрегулируйте для вертикального центрирования */
        padding-bottom: 0px; /* отрегулируйте для вертикального центрирования */
        line-height: 40px; /* чтобы текст оставался вертикально по центру */
    }
    label {
        margin-right: 10px; /* Добавление отступа справа */
    }
    #product_label, #model_label, #identifier_label {
        margin-left: 10px;
    }
    #position, #identifier {
        margin-right: 20px; /* Установите желаемый размер отступа */
    }
    .form-select.custom-select {
        background: #ffffff;
        border: 1px solid #cccccc;
        box-shadow: inset 0 1px 2px rgba(0,0,0,.075); /* Тень внутри */
        border-radius: 4px; /* Скругление углов, как в Chosen */
        height: 40px; /* Высота */
        /* Другие стили, если нужно */
    }
    .flex-container {
        display: flex;
        align-items: center; /* Выравнивание по вертикали */
    }
    .flex-container .btn-success {
        width: 200px;
        padding: 12px 24px;
        margin-left: 20px;
        border-radius: 5px; /* Скругление углов */
    }

    .form-select.custom-select:focus {
        border-color: #66afe9; /* Цвет границы при фокусе */
        outline: 0; /* Убрать стандартный outline */
        box-shadow: inset 0 1px 2px rgba(0,0,0,.075), 0 0 8px rgba(102,175,233,.6); /* Тень при фокусе */
    }


</style>

</head>
<body>
<div class="container" style="margin-top: 20px;">
<div class="row">

<div class="col-md-12">
<div class="modal-header">
    <h4 class="modal-title" id="myModalLabel">Управление картой клиента.</h4>
</div>
</div>
<div class="col-md-12">
<div style="margin-top:15px;">
<?php 
include 'conf.php';  
$pav = "SELECT * FROM schet WHERE del = '0' AND rand = $_GET[id]";
$pavresult = mysql_query($pav);
$pavoir = mysql_fetch_array($pavresult);
//echo "<script>console.log('" . json_encode($pavoir) . "');</script>";


//$uslugiresult = mysql_query("SELECT * FROM `produkti` WHERE `id` = " . $pavoir['produkt'] . " ");
//$uslugi = mysql_fetch_array($uslugiresult);
//echo "<script>console.log('" . $uslugi['parent']. "');</script>";

$sch = "SELECT DISTINCT nomerschet,data,d,m,y,oferta,prodlen,generac,name,lico,rand,otdel,filial,god,nomerdog,data,produkt,price,kto,inn,kpp,koment,ogrn,skidka,podpisantv FROM schet WHERE del = '0' AND rand ='".$_GET['id']."'  ORDER BY id DESC";
$schetresult = mysql_query($sch);
$schet = mysql_fetch_array($schetresult);

$kli = "SELECT * FROM ogrn WHERE ogrn =".$schet['ogrn'];
$kliresult = mysql_query($kli);
$klient = mysql_fetch_array($kliresult);

$sav1 = "SELECT * FROM produkti WHERE id = '".$schet['produkt']."' ORDER BY id DESC";
$savresult1 = mysql_query($sav1);
$savoir1 = mysql_fetch_array($savresult1);

$sav = "SELECT * FROM uslugi WHERE id = '".$savoir1['parent']."' ORDER BY id DESC";
$savresult = mysql_query($sav);
$savoir = mysql_fetch_array($savresult);

$freeSelector = false;
// echo "<script>console.log('" . $savoir1['parent']. "');</script>";
if(isset($_POST['poshel'])){
    if ($_POST['tip'] == '0') {$name = 'Клиент'; $freeSelector = true;} elseif ($_POST['tip'] == '00') {$name = 'СКБ Контур'; $freeSelector = true;} else {
        echo "<script>console.log('" . json_encode($_POST) . "');</script>";
        $uslugi = mysql_query("SELECT * FROM `uslugi` WHERE `uslugi`.`id` = ". $_POST['tip'] ." ");
        $uslugiResult = mysql_fetch_array($uslugi);

        $vendorProduct = mysql_query("SELECT * FROM `vendor_product` INNER JOIN product_model ON product_model.id=vendor_product.product_model WHERE vendor_product.`id` = ". $_POST['identifier'] ." ");
        $vendorProductResult = mysql_fetch_array($vendorProduct);
        $addJurnalskzi = "INSERT INTO `jurnalskzi`(`schet`, `name`, `tip`, `kay`, `product_model`, `vendor_product`) VALUES ('".$_GET['id']."','".$vendorProductResult['name']."','".$uslugiResult['name']."','".$vendorProductResult['barcode']."','".$vendorProductResult['2']."','".$vendorProductResult['0']."')";
        mysql_query($addJurnalskzi) or die(mysql_error($linkoldkoment));
    }
    if ($freeSelector) {
        $oldkomment = "INSERT INTO `jurnalskzi`(`schet`, `name`, `tip`, `kay`) VALUES ('".$_GET['id']."','".$_POST['name']."','".$name."','".$_POST['kay']."')";
        mysql_query($oldkomment) or die(mysql_error($linkoldkoment));
    }

//    header("Location: ".$_SERVER['HTTP_REFERER']);
} else if (isset($_POST['add_product'])){

    $vendorProduct = mysql_query("SELECT * FROM `vendor_product` INNER JOIN `product_model` ON product_model.id=vendor_product.product_model INNER JOIN `product_type` ON product_model.product_type=product_type.id WHERE vendor_product.`id` = ". $_POST['identifier'] ." ");
    $vendorProductResult = mysql_fetch_array($vendorProduct);
    //echo "<script>console.log('subtotal:" . $vendorProductResult['subtotal']. "');</script>";

    $summVenodProductQuery = mysql_query("SELECT * FROM `vendor_product` WHERE `id` = ".$vendorProductResult['0']." ");
    $venodProductQuery = mysql_fetch_array($summVenodProductQuery);


    //echo "<script>console.log('summ vendor ". $vendorProductResult['summ'] ."');</script>";
    if (empty($pavoir['priceks'])) {$nowSummSkbKontur = $vendorProductResult['summ'];} else {$nowSummSkbKontur = $pavoir['priceks'] + $vendorProductResult['summ'];}

    if ($vendorProductResult['subtotal'] === '1') {
    $koment = "UPDATE `schet` SET `priceks`='".$nowSummSkbKontur."' WHERE  `del` = '0' AND `rand` ='".$_GET['id']."' ";
    mysql_query($koment) or die(mysql_error($link)); }


    // echo "<script>console.log('schet:" . $_GET['id']. "');</script>";
    // echo "<script>console.log('tip:" . $savoir['name']. "');</script>";
    // echo "<script>console.log('schet_id:" . $_POST['position']. "');</script>";
    // echo "<script>console.log('name:" . $vendorProductResult['name']. "');</script>";
    // echo "<script>console.log('kay:" . $vendorProductResult['barcode']. "');</script>";
    // echo "<script>console.log('product_model:" . $vendorProductResult['2'] . "');</script>";
    // echo "<script>console.log('vendor_product:" . $vendorProductResult['0'] . "');</script>";
    // echo "<script>console.log('identifier:" . $_POST['identifier']. "');</script>";
    echo "<script>console.log('position" . $_POST['position'] . "');</script>";
    $addJurnalskzi = "INSERT INTO `jurnalskzi`(`schet`, `name`, `tip`, `kay`, `product_model`, `schet_id`, `vendor_product`) VALUES ('".$_GET['id']."','".$vendorProductResult['name']."','".$savoir['name']."','".$vendorProductResult['barcode']."','".$vendorProductResult['2']."','".$_POST['position']."','".$vendorProductResult['0']."')";
    mysql_query($addJurnalskzi) or die(mysql_error($linkoldkoment));
    //header("Location: ".$_SERVER['HTTP_REFERER']);
}



$fsdhhgf = "SELECT * FROM ogrn WHERE ogrn =".$pavoir['ogrn'];
$rfsdhhgf = mysql_query($fsdhhgf);
$pfsdhhgf = mysql_fetch_array($rfsdhhgf);
$dir = "doc/".$_GET['kli']."/";
$dir2 = "doc/".$_GET['kli']."/".$_GET['id']."/";
mkdir ($dir, 0777);   
mkdir ($dir2, 0777);
$str = '<html xmlns:v="urn:schemas-microsoft-com:vml"
xmlns:o="urn:schemas-microsoft-com:office:office"
xmlns:w="urn:schemas-microsoft-com:office:word"
xmlns:x="urn:schemas-microsoft-com:office:excel"
xmlns:m="http://schemas.microsoft.com/office/2004/12/omml"
xmlns="http://www.w3.org/TR/REC-html40">

<head>
<meta http-equiv=Content-Type content="text/html; charset=windows-utf8">
<meta name=ProgId content=Word.Document>
<meta name=Generator content="Microsoft Word 14">
<meta name=Originator content="Microsoft Word 14">
<link rel=File-List href="1111111111111111111111111111.files/filelist.xml">
<!--[if gte mso 9]><xml>
 <o:DocumentProperties>
  <o:Author>rs602</o:Author>
  <o:Template>Normal</o:Template>
  <o:LastAuthor>rs602</o:LastAuthor>
  <o:Revision>2</o:Revision>
  <o:TotalTime>1559</o:TotalTime>
  <o:LastPrinted>2015-02-05T07:56:00Z</o:LastPrinted>
  <o:Created>2015-02-05T11:49:00Z</o:Created>
  <o:LastSaved>2015-02-05T11:49:00Z</o:LastSaved>
  <o:Pages>1</o:Pages>
  <o:Words>73</o:Words>
  <o:Characters>417</o:Characters>
  <o:Lines>3</o:Lines>
  <o:Paragraphs>1</o:Paragraphs>
  <o:CharactersWithSpaces>489</o:CharactersWithSpaces>
  <o:Version>14.00</o:Version>
 </o:DocumentProperties>
 <o:OfficeDocumentSettings>
  <o:AllowPNG/>
 </o:OfficeDocumentSettings>
</xml><![endif]-->
<link rel=themeData href="1111111111111111111111111111.files/themedata.thmx">
<link rel=colorSchemeMapping
href="1111111111111111111111111111.files/colorschememapping.xml">
<!--[if gte mso 9]><xml>
 <w:WordDocument>
  <w:View>Print</w:View>
  <w:SpellingState>Clean</w:SpellingState>
  <w:GrammarState>Clean</w:GrammarState>
  <w:TrackMoves>false</w:TrackMoves>
  <w:TrackFormatting/>
  <w:PunctuationKerning/>
  <w:ValidateAgainstSchemas/>
  <w:SaveIfXMLInvalid>false</w:SaveIfXMLInvalid>
  <w:IgnoreMixedContent>false</w:IgnoreMixedContent>
  <w:AlwaysShowPlaceholderText>false</w:AlwaysShowPlaceholderText>
  <w:DoNotPromoteQF/>
  <w:LidThemeOther>RU</w:LidThemeOther>
  <w:LidThemeAsian>X-NONE</w:LidThemeAsian>
  <w:LidThemeComplexScript>X-NONE</w:LidThemeComplexScript>
  <w:Compatibility>
   <w:BreakWrappedTables/>
   <w:SnapToGridInCell/>
   <w:WrapTextWithPunct/>
   <w:UseAsianBreakRules/>
   <w:DontGrowAutofit/>
   <w:SplitPgBreakAndParaMark/>
   <w:EnableOpenTypeKerning/>
   <w:DontFlipMirrorIndents/>
   <w:OverrideTableStyleHps/>
  </w:Compatibility>
  <m:mathPr>
   <m:mathFont m:val="Cambria Math"/>
   <m:brkBin m:val="before"/>
   <m:brkBinSub m:val="&#45;-"/>
   <m:smallFrac m:val="off"/>
   <m:dispDef/>
   <m:lMargin m:val="0"/>
   <m:rMargin m:val="0"/>
   <m:defJc m:val="centerGroup"/>
   <m:wrapIndent m:val="1440"/>
   <m:intLim m:val="subSup"/>
   <m:naryLim m:val="undOvr"/>
  </m:mathPr></w:WordDocument>
</xml><![endif]--><!--[if gte mso 9]><xml>
 <w:LatentStyles DefLockedState="false" DefUnhideWhenUsed="true"
  DefSemiHidden="true" DefQFormat="false" DefPriority="99"
  LatentStyleCount="267">
  <w:LsdException Locked="false" Priority="0" SemiHidden="false"
   UnhideWhenUsed="false" QFormat="true" Name="Normal"/>
  <w:LsdException Locked="false" Priority="9" SemiHidden="false"
   UnhideWhenUsed="false" QFormat="true" Name="heading 1"/>
  <w:LsdException Locked="false" Priority="9" QFormat="true" Name="heading 2"/>
  <w:LsdException Locked="false" Priority="9" QFormat="true" Name="heading 3"/>
  <w:LsdException Locked="false" Priority="9" QFormat="true" Name="heading 4"/>
  <w:LsdException Locked="false" Priority="9" QFormat="true" Name="heading 5"/>
  <w:LsdException Locked="false" Priority="9" QFormat="true" Name="heading 6"/>
  <w:LsdException Locked="false" Priority="9" QFormat="true" Name="heading 7"/>
  <w:LsdException Locked="false" Priority="9" QFormat="true" Name="heading 8"/>
  <w:LsdException Locked="false" Priority="9" QFormat="true" Name="heading 9"/>
  <w:LsdException Locked="false" Priority="39" Name="toc 1"/>
  <w:LsdException Locked="false" Priority="39" Name="toc 2"/>
  <w:LsdException Locked="false" Priority="39" Name="toc 3"/>
  <w:LsdException Locked="false" Priority="39" Name="toc 4"/>
  <w:LsdException Locked="false" Priority="39" Name="toc 5"/>
  <w:LsdException Locked="false" Priority="39" Name="toc 6"/>
  <w:LsdException Locked="false" Priority="39" Name="toc 7"/>
  <w:LsdException Locked="false" Priority="39" Name="toc 8"/>
  <w:LsdException Locked="false" Priority="39" Name="toc 9"/>
  <w:LsdException Locked="false" Priority="35" QFormat="true" Name="caption"/>
  <w:LsdException Locked="false" Priority="10" SemiHidden="false"
   UnhideWhenUsed="false" QFormat="true" Name="Title"/>
  <w:LsdException Locked="false" Priority="1" Name="Default Paragraph Font"/>
  <w:LsdException Locked="false" Priority="11" SemiHidden="false"
   UnhideWhenUsed="false" QFormat="true" Name="Subtitle"/>
  <w:LsdException Locked="false" Priority="22" SemiHidden="false"
   UnhideWhenUsed="false" QFormat="true" Name="Strong"/>
  <w:LsdException Locked="false" Priority="20" SemiHidden="false"
   UnhideWhenUsed="false" QFormat="true" Name="Emphasis"/>
  <w:LsdException Locked="false" Priority="59" SemiHidden="false"
   UnhideWhenUsed="false" Name="Table Grid"/>
  <w:LsdException Locked="false" UnhideWhenUsed="false" Name="Placeholder Text"/>
  <w:LsdException Locked="false" Priority="1" SemiHidden="false"
   UnhideWhenUsed="false" QFormat="true" Name="No Spacing"/>
  <w:LsdException Locked="false" Priority="60" SemiHidden="false"
   UnhideWhenUsed="false" Name="Light Shading"/>
  <w:LsdException Locked="false" Priority="61" SemiHidden="false"
   UnhideWhenUsed="false" Name="Light List"/>
  <w:LsdException Locked="false" Priority="62" SemiHidden="false"
   UnhideWhenUsed="false" Name="Light Grid"/>
  <w:LsdException Locked="false" Priority="63" SemiHidden="false"
   UnhideWhenUsed="false" Name="Medium Shading 1"/>
  <w:LsdException Locked="false" Priority="64" SemiHidden="false"
   UnhideWhenUsed="false" Name="Medium Shading 2"/>
  <w:LsdException Locked="false" Priority="65" SemiHidden="false"
   UnhideWhenUsed="false" Name="Medium List 1"/>
  <w:LsdException Locked="false" Priority="66" SemiHidden="false"
   UnhideWhenUsed="false" Name="Medium List 2"/>
  <w:LsdException Locked="false" Priority="67" SemiHidden="false"
   UnhideWhenUsed="false" Name="Medium Grid 1"/>
  <w:LsdException Locked="false" Priority="68" SemiHidden="false"
   UnhideWhenUsed="false" Name="Medium Grid 2"/>
  <w:LsdException Locked="false" Priority="69" SemiHidden="false"
   UnhideWhenUsed="false" Name="Medium Grid 3"/>
  <w:LsdException Locked="false" Priority="70" SemiHidden="false"
   UnhideWhenUsed="false" Name="Dark List"/>
  <w:LsdException Locked="false" Priority="71" SemiHidden="false"
   UnhideWhenUsed="false" Name="Colorful Shading"/>
  <w:LsdException Locked="false" Priority="72" SemiHidden="false"
   UnhideWhenUsed="false" Name="Colorful List"/>
  <w:LsdException Locked="false" Priority="73" SemiHidden="false"
   UnhideWhenUsed="false" Name="Colorful Grid"/>
  <w:LsdException Locked="false" Priority="60" SemiHidden="false"
   UnhideWhenUsed="false" Name="Light Shading Accent 1"/>
  <w:LsdException Locked="false" Priority="61" SemiHidden="false"
   UnhideWhenUsed="false" Name="Light List Accent 1"/>
  <w:LsdException Locked="false" Priority="62" SemiHidden="false"
   UnhideWhenUsed="false" Name="Light Grid Accent 1"/>
  <w:LsdException Locked="false" Priority="63" SemiHidden="false"
   UnhideWhenUsed="false" Name="Medium Shading 1 Accent 1"/>
  <w:LsdException Locked="false" Priority="64" SemiHidden="false"
   UnhideWhenUsed="false" Name="Medium Shading 2 Accent 1"/>
  <w:LsdException Locked="false" Priority="65" SemiHidden="false"
   UnhideWhenUsed="false" Name="Medium List 1 Accent 1"/>
  <w:LsdException Locked="false" UnhideWhenUsed="false" Name="Revision"/>
  <w:LsdException Locked="false" Priority="34" SemiHidden="false"
   UnhideWhenUsed="false" QFormat="true" Name="List Paragraph"/>
  <w:LsdException Locked="false" Priority="29" SemiHidden="false"
   UnhideWhenUsed="false" QFormat="true" Name="Quote"/>
  <w:LsdException Locked="false" Priority="30" SemiHidden="false"
   UnhideWhenUsed="false" QFormat="true" Name="Intense Quote"/>
  <w:LsdException Locked="false" Priority="66" SemiHidden="false"
   UnhideWhenUsed="false" Name="Medium List 2 Accent 1"/>
  <w:LsdException Locked="false" Priority="67" SemiHidden="false"
   UnhideWhenUsed="false" Name="Medium Grid 1 Accent 1"/>
  <w:LsdException Locked="false" Priority="68" SemiHidden="false"
   UnhideWhenUsed="false" Name="Medium Grid 2 Accent 1"/>
  <w:LsdException Locked="false" Priority="69" SemiHidden="false"
   UnhideWhenUsed="false" Name="Medium Grid 3 Accent 1"/>
  <w:LsdException Locked="false" Priority="70" SemiHidden="false"
   UnhideWhenUsed="false" Name="Dark List Accent 1"/>
  <w:LsdException Locked="false" Priority="71" SemiHidden="false"
   UnhideWhenUsed="false" Name="Colorful Shading Accent 1"/>
  <w:LsdException Locked="false" Priority="72" SemiHidden="false"
   UnhideWhenUsed="false" Name="Colorful List Accent 1"/>
  <w:LsdException Locked="false" Priority="73" SemiHidden="false"
   UnhideWhenUsed="false" Name="Colorful Grid Accent 1"/>
  <w:LsdException Locked="false" Priority="60" SemiHidden="false"
   UnhideWhenUsed="false" Name="Light Shading Accent 2"/>
  <w:LsdException Locked="false" Priority="61" SemiHidden="false"
   UnhideWhenUsed="false" Name="Light List Accent 2"/>
  <w:LsdException Locked="false" Priority="62" SemiHidden="false"
   UnhideWhenUsed="false" Name="Light Grid Accent 2"/>
  <w:LsdException Locked="false" Priority="63" SemiHidden="false"
   UnhideWhenUsed="false" Name="Medium Shading 1 Accent 2"/>
  <w:LsdException Locked="false" Priority="64" SemiHidden="false"
   UnhideWhenUsed="false" Name="Medium Shading 2 Accent 2"/>
  <w:LsdException Locked="false" Priority="65" SemiHidden="false"
   UnhideWhenUsed="false" Name="Medium List 1 Accent 2"/>
  <w:LsdException Locked="false" Priority="66" SemiHidden="false"
   UnhideWhenUsed="false" Name="Medium List 2 Accent 2"/>
  <w:LsdException Locked="false" Priority="67" SemiHidden="false"
   UnhideWhenUsed="false" Name="Medium Grid 1 Accent 2"/>
  <w:LsdException Locked="false" Priority="68" SemiHidden="false"
   UnhideWhenUsed="false" Name="Medium Grid 2 Accent 2"/>
  <w:LsdException Locked="false" Priority="69" SemiHidden="false"
   UnhideWhenUsed="false" Name="Medium Grid 3 Accent 2"/>
  <w:LsdException Locked="false" Priority="70" SemiHidden="false"
   UnhideWhenUsed="false" Name="Dark List Accent 2"/>
  <w:LsdException Locked="false" Priority="71" SemiHidden="false"
   UnhideWhenUsed="false" Name="Colorful Shading Accent 2"/>
  <w:LsdException Locked="false" Priority="72" SemiHidden="false"
   UnhideWhenUsed="false" Name="Colorful List Accent 2"/>
  <w:LsdException Locked="false" Priority="73" SemiHidden="false"
   UnhideWhenUsed="false" Name="Colorful Grid Accent 2"/>
  <w:LsdException Locked="false" Priority="60" SemiHidden="false"
   UnhideWhenUsed="false" Name="Light Shading Accent 3"/>
  <w:LsdException Locked="false" Priority="61" SemiHidden="false"
   UnhideWhenUsed="false" Name="Light List Accent 3"/>
  <w:LsdException Locked="false" Priority="62" SemiHidden="false"
   UnhideWhenUsed="false" Name="Light Grid Accent 3"/>
  <w:LsdException Locked="false" Priority="63" SemiHidden="false"
   UnhideWhenUsed="false" Name="Medium Shading 1 Accent 3"/>
  <w:LsdException Locked="false" Priority="64" SemiHidden="false"
   UnhideWhenUsed="false" Name="Medium Shading 2 Accent 3"/>
  <w:LsdException Locked="false" Priority="65" SemiHidden="false"
   UnhideWhenUsed="false" Name="Medium List 1 Accent 3"/>
  <w:LsdException Locked="false" Priority="66" SemiHidden="false"
   UnhideWhenUsed="false" Name="Medium List 2 Accent 3"/>
  <w:LsdException Locked="false" Priority="67" SemiHidden="false"
   UnhideWhenUsed="false" Name="Medium Grid 1 Accent 3"/>
  <w:LsdException Locked="false" Priority="68" SemiHidden="false"
   UnhideWhenUsed="false" Name="Medium Grid 2 Accent 3"/>
  <w:LsdException Locked="false" Priority="69" SemiHidden="false"
   UnhideWhenUsed="false" Name="Medium Grid 3 Accent 3"/>
  <w:LsdException Locked="false" Priority="70" SemiHidden="false"
   UnhideWhenUsed="false" Name="Dark List Accent 3"/>
  <w:LsdException Locked="false" Priority="71" SemiHidden="false"
   UnhideWhenUsed="false" Name="Colorful Shading Accent 3"/>
  <w:LsdException Locked="false" Priority="72" SemiHidden="false"
   UnhideWhenUsed="false" Name="Colorful List Accent 3"/>
  <w:LsdException Locked="false" Priority="73" SemiHidden="false"
   UnhideWhenUsed="false" Name="Colorful Grid Accent 3"/>
  <w:LsdException Locked="false" Priority="60" SemiHidden="false"
   UnhideWhenUsed="false" Name="Light Shading Accent 4"/>
  <w:LsdException Locked="false" Priority="61" SemiHidden="false"
   UnhideWhenUsed="false" Name="Light List Accent 4"/>
  <w:LsdException Locked="false" Priority="62" SemiHidden="false"
   UnhideWhenUsed="false" Name="Light Grid Accent 4"/>
  <w:LsdException Locked="false" Priority="63" SemiHidden="false"
   UnhideWhenUsed="false" Name="Medium Shading 1 Accent 4"/>
  <w:LsdException Locked="false" Priority="64" SemiHidden="false"
   UnhideWhenUsed="false" Name="Medium Shading 2 Accent 4"/>
  <w:LsdException Locked="false" Priority="65" SemiHidden="false"
   UnhideWhenUsed="false" Name="Medium List 1 Accent 4"/>
  <w:LsdException Locked="false" Priority="66" SemiHidden="false"
   UnhideWhenUsed="false" Name="Medium List 2 Accent 4"/>
  <w:LsdException Locked="false" Priority="67" SemiHidden="false"
   UnhideWhenUsed="false" Name="Medium Grid 1 Accent 4"/>
  <w:LsdException Locked="false" Priority="68" SemiHidden="false"
   UnhideWhenUsed="false" Name="Medium Grid 2 Accent 4"/>
  <w:LsdException Locked="false" Priority="69" SemiHidden="false"
   UnhideWhenUsed="false" Name="Medium Grid 3 Accent 4"/>
  <w:LsdException Locked="false" Priority="70" SemiHidden="false"
   UnhideWhenUsed="false" Name="Dark List Accent 4"/>
  <w:LsdException Locked="false" Priority="71" SemiHidden="false"
   UnhideWhenUsed="false" Name="Colorful Shading Accent 4"/>
  <w:LsdException Locked="false" Priority="72" SemiHidden="false"
   UnhideWhenUsed="false" Name="Colorful List Accent 4"/>
  <w:LsdException Locked="false" Priority="73" SemiHidden="false"
   UnhideWhenUsed="false" Name="Colorful Grid Accent 4"/>
  <w:LsdException Locked="false" Priority="60" SemiHidden="false"
   UnhideWhenUsed="false" Name="Light Shading Accent 5"/>
  <w:LsdException Locked="false" Priority="61" SemiHidden="false"
   UnhideWhenUsed="false" Name="Light List Accent 5"/>
  <w:LsdException Locked="false" Priority="62" SemiHidden="false"
   UnhideWhenUsed="false" Name="Light Grid Accent 5"/>
  <w:LsdException Locked="false" Priority="63" SemiHidden="false"
   UnhideWhenUsed="false" Name="Medium Shading 1 Accent 5"/>
  <w:LsdException Locked="false" Priority="64" SemiHidden="false"
   UnhideWhenUsed="false" Name="Medium Shading 2 Accent 5"/>
  <w:LsdException Locked="false" Priority="65" SemiHidden="false"
   UnhideWhenUsed="false" Name="Medium List 1 Accent 5"/>
  <w:LsdException Locked="false" Priority="66" SemiHidden="false"
   UnhideWhenUsed="false" Name="Medium List 2 Accent 5"/>
  <w:LsdException Locked="false" Priority="67" SemiHidden="false"
   UnhideWhenUsed="false" Name="Medium Grid 1 Accent 5"/>
  <w:LsdException Locked="false" Priority="68" SemiHidden="false"
   UnhideWhenUsed="false" Name="Medium Grid 2 Accent 5"/>
  <w:LsdException Locked="false" Priority="69" SemiHidden="false"
   UnhideWhenUsed="false" Name="Medium Grid 3 Accent 5"/>
  <w:LsdException Locked="false" Priority="70" SemiHidden="false"
   UnhideWhenUsed="false" Name="Dark List Accent 5"/>
  <w:LsdException Locked="false" Priority="71" SemiHidden="false"
   UnhideWhenUsed="false" Name="Colorful Shading Accent 5"/>
  <w:LsdException Locked="false" Priority="72" SemiHidden="false"
   UnhideWhenUsed="false" Name="Colorful List Accent 5"/>
  <w:LsdException Locked="false" Priority="73" SemiHidden="false"
   UnhideWhenUsed="false" Name="Colorful Grid Accent 5"/>
  <w:LsdException Locked="false" Priority="60" SemiHidden="false"
   UnhideWhenUsed="false" Name="Light Shading Accent 6"/>
  <w:LsdException Locked="false" Priority="61" SemiHidden="false"
   UnhideWhenUsed="false" Name="Light List Accent 6"/>
  <w:LsdException Locked="false" Priority="62" SemiHidden="false"
   UnhideWhenUsed="false" Name="Light Grid Accent 6"/>
  <w:LsdException Locked="false" Priority="63" SemiHidden="false"
   UnhideWhenUsed="false" Name="Medium Shading 1 Accent 6"/>
  <w:LsdException Locked="false" Priority="64" SemiHidden="false"
   UnhideWhenUsed="false" Name="Medium Shading 2 Accent 6"/>
  <w:LsdException Locked="false" Priority="65" SemiHidden="false"
   UnhideWhenUsed="false" Name="Medium List 1 Accent 6"/>
  <w:LsdException Locked="false" Priority="66" SemiHidden="false"
   UnhideWhenUsed="false" Name="Medium List 2 Accent 6"/>
  <w:LsdException Locked="false" Priority="67" SemiHidden="false"
   UnhideWhenUsed="false" Name="Medium Grid 1 Accent 6"/>
  <w:LsdException Locked="false" Priority="68" SemiHidden="false"
   UnhideWhenUsed="false" Name="Medium Grid 2 Accent 6"/>
  <w:LsdException Locked="false" Priority="69" SemiHidden="false"
   UnhideWhenUsed="false" Name="Medium Grid 3 Accent 6"/>
  <w:LsdException Locked="false" Priority="70" SemiHidden="false"
   UnhideWhenUsed="false" Name="Dark List Accent 6"/>
  <w:LsdException Locked="false" Priority="71" SemiHidden="false"
   UnhideWhenUsed="false" Name="Colorful Shading Accent 6"/>
  <w:LsdException Locked="false" Priority="72" SemiHidden="false"
   UnhideWhenUsed="false" Name="Colorful List Accent 6"/>
  <w:LsdException Locked="false" Priority="73" SemiHidden="false"
   UnhideWhenUsed="false" Name="Colorful Grid Accent 6"/>
  <w:LsdException Locked="false" Priority="19" SemiHidden="false"
   UnhideWhenUsed="false" QFormat="true" Name="Subtle Emphasis"/>
  <w:LsdException Locked="false" Priority="21" SemiHidden="false"
   UnhideWhenUsed="false" QFormat="true" Name="Intense Emphasis"/>
  <w:LsdException Locked="false" Priority="31" SemiHidden="false"
   UnhideWhenUsed="false" QFormat="true" Name="Subtle Reference"/>
  <w:LsdException Locked="false" Priority="32" SemiHidden="false"
   UnhideWhenUsed="false" QFormat="true" Name="Intense Reference"/>
  <w:LsdException Locked="false" Priority="33" SemiHidden="false"
   UnhideWhenUsed="false" QFormat="true" Name="Book Title"/>
  <w:LsdException Locked="false" Priority="37" Name="Bibliography"/>
  <w:LsdException Locked="false" Priority="39" QFormat="true" Name="TOC Heading"/>
 </w:LatentStyles>
</xml><![endif]-->
<style>
<!--
 /* Font Definitions */
 @font-face
	{font-family:Calibri;
	panose-1:2 15 5 2 2 2 4 3 2 4;
	mso-font-charset:204;
	mso-generic-font-family:swiss;
	mso-font-pitch:variable;
	mso-font-signature:-520092929 1073786111 9 0 415 0;}
@font-face
	{font-family:Times;
	panose-1:2 2 6 3 5 4 5 2 3 4;
	mso-font-charset:204;
	mso-generic-font-family:roman;
	mso-font-pitch:variable;
	mso-font-signature:-536859905 -1073711039 9 0 511 0;}
 /* Style Definitions */
 p.MsoNormal, li.MsoNormal, div.MsoNormal
	{mso-style-unhide:no;
	mso-style-qformat:yes;
	mso-style-parent:"";
	margin-top:0cm;
	margin-right:0cm;
	margin-bottom:10.0pt;
	margin-left:0cm;
	line-height:115%;
	mso-pagination:widow-orphan;
	font-size:11.0pt;
	font-family:"Calibri","sans-serif";
	mso-ascii-font-family:Calibri;
	mso-ascii-theme-font:minor-latin;
	mso-fareast-font-family:Calibri;
	mso-fareast-theme-font:minor-latin;
	mso-hansi-font-family:Calibri;
	mso-hansi-theme-font:minor-latin;
	mso-bidi-font-family:"Times New Roman";
	mso-bidi-theme-font:minor-bidi;
	mso-fareast-language:EN-US;}
a:link, span.MsoHyperlink
	{mso-style-priority:99;
	color:blue;
	mso-themecolor:hyperlink;
	text-decoration:underline;
	text-underline:single;}
a:visited, span.MsoHyperlinkFollowed
	{mso-style-noshow:yes;
	mso-style-priority:99;
	color:purple;
	mso-themecolor:followedhyperlink;
	text-decoration:underline;
	text-underline:single;}
span.SpellE
	{mso-style-name:"";
	mso-spl-e:yes;}
span.GramE
	{mso-style-name:"";
	mso-gram-e:yes;}
.MsoChpDefault
	{mso-style-type:export-only;
	mso-default-props:yes;
	font-family:"Calibri","sans-serif";
	mso-ascii-font-family:Calibri;
	mso-ascii-theme-font:minor-latin;
	mso-fareast-font-family:Calibri;
	mso-fareast-theme-font:minor-latin;
	mso-hansi-font-family:Calibri;
	mso-hansi-theme-font:minor-latin;
	mso-bidi-font-family:"Times New Roman";
	mso-bidi-theme-font:minor-bidi;
	mso-fareast-language:EN-US;}
.MsoPapDefault
	{mso-style-type:export-only;
	margin-bottom:10.0pt;
	line-height:115%;}
@page WordSection1
	{size:841.9pt 595.3pt;
	mso-page-orientation:landscape;
	margin:7.1pt 41.0pt 90.5pt 49.65pt;
	mso-header-margin:35.4pt;
	mso-footer-margin:35.4pt;
	mso-paper-source:0;}
div.WordSection1
	{page:WordSection1;}
-->
</style>
<!--[if gte mso 10]>
<style>
 /* Style Definitions */
 table.MsoNormalTable
	{mso-style-name:"Обычная таблица";
	mso-tstyle-rowband-size:0;
	mso-tstyle-colband-size:0;
	mso-style-noshow:yes;
	mso-style-priority:99;
	mso-style-parent:"";
	mso-padding-alt:0cm 5.4pt 0cm 5.4pt;
	mso-para-margin-top:0cm;
	mso-para-margin-right:0cm;
	mso-para-margin-bottom:10.0pt;
	mso-para-margin-left:0cm;
	line-height:115%;
	mso-pagination:widow-orphan;
	font-size:11.0pt;
	font-family:"Calibri","sans-serif";
	mso-ascii-font-family:Calibri;
	mso-ascii-theme-font:minor-latin;
	mso-hansi-font-family:Calibri;
	mso-hansi-theme-font:minor-latin;
	mso-bidi-font-family:"Times New Roman";
	mso-bidi-theme-font:minor-bidi;
	mso-fareast-language:EN-US;}
</style>
<![endif]--><!--[if gte mso 9]><xml>
 <o:shapedefaults v:ext="edit" spidmax="1026"/>
</xml><![endif]--><!--[if gte mso 9]><xml>
 <o:shapelayout v:ext="edit">
  <o:idmap v:ext="edit" data="1"/>
 </o:shapelayout></xml><![endif]-->
</head>

<body lang=RU link=blue vlink=purple style="tab-interval:35.4pt">

<div class=WordSection1>
 
<table class=MsoNormalTable border=0 cellspacing=0 cellpadding=0 align=left
 width=100% style="width:100%;border-collapse:collapse;mso-yfti-tbllook:
 1184;mso-table-lspace:9.0pt;margin-left:6.75pt;mso-table-rspace:9.0pt;
 margin-right:6.75pt;mso-table-anchor-vertical:margin;mso-table-anchor-horizontal:
 margin;mso-table-left:-8.85pt;mso-table-top:30.75pt;mso-padding-alt:0cm 5.4pt 0cm 5.4pt">
 <tr style="mso-yfti-irow:0;mso-yfti-firstrow:yes;height:3.5pt">
  <td width=102 nowrap valign=bottom style="width:76.55pt;padding:0cm 5.4pt 0cm 5.4pt;
  height:3.5pt">
  <p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;mso-element:
  frame;mso-element-frame-hspace:9.0pt;mso-element-wrap:around;mso-element-anchor-horizontal:
  margin;mso-element-left:-8.8pt;mso-element-top:30.75pt;mso-height-rule:exactly"><b
  style="mso-bidi-font-weight:normal">Организация:</b></p>
  </td>
  <td width=161 style="width:500.0pt;padding:0cm 5.4pt 0cm 5.4pt;height:3.5pt"><p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;mso-element:
  frame;mso-element-frame-hspace:9.0pt;mso-element-wrap:around;mso-element-anchor-horizontal:
  margin;mso-element-left:-8.8pt;mso-element-top:30.75pt;mso-height-rule:exactly">'.$klient['naim'].'</p></td>
  <td width=774 rowspan=4 valign=top style="width:580.4pt;padding:0cm 5.4pt 0cm 5.4pt;
  height:3.5pt">
  <p class=MsoNormal align=center style="margin-bottom:0cm;margin-bottom:.0001pt;
  text-align:center"><span style="font-size:14.0pt;line-height:115%">Карта
  клиента № <b style="mso-bidi-font-weight:normal">'.$_GET['kli'].'</b></span></p>
  <p class=MsoNormal align=center style="margin-bottom:0cm;margin-bottom:.0001pt;
  text-align:center"><span style="font-size:12.0pt;line-height:115%">Дата:<span
  style="mso-spacerun:yes">  </span>'.$schet['d'].'.'.$schet['m'].'.'.$schet['y'].' г.</span><span lang=EN-US
  style="font-size:12.0pt;line-height:115%;mso-ansi-language:EN-US"></span></p>
  </td>
 </tr>
 <tr style="mso-yfti-irow:1;height:3.5pt">
  <td width=102 nowrap valign=bottom style="width:76.55pt;padding:0cm 5.4pt 0cm 5.4pt;
  height:3.5pt">
  <p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;mso-element:
  frame;mso-element-frame-hspace:9.0pt;mso-element-wrap:around;mso-element-anchor-horizontal:
  margin;mso-element-left:-8.8pt;mso-element-top:30.75pt;mso-height-rule:exactly"><b
  style="mso-bidi-font-weight:normal">ИНН/ КПП</b><b style="mso-bidi-font-weight:
  normal"><span lang=EN-US style="mso-ansi-language:EN-US">:</span></b></p>
  </td>
  <td width=161 valign=top style="width:500.0pt;padding:0cm 5.4pt 0cm 5.4pt;
  height:3.5pt">
  <p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;mso-element:
  frame;mso-element-frame-hspace:9.0pt;mso-element-wrap:around;mso-element-anchor-horizontal:
  margin;mso-element-left:-8.8pt;mso-element-top:30.75pt;mso-height-rule:exactly">'.$klient['inn'].'<span
  lang=EN-US style="mso-ansi-language:EN-US">/</span>'.$klient['kpp'].'</p>
  </td>
 </tr>
 <tr style="mso-yfti-irow:2;height:3.5pt">
  <td width=102 nowrap valign=bottom style="width:76.55pt;padding:0cm 5.4pt 0cm 5.4pt;
  height:3.5pt">
  <p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;mso-element:
  frame;mso-element-frame-hspace:9.0pt;mso-element-wrap:around;mso-element-anchor-horizontal:
  margin;mso-element-left:-8.8pt;mso-element-top:30.75pt;mso-height-rule:exactly"><b
  style="mso-bidi-font-weight:normal">Адрес</b><b style="mso-bidi-font-weight:
  normal"><span lang=EN-US style="mso-ansi-language:EN-US">:</span></b></p>
  </td>
  <td width=161 style="width:500.0pt;padding:0cm 5.4pt 0cm 5.4pt;height:3.5pt">
  <p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;mso-element:
  frame;mso-element-frame-hspace:9.0pt;mso-element-wrap:around;mso-element-anchor-horizontal:
  margin;mso-element-left:-8.8pt;mso-element-top:30.75pt;mso-height-rule:exactly">'.$klient['fakadress'].'</p>
  </td>
 </tr>
 <tr style="mso-yfti-irow:3;mso-yfti-lastrow:yes;height:8.25pt">
  <td width=102 nowrap valign=bottom style="width:76.55pt;padding:0cm 5.4pt 0cm 5.4pt;
  height:8.25pt">
  <p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;mso-element:
  frame;mso-element-frame-hspace:9.0pt;mso-element-wrap:around;mso-element-anchor-horizontal:
  margin;mso-element-left:-8.8pt;mso-element-top:30.75pt;mso-height-rule:exactly"><b
  style="mso-bidi-font-weight:normal">Контакт</b><b style="mso-bidi-font-weight:
  normal"><span lang=EN-US style="mso-ansi-language:EN-US">:</span></b></p>
  </td>
  <td width=161 valign=bottom style="width:500.0pt;padding:0cm 5.4pt 0cm 5.4pt;
  height:8.25pt">
  <p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;mso-element:
  frame;mso-element-frame-hspace:9.0pt;mso-element-wrap:around;mso-element-anchor-horizontal:
  margin;mso-element-left:-8.8pt;mso-element-top:30.75pt;mso-height-rule:exactly">';
  $lis = "SELECT * FROM klient WHERE id =".$schet['podpisantv'];
$resultlis = mysql_query($lis);
$personlis = mysql_fetch_array($resultlis);
$str .= $personlis['fio'];
$str .= ': ';
$str .= $personlis['tel'];
  $str .= '</p>
  </td>
 </tr>
</table>



<table class="MsoNormalTable table " border=0 cellspacing=0 cellpadding=0 align=left
 width=100% style="width:98%;border-collapse:collapse;mso-yfti-tbllook:
 1184;mso-table-lspace:9.0pt;margin-left:6.75pt;mso-table-rspace:9.0pt;
 margin-right:6.75pt;mso-table-anchor-vertical:page;mso-table-anchor-horizontal:
 margin;mso-table-left:-8.85pt;mso-table-top:119.3pt;mso-padding-alt:0cm 5.4pt 0cm 5.4pt">
 <thead>
 <tr style="mso-yfti-irow:0;mso-yfti-firstrow:yes;height:12.2pt">
  <td width=300 nowrap style="width:350pt;border:solid windowtext 1.0pt;
  mso-border-alt:solid windowtext .5pt;padding:0cm 5.4pt 0cm 5.4pt;height:12.2pt">
  <p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;mso-element:
  frame;mso-element-frame-hspace:9.0pt;mso-element-wrap:around;mso-element-anchor-vertical:
  page;mso-element-anchor-horizontal:margin;mso-element-left:-8.8pt;mso-element-top:
  119.3pt;mso-height-rule:exactly"><b style="mso-bidi-font-weight:normal">Наименование
  этапа</b></p>
  </td>
  <td width=57 style="width:90.5pt;border:solid windowtext 1.0pt;border-left:
  none;mso-border-left-alt:solid windowtext .5pt;mso-border-alt:solid windowtext .5pt;
  padding:0cm 5.4pt 0cm 5.4pt;height:12.2pt">
  <p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;mso-element:
  frame;mso-element-frame-hspace:9.0pt;mso-element-wrap:around;mso-element-anchor-vertical:
  page;mso-element-anchor-horizontal:margin;mso-element-left:-8.8pt;mso-element-top:
  119.3pt;mso-height-rule:exactly"><b style="mso-bidi-font-weight:normal">Кол-во</b></p>
  </td>
  <td width=595 style="border:solid windowtext 1.0pt;border-left:
  none;mso-border-top-alt:solid windowtext .5pt;mso-border-bottom-alt:solid windowtext .5pt;
  mso-border-right-alt:solid windowtext .5pt;padding:0cm 5.4pt 0cm 5.4pt;
  height:12.2pt">
  <p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;mso-element:
  frame;mso-element-frame-hspace:9.0pt;mso-element-wrap:around;mso-element-anchor-vertical:
  page;mso-element-anchor-horizontal:margin;mso-element-left:-8.8pt;mso-element-top:
  119.3pt;mso-height-rule:exactly"><b style="mso-bidi-font-weight:normal">Примечание</b></p>
  </td>
  <td width=85 style="width:150px;border:solid windowtext 1.0pt;border-left:
  none;mso-border-top-alt:solid windowtext .5pt;mso-border-bottom-alt:solid windowtext .5pt;
  mso-border-right-alt:solid windowtext .5pt;padding:0cm 5.4pt 0cm 5.4pt; 
  height:12.2pt">
  <p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;mso-element:
  frame;mso-element-frame-hspace:9.0pt;mso-element-wrap:around;mso-element-anchor-vertical:
  page;mso-element-anchor-horizontal:margin;mso-element-left:-8.8pt;mso-element-top:
  119.3pt;mso-height-rule:exactly"><b style="mso-bidi-font-weight:normal">Сумма</b></p>
  </td>
 </tr>
 </thead>
 
 ';
 
$i = 1;
$summ = 0;
$querySchet = mysql_query("SELECT * FROM schet WHERE del = '0' AND rand = '".$_GET['id']."'");
while($row = mysql_fetch_array($querySchet)) {
$rpod = "SELECT * FROM tarif WHERE id =".$row['prod'];
$resultrpod = mysql_query($rpod);
$personrpod = mysql_fetch_array($resultrpod);
  $str .= '<tr style="mso-yfti-irow:1;height:3.5pt">
  <td style="border:solid windowtext 1.0pt;border-top:
  none;mso-border-top-alt:solid windowtext .5pt;mso-border-alt:solid windowtext .5pt;
  padding:0cm 5.4pt 0cm 5.4pt;height:3.5pt">
  <p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;mso-element:
  frame;mso-element-frame-hspace:9.0pt;mso-element-wrap:around;mso-element-anchor-vertical:
  page;mso-element-anchor-horizontal:margin;mso-element-left:-8.8pt;mso-element-top:
  119.3pt;mso-height-rule:exactly">'.$personrpod['name'].'</p>
  </td>
  <td style="border-top:none;border-left:none;border-bottom:
  solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;mso-border-top-alt:
  solid windowtext .5pt;mso-border-left-alt:solid windowtext .5pt;mso-border-alt:
  solid windowtext .5pt;padding:0cm 5.4pt 0cm 5.4pt;height:3.5pt">
  <p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;mso-element:
  frame;mso-element-frame-hspace:9.0pt;mso-element-wrap:around;mso-element-anchor-vertical:
  page;mso-element-anchor-horizontal:margin;mso-element-left:-8.8pt;mso-element-top:
  119.3pt;mso-height-rule:exactly">'.$row['kvo'].' '.$personrpod['shtuk'].'</p>
  </td>
  <td style="border-top:none;border-left:none;
  border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  mso-border-top-alt:solid windowtext .5pt;mso-border-top-alt:solid windowtext .5pt;
  mso-border-bottom-alt:solid windowtext .5pt;mso-border-right-alt:solid windowtext .5pt;
  padding:0">';
  
	$str .= '<input type="text" style="width:100%;height: 24px;border:none;padding:2px 5px;" placeholder="..."  onchange="regkoment'.$row['id'].'(this.value)" value="'.$row['regkoment'].'">
	
	
	<script>
		function regkoment'.$row['id'].'(str) {
			$.ajax({
				type: "GET",
				url: "pusya.php",
				data: "vlad="+str+"&tip=regkoment&rand='.$_GET['id'].'&idprod='.$row['id'].'",
				success: function(msg){
				}
			});
		}
	</script>
	
	
	';
    $summ += $personrpod['price'] * $row['kvo'];
  $str .= '</td>
  <td style="border-top:none;border-left:none;border-bottom:
  solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;mso-border-top-alt:
  solid windowtext .5pt;mso-border-top-alt:solid windowtext .5pt;mso-border-bottom-alt:
  solid windowtext .5pt;mso-border-right-alt:solid windowtext .5pt;padding:
  0cm 5.4pt 0cm 5.4pt;height:3.5pt">
  <p class=MsoNormal align=right style="margin-bottom:0cm;margin-bottom:.0001pt;
  text-align:right;mso-element:frame;mso-element-frame-hspace:9.0pt;mso-element-wrap:
  around;mso-element-anchor-vertical:page;mso-element-anchor-horizontal:margin;
  mso-element-left:-8.8pt;mso-element-top:119.3pt;mso-height-rule:exactly">'.number_format($personrpod['price'] * $row['kvo'], 2, '.', ' ').'</p>
  </td>
 </tr>';
 }
 
 if ($pavoir['goroddd'] > 0){
 
 
  if($pavoir['goroddd'] == 450){
  $cena = "Пятигорск";
  } else if($pavoir['goroddd'] == 750){
  $cena = "КМВ";
  }else{
  $cena = $pavoir['goroddd'] / 23;
  }
 
  if($pavoir['goroddd'] == 450){
  $dcena = number_format(450, 2, '.', ' ');
  } else if($pavoir['goroddd'] == 750){
  $dcena = number_format(750, 2, '.', ' ');
  }else{
  $dcena = number_format(23, 2, '.', ' ');
  }

 
  $str .= "
 <tr><td style='border:solid windowtext 1.0pt;border-top:
  none;mso-border-top-alt:solid windowtext .5pt;mso-border-alt:solid windowtext .5pt;
  padding:0cm 5.4pt 0cm 5.4pt;height:3.5pt'>
  <p class=MsoNormal style='margin-bottom:0cm;margin-bottom:.0001pt;mso-element:
  frame;mso-element-frame-hspace:9.0pt;mso-element-wrap:around;mso-element-anchor-vertical:
  page;mso-element-anchor-horizontal:margin;mso-element-left:-8.8pt;mso-element-top:
  119.3pt;mso-height-rule:exactly'>Выезд</p>
  </td>
  <td style='border-top:none;border-left:none;border-bottom:
  solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;mso-border-top-alt:
  solid windowtext .5pt;mso-border-left-alt:solid windowtext .5pt;mso-border-alt:
  solid windowtext .5pt;padding:0cm 5.4pt 0cm 5.4pt;height:3.5pt'>
  <p class=MsoNormal style='margin-bottom:0cm;margin-bottom:.0001pt;mso-element:
  frame;mso-element-frame-hspace:9.0pt;mso-element-wrap:around;mso-element-anchor-vertical:
  page;mso-element-anchor-horizontal:margin;mso-element-left:-8.8pt;mso-element-top:
  119.3pt;mso-height-rule:exactly'>".$cena." км </p>
  </td>
  <td style='border-top:none;border-left:none;
  border-bottom:solid black 1.0pt;border-right:solid black 1.0pt;padding:1.4pt 2.8pt 1.4pt 2.8pt'>
  <p class=MsoNormal align=right style='margin-bottom:0cm;margin-bottom:.0001pt;
  text-align:right;line-height:normal;text-autospace:none'><span
  style='font-size:8.0pt;font-family:Times;color:black'></span></p>
  </td>
  <td style='border-top:none;border-left:none;border-bottom:
  solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;mso-border-top-alt:
  solid windowtext .5pt;mso-border-top-alt:solid windowtext .5pt;mso-border-bottom-alt:
  solid windowtext .5pt;mso-border-right-alt:solid windowtext .5pt;padding:
  0cm 5.4pt 0cm 5.4pt;height:3.5pt'>
  <p class=MsoNormal align=right style='margin-bottom:0cm;margin-bottom:.0001pt;
  text-align:right;mso-element:frame;mso-element-frame-hspace:9.0pt;mso-element-wrap:
  around;mso-element-anchor-vertical:page;mso-element-anchor-horizontal:margin;
  mso-element-left:-8.8pt;mso-element-top:119.3pt;mso-height-rule:exactly'>".number_format($pavoir['goroddd'], 2, '.', ' ')."</p>
  </td>
 </tr>
 ";
  }
 
 
 
 $str .= '
 <tr style="mso-yfti-irow:2;height:3.5pt">
  <td  nowrap colspan=3 valign=bottom style="
  border:solid windowtext 1.0pt;border-top:none;mso-border-top-alt:solid windowtext .5pt;
  mso-border-alt:solid windowtext .5pt;padding:0cm 5.4pt 0cm 5.4pt;height:3.5pt">
  <p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;mso-element:
  frame;mso-element-frame-hspace:9.0pt;mso-element-wrap:around;mso-element-anchor-vertical:
  page;mso-element-anchor-horizontal:margin;mso-element-left:-8.8pt;mso-element-top:
  119.3pt;mso-height-rule:exactly"><b style="mso-bidi-font-weight:normal">Итого:</b></p>
  </td>
  <td  valign=bottom style="border-top:none;border-left:
  none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  mso-border-top-alt:solid windowtext .5pt;mso-border-left-alt:solid windowtext .5pt;
  mso-border-alt:solid windowtext .5pt;padding:0cm 5.4pt 0cm 5.4pt;height:3.5pt">
  <p class=MsoNormal align=right style="margin-bottom:0cm;margin-bottom:.0001pt;
  text-align:right;mso-element:frame;mso-element-frame-hspace:9.0pt;mso-element-wrap:
  around;mso-element-anchor-vertical:page;mso-element-anchor-horizontal:margin;
  mso-element-left:-8.8pt;mso-element-top:119.3pt;mso-height-rule:exactly"><b
  style="mso-bidi-font-weight:normal">'.number_format($summ, 2, '.', ' ').'</b></p>
  </td>
 </tr>
 <tr style="mso-yfti-irow:3;height:6.5pt">
  <td nowrap colspan=4 style="border:none;border-bottom:
  solid windowtext 1.0pt;mso-border-top-alt:solid windowtext .5pt;mso-border-top-alt:
  solid windowtext .5pt;mso-border-bottom-alt:solid windowtext .5pt;padding:
  0cm 5.4pt 0cm 5.4pt;height:6.5pt">
  <p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;mso-element:
  frame;mso-element-frame-hspace:9.0pt;mso-element-wrap:around;mso-element-anchor-vertical:
  page;mso-element-anchor-horizontal:margin;mso-element-left:-8.8pt;mso-element-top:
  119.3pt;mso-height-rule:exactly">&nbsp;</p>
  </td>
 </tr>
 <tr style="mso-yfti-irow:4;height:6.5pt">
  <td  nowrap style="border:solid windowtext 1.0pt;
  border-top:none;mso-border-top-alt:solid windowtext .5pt;mso-border-alt:solid windowtext .5pt;
  padding:0cm 5.4pt 0cm 5.4pt;height:6.5pt">
  <p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;mso-element:
  frame;mso-element-frame-hspace:9.0pt;mso-element-wrap:around;mso-element-anchor-vertical:
  page;mso-element-anchor-horizontal:margin;mso-element-left:-8.8pt;mso-element-top:
  119.3pt;mso-height-rule:exactly"><b style="mso-bidi-font-weight:normal">Перечень
  документов на подпись (печать)</b></p>
  </td>
  <td w style="border-top:none;border-left:none;border-bottom:
  solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;mso-border-top-alt:
  solid windowtext .5pt;mso-border-left-alt:solid windowtext .5pt;mso-border-alt:
  solid windowtext .5pt;padding:0cm 5.4pt 0cm 5.4pt;height:6.5pt">
  <p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;mso-element:
  frame;mso-element-frame-hspace:9.0pt;mso-element-wrap:around;mso-element-anchor-vertical:
  page;mso-element-anchor-horizontal:margin;mso-element-left:-8.8pt;mso-element-top:
  119.3pt;mso-height-rule:exactly"><b style="mso-bidi-font-weight:normal">Шт.</b></p>
  </td> 
  <td colspan=2 style="border-top:none;border-left:none;
  border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  mso-border-top-alt:solid windowtext .5pt;mso-border-left-alt:solid windowtext .5pt;
  mso-border-alt:solid windowtext .5pt;padding:0cm 5.4pt 0cm 5.4pt;height:6.5pt">
  <p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;mso-element:
  frame;mso-element-frame-hspace:9.0pt;mso-element-wrap:around;mso-element-anchor-vertical:
  page;mso-element-anchor-horizontal:margin;mso-element-left:-8.8pt;mso-element-top:
  119.3pt;mso-height-rule:exactly"><b style="mso-bidi-font-weight:normal">Примечание</b></p>
  </td>
 </tr></tabte>
';


$str .= '<form action="" method="post">';
$str .= '<table class="tableq MsoNormalTable " border=0 cellspacing=0 cellpadding=0 align=left
 width=100% style="cursor: pointer;width:98%;border-collapse:collapse;mso-yfti-tbllook:
 1184;mso-table-lspace:9.0pt;margin-left:6.75pt;   margin-bottom: 20px; mso-table-rspace:9.0pt;
 margin-right:6.75pt;mso-table-anchor-vertical:page;mso-table-anchor-horizontal:
 margin;mso-table-left:-8.85pt;mso-table-top:119.3pt;mso-padding-alt:0cm 5.4pt 0cm 5.4pt">';
		$querykw = mysql_query("SELECT * FROM dokzabr WHERE del_tip = 0");
			while($rowkw = mysql_fetch_array($querykw)) {
			$resultlassq = mysql_query("SELECT count(*) FROM dokstampzabr WHERE doki = '".$rowkw['id']."' AND schet = '".$_GET['id']."' AND idkli =".$_GET['kli']);
				$classq = mysql_result($resultlassq, 0);
				$str .= '<tr class="';
				if ($classq > 0) { 
					$str .= 'highlight_success';
				}
				$str .= '" style="mso-yfti-irow:4;height:6.5pt">
  <td  nowrap style="width: 1pt; border:solid windowtext 1.0pt;
  border-top:none;mso-border-top-alt:solid windowtext .5pt;mso-border-alt:solid windowtext .5pt;
  padding:0cm 5.4pt 0cm 5.4pt;height:6.5pt">
  <p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;mso-element:
  frame;mso-element-frame-hspace:9.0pt;mso-element-wrap:around;mso-element-anchor-vertical:
  page;mso-element-anchor-horizontal:margin;mso-element-left:-8.8pt;mso-element-top:
  119.3pt;mso-height-rule:exactly">';
				$str .= '<input type="checkbox" id="check'.$rowkw['id'].'"  onclick="validate'.$rowkw['id'].'()" value="'.$rowkw['id'].'"';
				
				if ($classq > 0) { 
					$str .= 'checked';
				}
				$str .=' >';
				$str .= ' </p></td>';
				$str .= '<td nowrap style="width: 329pt; border:solid windowtext 1.0pt;
  border-top:none;mso-border-top-alt:solid windowtext .5pt;mso-border-alt:solid windowtext .5pt;
  padding:0cm 5.4pt 0cm 5.4pt;height:6.5pt">';
				$str .= $rowkw['name'];
				$str .= '</td>';
				$str .= '<td id="kommentq'.$rowkw['id'].'" style="width: 65.5pt; border-top:none;border-left:none;border-bottom:
  solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;mso-border-top-alt:
  solid windowtext .5pt;mso-border-left-alt:solid windowtext .5pt;mso-border-alt:
  solid windowtext .5pt;padding:0cm 5.4pt 0cm 5.4pt;height:6.5pt">
  <p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;mso-element:
  frame;mso-element-frame-hspace:9.0pt;mso-element-wrap:around;mso-element-anchor-vertical:
  page;mso-element-anchor-horizontal:margin;mso-element-left:-8.8pt;mso-element-top:
  119.3pt;mso-height-rule:exactly">
  
  <div id="displayq'.$rowkw['id'].'">

					<input  type="text" name="shtuk'.$rowkw['id'].'" value="';
					if ($classq > 0) {
					$rshtuk = "SELECT * FROM dokstampzabr WHERE doki = '".$rowkw['id']."' AND schet = '".$_GET['id']."' AND idkli =".$_GET['kli'];
					$reshtuk = mysql_query($rshtuk);
					$peshtuk = mysql_fetch_array($reshtuk);
						$str .= $peshtuk['shtuk'];
					}else{
						$str .= '0';
					}
					$str .= '" id="input'.$rowkw['id'].'" style="width: 28px;text-align: center;height: 18px;padding: 0px;margin-top: 2px;border: none;">
					<input name="submit'.$rowkw['id'].'" type="submit" value="ОК" class="btn btn-info btn-xs" style="float: right;height: 18px;line-height: 14px;margin-top: 2px;">
				</div></p>
				';
   if(isset($_POST['submit'.$rowkw['id']])){
$koment = "UPDATE `dokstampzabr` SET `shtuk`='".$_POST['shtuk'.$rowkw['id']]."' WHERE `doki` ='".$rowkw['id']."' AND `schet` ='".$_GET['id']."' ";
mysql_query($koment) or die(mysql_error($link));
$str .= $link.'<script type="text/javascript">
   document.location.href = "'.$_SERVER['REQUEST_URI'].'";
</script>';
}
  $str .= '</td> ';
  


				$str .= '<td colspan=2 style="border-top:none;border-left:none;
  border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  mso-border-top-alt:solid windowtext .5pt;mso-border-left-alt:solid windowtext .5pt;
  mso-border-alt:solid windowtext .5pt;padding:0;">
  
  <b style="mso-bidi-font-weight:normal">
  
  <input  id="inputz'.$rowkw['id'].'" type="text" style="width:100%;  ';
				if ($classq > 0) { 
					$str .= 'display:block;';
				}else{
					$str .= 'display:none;';
				}
				$rshtuk = "SELECT * FROM dokstampzabr WHERE doki = '".$rowkw['id']."' AND schet = '".$_GET['id']."' AND idkli =".$_GET['kli'];
					$reshtuk = mysql_query($rshtuk);
					$peshtuk = mysql_fetch_array($reshtuk);
  $str .= 'height: 24px;border:none;padding:2px 5px;    background: rgba(255, 255, 255, 0.5);" placeholder="..." value="'.$peshtuk['text'].'" onchange="regtkoment'.$rowkw['id'].'(this.value)">
  

	
	<script>
		function regtkoment'.$rowkw['id'].'(str) {
			$.ajax({
				type: "GET",
				url: "pusya.php",
				data: "vlad="+str+"&tip=regtkoment&doki='.$rowkw['id'].'&schet='.$_GET['id'].'&idkli='.$_GET['kli'].'",
				success: function(msg){
				}
			});
		}
	</script>

  
  </b>
  </td>';
				$str .= '</tr>';
$str .= '<script>
function validate'.$rowkw['id'].'(){
if (check'.$rowkw['id'].'.checked == 1){
$("#input'.$rowkw['id'].'").val("1");
document.getElementById("inputz'.$rowkw['id'].'").style.display="block";
} else {
$("#input'.$rowkw['id'].'").val("0"); 
document.getElementById("inputz'.$rowkw['id'].'").style.display="none";
}
}
</script>
 
 ';

 

			}	
$str .= '</table>';
$str .= '</form>

<br>
<h4>
Прилагается:
</h4>
';

$str .= '<table class="table" style="font-size: 16px;border-color: #333;">';
		$querykw = mysql_query("SELECT * FROM jurnalskzi WHERE schet='".$_GET['id']."'");
			while($rowkw = mysql_fetch_array($querykw)) {
				$str .= '<tr>';
                $str .= '<td style="border-color: #333;">';
                $str .= $rowkw['tip'];
                $str .= '</td>';
				$str .= '<td style="border-color: #333;font-weight: bold;">';
				$str .= $rowkw['name'];
				$str .= '</td>';
				$str .= '<td style="border-color: #333;">';
				$str .= $rowkw['kay'];
				$str .= '</td>';
				$str .= '<td style="border-color: #333;">';
				$str .= "<a href='./deljurnalskzi.php?id=".$rowkw['id']."'><span class='glyphicon glyphicon-trash' aria-hidden='true'></span></a>";
				$str .= '</td>';
				$str .= '</tr>';
}
$str .= '</table>
<br>
<br>
';


$querySchet2 = mysql_query("SELECT * FROM schet INNER JOIN tarif ON tarif.id=schet.prod WHERE schet.del = '0' AND rand = '".$_GET['id']."' AND tarif.product_model IS NOT NULL ");
$countquerySchet2 = mysql_num_rows($querySchet2);
if ( $countquerySchet2 > 0) {
     $str .='<button id="toggleButton">Показать форму с привязкой к счету</button>';
} // Кнопка для скрытия/показа
$str .='<form  id="addFreeProduct" action="'.$_SERVER['REQUEST_URI'].'" method="post">';
$str .= '                       <div class="flex-container">';
$str .='						<label>Склад:</label>
								<select id="uslugi_free" class="col-md-12 form-control input-sm" type="text" name="tip">
								<option selected value="0">Клиент</option>
								<option value="00">СКБ Контур</option>

								';
$query214 = mysql_query("SELECT * from users_access  WHERE users = '".$userdata['users_id']."'");
while($row214 = mysql_fetch_array($query214)) {
    $query32 = mysql_query("SELECT * from uslugi  WHERE del = '0' AND schet = '0' AND id = '".$row214['uslugi']."' ORDER BY name ");
    while($row32 = mysql_fetch_array($query32)) {
        $str .="<option value=";
        $str .=$row32['id'];
        $str .=">";
        $str .=$row32['name'];
        $str .="</option>";
    }
}
$str .='                        </select>';
$str .='<label  style="display: none;" id="product_label">Продукт:</label>';
$str .='<select  style="display: none;" id="product_select" class="col-md-12 form-control input-sm"></select>';
$str .='	<label id="model_label">Модель:</label>
			<select id="model_free" class="col-md-12 form-control input-sm" type="text" name="name">
			<option>Рутокен</option>
			<option>Етокен</option>
			<option>JaCarta SE</option>
			<option>USB-флеш-накопитель</option>
			<option>Retail Declaration v.2.0.</option>
			<option>Крипто ПРО</option>
			<option>Крипто ЭКД</option>
			</select>';
$str .='	<label id="identifier_label">Идентификатор:</label>';
$str .='	<input id="number_free" type="text" class="form-control input-sm" name="kay" value="">';
$str .= '   <select style="display: none;"  class="chosen-select" id="identifier_free" name="identifier"  data-placeholder="Выбрать товар"></select>';
$str .='	<input id="poshel" type="submit" name="poshel" value="Добавить" class="btn btn-success">
			</div> </form>';
$str .= '<form id="addProduct" action="'.$_SERVER['REQUEST_URI'].'" method="post" style="display: none">'; // Используем flexbox для выравнивания элементов в строку и gap для добавления промежутков между элементами
$str .= '<div class="flex-container">';
$str .= '<label for="position">Позиция счета:</label>';
if ( $countquerySchet2 === 1 ) {
    $str .= '<select readonly class="form-select custom-select" id="position" name="position">';
    $onePosition = mysql_fetch_array($querySchet2);

    $queryJurnal = mysql_query("SELECT * FROM `jurnalskzi`  WHERE `schet_id` = ".$onePosition['0']." ");
    $countqueryJurnal = mysql_num_rows($queryJurnal);

    //echo "<script>console.log('" . $countqueryJurnal . " countqueryJurnal');</script>";
    //echo "<script>console.log('" . $onePosition['kvo'] . "');</script>";

    if ($onePosition['kvo'] > $countqueryJurnal) {
    $resultrpod = mysql_fetch_array(mysql_query("SELECT * FROM tarif WHERE id =".$onePosition['prod']));
    $str .= '<option value="'.$onePosition['0'].'" data-product-model="'. $resultrpod['product_model'] .'" selected>'.$resultrpod['name'].'</option>';
    } else { $str .= '<option disabled value="0" data-product-model="0" selected></option>'; }
} else {
    $str .= '<select class="form-select custom-select" id="position" name="position">';
    $str .= '<option value="0" selected>Выбрать позицию</option>';
    while($row = mysql_fetch_array($querySchet2)) {

        $queryJurnal = mysql_query("SELECT * FROM `jurnalskzi`  WHERE `schet_id` = ".$row['0']." ");
        $countqueryJurnal = mysql_num_rows($queryJurnal);

        //echo "<script>console.log('" . $countqueryJurnal . " countqueryJurnal');</script>";
        //echo "<script>console.log('" . $row['0'] . "');</script>";
        //echo "<script>console.log('" . $row['kvo'] . "');</script>";

        if ($row['kvo'] > $countqueryJurnal){
        $rpod2 = "SELECT * FROM tarif WHERE id =" . $row['prod'];
        $resultrpod2 = mysql_query($rpod2);
        $personrpod2 = mysql_fetch_array($resultrpod2);
        $str .= '<option value="' . $row['0'] . '" data-product-model="' . $personrpod2['product_model'] . '" selected>' . $personrpod2['name'] . '</option>';
        }
    }
}

$str .= '</select>';
$str .= '<label for="position">Идентификатор:</label>';
$str .= '<select class="chosen-select" id="identifier" name="identifier"  data-placeholder="Выбрать товар"></select>';
$str .= '<input type="submit" name="add_product" id="addButton" value="Добавить" class="btn btn-success">';
$str .= '</div> </form>';

echo $str;




?>
</div>
</div>
</div>
</div>
<script>
    $('#addButton').on('click', function() {
        setTimeout(() => {
            $(this).prop('disabled', true);  // Деактивация кнопки после нажатия
        }, 100);  // Задержка в 100 мс
    });
    $(document).ready(function() {
        $("#identifier").chosen({width: "400px",
            disable_search_threshold: 8,
            inherit_select_classes:true,
            no_results_text: "не найдено",
            placeholder_text_single: "Выбрать товар",
            search_contains: true});
        $("#identifier_free").chosen({width: "400px",
            disable_search_threshold: 8,
            inherit_select_classes:true,
            no_results_text: "не найдено",
            placeholder_text_single: "Выбрать товар",
            search_contains: true});


    });
    // JavaScript для переключения видимости
    document.getElementById('toggleButton').addEventListener('click', function() {
        var form = document.getElementById('addFreeProduct');
        var selectUslugiFree = document.getElementById('uslugi_free');
        var formAddProduct = document.getElementById('addProduct');
        var toggleButton = document.getElementById('toggleButton');
        if (form.style.display === 'none') {
            form.style.display = 'block';
            formAddProduct.style.display = 'none';
            toggleButton.innerHTML = 'Показать форму с привязкой к счету';
            selectUslugiFree.selectedIndex = 0;
            updateFreeLine();
        } else {
            form.style.display = 'none';
            formAddProduct.style.display = 'block';
            toggleButton.innerHTML = 'Показать свободную форму';
            $("#identifier").trigger("chosen:updated");
        }
    });
</script>

<script>
    function ajaxRequest(url, type, data = {}, queryParams = {}) {
        return new Promise((resolve, reject) => {
            // Преобразование queryParams в строку запроса
            const queryString = Object.keys(queryParams)
                .map(key => encodeURIComponent(key) + '=' + encodeURIComponent(queryParams[key]))
                .join('&');

            const apiUrl = url + (queryString ? '?' + queryString : '');
            //console.log(apiUrl)
            $.ajax({
                type: type,
                url: apiUrl,
                contentType: "application/json",
                cache: false,
                data: type === "GET" ? null : JSON.stringify(data),
                success: function(response) {
                    resolve(response); // Разрешить промис с полученным ответом
                    // console.log(response)
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    // console.error(textStatus, errorThrown);
                    reject(errorThrown); // Отклонить промис с текстом ошибки
                }
            });
        });
    }
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var selectPosition = document.getElementById('position');
        var selectIdentifier = document.getElementById('identifier');
        var submitButton = document.querySelector('[name="add_product"]');

        var selectUslugiFree = document.getElementById('uslugi_free');
        var selectProductFree = document.getElementById('product_free');
        var selectProductFreeSelect = document.getElementById('product_select');
        var selectModelFree = document.getElementById('model_free');

        function updateFormState() {
            // Блокировка и сброс модели и позиции счета, если выбрано значение 0 у продукта
            if (selectPosition.value === '0') {
                selectIdentifier.value = '0';
                selectIdentifier.disabled = true;
                $("#identifier").trigger("chosen:updated");
            } else {
                selectIdentifier.disabled = false;
            }

            // Блокировка кнопки "Добавить", если не все условия выполнены
            submitButton.disabled = selectIdentifier.value === '0' ||
                selectPosition.value === '0';
        }

        function handleChange(event) {
            var changedSelect = event.target; // Элемент, который был изменен

            // Проверка, какой select был изменен
            if (changedSelect === selectIdentifier) {
                console.log('Изменен select "Идентификатор"');

                // Дополнительная логика для select "Модель"
            } else if (changedSelect === selectPosition) {
                console.log('Изменен select "Позиция счета"');
                if (selectPosition.value !== '0') {
                    let idProduct = selectPosition.options[selectPosition.selectedIndex].getAttribute('data-product-model');
                    downloadVendorProduct(idProduct);
                } else {$("#identifier").trigger("chosen:updated");}
                // Дополнительная логика для select "Позиция счета"
            }

            // Общая логика обновления состояния формы
            updateFormState();
        }

        // Назначение обработчиков событий на изменение select и ввода в input
        [selectPosition].forEach(function(select) {
            select.addEventListener('change', updateFormState);
        });
        selectUslugiFree.addEventListener('change', updateFreeLine);
        selectProductFreeSelect.addEventListener('change', updateFreeLineSelect);
        selectModelFree.addEventListener('change', updateFreeModelSelect);

        // Инициализация состояния формы при загрузке
        updateFormState();
        selectPosition.addEventListener('change', handleChange);
    });
    var selectPosition = document.getElementById('position');
    if (selectPosition.value !== '0') {
        let idProduct = selectPosition.options[selectPosition.selectedIndex].getAttribute('data-product-model');
        downloadVendorProduct(idProduct);
    }
function downloadVendorProduct(idProduct) {
    //console.log(idProduct)
    var selectIdentifier = document.getElementById('identifier');
    selectIdentifier.innerHTML = '';
    const queryParams = { tip: 'get-vendor-product', vendor_product: idProduct };
    // Загружаем модели для выбранного продукта
    //var option = document.createElement('option');
    //option.value = "0";
    //option.textContent = "Выбрать товар";
    //option.selected;
    //selectIdentifier.appendChild(option);
    let productsEmpty = true;
    ajaxRequest("warehouse.php", "GET", {}, queryParams).then(products => {
        products.data.forEach(product => {
            //console.log(product)
            productsEmpty = false;
            var option = document.createElement('option');
            option.value = product['0'];
            option.textContent = product['barcode'] === null ? 'Нет идентификатора' : product['barcode'];
            selectIdentifier.appendChild(option);
        })
    $("#identifier").trigger("chosen:updated");
    }).catch(error => {
        console.error('Ошибка при загрузке моделей: ', error);
    }).finally(() => {
        if (productsEmpty) {
            selectIdentifier.innerHTML = '';
            selectIdentifier.disabled = true;
            var option = document.createElement('option');
            option.value = "0";
            option.textContent = "На складе нет товаров";
            option.selected;
            selectIdentifier.appendChild(option);
        }
    });

}

$('#identifier').on('change', function(evt, params) {
    var submitButton = document.querySelector('[name="add_product"]');
    console.log(params.selected, 'params')
    if (params.selected === '0') {
        submitButton.disabled = true;
    } else {submitButton.disabled = false;}
});

function updateFreeLine() {
    let selectUslugiFree = document.getElementById('uslugi_free');
    let labelProductFree = document.getElementById('product_label');
    let selectProductFree = document.getElementById('product_select');
    let selectModelFree = document.getElementById('model_free');
    let inputNumberFree = document.getElementById('number_free');
    let selectNumberFree = document.getElementById('identifier_free');
    let selectNumberFreeChosen = document.getElementById('identifier_free_chosen');
    let addButton = document.getElementById('poshel');

    selectModelFree.innerHTML = '';
    if (['0', '00'].includes(selectUslugiFree.value)) {
        // Данные для option
        var options = ["Рутокен", "Етокен", "JaCarta SE", "USB-флеш-накопитель", "Retail Declaration v.2.0.", "Крипто ПРО", "Крипто ЭКД"];

        // Перебираем массив данных и создаем новые элементы option
        options.forEach(function(text) {
            var option = document.createElement("option"); // Создаем новый элемент option
            option.textContent = text; // Устанавливаем текст для option
            option.value = text; // Устанавливаем значение для option (можно изменить при необходимости)
            selectModelFree.appendChild(option); // Добавляем option в select
        });

        selectModelFree.disabled = false;
        selectModelFree.selectedIndex = 0;
        labelProductFree.style.display = 'none';
        selectProductFree.style.display = 'none';
        inputNumberFree.style.display = 'block';
        // selectNumberFree.style.display = 'none';
        selectNumberFree.style.display = 'none';
        selectNumberFreeChosen.style.display = 'none';
        $("#identifier_free").trigger("chosen:updated");
        $("#identifier_free_chosen").css('width', '400px');
        addButton.disabled = false;

    } else {
        addButton.disabled = true;
        labelProductFree.style.display = 'block';
        selectProductFree.style.display = 'block';
        selectProductFree.innerHTML = '';
        inputNumberFree.style.display = 'none';
        // selectNumberFree.style.display = 'block';
        selectNumberFreeChosen.style.display = 'block';
        selectNumberFree.disabled = true;
        selectNumberFree.innerHTML = '';
        $("#identifier_free").trigger("chosen:updated");
        selectModelFree.disabled = true;
        selectProductFree.innerHTML = '';
        var option = document.createElement('option');
        option.value = '0';
        option.textContent = 'Выберите продукт';
        option.selected;
        selectProductFree.appendChild(option);
        const queryParams = { tip: 'product-type', uslugi: selectUslugiFree.value };
        ajaxRequest("warehouse.php", "GET", {}, queryParams).then(product_types => {
            product_types.data.forEach(product_type => {
                //console.log(product)
                var option = document.createElement('option');
                option.value = product_type['0'];
                option.textContent = product_type['name'];
                selectProductFree.appendChild(option);
            });
        }).catch(error => {
            console.error('Ошибка при загрузке моделей: ', error);
        }).finally(() => {
        });
    }

}

function updateFreeLineSelect() {
    let selectProductFree = document.getElementById('product_select');
    let selectModelFree = document.getElementById('model_free');
    let selectNumberFree = document.getElementById('identifier_free');
    let addButton = document.getElementById('poshel');

    if (selectProductFree.value === '0') {
        selectModelFree.innerHTML = '';
        selectModelFree.disabled = true;
        addButton.disabled = true;
    } else {
        selectModelFree.innerHTML = '';
        selectNumberFree.innerHTML = '';
        selectNumberFree.disabled = true;
        $("#identifier_free").trigger("chosen:updated");
        let queryParams = { tip: 'get-product-model', product: selectProductFree.value };
        var option = document.createElement('option');
        option.value = '0';
        option.textContent = 'Выберите модель';
        option.selected;
        selectModelFree.appendChild(option);
        ajaxRequest("warehouse.php", "GET", {}, queryParams).then(models => {
            console.log(models)
            models.data.forEach(model => {
                //console.log(product)
                var option = document.createElement('option');
                option.value = model['0'];
                option.textContent = model['name'];
                selectModelFree.appendChild(option);
            });
            selectModelFree.disabled = false;
        }).catch(error => {
            console.error('Ошибка при загрузке моделей: ', error);
        }).finally(() => {
        });
}
}

function updateFreeModelSelect() {
    var selectModelFree = document.getElementById('model_free');
    let selectNumberFree = document.getElementById('identifier_free');
    let addButton = document.getElementById('poshel');

    if (selectModelFree.value === '0') {
        addButton.disabled = true;
        selectNumberFree.innerHTML = '';
        selectNumberFree.disabled = true;
        $("#identifier_free").trigger("chosen:updated");
    } else {
        addButton.disabled = false;
        selectNumberFree.innerHTML = '';
        const queryParams = { tip: 'get-vendor-product', vendor_product: selectModelFree.value };
        let productsEmpty = true;
        ajaxRequest("warehouse.php", "GET", {}, queryParams).then(products => {
            products.data.forEach(product => {
                //console.log(product)
                productsEmpty = false;
                var option = document.createElement('option');
                option.value = product['0'];
                option.textContent = product['barcode'] === null ? 'Нет идентификатора' : product['barcode'];
                selectNumberFree.appendChild(option);
            })
            selectNumberFree.disabled = false;
            $("#identifier_free").trigger("chosen:updated");
        }).catch(error => {
            console.error('Ошибка при загрузке моделей: ', error);
        }).finally(() => {
            if (productsEmpty) {
                selectNumberFree.innerHTML = '';
                selectNumberFree.disabled = true;
                var option = document.createElement('option');
                option.value = "0";
                option.textContent = "На складе нет товаров";
                option.selected;
                selectNumberFree.appendChild(option);
            }
        });
    }
}


</script>

 <script>
$(document).ready(function () {
    $("input[type='checqkbox']").change(function (e) {
        if ($(this).is(":checked")) {
            $(this).closest('tr').addClass("highlight_success");
			$("input[type='checqkbox']").document.getElementById("yes").style.display="block";
        } else {
            $(this).closest('tr').removeClass("highlight_success");
			$("input[type='checqkbox']").document.getElementById("yes").style.display="none";
        }
    });

    $('.tableq td').click(function (event) {
        if (event.target.type !== 'checkbox') {
            $(':checkbox', this).trigger('click');
        }
    });

    $(".tableq tr input[type='checkbox']").change(function (e) {
        if ($(this).is(":checked")) {
            $(this).closest('tr').addClass("highlight_success").removeClass("highlights_row");
			$.ajax({
			   type: "GET",
			   url: "dokzabr.php",
			   data: "id=1&schet=<?php echo $_GET['id']; ?>&idkli=<?php echo $_GET['kli']; ?>&doki="+$(this).closest("input[type='checkbox']").val(),
			   success: function(msg){
			   }
			});
        } else {
            $(this).closest('tr').removeClass("highlight_success").addClass("highlights_row");
			$.ajax({
			   type: "GET",
			   url: "dokzabr.php",
			   data: "id=0&schet=<?php echo $_GET['id']; ?>&idkli=<?php echo $_GET['kli']; ?>&doki="+$(this).closest("input[type='checkbox']").val(),
			   success: function(msg){
			   }
			});
        }
    });
    var selectUslugiFree = document.getElementById('uslugi_free');
    selectUslugiFree.selectedIndex = 0;
    updateFreeLine();
});
 </script>
</body>
</html>

