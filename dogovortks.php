<?php
include 'conf.php';



$pav = "SELECT * FROM schet WHERE rand = $_GET[id]";
$pavresult = mysql_query($pav);
$pavoir = mysql_fetch_array($pavresult);


$fsdhhgf = "SELECT * FROM ogrn WHERE ogrn =".$pavoir['ogrn'];
$rfsdhhgf = mysql_query($fsdhhgf);
$pfsdhhgf = mysql_fetch_array($rfsdhhgf);

$sch = "SELECT DISTINCT nomerschet,kolichschet,data,d,m,y,prodlen,generac,name,lico,rand,otdel,filial,god,nomerdog,produkt,price,kto,inn,kpp,koment,ogrn,skidka,podpisant FROM schet WHERE rand ='".$_GET['id']."'  ORDER BY id DESC";
$schetresult = mysql_query($sch);
$schet = mysql_fetch_array($schetresult);

$kli = "SELECT * FROM ogrn WHERE id =".$_GET['kli'];
$kliresult = mysql_query($kli);
$klient = mysql_fetch_array($kliresult);

$sav1 = "SELECT * FROM produkti WHERE id = '".$schet['produkt']."' ORDER BY id DESC";
$savresult1 = mysql_query($sav1);
$savoir1 = mysql_fetch_array($savresult1);

$sav = "SELECT * FROM uslugi WHERE id = '".$savoir1['parent']."' ORDER BY id DESC";
$savresult = mysql_query($sav);
$savoir = mysql_fetch_array($savresult);
// -------------
$ogip = strlen($klient['ogrn']);
// -------------
$lis = "SELECT * FROM klient WHERE id =".$schet['podpisant'];
$resultlis = mysql_query($lis);
$personlis = mysql_fetch_array($resultlis);
// -------------
$ssser = mb_substr(strstr($personlis['fio']," "),1,99,'UTF-8');
$srachnah = strstr($ssser," ");
// -------------
$god = $schet['y']+1;


$dir = "doc/".$_GET['kli']."/";
$dir2 = "doc/".$_GET['kli']."/".$_GET['id']."/";
mkdir ($dir, 0777);
mkdir ($dir2, 0777);
$fp = fopen($dir2."kontrakt.doc", 'w+');


$str ='<html xmlns:v="urn:schemas-microsoft-com:vml"
xmlns:o="urn:schemas-microsoft-com:office:office"
xmlns:w="urn:schemas-microsoft-com:office:word"
xmlns:m="http://schemas.microsoft.com/office/2004/12/omml"
xmlns="http://www.w3.org/TR/REC-html40">

<head>
<meta http-equiv=Content-Type content="text/html; charset=utf-8">
<meta name=ProgId content=Word.Document>
<meta name=Generator content="Microsoft Word 12">
<meta name=Originator content="Microsoft Word 12">
<link rel=File-List href="kontrakt_1500a1.files/filelist.xml">
<!--[if gte mso 9]><xml>
 <o:DocumentProperties>
  <o:Author>IO</o:Author>
  <o:Template>Normal</o:Template>
  <o:LastAuthor>Jaz</o:LastAuthor>
  <o:Revision>2</o:Revision>
  <o:TotalTime>10</o:TotalTime>
  <o:Created>2015-10-19T11:26:00Z</o:Created>
  <o:LastSaved>2015-10-19T11:26:00Z</o:LastSaved>
  <o:Pages>3</o:Pages>
  <o:Words>1915</o:Words>
  <o:Characters>10920</o:Characters>
  <o:Lines>91</o:Lines>
  <o:Paragraphs>25</o:Paragraphs>
  <o:CharactersWithSpaces>12810</o:CharactersWithSpaces>
  <o:Version>12.00</o:Version>
 </o:DocumentProperties>
 <o:OfficeDocumentSettings>
  <o:AllowPNG/>
 </o:OfficeDocumentSettings>
</xml><![endif]-->
<link rel=themeData href="kontrakt_1500a1.files/themedata.thmx">
<link rel=colorSchemeMapping href="kontrakt_1500a1.files/colorschememapping.xml">
<!--[if gte mso 9]><xml>
 <w:WordDocument>
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
   <w:DontVertAlignCellWithSp/>
   <w:DontBreakConstrainedForcedTables/>
   <w:DontVertAlignInTxbx/>
   <w:Word11KerningPairs/>
   <w:CachedColBalance/>
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
	{font-family:"Cambria Math";
	panose-1:0 0 0 0 0 0 0 0 0 0;
	mso-font-charset:1;
	mso-generic-font-family:roman;
	mso-font-format:other;
	mso-font-pitch:variable;
	mso-font-signature:0 0 0 0 0 0;}
@font-face
	{font-family:Calibri;
	panose-1:2 15 5 2 2 2 4 3 2 4;
	mso-font-charset:204;
	mso-generic-font-family:swiss;
	mso-font-pitch:variable;
	mso-font-signature:-536870145 1073786111 1 0 415 0;}
@font-face
	{font-family:Verdana;
	panose-1:2 11 6 4 3 5 4 4 2 4;
	mso-font-charset:204;
	mso-generic-font-family:swiss;
	mso-font-pitch:variable;
	mso-font-signature:-1593833729 1073750107 16 0 415 0;}
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
	
	mso-bidi-theme-font:minor-bidi;
	mso-fareast-language:EN-US;}
p.MsoListParagraph, li.MsoListParagraph, div.MsoListParagraph
	{mso-style-priority:34;
	mso-style-unhide:no;
	mso-style-qformat:yes;
	margin-top:0cm;
	margin-right:0cm;
	margin-bottom:10.0pt;
	margin-left:36.0pt;
	mso-add-space:auto;
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
	
	mso-bidi-theme-font:minor-bidi;
	mso-fareast-language:EN-US;}
p.MsoListParagraphCxSpFirst, li.MsoListParagraphCxSpFirst, div.MsoListParagraphCxSpFirst
	{mso-style-priority:34;
	mso-style-unhide:no;
	mso-style-qformat:yes;
	mso-style-type:export-only;
	margin-top:0cm;
	margin-right:0cm;
	margin-bottom:0cm;
	margin-left:36.0pt;
	margin-bottom:.0001pt;
	mso-add-space:auto;
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
	
	mso-bidi-theme-font:minor-bidi;
	mso-fareast-language:EN-US;}
p.MsoListParagraphCxSpMiddle, li.MsoListParagraphCxSpMiddle, div.MsoListParagraphCxSpMiddle
	{mso-style-priority:34;
	mso-style-unhide:no;
	mso-style-qformat:yes;
	mso-style-type:export-only;
	margin-top:0cm;
	margin-right:0cm;
	margin-bottom:0cm;
	margin-left:36.0pt;
	margin-bottom:.0001pt;
	mso-add-space:auto;
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
	
	mso-bidi-theme-font:minor-bidi;
	mso-fareast-language:EN-US;}
p.MsoListParagraphCxSpLast, li.MsoListParagraphCxSpLast, div.MsoListParagraphCxSpLast
	{mso-style-priority:34;
	mso-style-unhide:no;
	mso-style-qformat:yes;
	mso-style-type:export-only;
	margin-top:0cm;
	margin-right:0cm;
	margin-bottom:10.0pt;
	margin-left:36.0pt;
	mso-add-space:auto;
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
	
	mso-bidi-theme-font:minor-bidi;
	mso-fareast-language:EN-US;}
span.apple-converted-space
	{mso-style-name:apple-converted-space;
	mso-style-unhide:no;}
span.SpellE
	{mso-style-name:"";
	mso-spl-e:yes;}
span.GramE
	{mso-style-name:"";
	mso-gram-e:yes;}
