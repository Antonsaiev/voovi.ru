<?php
# подключаем конфиг
include 'conf.php';  # проверка авторизации
if (isset($_COOKIE['id']) and isset($_COOKIE['hash'])) 
{
$userdata = mysql_fetch_assoc(mysql_query("SELECT * FROM users WHERE users_id = '".intval($_COOKIE['id'])."' LIMIT 1"));if(($userdata['users_hash'] !== $_COOKIE['hash']) or ($userdata['users_id'] !== $_COOKIE['id'])) 
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
$pav = "SELECT * FROM schet WHERE del = '0' AND rand = $_GET[id]";
$pavresult = mysql_query($pav);
$pavoir = mysql_fetch_array($pavresult);

$sch = "SELECT DISTINCT nomerschet,kolichschet,fadress,data,d,m,y,tell,oferta,prodlen,generac,name,lico,rand,otdel,filial,god,nomerdog,data,produkt,price,kto,inn,kpp,koment,ogrn,skidka,podpisantv,vladelec FROM schet WHERE del = '0' AND rand ='".$_GET['id']."'  ORDER BY id DESC";
$schetresult = mysql_query($sch);
$schet = mysql_fetch_array($schetresult);
$sch1212 = "SELECT * FROM klient WHERE id='".$schet['vladelec']."'  ORDER BY id DESC";
$schetresult1212 = mysql_query($sch1212);
$schet1212 = mysql_fetch_array($schetresult1212);
$kli = "SELECT * FROM ogrn WHERE id =".$_GET['kli'];
$kliresult = mysql_query($kli);
$klient = mysql_fetch_array($kliresult);

$sav1 = "SELECT * FROM produkti WHERE id = '".$schet['produkt']."' ORDER BY id DESC";
$savresult1 = mysql_query($sav1);
$savoir1 = mysql_fetch_array($savresult1);

$sav = "SELECT * FROM uslugi WHERE id = '".$savoir1['parent']."' ORDER BY id DESC";
$savresult = mysql_query($sav);
$savoir = mysql_fetch_array($savresult);
$fsdhhgf = "SELECT * FROM ogrn WHERE ogrn =".$pavoir['ogrn'];
$rfsdhhgf = mysql_query($fsdhhgf);
$pfsdhhgf = mysql_fetch_array($rfsdhhgf);
$dir = "doc/".$_GET['kli']."/";
$dir2 = "doc/".$_GET['kli']."/".$_GET['id']."/";
mkdir ($dir, 0777);   
mkdir ($dir2, 0777);   
$fp = fopen($dir2."regkartkli.doc", 'w+');
$str = '<html xmlns:v="urn:schemas-microsoft-com:vml"
xmlns:o="urn:schemas-microsoft-com:office:office"
xmlns:w="urn:schemas-microsoft-com:office:word"
xmlns:x="urn:schemas-microsoft-com:office:excel"
xmlns:m="http://schemas.microsoft.com/office/2004/12/omml"
xmlns="http://www.w3.org/TR/REC-html40">

<head><link href="css/bootstrap.min.css" rel="stylesheet">
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

<div class="">
 <p class="MsoNormal" align="center" style="margin-bottom:5.65pt;text-align:center;
line-height:normal;text-autospace:none"><b><span style="font-size: 22pt;
font-family:&quot;Times&quot;,&quot;serif&quot;;color:black">'.$savoir['name'].'</span></b></p>
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
  style="mso-spacerun:yes">  </span>'.date('d.m.Y').' г.</span><span lang=EN-US
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
 <tr style="mso-yfti-irow:1;height:3.5pt">
  <td width=102 nowrap valign=bottom style="width:76.55pt;padding:0cm 5.4pt 0cm 5.4pt;
  height:3.5pt">
  <p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;mso-element:
  frame;mso-element-frame-hspace:9.0pt;mso-element-wrap:around;mso-element-anchor-horizontal:
  margin;mso-element-left:-8.8pt;mso-element-top:30.75pt;mso-height-rule:exactly"><b
  style="mso-bidi-font-weight:normal">Тел. ЛК</b><b style="mso-bidi-font-weight:
  normal"><span lang=EN-US style="mso-ansi-language:EN-US">:</span></b></p>
  </td>
  <td width=161 valign=top style="width:500.0pt;padding:0cm 5.4pt 0cm 5.4pt;
  height:3.5pt">
  <p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;mso-element:
  frame;mso-element-frame-hspace:9.0pt;mso-element-wrap:around;mso-element-anchor-horizontal:
  margin;mso-element-left:-8.8pt;mso-element-top:30.75pt;mso-height-rule:exactly">'.$schet['tell'].'</p>
  </td>
 </tr>
  <tr style="mso-yfti-irow:1;height:3.5pt">
  <td width=102 nowrap valign=bottom style="width:76.55pt;padding:0cm 5.4pt 0cm 5.4pt;
  height:3.5pt">
  <p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;mso-element:
  frame;mso-element-frame-hspace:9.0pt;mso-element-wrap:around;mso-element-anchor-horizontal:
  margin;mso-element-left:-8.8pt;mso-element-top:30.75pt;mso-height-rule:exactly"><b
  style="mso-bidi-font-weight:normal">Владелец сертификата</b><b style="mso-bidi-font-weight:
  normal"><span lang=EN-US style="mso-ansi-language:EN-US">:</span></b></p>
  </td>
  <td width=161 valign=top style="width:500.0pt;padding:0cm 5.4pt 0cm 5.4pt;
  height:3.5pt">
  <p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;mso-element:
  frame;mso-element-frame-hspace:9.0pt;mso-element-wrap:around;mso-element-anchor-horizontal:
  margin;mso-element-left:-8.8pt;mso-element-top:30.75pt;mso-height-rule:exactly">'.$schet1212['fio'].'</p>
  </td>
 </tr>
 <tr style="mso-yfti-irow:2;height:3.5pt">
  <td width=102 nowrap valign=bottom style="width:76.55pt;padding:0cm 5.4pt 0cm 5.4pt;
  height:3.5pt">
  <p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;mso-element:
  frame;mso-element-frame-hspace:9.0pt;mso-element-wrap:around;mso-element-anchor-horizontal:
  margin;mso-element-left:-8.8pt;mso-element-top:30.75pt;mso-height-rule:exactly"><b
  style="mso-bidi-font-weight:normal">Cчет выставил</b><b style="mso-bidi-font-weight:
  normal"><span lang=EN-US style="mso-ansi-language:EN-US">:</span></b></p>
  </td>
  <td width=161 style="width:500.0pt;padding:0cm 5.4pt 0cm 5.4pt;height:3.5pt">
  <p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;mso-element:
  frame;mso-element-frame-hspace:9.0pt;mso-element-wrap:around;mso-element-anchor-horizontal:
  margin;mso-element-left:-8.8pt;mso-element-top:30.75pt;mso-height-rule:exactly">';
  $lis = "SELECT * FROM klient WHERE id =".$schet['podpisantv'];
$ktolgenerac = "SELECT * FROM users WHERE users_id =".$schet['kto'];
$ktorgenerac = mysql_query($ktolgenerac);
$ktopgenerac = mysql_fetch_array($ktorgenerac);
$kto = $ktopgenerac['f_name'];
$str .= mb_substr($kto,0,1,'UTF-8').'. ';
$str .= $ktopgenerac['l_name'];
  $str .= '</p>
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
  margin;mso-element-left:-8.8pt;mso-element-top:30.75pt;mso-height-rule:exactly">'.$schet['fadress'].'</p>
  </td>
 </tr>
 <tr style="mso-yfti-irow:2;height:3.5pt">
  <td width=102 nowrap valign=bottom style="width:76.55pt;padding:0cm 5.4pt 0cm 5.4pt;
  height:3.5pt">
  <p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;mso-element:
  frame;mso-element-frame-hspace:9.0pt;mso-element-wrap:around;mso-element-anchor-horizontal:
  margin;mso-element-left:-8.8pt;mso-element-top:30.75pt;mso-height-rule:exactly"><b
  style="mso-bidi-font-weight:normal">Номер счета</b><b style="mso-bidi-font-weight:
  normal"><span lang=EN-US style="mso-ansi-language:EN-US">:</span></b></p>
  </td>
  <td width=161 style="width:500.0pt;padding:0cm 5.4pt 0cm 5.4pt;height:3.5pt">
  <p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;mso-element:
  frame;mso-element-frame-hspace:9.0pt;mso-element-wrap:around;mso-element-anchor-horizontal:
  margin;mso-element-left:-8.8pt;mso-element-top:30.75pt;mso-height-rule:exactly">'.$schet['god'].$schet['kto'].$schet['otdel'].$schet['kolichschet'].'</p>
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



<table class=MsoNormalTable border=0 cellspacing=0 cellpadding=0 align=left
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
$query = mysql_query("SELECT * FROM schet WHERE del = '0' AND rand = '".$_GET['id']."'");
while($row = mysql_fetch_array($query)) {
	if(!empty($row['prod'])){
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
  <td style="border:solid windowtext 1.0pt;border-top:
  none;mso-border-top-alt:solid windowtext .5pt;mso-border-alt:solid windowtext .5pt;
  padding:0cm 5.4pt 0cm 5.4pt;height:3.5pt">
  <p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;mso-element:
  frame;mso-element-frame-hspace:9.0pt;mso-element-wrap:around;mso-element-anchor-vertical:
  page;mso-element-anchor-horizontal:margin;mso-element-left:-8.8pt;mso-element-top:
  119.3pt;mso-height-rule:exactly">
  '.$row['regkoment'].'
  </p>
  </td>
  <td style="border-top:none;border-left:none;border-bottom:
  solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;mso-border-top-alt:
  solid windowtext .5pt;mso-border-top-alt:solid windowtext .5pt;mso-border-bottom-alt:
  solid windowtext .5pt;mso-border-right-alt:solid windowtext .5pt;padding:
  0cm 5.4pt 0cm 5.4pt;height:3.5pt">
  <p class=MsoNormal align=right style="white-space: nowrap;margin-bottom:0cm;margin-bottom:.0001pt;
  text-align:right;mso-element:frame;mso-element-frame-hspace:9.0pt;mso-element-wrap:
  around;mso-element-anchor-vertical:page;mso-element-anchor-horizontal:margin;
  mso-element-left:-8.8pt;mso-element-top:119.3pt;mso-height-rule:exactly">'.number_format($personrpod['price'] * $row['kvo'], 2, '.', ' ').'</p>
  </td>
 </tr>
 
 ';
 }
 }
 
 if ($pavoir['goroddd'] > 0){
 
 
  if($pavoir['goroddd'] == 450){
  $cena = "Пятигорск";
  } else if($pavoir['goroddd'] == 750){
  $cena = "КМВ";
  } else if($pavoir['goroddd'] == 1500){
  $cena = "Георгиевск";
  }else{
  $cena = $pavoir['goroddd'] / 23;
  }
 
  if($pavoir['goroddd'] == 450){
  $dcena = number_format(450, 2, '.', ' ');
  } else if($pavoir['goroddd'] == 750){
  $dcena = number_format(750, 2, '.', ' ');
  } else if($pavoir['goroddd'] == 1500){
  $dcena = number_format(1500, 2, '.', ' ');
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
  style="mso-bidi-font-weight:normal">'.number_format($pavoir['price'], 2, '.', ' ').'</b><b
  style="mso-bidi-font-weight:normal"><span lang=EN-US style="mso-ansi-language:
  EN-US"></span></b></p>
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
 </tr>
 
 

 ';
 
 
 
$queryd = mysql_query("SELECT * FROM dokstampzabr WHERE schet = '".$_GET['id']."'");
while($rowd = mysql_fetch_array($queryd)) {
$rpodd = "SELECT * FROM dokzabr WHERE id =".$rowd['doki'];
$resultrpodd = mysql_query($rpodd);
$personrpodd = mysql_fetch_array($resultrpodd);
  $str .= '<tr style="mso-yfti-irow:5;height:3.5pt">
  <td nowrap valign=bottom style="border:solid windowtext 1.0pt;
  border-top:none;mso-border-top-alt:solid windowtext .5pt;mso-border-alt:solid windowtext .5pt;
  padding:0cm 5.4pt 0cm 5.4pt;height:3.5pt">
  <p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;mso-element:
  frame;mso-element-frame-hspace:9.0pt;mso-element-wrap:around;mso-element-anchor-vertical:
  page;mso-element-anchor-horizontal:margin;mso-element-left:-8.8pt;mso-element-top:
  119.3pt;mso-height-rule:exactly">'.$personrpodd['name'].'</p>
  </td>
  <td valign=bottom style="border-top:none;border-left:
  none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  mso-border-top-alt:solid windowtext .5pt;mso-border-left-alt:solid windowtext .5pt;
  mso-border-alt:solid windowtext .5pt;padding:0cm 5.4pt 0cm 5.4pt;height:3.5pt">
  <p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;mso-element:
  frame;mso-element-frame-hspace:9.0pt;mso-element-wrap:around;mso-element-anchor-vertical:
  page;mso-element-anchor-horizontal:margin;mso-element-left:-8.8pt;mso-element-top:
  119.3pt;mso-height-rule:exactly">'.$rowd['shtuk'].' шт</p>
  </td>
  <td colspan=2 valign=bottom style="border-top:none;
  border-left:none;border-bottom:solid windowtext 1.0pt;border-right:solid windowtext 1.0pt;
  mso-border-top-alt:solid windowtext .5pt;mso-border-left-alt:solid windowtext .5pt;
  mso-border-alt:solid windowtext .5pt;padding:0cm 5.4pt 0cm 5.4pt;height:3.5pt">
  <p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;mso-element:
  frame;mso-element-frame-hspace:9.0pt;mso-element-wrap:around;mso-element-anchor-vertical:
  page;mso-element-anchor-horizontal:margin;mso-element-left:-8.8pt;mso-element-top:
  119.3pt;mso-height-rule:exactly">'.$rowd['text'].'</p>
  </td>
 </tr>
 
 
 
 
 
 
 
 ';
 }
 
 $str .= '</table>
 
 
<br>
<div style="
    margin: 10px;
    margin-top: 161px;
    position: initial;
    margin-bottom: -40px;
">';
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
				$str .= '1 шт.';
				$str .= '</td>';
				$str .= '<td style="border-color: #333;">';
				$str .= $rowkw['kay'];
				$str .= '</td>';
				$str .= '</tr>';
}
$str .= '</table>
<br>
 
 



 <div style="border: 1px solid #333;padding: 0px 10px;">
<h4 style="border: none;">
Заполняет исполнитель:
</h4>

<table class="table" style="">
  
  <p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;
  ">*<b style="mso-bidi-font-weight:normal">Согласно выше указанному все работы выполнил; носитель, лицензии, эцп выдал:<span
  style="mso-spacerun:yes"> </span><br>Подпись_____________________<span
  style="mso-spacerun:yes">  </span>Дата ___<span class=GramE> .</span> ___ . ______г.<span
  style="mso-spacerun:yes">   </span>ФИО <i style="text-decoration: underline;">'.mb_substr($userdata['f_name'],0,1,'UTF-8').'. '.$userdata['l_name'].'</i></b></p>
  <p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;mso-element:
  frame;mso-element-frame-hspace:9.0pt;mso-element-wrap:around;mso-element-anchor-vertical:
  page;mso-element-anchor-horizontal:margin;mso-element-left:-8.8pt;mso-element-top:
  119.3pt;mso-height-rule:exactly"><span lang=EN-US style="mso-ansi-language:
  EN-US">&nbsp;</span></p>
  </td>
 </tr>
</table>
<table class="table" style="">
  
  <p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;
  ">*<b style="mso-bidi-font-weight:normal">Установку выполнил,документы согласно выше указанному перечню получил:<span
  style="mso-spacerun:yes"> </span><br>Подпись_____________________<span
  style="mso-spacerun:yes">  </span>Дата ___<span class=GramE> .</span> ___ . ______г.<span
  style="mso-spacerun:yes">   </span>ФИО ___________________________ </b></p>
  <p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;mso-element:
  frame;mso-element-frame-hspace:9.0pt;mso-element-wrap:around;mso-element-anchor-vertical:
  page;mso-element-anchor-horizontal:margin;mso-element-left:-8.8pt;mso-element-top:
  119.3pt;mso-height-rule:exactly"><span lang=EN-US style="mso-ansi-language:
  EN-US">&nbsp;</span></p>
  </td>
 </tr>
</table>

<br>
</div>
<br>

 <div style="border: 1px solid #333;padding: 0px 10px;">
<h4 style="border: none;">
Заполняет заказчик:
</h4>
 
 <table class="table" style="    background: none;font-size: 16px;border-color: #333;">
 <tr style="mso-yfti-irow:6;mso-yfti-lastrow:yes;height:3.5pt">
  <td nowrap colspan=4 valign=bottom style="
  border:none;mso-border-top-alt:solid windowtext .5pt;padding:0cm 5.4pt 0cm 5.4pt;
  height:3.5pt">
  <p class=MsoNormal  style="margin-bottom:0cm;margin-bottom:.0001pt;
  "><b style="mso-bidi-font-weight:normal"> <table class="table">
  <tr style="background: #eee;font-size: 14px;font-weight: bold;">
  <td>ФИО: </td> <td style="padding: 4px 3px;">Дата ___.___.______г.</td>
  </tr>
  <tr style="font-size: 14px;">
  <td style="width: 70%;">Работы(услуги, продукции, ЭЦП*) принял согласно оплаченым счетам, притензий не имею</td> <td style="padding: 4px 3px;">Подпись:</td>
  </tr>
</table>
<h6 style="border: none; font-size: 9px;">
* - ЭЦП выпускается на носитель заказчика или на приобретенный носитель в случае оплаченого счета.
</h6>
  </b></p>
 
  </td>
 </tr>
</table>



</div>

<h4>
Дополнительный коментарий:
</h4>



</div>






</div>

</body>

</html>';
echo $str;
fwrite($fp, $str);

fclose($fp);
?>