.MsoChpDefault
	{mso-style-type:export-only;
	mso-default-props:yes;
	mso-ascii-font-family:Calibri;
	mso-ascii-theme-font:minor-latin;
	mso-fareast-font-family:Calibri;
	mso-fareast-theme-font:minor-latin;
	mso-hansi-font-family:Calibri;
	mso-hansi-theme-font:minor-latin;
	
	mso-bidi-theme-font:minor-bidi;
	mso-fareast-language:EN-US;}
.MsoPapDefault
	{mso-style-type:export-only;
	margin-bottom:10.0pt;
	line-height:115%;}
@page WordSection1
	{size:595.3pt 841.9pt;
	margin:2.0cm 42.5pt 2.0cm 3.0cm;
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
	mso-style-qformat:yes;
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
	mso-fareast-language:EN-US;}
</style>
<![endif]--><!--[if gte mso 9]><xml>
 <o:shapedefaults v:ext="edit" spidmax="2050"/>
</xml><![endif]--><!--[if gte mso 9]><xml>
 <o:shapelayout v:ext="edit">
  <o:idmap v:ext="edit" data="1"/>
 </o:shapelayout></xml><![endif]-->
</head>

<body lang=RU style="tab-interval:35.4pt">

<div class=WordSection1>

<p class=MsoNormal align=center style="margin-bottom:0cm;margin-bottom:.0001pt;
text-align:center;line-height:normal"><a name="RANGE!C3:U128"><b><span
style="font-size:12.0pt;color:black;mso-fareast-language:RU">Договор</span></b></a><span
style=""></span><span style="mso-ascii-font-family:
Calibri;mso-hansi-font-family:Calibri;
color:black;mso-fareast-language:RU"><o:p></o:p></span></p>
<br>
<p class=MsoNormal align=center style="margin-bottom:0cm;margin-bottom:.0001pt;
text-align:center;line-height:normal"><a name="RANGE!C3:U128"><b><span
style="font-size:12.0pt;color:black;mso-fareast-language:RU">оказания услуг уполномоченного представителя</span></b></a><span
style=""></span><span style="mso-ascii-font-family:
Calibri;mso-hansi-font-family:Calibri;
color:black;mso-fareast-language:RU"><o:p></o:p></span></p>

<p class=MsoNormal align=center style="margin-bottom:0cm;margin-bottom:.0001pt;
text-align:center;line-height:normal"><b><span lang=EN-US style="font-size:
12.0pt;
color:black;mso-ansi-language:EN-US;mso-fareast-language:RU">&nbsp;</span></b><span
style="mso-ascii-font-family:Calibri;
mso-hansi-font-family:Calibri;
color:black;mso-fareast-language:RU"><o:p></o:p></span></p>

<table class=MsoNormalTable border=0 cellspacing=0 cellpadding=0
 style="border-collapse:collapse;mso-yfti-tbllook:1184;mso-padding-alt:0cm 0cm 0cm 0cm">
 <tr style="mso-yfti-irow:0;mso-yfti-firstrow:yes;mso-yfti-lastrow:yes">
  <td width=362 valign=top style="width:200.15pt;padding:0cm 5.4pt 0cm 5.4pt">
  <p class=MsoNormal style="border-bottom: 1px solid;margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal"><span style="font-size:14.0pt;
  mso-fareast-language:RU" >г.
  Пятигорск</span><span style="mso-ascii-font-family:Calibri;mso-hansi-font-family:Calibri;
  mso-fareast-language:RU"><o:p></o:p></span></p>
  </td>
 </tr>
</table>
<br>
<br>
<p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;text-align:
justify;line-height:normal"><span class=GramE><span style="mso-ascii-font-family:
Calibri;mso-hansi-font-family:Calibri;
color:black;mso-fareast-language:RU">Информационный Центр «SAVOIR» ИП Никитенко М.И., именуемый в дальнейшем Исполнитель,
 в лице Индивидуального предпринимателя Никитенко Майи Исмаиловны, действующей на основании Свидетельства о внесении ЕГРИП 
 записи об индивидуальном предпринимателе серии 26 № 001444477, выданного от 24.11.2004г., предоставит услуги Уполномоченного представителя,
  перечисленные в Перечне предоставляемых услуг (приложении № 1 к настоящему Договору), (далее Услуги) на условиях настоящего Договора (публичной оферты)
 (далее Договор) любому физическому или юридическому лицу,
 именуемому в дальнейшем «Заказчик», в случае принятия условий Договора (публичной оферты) и его Приложений в целом.</span></p>


<p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;text-align:
justify;line-height:normal"><b><span style="font-size:14.0pt;
color:black;mso-fareast-language:
RU">1. ПОРЯДОК ПРИНЯТИЯ УСЛОВИЙ ДОГОВОРА</span></b><span style="mso-ascii-font-family:Calibri;
mso-hansi-font-family:Calibri;
color:black;mso-fareast-language:RU"><o:p></o:p></span></p>

<p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;text-align:
justify;line-height:normal"><span style="font-size:15px;
color:black;mso-fareast-language:
RU">1.1. Заказчик знакомится с текстом настоящего Договора, Перечнем и Прейскурантом услуг. <span class=GramE> .</span></span><span
style="mso-ascii-font-family:Calibri;
mso-hansi-font-family:Calibri;
color:black;mso-fareast-language:RU"><o:p></o:p></span></p>

<p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;text-align:
justify;line-height:normal"><span style="font-size:15px;
color:black;mso-fareast-language:
RU">1.2. Согласием (акцептом) Заказчика с условиями Договора и его Приложений, является выполнение Заказчиком следующих действий (ст. 438 ГК РФ):
 поступление от Заказчика в адрес ИЦ «SAVOIR», оформленной в письменном виде Заявки на определенный вид услуг (Далее – «Заявка»)</span><span
style="mso-ascii-font-family:Calibri;
mso-hansi-font-family:Calibri;
color:black;mso-fareast-language:RU"><o:p></o:p></span></p>

<p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;text-align:
justify;line-height:normal"><span style="font-size:15px;
color:black;mso-fareast-language:
RU">1.3. Датой заключения настоящего Договора считается дата поступления и регистрации первой Заявки Заказчика в информационной системе ИЦ «SAVOIR» 
после вступления в силу настоящего Договора.</span><span style="mso-ascii-font-family:
Calibri;mso-hansi-font-family:Calibri;
color:black;mso-fareast-language:RU"><o:p></o:p></span></p>

<p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;text-align:
justify;line-height:normal"><span style="font-size:15px;
color:black;mso-fareast-language:
RU">1.4. При наличии представленной Заказчиком Заявки у Исполнителя - настоящий Договор считается заключенным,
 и Исполнитель присваивает ему регистрационный номер.</span><span style="mso-ascii-font-family:Calibri;
mso-hansi-font-family:Calibri;
color:black;mso-fareast-language:RU"><o:p></o:p></span></p>


<p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;text-align:
justify;line-height:normal"><span style="font-size:15px;
color:black;mso-fareast-language:
RU">1.5. Действующая версия Договора является официальным документом и размещается в свободном доступе на стендах в офисах ИЦ «Savoir».
 Все изменения и дополнения к данному договору вступают в силу с момента опубликования. 
Перед подачей очередной Заявки, Заказчик обязан ознакомиться с наличием имеющихся изменений. 
 </span><span style="mso-ascii-font-family:Calibri;
mso-hansi-font-family:Calibri;
color:black;mso-fareast-language:RU"><o:p></o:p></span></p>

<p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:
normal"><b><span style="font-size:14.0pt;
color:black;mso-fareast-language:
RU">2. ПРЕДМЕТ ДОГОВОРА</span></b><span style="mso-ascii-font-family:
Calibri;mso-hansi-font-family:Calibri;
color:black;mso-fareast-language:RU"><o:p></o:p></span></p>

<p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:
normal"><b><span style="font-size:15px;
color:black;mso-fareast-language:
RU">2.1. Виды услуг</span></b><span style="mso-ascii-font-family:Calibri;
mso-hansi-font-family:Calibri;
color:black;mso-fareast-language:RU"><o:p></o:p></span></p>

<p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;text-align:
justify;line-height:normal"><span style="font-size:15px;
color:black;mso-fareast-language:
RU">2.1.1. Исполнитель в соответствии с условиями настоящего Договора, действующим Прейскурантом услуг Исполнителя, в рамках поступившей от Заказчика Заявки,
 на основании полученной от Заказчика Доверенности - обязуется оказать Заказчику услуги Уполномоченного представителя, связанные с созданием Электронного 
 документооборота (далее ЭДО) Заказчика с указанным в Заявке адресатом 
(Инспекции Федеральной Налоговой Службы, Внебюджетные Фонды Пенсионного и Социального страхования (Далее Государственные Контролирующие органы) и пр.).
<br>В рамках создания ЭДО Исполнитель осуществляет:</span><span style="mso-ascii-font-family:Calibri;
mso-hansi-font-family:Calibri;
color:black;mso-fareast-language:RU"><o:p></o:p></span></p>


<p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;text-align:
justify;line-height:normal"><span style="font-size:15px;
color:black;mso-fareast-language:
RU">2.1.1.1. подготовку электронного формата  бухгалтерской  и  налоговой  отчетности, сведений, запросов, информационных писем и прочей документации,
 в соответствии с предоставленной Заказчиком Исполнителю информации 
 оформленной должным образом на бумажном носителе (далее Документы) 
 </span><span style="mso-ascii-font-family:Calibri;
mso-hansi-font-family:Calibri;
color:black;mso-fareast-language:RU"><o:p></o:p></span></p>

<p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;text-align:
justify;line-height:normal"><span style="font-size:15px;
color:black;mso-fareast-language:
RU">2.1.1.2. отравку подготовленных Исполнителем или полученных в электронном виде от Заказчика Документов в Государственные контролирующие
 органы или иным адресатам, указанным в Заявке в рамках создания ЭДО Заказчика,
 через защищенные телекоммуникационные каналы связи (далее ТКС), с использованием сертифицированных средств криптографической защиты информации (далее СКЗИ). 
 </span><span style="mso-ascii-font-family:Calibri;
mso-hansi-font-family:Calibri;
color:black;mso-fareast-language:RU"><o:p></o:p></span></p>

<p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;text-align:
justify;line-height:normal"><span style="font-size:15px;
color:black;mso-fareast-language:
RU">2.1.1.3. получение по ТКС в адрес Заказчика ответов от адресатов и передачу их Заказчику на электронном и/или бумажном носителе 
 </span><span style="mso-ascii-font-family:Calibri;
mso-hansi-font-family:Calibri;
color:black;mso-fareast-language:RU"><o:p></o:p></span></p>


<p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;text-align:
justify;line-height:normal"><span style="font-size:15px;
color:black;mso-fareast-language:
RU">2.1.2. При передаче Документов по ТКС, функции Исполнителя ограничиваются только передачей электронного документа Заказчика через ТКС адресату. 
Передача оригиналов Доверенностей, Соглашений, проведение переговоров с адресатом осуществляется Заказчиком самостоятельно либо на платной основе.
</span><span style="mso-ascii-font-family:Calibri;
mso-hansi-font-family:Calibri;
color:black;mso-fareast-language:RU"><o:p></o:p></span></p>

<p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;text-align:
justify;line-height:normal"><span style="font-size:15px;
color:black;mso-fareast-language:
RU">2.1.3. Повторная отправка при наличии отрицательного протокола контроля,
 осуществляется по дополнительной Заявке Заказчика с оплатой по действующему на момент отправки Прейскуранту, 
 кроме случаев указанных в п. 3.1.5.</span><span style="mso-ascii-font-family:
Calibri;mso-hansi-font-family:Calibri;
color:black;mso-fareast-language:RU"><o:p></o:p></span></p>

<p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;text-align:
justify;line-height:normal"><span style="font-size:15px;
color:black;mso-fareast-language:
RU">2.1.4. Консультирование по устранению ошибок и правильности расчетов, 
о наличии новшеств в Законодательстве осуществяется  на платой основе по действующему на момент отправки Прейскуранту
</span><span style="mso-ascii-font-family:Calibri;mso-hansi-font-family:Calibri;
color:black;mso-fareast-language:RU"><o:p></o:p></span></p>

<p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;text-align:
justify;line-height:normal"><span style="font-size:15px;
color:black;mso-fareast-language:
RU">2.1.5. Личное представление Исполнителем Заказчика в Государственных контролирующих органах, необходимое для решения спорных ситуаций с отправленными Документами, возникшие не по вине Исполнителя,
 или решения иных задач указанных в Заявке оплачиваются дополнительно в соответствии с действующим Прейскурантом.
  </span><span style="mso-ascii-font-family:Calibri;mso-hansi-font-family:Calibri;
color:black;mso-fareast-language:RU"><o:p></o:p></span></p>

<p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;text-align:
justify;line-height:normal"><span style="font-size:15px;
color:black;mso-fareast-language:
RU">2.1.6. В последние дни регламентирующих действующим законодательством сроков представления Документов в Государственные контролирующие органы  
РФ (далее Срок представления Документов) действует двойной тариф на любые оказываемые услуги.</span><span style="mso-ascii-font-family:Calibri;mso-hansi-font-family:Calibri;
color:black;mso-fareast-language:RU"><o:p></o:p></span></p>

<p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;text-align:
justify;line-height:normal"><span style="font-size:15px;
color:black;mso-fareast-language:
RU">2.1.7. Заблаговременное уведомление Заказчика об изменениях в законодательных актах, 
правилах приема и отправки отчетности, в обязанности Исполнителя не входит. </span><span style="mso-ascii-font-family:
Calibri;mso-hansi-font-family:Calibri;
color:black;mso-fareast-language:RU"><o:p></o:p></span></p>

<p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:
normal"><b><span style="font-size:15px;
color:black;mso-fareast-language:
RU">2.2. Выдача и регистрация Доверенности</span></b><span style="mso-ascii-font-family:Calibri;
mso-hansi-font-family:Calibri;
color:black;mso-fareast-language:RU"><o:p></o:p></span></p>

<p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;text-align:
justify;line-height:normal"><span style="font-size:15px;
color:black;mso-fareast-language:
RU">Все услуги осуществляются только при наличии у Исполнителя должным образом оформленной и зарегистрированной Доверенности:
</span><span style="mso-ascii-font-family:Calibri;mso-hansi-font-family:Calibri;
color:black;mso-fareast-language:RU"><o:p></o:p></span></p>






<p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;text-align:
justify;line-height:normal"><span style="font-size:15px;
color:black;mso-fareast-language:
RU">2.2.1. Доверенность, выдаваемая Заказчиком Исполнителю, может быть двух видов :
<br>- подписанная собственоручно Заказчиком и скреплена печатью,
<br>- заверена нотариально. 
</span><span style="mso-ascii-font-family:Calibri;mso-hansi-font-family:Calibri;
color:black;mso-fareast-language:RU"><o:p></o:p></span></p>

<p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;text-align:
justify;line-height:normal"><span style="font-size:15px;
color:black;mso-fareast-language:
RU">2.2.2. Исполнитель регистрирует выданную Заказчиком Доверенность и присваивает ей свой регистрационный номер;</span><span style="mso-ascii-font-family:Calibri;
mso-hansi-font-family:Calibri;
color:black;mso-fareast-language:RU"><o:p></o:p></span></p>

<p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;text-align:
justify;line-height:normal"><span style="font-size:15px;
color:black;mso-fareast-language:
RU">2.2.3. До подачи Заявки на оказание услуг Заказчик самостоятельно передает по одному экземпляру Доверенности
 во все Государственные контролирующие органы, в которые будут направляться Документы.</span><span style="mso-ascii-font-family:
Calibri;mso-hansi-font-family:Calibri;
color:black;mso-fareast-language:RU"><o:p></o:p></span></p>

<p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;text-align:
justify;line-height:normal"><span style="font-size:15px;
color:black;mso-fareast-language:
RU">2.2.4. Оригинал Доверенности с отметками госструктур  Заказчик выдает Исполнителю, а копию выданной Доверенности  с отметкой Исполнителя должен хранить у себя. </span><span
style="mso-ascii-font-family:Calibri;
mso-hansi-font-family:Calibri;
color:black;mso-fareast-language:RU"><o:p></o:p></span></p>

<p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:
normal"><b><span style="font-size:15px;
color:black;mso-fareast-language:
RU">2.3. Порядок приема и обработка Заявок</span></b><span style="mso-ascii-font-family:Calibri;
mso-hansi-font-family:Calibri;
color:black;mso-fareast-language:RU"><o:p></o:p></span></p>

<p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;text-align:
justify;line-height:normal"><span style="font-size:15px;
color:black;mso-fareast-language:
RU">2.3.1. Заявки принимаются в соответствии с Графиком работы с клиентами
</span><span style="mso-ascii-font-family:Calibri;mso-hansi-font-family:Calibri;
color:black;mso-fareast-language:RU"><o:p></o:p></span></p>






<p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;text-align:
justify;line-height:normal"><span style="font-size:15px;
color:black;mso-fareast-language:
RU">2.3.2. Заявка на оказание услуг оформляется Заказчиком в письменном виде. При регистрации Заявки Заказчику выдается 
Квитанция на получение Документов и платежные документы. Прием Заявок по телефону, через e-mail не допускается.
</span><span style="mso-ascii-font-family:Calibri;mso-hansi-font-family:Calibri;
color:black;mso-fareast-language:RU"><o:p></o:p></span></p>

<p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;text-align:
justify;line-height:normal"><span style="font-size:15px;
color:black;mso-fareast-language:
RU">2.3.3. Исполнитель самостоятельно без предварительного уведомления Заказчика, в зависимости от принятого объема работы в последние 4
 дня регламентирующих действующим законодательством сроков представления данных Документов в Государственные контролирующие органы 
 РФ (далее Срок представления Документов), может устанавливать время приема Заявок.</span><span style="mso-ascii-font-family:Calibri;
mso-hansi-font-family:Calibri;
color:black;mso-fareast-language:RU"><o:p></o:p></span></p>

<p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:
normal"><b><span style="font-size:15px;
color:black;mso-fareast-language:
RU">2.4. Правила оказания усуг при приеме Документов на магнитном носителе</span></b><span style="mso-ascii-font-family:Calibri;
mso-hansi-font-family:Calibri;
color:black;mso-fareast-language:RU"><o:p></o:p></span></p>

<p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;text-align:
justify;line-height:normal"><span style="font-size:15px;
color:black;mso-fareast-language:
RU">2.4.1. Отправка осуществляется в присутствии Заказчика, с выдачей подтверждения оператора об отправке, заверенное штампом сотрудника Исполнителя.
</span><span style="mso-ascii-font-family:Calibri;mso-hansi-font-family:Calibri;
color:black;mso-fareast-language:RU"><o:p></o:p></span></p>






<p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;text-align:
justify;line-height:normal"><span style="font-size:15px;
color:black;mso-fareast-language:
RU">2.4.2. При приеме Документов в электронном виде Исполнитель осуществляет только отправку данных документов.
 Контроль соответствия данных представленных на магнитном носителе его бумажному оригиналу, 
уведомление Заказчика о наличии отрицательного протокола контроля в обязанности Исполнителя не входит
</span><span style="mso-ascii-font-family:Calibri;mso-hansi-font-family:Calibri;
color:black;mso-fareast-language:RU"><o:p></o:p></span></p>

<p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;text-align:
justify;line-height:normal"><span style="font-size:15px;
color:black;mso-fareast-language:
RU">2.4.3. При отправке электронные Документы контролируются программо-аппаратным комплексом в соответствии с требованиями законодательства.
 Исправление ошибок Исполнителем оплачивается по действующему на момент отправки Прейскуранту. 
 Исправление Исполнителем фатальных ошибок (не соответствие представленного электронного документа утвержденному законадельством шаблону, грубое нарущение расчетов в Дкументе и пр.)
  не предусматривается. Подобные Документы принимаются для отправки на тех же условиях что и Документы представляемые на бумажном носителе.
  </span><span style="mso-ascii-font-family:Calibri;
mso-hansi-font-family:Calibri;
color:black;mso-fareast-language:RU"><o:p></o:p></span></p>
<br>
<br>
<br>
<br>
<p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:
normal"><b><span style="font-size:15px;
color:black;mso-fareast-language:
RU">2.5. Правила оказания услуг при приеме Документов на бумажном носителе</span></b><span style="mso-ascii-font-family:Calibri;
mso-hansi-font-family:Calibri;
color:black;mso-fareast-language:RU"><o:p></o:p></span></p>

<p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;text-align:
justify;line-height:normal"><span style="font-size:15px;
color:black;mso-fareast-language:
RU">2.5.1. Для формирования электронного формата Заказчик обязан предоставлять Исполнителю оригинал Документа на бумажном носителе, 
оформленный в соответствии с требованиями действующего законодательства, нормами положений о бухгалтерском учете и/или другими нормативными актами,
 с сылками на Доверенность.
  В случае ненадлежащего или неполного предоставления необходимой информации, Исполнитель не несет ответственность за качество услуг
</span><span style="mso-ascii-font-family:Calibri;mso-hansi-font-family:Calibri;
color:black;mso-fareast-language:RU"><o:p></o:p></span></p>



<p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;text-align:
justify;line-height:normal"><span style="font-size:15px;
color:black;mso-fareast-language:
RU">2.5.2. Формирование электронного шаблона и отправка Документа адресату производится в отсутствии Заказчика.
</span><span style="mso-ascii-font-family:Calibri;mso-hansi-font-family:Calibri;
color:black;mso-fareast-language:RU"><o:p></o:p></span></p>

<p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;text-align:
justify;line-height:normal"><span style="font-size:15px;
color:black;mso-fareast-language:
RU">2.5.3. Документы подготавливаются и отправляются в порядке их Сроков представления в Государственные контролирующие органы
  </span><span style="mso-ascii-font-family:Calibri;
mso-hansi-font-family:Calibri;
color:black;mso-fareast-language:RU"><o:p></o:p></span></p>

<p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;text-align:
justify;line-height:normal"><span style="font-size:15px;
color:black;mso-fareast-language:
RU">2.5.4.  При обнаружении Системой фатальных ошибок в представленных на бумажном носителе Документах во время отправки,
   Документ отправляется с исправленым Системой расчетом. 
Уточненный Документ в данном случае передается на общих основаниях за счет Заказчика. 
Исправление ошибок по телефону не допускается.
</span><span style="mso-ascii-font-family:Calibri;mso-hansi-font-family:Calibri;
color:black;mso-fareast-language:RU"><o:p></o:p></span></p>

<p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;text-align:
justify;line-height:normal"><span style="font-size:15px;
color:black;mso-fareast-language:
RU">2.5.5. При наличии отрицательного протокола контроля,
 Исполнитель устраняет ошибки, возникшие по его вине самостоятельно. 
 Уведомление Заказчика по телефону о наличии отрицательного протокола в обязанности Исполнителя не входит
  </span><span style="mso-ascii-font-family:Calibri;
mso-hansi-font-family:Calibri;
color:black;mso-fareast-language:RU"><o:p></o:p></span></p>

<p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:
normal"><b><span style="font-size:15px;
color:black;mso-fareast-language:
RU">2.6. Выдача протоколов контроля</span></b><span style="mso-ascii-font-family:Calibri;
mso-hansi-font-family:Calibri;
color:black;mso-fareast-language:RU"><o:p></o:p></span></p>

<p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;text-align:
justify;line-height:normal"><span style="font-size:15px;
color:black;mso-fareast-language:
RU">2.6.1. Возвратные экземпляры выдаются только при наличии должным образом оформленной Квитанции на получение документов в порядке общей очереди. 
</span><span style="mso-ascii-font-family:Calibri;mso-hansi-font-family:Calibri;
color:black;mso-fareast-language:RU"><o:p></o:p></span></p>



<p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;text-align:
justify;line-height:normal"><span style="font-size:15px;
color:black;mso-fareast-language:
RU">2.6.2. По завершении документооборота но не не ранее 2 (двух) дней со дня получения подтверждения от адресата Заказчику возвращается оригинал предоставленного 
Документа на бумажном носителе со служебными отметками Исполнителя.
 Выдается протокол контроля, или квитанция о приеме документа, либо уведомление об отказе приема документа.  
</span><span style="mso-ascii-font-family:Calibri;mso-hansi-font-family:Calibri;
color:black;mso-fareast-language:RU"><o:p></o:p></span></p>

<p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;text-align:
justify;line-height:normal"><span style="font-size:15px;
color:black;mso-fareast-language:
RU">2.6.3. В последние 2 (два) дня Сроков представления Документов, возвратные экземпляры не выдаются.
  </span><span style="mso-ascii-font-family:Calibri;
mso-hansi-font-family:Calibri;
color:black;mso-fareast-language:RU"><o:p></o:p></span></p>

<p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;text-align:
justify;line-height:normal"><span style="font-size:15px;
color:black;mso-fareast-language:
RU">2.6.4. Не востребованные в течение 90(девяносто) дней с момента предоставления оригиналы представленных Документов Заказчиком подлежат уничтожению. 
За распечатку Заказчику копии отправленных Документов взимается дополнительная плата.
</span><span style="mso-ascii-font-family:Calibri;mso-hansi-font-family:Calibri;
color:black;mso-fareast-language:RU"><o:p></o:p></span></p>



<p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:
normal"><b><span style="font-size:15px;
color:black;mso-fareast-language:
RU">2.7. Условия при которых Заказчику может быть отказано в оказании услуг/span></b><span style="mso-ascii-font-family:Calibri;
mso-hansi-font-family:Calibri;
color:black;mso-fareast-language:RU"><o:p></o:p></span></p>

<p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;text-align:
justify;line-height:normal"><span style="font-size:15px;
color:black;mso-fareast-language:
RU">Исполнитель имеет право отказать в оказании услуги по следующим причинам:
</span><span style="mso-ascii-font-family:Calibri;mso-hansi-font-family:Calibri;
color:black;mso-fareast-language:RU"><o:p></o:p></span></p>



<p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;text-align:
justify;line-height:normal"><span style="font-size:15px;
color:black;mso-fareast-language:
RU">2.7.1. при наличие фатальной ошибки в представленном электронном Документе - до устранения ошибок. После устранения ошибок, Заявка Заказчика принимается на общих основаниях в порядке очереди 
</span><span style="mso-ascii-font-family:Calibri;mso-hansi-font-family:Calibri;
color:black;mso-fareast-language:RU"><o:p></o:p></span></p>

<p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;text-align:
justify;line-height:normal"><span style="font-size:15px;
color:black;mso-fareast-language:
RU">2.7.2. при отсутствии у Исполнителя должным образом оформленной Доверенности, выданной Заказчиком. 
  </span><span style="mso-ascii-font-family:Calibri;
mso-hansi-font-family:Calibri;
color:black;mso-fareast-language:RU"><o:p></o:p></span></p>







<p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:
normal"><b><span style="font-size:14.0pt;
color:black;mso-fareast-language:
RU">3. ОБЯЗАННОСТИ СТОРОН</span></b><span
style="mso-ascii-font-family:Calibri;
mso-hansi-font-family:Calibri;
color:black;mso-fareast-language:RU"><o:p></o:p></span></p>

<p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:
normal"><b><span style="font-size:15px;
color:black;mso-fareast-language:
RU">3.1. Исполнитель обязан:</span></b><span style="mso-ascii-font-family:Calibri;
mso-hansi-font-family:Calibri;
color:black;mso-fareast-language:RU"><o:p></o:p></span></p>

<p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;text-align:
justify;line-height:normal"><span style="font-size:15px;
color:black;mso-fareast-language:
RU">3.1.1. Отправить электронные форматы принятых и подготовленных Документов Заказчика, заверив электронно-цифровой подписью Никитенко М.И. через ТКС не позднее Срока представления Документов
</span><span style="mso-ascii-font-family:Calibri;mso-hansi-font-family:Calibri;
color:black;mso-fareast-language:RU"><o:p></o:p></span></p>



<p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;text-align:
justify;line-height:normal"><span style="font-size:15px;
color:black;mso-fareast-language:
RU">3.1.2.  При поступлении от Заказчика неформализованных Документов (письма, сообщения и пр.),
 отправить их адресату в оговоренные в каждом отдельном случае сроки,
  при не указании сроков - в течении пяти рабочих дней с момента поступления Заявки.  
</span><span style="mso-ascii-font-family:Calibri;mso-hansi-font-family:Calibri;
color:black;mso-fareast-language:RU"><o:p></o:p></span></p>

<p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;text-align:
justify;line-height:normal"><span style="font-size:15px;
color:black;mso-fareast-language:
RU">3.1.3. При подготовке электронного формата Документа, представленного Заказчиком на бумажном носителе,
 подготовить электронный Документ в соответствии с утвержденными законодательством форматами, 
 а ткже обеспечить соответствие сведений в электронном формате бумажному носителю. 
  </span><span style="mso-ascii-font-family:Calibri;
mso-hansi-font-family:Calibri;
color:black;mso-fareast-language:RU"><o:p></o:p></span></p>

<p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;text-align:
justify;line-height:normal"><span style="font-size:15px;
color:black;mso-fareast-language:
RU">3.1.4. Обязанности Исполнителя по каждой Заявке считаются выполненными при наличии подтверждения оператора связи об отпраке или наличии квитанции о доставке документа адресату не зависимо от содержания протокола контроля. 
</span><span style="mso-ascii-font-family:Calibri;mso-hansi-font-family:Calibri;
color:black;mso-fareast-language:RU"><o:p></o:p></span></p>

<p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;text-align:
justify;line-height:normal"><span style="font-size:15px;
color:black;mso-fareast-language:
RU">3.1.5. При получении отрицательного протокола контроля из-за искажения Исполнителем предоставленной Заказчиком информации,
 Исполнитель обязан отправить Документы повторно или направить 
 адресату уточненный Документ за свой счет. Во всех остальных случаях повторная отправка Документов оплачивается дополнительно. 
  </span><span style="mso-ascii-font-family:Calibri;
mso-hansi-font-family:Calibri;
color:black;mso-fareast-language:RU"><o:p></o:p></span></p>

<p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;text-align:
justify;line-height:normal"><span style="font-size:15px;
color:black;mso-fareast-language:
RU">3.1.6. При выдаче Протоколов контроля подписать и заверить Расписку о выдаче документов, подтверждающую исполнение Заказчиком своих обязательств в соответствии с п.3.2.7. настоящего Договора.
  </span><span style="mso-ascii-font-family:Calibri;
mso-hansi-font-family:Calibri;
color:black;mso-fareast-language:RU"><o:p></o:p></span></p>




<p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:
normal"><b><span style="font-size:15px;
color:black;mso-fareast-language:
RU">3.2. Обязанности Заказчика:</span></b><span style="mso-ascii-font-family:Calibri;
mso-hansi-font-family:Calibri;
color:black;mso-fareast-language:RU"><o:p></o:p></span></p>

<p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;text-align:
justify;line-height:normal"><span style="font-size:15px;
color:black;mso-fareast-language:
RU">3.2.1. Выдать Исполнителю до момента передачи Документов Заказчика по ТКС, 
оформленную должным образом Доверенность на право представления интересов Заказчика в Государственных контролирующих органах 
в соответствии с настоящим Договором. 
</span><span style="mso-ascii-font-family:Calibri;mso-hansi-font-family:Calibri;
color:black;mso-fareast-language:RU"><o:p></o:p></span></p>



<p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;text-align:
justify;line-height:normal"><span style="font-size:15px;
color:black;mso-fareast-language:
RU">3.2.2. Следить за наличием и сроком действия Доверенности.
 При истечении срока действия Доверенности или смене руководителя Заказчика,
  заблаговременно не позднее чем за 2(два) дня до предачи очередных Документов, выдать новую Доверенность. 
</span><span style="mso-ascii-font-family:Calibri;mso-hansi-font-family:Calibri;
color:black;mso-fareast-language:RU"><o:p></o:p></span></p>

<p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;text-align:
justify;line-height:normal"><span style="font-size:15px;
color:black;mso-fareast-language:
RU">3.2.3. При предоставлении Документов в ИЦ «SAVOIR» или получении возвратных экземпляров по требованию сотрудников ИЦ «SAVOIR» предъявить документ, удостоверяющий личность.  
  </span><span style="mso-ascii-font-family:Calibri;
mso-hansi-font-family:Calibri;
color:black;mso-fareast-language:RU"><o:p></o:p></span></p>

<p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;text-align:
justify;line-height:normal"><span style="font-size:15px;
color:black;mso-fareast-language:
RU">3.2.4. Направлять Заявку Исполнителю на оказание услуг в письменном виде, 
заверенную должным образом в срок не позднее 2(двух) дней до последнего дня Сроков представления Документов,
 а для передачи Документов в Пенсионный фонд РФ не позднее  4 (четырех) дней. 
</span><span style="mso-ascii-font-family:Calibri;mso-hansi-font-family:Calibri;
color:black;mso-fareast-language:RU"><o:p></o:p></span></p>

<p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;text-align:
justify;line-height:normal"><span style="font-size:15px;
color:black;mso-fareast-language:
RU">3.2.5. Предоставлять Документы,  с обязательным указанием действующего номера  телефона ответственного лица сотрудника Заказчика, 
по которому сотрудник ИЦ «SAVOIR» может уточнить возникшие вопросы при подготовке электронного формата Документа.
 Обеспечить связь по указанному телефону вплоть до получения Возвратного экземпляра Документа Заказчиком с 8:00 до 19:00 ежедневно без выходных,
  а в последний день Срока предоставления Документов с 8:00 до 23:00.
 Исполнитель не несет ответственности за качество услуги в случае отсутствия возможности связаться с ответственным лицом Заказчика по телефону.
  </span><span style="mso-ascii-font-family:Calibri;
mso-hansi-font-family:Calibri;
color:black;mso-fareast-language:RU"><o:p></o:p></span></p>

<p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;text-align:
justify;line-height:normal"><span style="font-size:15px;
color:black;mso-fareast-language:
RU">3.2.6. Оплатить услуги Исполнителя в размере и в срок, предусмотренный настоящим договором;
  </span><span style="mso-ascii-font-family:Calibri;
mso-hansi-font-family:Calibri;
color:black;mso-fareast-language:RU"><o:p></o:p></span></p>

<p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;text-align:
justify;line-height:normal"><span style="font-size:15px;
color:black;mso-fareast-language:
RU">3.2.7. Забрать возвратный экземпляр Документа в офисе ИЦ «SAVOIR» не позднее 14 (четырнадцати) календарных дней с момента истечения Срока представления Документов. 
</span><span style="mso-ascii-font-family:Calibri;mso-hansi-font-family:Calibri;
color:black;mso-fareast-language:RU"><o:p></o:p></span></p>



<p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;text-align:
justify;line-height:normal"><span style="font-size:15px;
color:black;mso-fareast-language:
RU">3.2.8. При получении Возвратных экземпляров Документов, протоколов контроля, выписок и прочих Документов представитель Заказчика обязан проверить
 соответствие данных в предоставленном оригинале Документа и протоколах отправки и контроля,
 а так же наличие на них и оригиналах предоставленных Документов служебных отметок.  
</span><span style="mso-ascii-font-family:Calibri;mso-hansi-font-family:Calibri;
color:black;mso-fareast-language:RU"><o:p></o:p></span></p>

<p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;text-align:
justify;line-height:normal"><span style="font-size:15px;
color:black;mso-fareast-language:
RU">3.2.9. Минимум один раз в год, до 30 (тридцатого) мая, следующего за отчетным периодом года, произвести сверку с Государственными контролирующими органами на предмет полного представления Документов по ТКС. 
  </span><span style="mso-ascii-font-family:Calibri;
mso-hansi-font-family:Calibri;
color:black;mso-fareast-language:RU"><o:p></o:p></span></p>

<p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;text-align:
justify;line-height:normal"><span style="font-size:15px;
color:black;mso-fareast-language:
RU">3.2.10. сохранять платежные документы, Расписку о выдаче документов,
 возвратные экземпляры и оригиналы Документов со служебными отметками в течении срока установленного законодательством для хранения бухгалтерской и налоговой документации.
</span><span style="mso-ascii-font-family:Calibri;mso-hansi-font-family:Calibri;
color:black;mso-fareast-language:RU"><o:p></o:p></span></p>

<p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;text-align:
justify;line-height:normal"><span style="font-size:15px;
color:black;mso-fareast-language:
RU">3.2.11. При получении отрицательного протокола контроля,
 Заказчик обязан самостоятельно провести сверку с Государственными контролирующими органами, исправить ошибки и требования адресата.  
  </span><span style="mso-ascii-font-family:Calibri;
mso-hansi-font-family:Calibri;
color:black;mso-fareast-language:RU"><o:p></o:p></span></p>


<p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:
normal"><b><span style="font-size:14.0pt;
color:black;mso-fareast-language:
RU">4. ЦЕНА ДОГОВОРА И ПОРЯДОК РАСЧЕТОВ</span></b><span
style="mso-ascii-font-family:Calibri;
mso-hansi-font-family:Calibri;
color:black;mso-fareast-language:RU"><o:p></o:p></span></p>

<p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;text-align:
justify;line-height:normal"><span style="font-size:15px;
color:black;mso-fareast-language:
RU">4.1. Стоимость услуг, оказанных в рамках настоящего договора, определяется в соответствии с действующим Прейскурантом.
</span><span style="mso-ascii-font-family:Calibri;mso-hansi-font-family:Calibri;
color:black;mso-fareast-language:RU"><o:p></o:p></span></p>



<p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;text-align:
justify;line-height:normal"><span style="font-size:15px;
color:black;mso-fareast-language:
RU">4.2. Оплата услуг осуществляется наличным расчетом по предоплате, безналичным расчетом не позднее 3-х дней с момента поступления Заявки.  
</span><span style="mso-ascii-font-family:Calibri;mso-hansi-font-family:Calibri;
color:black;mso-fareast-language:RU"><o:p></o:p></span></p>

<p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;text-align:
justify;line-height:normal"><span style="font-size:15px;
color:black;mso-fareast-language:
RU">4.3. При нарушении сроков оплаты взимается пеня в размере 0,3%  от стоимости  выполненных работ за каждый день просрочки. 
  </span><span style="mso-ascii-font-family:Calibri;
mso-hansi-font-family:Calibri;
color:black;mso-fareast-language:RU"><o:p></o:p></span></p>

<p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;text-align:
justify;line-height:normal"><span style="font-size:15px;
color:black;mso-fareast-language:
RU">4.4. Исполнитель имеет право изменять Прейскурант услуг без предварительного согласования с Заказчиком, обеспечивая при этом публикацию измененных условий на стендах и имеющихся на момент изменений у Исполнителя информационных ресурсах. 
При этом перерасчет оплаченных Заказчиком по предоплате услуг не производится, и оплаченные услуги оказываются в полном объеме.
</span><span style="mso-ascii-font-family:Calibri;mso-hansi-font-family:Calibri;
color:black;mso-fareast-language:RU"><o:p></o:p></span></p>

<br>
<br>
<br>
<br>
<br>
<p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:
normal"><b><span style="font-size:14.0pt;
color:black;mso-fareast-language:
RU">5. КОНФИДИЦИАЛЬНОСТЬ</span></b><span
style="mso-ascii-font-family:Calibri;
mso-hansi-font-family:Calibri;
color:black;mso-fareast-language:RU"><o:p></o:p></span></p>

<p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;text-align:
justify;line-height:normal"><span style="font-size:15px;
color:black;mso-fareast-language:
RU">5.1. Стороны обязуются сохранять строгую конфиденциальность информации, полученной в ходе исполнения настоящего Договора,
 и принять все возможные меры, чтобы предохранить полученную информацию от разглашения третьим лицам.
</span><span style="mso-ascii-font-family:Calibri;mso-hansi-font-family:Calibri;
color:black;mso-fareast-language:RU"><o:p></o:p></span></p>



<p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;text-align:
justify;line-height:normal"><span style="font-size:15px;
color:black;mso-fareast-language:
RU">5.2. Исполнитель не несет ответственности в случае передачи информации Государственным органам, имеющим право ее затребовать в соответствии с законодательством Российской Федерации.
 При получении запроса о предоставлении любой информации о Заказчике от Государственных органов,
 Исполнитель обязан не позднее дня, следующего за датой получения запроса, уведомить об этом Заказчика 
</span><span style="mso-ascii-font-family:Calibri;mso-hansi-font-family:Calibri;
color:black;mso-fareast-language:RU"><o:p></o:p></span></p>

<p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;text-align:
justify;line-height:normal"><span style="font-size:15px;
color:black;mso-fareast-language:
RU">5.3. В соответствии с требованиями Закона от 27.07.06 г. «О персональных данных» № 152-ФЗ Стороны обязуются соблюдать условия Положения по обработке информации содержащей персональные данные  
(Приложение №1)
  </span><span style="mso-ascii-font-family:Calibri;
mso-hansi-font-family:Calibri;
color:black;mso-fareast-language:RU"><o:p></o:p></span></p>

<p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;text-align:
justify;line-height:normal"><span style="font-size:15px;
color:black;mso-fareast-language:
RU">5.4. Исполнитель не несет ответственности за разглашение информации Заказчика являющейся по закону общедоступной или разглашенной самим Заказчиком.
</span><span style="mso-ascii-font-family:Calibri;mso-hansi-font-family:Calibri;
color:black;mso-fareast-language:RU"><o:p></o:p></span></p>

<p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:
normal"><b><span style="font-size:14.0pt;
color:black;mso-fareast-language:
RU">6. ОТВЕТСТВЕННОСТЬ СТОРОН</span></b><span style="mso-ascii-font-family:
Calibri;mso-hansi-font-family:Calibri;
color:black;mso-fareast-language:RU"><o:p></o:p></span></p>

<p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;text-align:
justify;line-height:normal"><span style="font-size:15px;
color:black;mso-fareast-language:
RU">6.1. Невыполнение или ненадлежащее выполнение обязательств по настоящему Договору при наличии доказанной вины одной из Сторон,
 Стороны несут друг перед другом  имущественную ответственность в соответствии с законодательством Российской Федерации.</span><span
style="mso-ascii-font-family:Calibri;
mso-hansi-font-family:Calibri;
color:black;mso-fareast-language:RU"><o:p></o:p></span></p>

<p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;text-align:
justify;line-height:normal"><span style="font-size:15px;
color:black;mso-fareast-language:
RU">6.2. В случае нарушения Сроков представления Документов Заказчика в Государственные контролирующие органы по вине  Исполнителя - отсутствие подтверждения об отправке документа и отсутствие нарушений 
Заказчиком своих обязательств  указанных в разделе 3 настоящего Договора, Исполнитель при наличии требования контролирующего органа к Заказчику об уплате штрафов, 
Исполнитель обязан возместить Заказчику ущерб в размере минимального штрафа, в соответствии со статьей 119 Налогового Кодекса РФ подлежащего уплате за 1(один) полный или неполный месяц просрочки. </span><span style="mso-ascii-font-family:
Calibri;mso-hansi-font-family:Calibri;
color:black;mso-fareast-language:RU"><o:p></o:p></span></p>


<p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;text-align:
justify;line-height:normal"><span style="font-size:15px;
color:black;mso-fareast-language:
RU">6.3. Иная ответственность по любым искам и претензиям ограничивается суммами, полученными или подлежащими получению Исполнителем,
 в соответствии с условиями настоящего Договора при оказании услуг по каждой отдельно взятой Заявке. 
</span><span style="mso-ascii-font-family:Calibri;mso-hansi-font-family:Calibri;
color:black;mso-fareast-language:RU"><o:p></o:p></span></p>



<p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;text-align:
justify;line-height:normal"><span style="font-size:15px;
color:black;mso-fareast-language:
RU">6.4. Стороны освобождаются от ответственности по настоящему Договору в случае возникновения обстоятельств непреодолимой силы, таких как стихийные бедствия, массовые беспорядки, 
террористические акты, противоправные действия третьих лиц, если сторона предъявляет доказательства того, что эти обстоятельства воспрепятствовали исполнению своих обязательств по Договору 
</span><span style="mso-ascii-font-family:Calibri;mso-hansi-font-family:Calibri;
color:black;mso-fareast-language:RU"><o:p></o:p></span></p>

<p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;text-align:
justify;line-height:normal"><span style="font-size:15px;
color:black;mso-fareast-language:
RU">6.5. При передаче Документов по ТКС своей подписью Исполнитель подтверждает только факт наличия поступившего Документа от Заказчика для передачи адресату.
 Ответственность за содержание, достоверность и полноту передаваемой информации возлагается на Заказчика.
  </span><span style="mso-ascii-font-family:Calibri;
mso-hansi-font-family:Calibri;
color:black;mso-fareast-language:RU"><o:p></o:p></span></p>

<p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;text-align:
justify;line-height:normal"><span style="font-size:15px;
color:black;mso-fareast-language:
RU">6.6. Исполнитель не несет ответственности: 
</span><span style="mso-ascii-font-family:Calibri;mso-hansi-font-family:Calibri;
color:black;mso-fareast-language:RU"><o:p></o:p></span></p>

<p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;text-align:
justify;line-height:normal"><span style="font-size:15px;
color:black;mso-fareast-language:
RU">6.6.1. за качество услуги при нарушении Заказчиком условий настоящего Договора;
  </span><span style="mso-ascii-font-family:Calibri;
mso-hansi-font-family:Calibri;
color:black;mso-fareast-language:RU"><o:p></o:p></span></p>

<p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;text-align:
justify;line-height:normal"><span style="font-size:15px;
color:black;mso-fareast-language:
RU">6.6.2. за какие либо последствия,  возникающие у Заказчика с Государственными контролирующими органами,
 если электронные Документы, переданные по ТКС будут признаны не действительными или аннулированы по причине отсутствия у Исполнителя или истечения срока выданной
 Заказчиком доверенности на момент отправки по ТКС электронного Документа;
  </span><span style="mso-ascii-font-family:Calibri;
mso-hansi-font-family:Calibri;
color:black;mso-fareast-language:RU"><o:p></o:p></span></p>

<p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;text-align:
justify;line-height:normal"><span style="font-size:15px;
color:black;mso-fareast-language:
RU">6.6.3. за получение от адресата отрицательного протокола контроля, кроме случаев указанных в п. 3.1.5.  
</span><span style="mso-ascii-font-family:Calibri;mso-hansi-font-family:Calibri;
color:black;mso-fareast-language:RU"><o:p></o:p></span></p>



<p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;text-align:
justify;line-height:normal"><span style="font-size:15px;
color:black;mso-fareast-language:
RU">6.6.4. за сроки получения ответов от адресатов, содержание протоколов и уведомлений.  
</span><span style="mso-ascii-font-family:Calibri;mso-hansi-font-family:Calibri;
color:black;mso-fareast-language:RU"><o:p></o:p></span></p>

<p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;text-align:
justify;line-height:normal"><span style="font-size:15px;
color:black;mso-fareast-language:
RU">6.7. Если в течение 14 (четырнадцати) календарных дней с даты истечения Срока представления Документов по каждму отдельно взятоу Документу,
 Исполнителю не поступила претензия,
 то услуги считаются оказанными надлежащим образом, в полном объеме, качественно и в срок. Претензии по истечении этого срока не принимаются.
  </span><span style="mso-ascii-font-family:Calibri;
mso-hansi-font-family:Calibri;
color:black;mso-fareast-language:RU"><o:p></o:p></span></p>

<p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;text-align:
justify;line-height:normal"><span style="font-size:15px;
color:black;mso-fareast-language:
RU">6.8. В случае возникновения спорных ситуаций, претензии со стороны Заказчика принимаются только по предъявлении им оригинала Возвратного экземпляра
 Документа оформленного в соответствии с требованием законодательства и наличием на нем служебных отметок,
 платежного документа и Расписки о выдаче Документов.  
</span><span style="mso-ascii-font-family:Calibri;mso-hansi-font-family:Calibri;
color:black;mso-fareast-language:RU"><o:p></o:p></span></p>

<p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;text-align:
justify;line-height:normal"><span style="font-size:15px;
color:black;mso-fareast-language:
RU">6.9. Споры по настоящему Договору рассматриваются сторонами обоюдно, а при не достижении соглашения  в судебном порядке. 
  </span><span style="mso-ascii-font-family:Calibri;
mso-hansi-font-family:Calibri;
color:black;mso-fareast-language:RU"><o:p></o:p></span></p>

<p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:
normal"><b><span style="font-size:14.0pt;
color:black;mso-fareast-language:
RU">7. СРОК ДЕЙСТВИЯ И ПОРЯДОК РАСТОРЖЕНИЯ ДОГОВОРА</span></b><span style="mso-ascii-font-family:
Calibri;mso-hansi-font-family:Calibri;
color:black;mso-fareast-language:RU"><o:p></o:p></span></p>

<p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;text-align:
justify;line-height:normal"><span style="font-size:15px;
color:black;mso-fareast-language:
RU">7.1. Настоящий Договор вступает в силу с момента оказания регистрации первой Заявки Заказчика действует до выполнения Сторонами своих обязательств.</span><span
style="mso-ascii-font-family:Calibri;
mso-hansi-font-family:Calibri;
color:black;mso-fareast-language:RU"><o:p></o:p></span></p>

<p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;text-align:
justify;line-height:normal"><span style="font-size:15pt;
color:black;mso-fareast-language:
RU">7.2. Любая из сторон вправе расторгнуть Договор в одностороннем порядке. 
Расторжение договора не освобождает стороны от выполнения обязательств, возникших до расторжения настоящего Договора; </span><span style="mso-ascii-font-family:
Calibri;mso-hansi-font-family:Calibri;
color:black;mso-fareast-language:RU"><o:p></o:p></span></p>


<p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;text-align:
justify;line-height:normal"><span style="font-size:15px;
color:black;mso-fareast-language:
RU">7.3. Исполнитель имеет право в одностороннем порядке изменить условия настоящего Договора без указания причин. 
 В случае внесения Исполнителем изменений в Договор, такие изменения вступают в силу с момента опубликования. 
</span><span style="mso-ascii-font-family:Calibri;mso-hansi-font-family:Calibri;
color:black;mso-fareast-language:RU"><o:p></o:p></span></p>



<p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;text-align:
justify;line-height:normal"><span style="font-size:15px;
color:black;mso-fareast-language:
RU">7.4. Заказчик соглашается и признает, что внесение изменений в Договор влечет за собой внесение этих изменений в заключенный и действующий между Заказчиком и Исполнителем Договор,
 и эти изменения в Договор вступают в силу одновременно с вступлением в силу таких изменений в Договор.
</span><span style="mso-ascii-font-family:Calibri;mso-hansi-font-family:Calibri;
color:black;mso-fareast-language:RU"><o:p></o:p></span></p>

<p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;text-align:
justify;line-height:normal"><span style="font-size:15px;
color:black;mso-fareast-language:
RU">7.5. Настоящий договор составлен в одном экземпляре, депозитарием которого является ИЦ«SAVOIR». 
Исполнитель обязан выдавать копию настоящего Договора, заверенную им же, по заявлению любого Заказчика
  </span><span style="mso-ascii-font-family:Calibri;
mso-hansi-font-family:Calibri;
color:black;mso-fareast-language:RU"><o:p></o:p></span></p>


<p class=MsoNormal style=""><b><span style="font-size:14.0pt;
color:black;mso-fareast-language:
RU">8. АДРЕС, РЕКВИЗИТЫ ИСПОЛНИТЕЛЯ</span></b><span style="mso-ascii-font-family:
Calibri;mso-hansi-font-family:Calibri;
color:black;mso-fareast-language:RU"><o:p></o:p></span></p>
<table class="container"style="
    width: 100%;
    font-size: 12px;
">
<tr>
<td  valign=top style="width:100%;padding:0cm 0cm 0cm 0cm">
<div style="    width: 100%;">
<img src="/img/dogovor1.png" style="
">
  </div>
  </td>
</tr>
</table>
<table class="container"style="
    width: 100%;
    font-size: 12px;
">
<tr>
<td  valign=top style="width:100%;padding:0cm 0cm 0cm 0cm">
<div style="    width: 100%;">
<img src="/img/dogovor2.jpg" style="
">
  </div>
  </td>
</tr>
</table>
<p class=MsoNormal><o:p>&nbsp;</o:p></p>

</div>

</body>

</html>';

echo $str;

fwrite($fp, $str);

fclose($fp);
?>