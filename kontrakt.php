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
<meta http-equiv=Content-Type content="text/html; charset=windows-1251">
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
style="font-size:12.0pt;color:black;mso-fareast-language:RU">Контракт № '.$schet['god'].$schet['kto'].$schet['otdel'].$schet['nomerdog'].'</span></b></a><span
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
  <td width=362 valign=top style="width:271.15pt;padding:0cm 5.4pt 0cm 5.4pt">
  <p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal"><span style="font-size:8.0pt;
  mso-fareast-language:RU">г.
  Пятигорск</span><span style="mso-ascii-font-family:Calibri;mso-hansi-font-family:Calibri;
  mso-fareast-language:RU"><o:p></o:p></span></p>
  </td>
  <td width=362 valign=top style="width:271.2pt;padding:0cm 5.4pt 0cm 5.4pt">
  <p class=MsoNormal align=right style="margin-bottom:0cm;margin-bottom:.0001pt;
  text-align:right;line-height:normal"><span style="font-size:8.0pt;mso-fareast-language:
  RU">от '.$schet['d'].'.'.$schet['m'].'.'.$schet['y'].' г.</span><span style="mso-ascii-font-family:Calibri;
  mso-hansi-font-family:Calibri;
  mso-fareast-language:RU"><o:p></o:p></span></p>
  </td>
 </tr>
</table>

<p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;text-align:
justify;line-height:normal"><span class=GramE><span style="mso-ascii-font-family:
Calibri;mso-hansi-font-family:Calibri;
color:black;mso-fareast-language:RU">'.$klient['naim'].'</span><span style="font-size:8.0pt;
color:black;mso-fareast-language:
RU">&nbsp;именуем'; 

if($ogip == 15  || $ogip < 15 ){
$str .= 'ый ';
}else if($ogip == 13 || $ogip < 13){
$str .= 'ое ';
}else{
$str .= $ogip;
}
$str .= 'в дальнейшем «Заказчик» в лице '.$personlis['dol'].' ';
$str .= strstr($personlis['fio']," ", true);
$str .= mb_substr(strstr($personlis['fio']," "),0,2,'UTF-8').".";
$str .= mb_substr(strstr($srachnah," "),0,2,'UTF-8');
$str .= '&nbsp;действующего на
основании</span><span style="mso-ascii-font-family:Calibri;mso-hansi-font-family:Calibri;
color:black;mso-fareast-language:RU">&nbsp;'.$personlis['naosnovanii'].'&nbsp;</span><span
style="font-size:8.0pt;color:black;mso-fareast-language:RU">с одной стороны, и&nbsp;
'.$savoir['name'].', в лице '.$savoir['headog'].'</span><span style="mso-ascii-font-family:Calibri;mso-hansi-font-family:Calibri;
color:black;mso-fareast-language:RU"><o:p></o:p></span></p>

<p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:
normal"><span style="font-size:8.0pt;
color:black;mso-fareast-language:
RU">&nbsp;</span><span style="mso-ascii-font-family:Calibri;mso-hansi-font-family:Calibri;
color:black;mso-fareast-language:RU"><o:p></o:p></span></p>

<p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;text-align:
justify;line-height:normal"><b><span style="font-size:8.0pt;
color:black;mso-fareast-language:
RU">1. ПРЕДМЕТ КОНТРАКТА</span></b><span style="mso-ascii-font-family:Calibri;
mso-hansi-font-family:Calibri;
color:black;mso-fareast-language:RU"><o:p></o:p></span></p>

<p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;text-align:
justify;line-height:normal"><span style="font-size:8.0pt;
color:black;mso-fareast-language:
RU">1.1. Заказчик поручает, а Исполнитель принимает на себя обязательства по
установке, настройке, сопровождению на рабочем месте Заказчика программных
комплексов (далее - ПК)&nbsp; и оборудования в соответствии со Спецификацией
(Приложение №1 к настоящему Контракту)<span class=GramE> .</span></span><span
style="mso-ascii-font-family:Calibri;
mso-hansi-font-family:Calibri;
color:black;mso-fareast-language:RU"><o:p></o:p></span></p>

<p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;text-align:
justify;line-height:normal"><span style="font-size:8.0pt;
color:black;mso-fareast-language:
RU">1.2. Если оказание услуг осуществляется в несколько этапов, то
предварительный График оказания услуг составляется при первом посещении
Заказчика&nbsp; и может изменяться по договоренности сторон.</span><span
style="mso-ascii-font-family:Calibri;
mso-hansi-font-family:Calibri;
color:black;mso-fareast-language:RU"><o:p></o:p></span></p>

<p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;text-align:
justify;line-height:normal"><span style="font-size:8.0pt;
color:black;mso-fareast-language:
RU">1.3. Если оказание услуг разбивается на этапы по инициативе Заказчика или
при нарушении п.2.2.1., то каждый выезд специалиста оплачивается отдельно в
соответствии с действующим прейскурантом.</span><span style="mso-ascii-font-family:
Calibri;mso-hansi-font-family:Calibri;
color:black;mso-fareast-language:RU"><o:p></o:p></span></p>

<p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;text-align:
justify;line-height:normal"><span style="font-size:8.0pt;
color:black;mso-fareast-language:
RU">1.4. Консультирование по телефону по работе с установленными ПК,
информирование об изменениях, связанных с работой ПК по телефону, <span
class=SpellE>e-mail</span>, через размещение информации на сайте осуществляется
Исполнителем бесплатно.</span><span style="mso-ascii-font-family:Calibri;
mso-hansi-font-family:Calibri;
color:black;mso-fareast-language:RU"><o:p></o:p></span></p>

<p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;text-align:
justify;line-height:normal"><span style="font-size:8.0pt;
color:black;mso-fareast-language:
RU">&nbsp;</span><span style="mso-ascii-font-family:Calibri;mso-hansi-font-family:Calibri;
color:black;mso-fareast-language:RU"><o:p></o:p></span></p>

<p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:
normal"><b><span style="font-size:8.0pt;
color:black;mso-fareast-language:
RU">2. ОТВЕТСТВЕННОСТЬ СТОРОН</span></b><span style="mso-ascii-font-family:
Calibri;mso-hansi-font-family:Calibri;
color:black;mso-fareast-language:RU"><o:p></o:p></span></p>

<p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:
normal"><b><span style="font-size:8.0pt;
color:black;mso-fareast-language:
RU">2.1. Исполнитель обязан:</span></b><span style="mso-ascii-font-family:Calibri;
mso-hansi-font-family:Calibri;
color:black;mso-fareast-language:RU"><o:p></o:p></span></p>

<p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;text-align:
justify;line-height:normal"><span style="font-size:8.0pt;
color:black;mso-fareast-language:
RU">2.1.1. Исполнитель обязан приступить к оказанию услуг в течение 5 дней <span
class=GramE>с даты поступления</span> денежных средств Исполнителю или в
предварительно оговоренный срок.</span><span style="mso-ascii-font-family:Calibri;
mso-hansi-font-family:Calibri;
color:black;mso-fareast-language:RU"><o:p></o:p></span></p>

<p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;text-align:
justify;line-height:normal"><span style="font-size:8.0pt;
color:black;mso-fareast-language:
RU">2.1.2. Соблюдать График оказания услуг, а при его нарушении по независящим
от Исполнителя причинам, своевременно, не <span class=GramE>позднее</span> чем
за 1 (один час), сообщить заказчику об изменениях и откорректировать дату
очередного посещения</span><span style="mso-ascii-font-family:Calibri;
mso-hansi-font-family:Calibri;
color:black;mso-fareast-language:RU"><o:p></o:p></span></p>

<p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;text-align:
justify;line-height:normal"><span style="font-size:8.0pt;
color:black;mso-fareast-language:
RU">2.1.3. Настроить ПК, установить обновления ПК, связанных с налоговой
отчетностью, не позднее сроков сдачи налоговой и бухгалтерской отчетности. При
невозможности соблюсти данные сроки по вине Исполнителя, Исполнитель обязан
способствовать сдаче отчетности Заказчика в Государственные контролирующие
органы через собственные телекоммуникационные каналы связи при условии
предоставления отчетности на магнитном носителе в офис Исполнителя, без
взимания платы. При невозможности соблюсти данные сроки из-за неустановленных
обновлений ПК по вине Заказчика, отчетность может быть отправлена в
соответствии с действующим прейскурантом.</span><span style="mso-ascii-font-family:
Calibri;mso-hansi-font-family:Calibri;
color:black;mso-fareast-language:RU"><o:p></o:p></span></p>

<p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;text-align:
justify;line-height:normal"><span style="font-size:8.0pt;
color:black;mso-fareast-language:
RU">2.1.4. Исполнитель не несет ответственности, за несоблюдение Графика
оказания услуг или какие либо последствия,&nbsp; возникшие у Заказчика
вследствие не установленных обновлений или не рабочего состояния ПК в следующих
случаях:</span><span style="mso-ascii-font-family:Calibri;mso-hansi-font-family:Calibri;
color:black;mso-fareast-language:RU"><o:p></o:p></span></p>

<p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;text-align:
justify;line-height:normal"><span style="font-size:8.0pt;
color:black;mso-fareast-language:
RU">2.1.4.1. Если на рабочем месте Заказчика установлены нелицензионные
программные продукты (<span class=SpellE>Windows</span>, Антивирусные программы
и др.)</span><span style="mso-ascii-font-family:Calibri;mso-hansi-font-family:Calibri;
color:black;mso-fareast-language:RU"><o:p></o:p></span></p>

<p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;text-align:
justify;line-height:normal"><span style="font-size:8.0pt;
color:black;mso-fareast-language:
RU">2.1.4.2. Если необходимые обновления ПК опубликованы разработчиками и/или <span
class=SpellE>спецоператором</span> связи в последний день срока сдачи
отчетности</span><span style="mso-ascii-font-family:Calibri;mso-hansi-font-family:Calibri;
color:black;mso-fareast-language:RU"><o:p></o:p></span></p>

<p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;text-align:
justify;line-height:normal"><span style="font-size:8.0pt;
color:black;mso-fareast-language:
RU">2.1.4.3. Если&nbsp; установка обновлений и настройка ПК была не возможна,
по какой либо причине по вине Заказчика</span><span style="mso-ascii-font-family:
Calibri;mso-hansi-font-family:Calibri;
color:black;mso-fareast-language:RU"><o:p></o:p></span></p>

<p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:
normal"><span style="font-size:8.0pt;
color:black;mso-fareast-language:
RU">2.1.4.4. Если&nbsp; повреждения ПК возникли&nbsp; в последний день сдачи
отчетности</span><span style="mso-ascii-font-family:Calibri;mso-hansi-font-family:Calibri;
color:black;mso-fareast-language:RU"><o:p></o:p></span></p>

<p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;text-align:
justify;line-height:normal"><span style="font-size:8.0pt;
color:black;mso-fareast-language:
RU">2.1.4.5. Если&nbsp; сбои&nbsp; в работе ПК вызваны&nbsp; действиями 3-й
стороны (сбой системы операторов связи,&nbsp; нарушение работы провайдеров,
ошибки разработчиков, установка каких либо программ или обновлений на компьютер
<span class=GramE>работником</span> не имеющим полномочий от Исполнителя, без
уведомления Исполнителя и др.)</span><span style="mso-ascii-font-family:Calibri;
mso-hansi-font-family:Calibri;
color:black;mso-fareast-language:RU"><o:p></o:p></span></p>

<p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;text-align:
justify;line-height:normal"><span style="font-size:8.0pt;
color:black;mso-fareast-language:
RU">2.1.5. Исполнитель имеет право в одностороннем порядке изменить стоимость
услуг, а также их состав. При этом перерасчет оплаченных Заказчиком по
предоплате услуг не производится, и услуги оказываются в полном объеме;</span><span
style="mso-ascii-font-family:Calibri;
mso-hansi-font-family:Calibri;
color:black;mso-fareast-language:RU"><o:p></o:p></span></p>

<p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:
normal"><b><span style="font-size:8.0pt;
color:black;mso-fareast-language:
RU">2.2. Заказчик обязан</span></b><span style="mso-ascii-font-family:Calibri;
mso-hansi-font-family:Calibri;
color:black;mso-fareast-language:RU"><o:p></o:p></span></p>

<p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;text-align:
justify;line-height:normal"><span style="font-size:8.0pt;
color:black;mso-fareast-language:
RU">2.2.1. К моменту установки ПК и каждому&nbsp; посещению Исполнителя, для
выполнения обязанностей по контракту, обеспечить рабочее состояние компьютерной
техники:</span><span style="mso-ascii-font-family:Calibri;mso-hansi-font-family:Calibri;
color:black;mso-fareast-language:RU"><o:p></o:p></span></p>

<p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;text-align:
justify;line-height:normal"><span style="font-size:8.0pt;
color:black;mso-fareast-language:
RU">2.2.1.1. оборудовать рабочее место компьютерной техникой в соответствии с
требованиями&nbsp; устанавливаемых ПК, дисководом&nbsp; «А»&nbsp; и доступом к
CD дисководу,&nbsp; техническими средствами для подключения к сети Интернет,
обеспечить подключение данной компьютерной техники&nbsp; и организовать полный
доступ в сеть Интернет.&nbsp;</span><span style="mso-ascii-font-family:Calibri;
mso-hansi-font-family:Calibri;
color:black;mso-fareast-language:RU"><o:p></o:p></span></p>

<p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;text-align:
justify;line-height:normal"><span style="font-size:8.0pt;
color:black;mso-fareast-language:
RU">2.2.1.2.&nbsp; при не возможности обеспечить рабочее состояние
компьютерной&nbsp; техники к приезду специалиста, своевременно, не <span
class=GramE>позднее</span>&nbsp; чем за&nbsp; 1 (один) день, согласовать с
Исполнителем &nbsp;новый График оказания услуг.</span><span style="mso-ascii-font-family:
Calibri;mso-hansi-font-family:Calibri;
color:black;mso-fareast-language:RU"><o:p></o:p></span></p>

<p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;text-align:
justify;line-height:normal"><span style="font-size:8.0pt;
color:black;mso-fareast-language:
RU">2.2.1.3.при невозможности организовать рабочее состояние техники&nbsp; по
вине&nbsp; 3-й стороны (<span class=GramE>например</span> отсутствие связи с
Интернет и др.) на момент&nbsp; визита Исполнителя сообщить Исполнителю&nbsp; о
данном факте по телефону не позднее, чем&nbsp; за 1 (один) час до назначенной
встречи и откорректировать График оказания услуг.&nbsp;</span><span
style="mso-ascii-font-family:Calibri;
mso-hansi-font-family:Calibri;
color:black;mso-fareast-language:RU"><o:p></o:p></span></p>

<p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;text-align:
justify;line-height:normal"><span style="font-size:8.0pt;
color:black;mso-fareast-language:
RU">2.2.1.4.при обнаружении Исполнителем, во время визита, неисполнения
Заказчиком требований пунктов 2.2.1.1. - 2.2.1.3 и не возможность выполнения
своих обязанностей&nbsp; по каким либо причинам, не зависящим от Исполнителя -
данное посещение признается «Ложным вызовом» и должно быть оформлено
соответствующим образом.</span><span style="mso-ascii-font-family:Calibri;
mso-hansi-font-family:Calibri;
color:black;mso-fareast-language:RU"><o:p></o:p></span></p>

<p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:
normal"><span style="font-size:8.0pt;
color:black;mso-fareast-language:
RU">2.2.2. Строго соблюдать все требования, инструкции и лицензионные
соглашения по работе с установленными&nbsp; ПК.</span><span style="mso-ascii-font-family:
Calibri;mso-hansi-font-family:Calibri;
color:black;mso-fareast-language:RU"><o:p></o:p></span></p>

<p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:
normal"><span style="font-size:8.0pt;
color:black;mso-fareast-language:
RU">2.2.3. Содействовать выполнению своих обязанностей Исполнителю.</span><span
style="mso-ascii-font-family:Calibri;
mso-hansi-font-family:Calibri;
color:black;mso-fareast-language:RU"><o:p></o:p></span></p>

<p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;text-align:
justify;line-height:normal"><span style="font-size:8.0pt;
color:black;mso-fareast-language:
RU">2.2.4. По требованию Исполнителя предоставить возможность установки
обновлений и настройки ПК в нерабочее время Заказчика, если такое требование
выдвинуто не ранее чем за&nbsp; 3 (три) дня до последнего дня&nbsp; сроков
сдачи отчетности.</span><span style="mso-ascii-font-family:Calibri;mso-hansi-font-family:Calibri;
color:black;mso-fareast-language:RU"><o:p></o:p></span></p>

<p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;text-align:
justify;line-height:normal"><span style="font-size:8.0pt;
color:black;mso-fareast-language:
RU">2.2.5. Незамедлительно после оказания услуг подписать Акт сдачи-приемки
оказанных услуг&nbsp; или&nbsp; подписать акт о подтверждении&nbsp; «ложного
вызова».</span><span style="mso-ascii-font-family:Calibri;mso-hansi-font-family:Calibri;
color:black;mso-fareast-language:RU"><o:p></o:p></span></p>

<p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;text-align:
justify;line-height:normal"><span style="font-size:8.0pt;
color:black;mso-fareast-language:
RU">2.2.6. Обеспечить присутствие ответственного лица за&nbsp; работу с
установленными ПК на всех семинарах&nbsp; по обучению работе&nbsp; с установленными&nbsp;
ПК, организуемых&nbsp; Исполнителем.</span><span style="mso-ascii-font-family:
Calibri;mso-hansi-font-family:Calibri;
color:black;mso-fareast-language:RU"><o:p></o:p></span></p>

<p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;text-align:
justify;line-height:normal"><span style="font-size:8.0pt;
color:black;mso-fareast-language:
RU">2.2.7. Заблаговременно до сдачи отчетности проверить рабочее состояние ПК</span><span
style="mso-ascii-font-family:Calibri;
mso-hansi-font-family:Calibri;
color:black;mso-fareast-language:RU"><o:p></o:p></span></p>

<p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;text-align:
justify;line-height:normal"><span style="font-size:8.0pt;
color:black;mso-fareast-language:
RU">2.2.8. Перед использованием ПК проверять наличие новой информации в системе
ПК и на сайте поставщика</span><span style="mso-ascii-font-family:Calibri;
mso-hansi-font-family:Calibri;
color:black;mso-fareast-language:RU"><o:p></o:p></span></p>

<p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;text-align:
justify;line-height:normal"><span style="font-size:8.0pt;
color:black;mso-fareast-language:
RU">2.2.9. Незамедлительно в письменном виде сообщать Исполнителю о <span
class=GramE>смене</span> каких либо регистрационных данных Заказчика
(наименование организации, смена руководителя, телефонов, адресов, и пр.)</span><span
style="mso-ascii-font-family:Calibri;
mso-hansi-font-family:Calibri;
color:black;mso-fareast-language:RU"><o:p></o:p></span></p>

<p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;text-align:
justify;line-height:normal"><span style="font-size:8.0pt;
color:black;mso-fareast-language:
RU">&nbsp;</span><span style="mso-ascii-font-family:Calibri;mso-hansi-font-family:Calibri;
color:black;mso-fareast-language:RU"><o:p></o:p></span></p>

<p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:
normal"><b><span style="font-size:8.0pt;
color:black;mso-fareast-language:
RU">3. ПОРЯДОК ОКАЗАНИЯ, СДАЧИ-ПРИЕМКИ&nbsp; УСЛУГ.</span></b><span
style="mso-ascii-font-family:Calibri;
mso-hansi-font-family:Calibri;
color:black;mso-fareast-language:RU"><o:p></o:p></span></p>

<p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;text-align:
justify;line-height:normal"><span style="font-size:8.0pt;
color:black;mso-fareast-language:
RU">3.1. Услуги по настоящему Контракту оказываются после поступления оплаты
стоимости услуг на расчетный счет или в кассу Исполнителя.</span><span
style="mso-ascii-font-family:Calibri;
mso-hansi-font-family:Calibri;
color:black;mso-fareast-language:RU"><o:p></o:p></span></p>

<p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:
normal"><span style="font-size:8.0pt;
color:black;mso-fareast-language:
RU">3.2. Услуги по настоящему Контракту оказываются в соответствии с Графиком
оказания услуг, который формируется по предварительной договоренности Сторон на
основании Спецификации (приложение №1 к настоящему Контракту) или Заявки на
вызов специалиста оформленной должным образом, поступившей от Заказчика. При
мотивированной необходимости допускается внести изменения в График оказания
услуг после предварительной договоренности по телефону Ответственных лиц
Сторон, с обязательным дальнейшим оформлением зафиксированных изменений в
письменном виде.&nbsp;</span><span style="mso-ascii-font-family:Calibri;
mso-hansi-font-family:Calibri;
color:black;mso-fareast-language:RU"><o:p></o:p></span></p>

<p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;text-align:
justify;line-height:normal"><span style="font-size:8.0pt;
color:black;mso-fareast-language:
RU">3.3. При нарушении Заказчиком п.4.2 настоящего Контракта Исполнитель имеет
право отказать в оказании услуг Заказчику до момента поступления оплаты.</span><span
style="mso-ascii-font-family:Calibri;
mso-hansi-font-family:Calibri;
color:black;mso-fareast-language:RU"><o:p></o:p></span></p>

<p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:
normal"><span style="font-size:8.0pt;
color:black;mso-fareast-language:
RU">3.4. Если при оказании услуг <span class=GramE>обнаруживается что объем
оказываемых услуг превышает</span> оплаченный объем услуг указанный в Заявке на
вызов специалиста, Исполнитель выставляет Счет на оплату дополнительно
оказанных услуг.</span><span style="mso-ascii-font-family:Calibri;mso-hansi-font-family:Calibri;
color:black;mso-fareast-language:RU"><o:p></o:p></span></p>

<p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;text-align:
justify;line-height:normal"><span style="font-size:8.0pt;
color:black;mso-fareast-language:
RU">3.5. Услуга считается оказанной после соответствующего оформления Акта
сдачи-приемки оказанных услуг, который подписывается незамедлительно после
оказания услуг.</span><span style="mso-ascii-font-family:Calibri;mso-hansi-font-family:Calibri;
color:black;mso-fareast-language:RU"><o:p></o:p></span></p>

<p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;text-align:
justify;line-height:normal"><span style="font-size:8.0pt;
color:black;mso-fareast-language:
RU">&nbsp;</span><span style="mso-ascii-font-family:Calibri;mso-hansi-font-family:Calibri;
color:black;mso-fareast-language:RU"><o:p></o:p></span></p>

<p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:
normal"><b><span style="font-size:8.0pt;
color:black;mso-fareast-language:
RU">4. СТОИМОСТЬ&nbsp; УСЛУГ&nbsp; И&nbsp; ПОРЯДОК&nbsp; РАСЧЕТОВ</span></b><span
style="mso-ascii-font-family:Calibri;
mso-hansi-font-family:Calibri;
color:black;mso-fareast-language:RU"><o:p></o:p></span></p>

<p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;text-align:
justify;line-height:normal"><span style="font-size:8.0pt;
color:black;mso-fareast-language:
RU">4.1. Стоимость услуг по настоящему Контракту определяется&nbsp; на
основании Спецификации (приложение №1 к настоящему Контракту), в соответствии с
действующим на дату оказания услуг прейскурантом Исполнителя.</span><span
style="mso-ascii-font-family:Calibri;
mso-hansi-font-family:Calibri;
color:black;mso-fareast-language:RU"><o:p></o:p></span></p>

<p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:
normal"><span style="font-size:8.0pt;
color:black;mso-fareast-language:
RU">4.2 Стоимость услуг по настоящему Контракту незапланированных в
Спецификации (приложение №1 к настоящему Контракту) определяется на основании
Счета на оплату, выставленного на основании поступившей Заявки на вызов
специалиста в соответствии с действующим на дату оказания услуг прейскурантом
Исполнителя.</span><span style="mso-ascii-font-family:Calibri;mso-hansi-font-family:Calibri;
color:black;mso-fareast-language:RU"><o:p></o:p></span></p>

<p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;text-align:
justify;line-height:normal"><span style="font-size:8.0pt;
color:black;mso-fareast-language:
RU">4.3.&nbsp; Оплата услуг по настоящему Контракту производится путем 100%
предоплаты в течение пяти рабочих дней с момента подписания Сторонами
настоящего Контракта или поступления Заявки на вызов специалиста на основании
выставленного Исполнителем Счета на оплату.</span><span style="mso-ascii-font-family:
Calibri;mso-hansi-font-family:Calibri;
color:black;mso-fareast-language:RU"><o:p></o:p></span></p>

<p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;text-align:
justify;line-height:normal"><span style="font-size:8.0pt;
color:black;mso-fareast-language:
RU">4.4. Оплата&nbsp; услуг, оказанных до получения Исполнителем оплаты,&nbsp;
производится на основании выставленного Исполнителем Счета на оплату в срок не
позднее 10 (десяти) рабочих дней с момента подписания Сторонами Контракта Акта
сдачи-приемки оказанных услуг. При нарушении сроков оплаты взимается пеня в
размере 0,5%&nbsp; от стоимости&nbsp; выполненных работ за каждый день
просрочки.</span><span style="mso-ascii-font-family:Calibri;mso-hansi-font-family:Calibri;
color:black;mso-fareast-language:RU"><o:p></o:p></span></p>

<p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;text-align:
justify;line-height:normal"><span style="font-size:8.0pt;
color:black;mso-fareast-language:
RU">4.5.&nbsp; Оплата незапланированных услуг проводимых по требованию
Заказчика, в не рабочее время (с 18 часов вечера&nbsp; до&nbsp; 8 часов
утра)&nbsp; оплачивается в двойном размере.</span><span style="mso-ascii-font-family:
Calibri;mso-hansi-font-family:Calibri;
color:black;mso-fareast-language:RU"><o:p></o:p></span></p>

<p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;text-align:
justify;line-height:normal"><span style="font-size:8.0pt;
color:black;mso-fareast-language:
RU">4.6. При&nbsp; наличии «Ложного вызова» - не обеспечении рабочего состояния
компьютерной техники (п. 2.2.1) Заказчик оплачивает Исполнителю неустойку в
размере&nbsp; стоимости 1 (одного) часа работы плюс транспортные расходы.</span><span
style="mso-ascii-font-family:Calibri;
mso-hansi-font-family:Calibri;
color:black;mso-fareast-language:RU"><o:p></o:p></span></p>

<p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;text-align:
justify;line-height:normal"><span style="font-size:8.0pt;
color:black;mso-fareast-language:
RU">&nbsp;</span><span style="mso-ascii-font-family:Calibri;mso-hansi-font-family:Calibri;
color:black;mso-fareast-language:RU"><o:p></o:p></span></p>

<p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:
normal"><b><span style="font-size:8.0pt;
color:black;mso-fareast-language:
RU">5. СРОКИ&nbsp; ДЕЙСТВИЯ&nbsp; И ПОРЯДОК РАСТОРЖЕНИЯ КОНТРАКТА</span></b><span
style="mso-ascii-font-family:Calibri;
mso-hansi-font-family:Calibri;
color:black;mso-fareast-language:RU"><o:p></o:p></span></p>

<p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:
normal"><span style="font-size:8.0pt;
color:black;mso-fareast-language:
RU">5.1. Настоящий Контра<span class=GramE>кт вст</span>упает в силу с момента
подписания и действует до '.$schet['d'].'.'.$schet['m'].'.'.$god.' г.</span><span style="mso-ascii-font-family:
Calibri;mso-hansi-font-family:Calibri;
color:black;mso-fareast-language:RU"><o:p></o:p></span></p>

<p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;text-align:
justify;line-height:normal"><span style="font-size:8.0pt;
color:black;mso-fareast-language:
RU">5.2. При отсутствии письменных намерений Сторон о расторжении отношений
(направляемых за месяц до прекращения срока действия Контракта) Контракт
считается пролонгированным на следующий год.</span><span style="mso-ascii-font-family:
Calibri;mso-hansi-font-family:Calibri;
color:black;mso-fareast-language:RU"><o:p></o:p></span></p>

<p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;text-align:
justify;line-height:normal"><span style="font-size:8.0pt;
color:black;mso-fareast-language:
RU">5.3. Любая из сторон вправе расторгнуть Контракт в одностороннем порядке,
известив об этом другую сторону не позднее, чем за 30 (тридцать) дней до даты
расторжения Контракта. При прекращении Контракта финансовые <span class=SpellE>задолжности</span>
Сторон должны быть погашены. Стороны осуществляют окончательный взаиморасчет в
течение 10 (десяти) дней с момента расторжения Контракта.</span><span
style="mso-ascii-font-family:Calibri;
mso-hansi-font-family:Calibri;
color:black;mso-fareast-language:RU"><o:p></o:p></span></p>

<p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;text-align:
justify;line-height:normal"><span style="font-size:8.0pt;
color:black;mso-fareast-language:
RU">5.4. Исполнитель имеет право в одностороннем порядке изменить условия
настоящего Контракта в зависимости от изменения требований законодательных
актов к исполнению оказываемых услуг. Отказ Заказчика, в данном случае,
подписать изменения&nbsp; к Контракту ведет к автоматическому расторжению
настоящего Контракта и освобождает Исполнителя от исполнения обязательств,
возникших до расторжения контракта;</span><span style="mso-ascii-font-family:
Calibri;mso-hansi-font-family:Calibri;
color:black;mso-fareast-language:RU"><o:p></o:p></span></p>

<p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;text-align:
justify;line-height:normal"><span style="font-size:8.0pt;
color:black;mso-fareast-language:
RU">&nbsp;</span><span style="mso-ascii-font-family:Calibri;mso-hansi-font-family:Calibri;
color:black;mso-fareast-language:RU"><o:p></o:p></span></p>

<p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:
normal"><b><span style="font-size:8.0pt;
color:black;mso-fareast-language:
RU">6. ОТВЕТСТВЕННОСТЬ СТОРОН</span></b><span style="mso-ascii-font-family:
Calibri;mso-hansi-font-family:Calibri;
color:black;mso-fareast-language:RU"><o:p></o:p></span></p>

<p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;text-align:
justify;line-height:normal"><span style="font-size:8.0pt;
color:black;mso-fareast-language:
RU">6.1. Ответственность по любым искам и претензиям ограничивается суммами,
полученными или подлежащими получению в соответствии с условиями настоящего Контракта</span><span
style="mso-ascii-font-family:Calibri;
mso-hansi-font-family:Calibri;
color:black;mso-fareast-language:RU"><o:p></o:p></span></p>

<p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;text-align:
justify;line-height:normal"><span style="font-size:8.0pt;
color:black;mso-fareast-language:
RU">6.2.&nbsp; Стороны освобождаются от ответственности по настоящему Контракту
в случае возникновения обстоятельств непреодолимой силы, таких как стихийные
бедствия, массовые беспорядки, террористические акты, противоправные действия
третьих лиц, если сторона предъявляет доказательства того, что эти
обстоятельства воспрепятствовали исполнению своих обязательств по Контракту.
Уведомление о таких обстоятельствах должно быть произведено в течение семи
суток с момента их возникновения.</span><span style="mso-ascii-font-family:
Calibri;mso-hansi-font-family:Calibri;
color:black;mso-fareast-language:RU"><o:p></o:p></span></p>

<p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:
normal"><span style="font-size:8.0pt;
color:black;mso-fareast-language:
RU">6.3. Стороны берут на себя взаимные обязательства по соблюдению
конфиденциальности любой информации и <span class=GramE>документации,
представленной Стороной другой Стороне напрямую или опосредовано</span> в связи
с настоящим Контрактом, независимо от того, когда была представлена такая
информация: до, в процессе или по истечении срока действия настоящего Контракта.</span><span
style="mso-ascii-font-family:Calibri;
mso-hansi-font-family:Calibri;
color:black;mso-fareast-language:RU"><o:p></o:p></span></p>

<p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:
normal"><span style="font-size:8.0pt;
color:black;mso-fareast-language:
RU">6.4. Обязательства по соблюдению конфиденциальности не распространяются на
общедоступную информацию, а так&nbsp; же на информацию, которая станет известна
третьим лицам не по вине одной из сторон настоящего контракта.</span><span
style="mso-ascii-font-family:Calibri;
mso-hansi-font-family:Calibri;
color:black;mso-fareast-language:RU"><o:p></o:p></span></p>

<p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:
normal"><span style="font-size:8.0pt;
color:black;mso-fareast-language:
RU">6.5. Стороны берут на себя взаимные обязательства по согласованию в
письменном виде любые сообщения с упоминанием другой Стороны, ссылки на
фирменное наименование, размещение фирменной символики Сторон в полиграфических
изделиях, выставочных стендах, на интернет-сайтах и других СМИ.</span><span
style="mso-ascii-font-family:Calibri;
mso-hansi-font-family:Calibri;
color:black;mso-fareast-language:RU"><o:p></o:p></span></p>

<p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:
normal"><b><span style="font-size:8.0pt;
color:black;mso-fareast-language:
RU">7. ЗАКЛЮЧИТЕЛЬНЫЕ ПОЛОЖЕНИЯ</span></b><span style="mso-ascii-font-family:
Calibri;mso-hansi-font-family:Calibri;
color:black;mso-fareast-language:RU"><o:p></o:p></span></p>

<p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:
normal"><span style="font-size:8.0pt;
color:black;mso-fareast-language:
RU">7.1. Все, что не урегулировано настоящим Контрактом, регулируется
действующим законодательством РФ.</span><span style="mso-ascii-font-family:
Calibri;mso-hansi-font-family:Calibri;
color:black;mso-fareast-language:RU"><o:p></o:p></span></p>

<p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;text-align:
justify;line-height:normal"><span style="font-size:8.0pt;
color:black;mso-fareast-language:
RU">7.2. Споры по настоящему Контракту рассматриваются Сторонами обоюдно, а при
не достижении соглашения – в Арбитражном суде по месту нахождения ответчика</span><span
style="mso-ascii-font-family:Calibri;
mso-hansi-font-family:Calibri;
color:black;mso-fareast-language:RU"><o:p></o:p></span></p>

<p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;text-align:
justify;line-height:normal"><span style="font-size:8.0pt;
color:black;mso-fareast-language:
RU"><br>
7.3. Все изменения, дополнения и приложения к настоящему Контракту оформляются
в двух экземплярах в письменной форме и вступают в силу с момента подписания
Сторонами.</span><span style="mso-ascii-font-family:Calibri;mso-hansi-font-family:Calibri;
color:black;mso-fareast-language:RU"><o:p></o:p></span></p>

<p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;text-align:
justify;line-height:normal"><span style="font-size:8.0pt;
color:black;mso-fareast-language:
RU">7.4. Настоящий <a name="_GoBack"></a>Контракт составлен в двух экземплярах,
имеющих равную юридическую силу, по одному для каждой из Сторон.</span><span
style="mso-ascii-font-family:Calibri;
mso-hansi-font-family:Calibri;
color:black;mso-fareast-language:RU"><o:p></o:p></span></p>

<p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:
normal"><span style="font-size:8.0pt;
color:black;mso-fareast-language:
RU">&nbsp;</span><span style="mso-ascii-font-family:Calibri;mso-hansi-font-family:Calibri;
color:black;mso-fareast-language:RU"><o:p></o:p></span></p>

<table class=MsoNormalTable border=0 cellspacing=0 cellpadding=0 width=707
 style="width:529.9pt;border-collapse:collapse;mso-yfti-tbllook:1184;
 mso-padding-alt:0cm 0cm 0cm 0cm">
 <tr style="mso-yfti-irow:0;mso-yfti-firstrow:yes;height:14.25pt">
  <td width=359 nowrap valign=top style="width:269.35pt;background:white;
  padding:0cm 5.4pt 0cm 5.4pt;height:14.25pt">
  <p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;text-align:
  justify;line-height:normal"><b><span lang=EN-US style="font-size:8.0pt;
  
  mso-ansi-language:EN-US;mso-fareast-language:RU">8</span></b><b><span
  style="font-size:8.0pt;mso-fareast-language:RU">. СВЕДЕНИЯ ОБ ОПЕРАТОРЕ</span></b><span
  style="mso-ascii-font-family:Calibri;
  mso-hansi-font-family:Calibri;
  mso-fareast-language:RU"><o:p></o:p></span></p>
  </td>
  <td width=347 valign=top style="width:260.55pt;background:white;padding:0cm 5.4pt 0cm 5.4pt;
  height:14.25pt">
  <p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;text-align:
  justify;line-height:normal"><b><span lang=EN-US style="font-size:8.0pt;
  
  mso-ansi-language:EN-US;mso-fareast-language:RU">9</span></b><b><span
  style="font-size:8.0pt;mso-fareast-language:RU">. СВЕДЕНИЯ ОБ АБОНЕНТЕ</span></b><span
  style="mso-ascii-font-family:Calibri;
  mso-hansi-font-family:Calibri;
  mso-fareast-language:RU"><o:p></o:p></span></p>
  </td>
 </tr>
 <tr style="mso-yfti-irow:1;height:14.25pt">
  <td width=359 valign=top style="width:269.35pt;background:white;padding:0cm 5.4pt 0cm 5.4pt;
  height:14.25pt">
  <p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;text-align:
  justify;line-height:normal"><span style="font-size:8.0pt;
  mso-fareast-language:RU">Наименование:</span><span
  style="mso-ascii-font-family:Calibri;
  mso-hansi-font-family:Calibri;
  mso-fareast-language:RU"><o:p></o:p></span></p>
  <p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;text-align:
  justify;line-height:normal"><span style="font-size:8.0pt;
  mso-fareast-language:RU">'.$savoir['full_name'].'</span></span><span
  style="mso-ascii-font-family:Calibri;
  mso-hansi-font-family:Calibri;
  mso-fareast-language:RU"><o:p></o:p></span></p>
  </td>
  <td width=347 valign=top style="width:260.55pt;background:white;padding:0cm 5.4pt 0cm 5.4pt;
  height:14.25pt">
  <p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;text-align:
  justify;line-height:normal"><span style="font-size:8.0pt;
  mso-fareast-language:RU">Наименование:</span><span
  style="mso-ascii-font-family:Calibri;
  mso-hansi-font-family:Calibri;
  mso-fareast-language:RU"><o:p></o:p></span></p>
  <p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;text-align:
  justify;line-height:normal"><span style="font-size:8.0pt;
  mso-fareast-language:RU">'.$klient['naim'].'</span><span style="mso-ascii-font-family:Calibri;mso-hansi-font-family:Calibri;
  mso-fareast-language:RU"><o:p></o:p></span></p>
  </td>
 </tr>
 <tr style="mso-yfti-irow:2;height:12.75pt">
  <td width=359 nowrap valign=top style="width:269.35pt;background:white;
  padding:0cm 5.4pt 0cm 5.4pt;height:12.75pt">
  <p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal"><span style="font-size:8.0pt;
  mso-fareast-language:RU">Юридический
  адрес: '.$savoir['adres'].'&nbsp;</span><span
  style="mso-ascii-font-family:Calibri;
  mso-hansi-font-family:Calibri;
  mso-fareast-language:RU"><o:p></o:p></span></p>
  <p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal"><span style="font-size:8.0pt;
  mso-fareast-language:RU">Фактический
  адрес: '.$savoir['adres'].'</span><span style="mso-ascii-font-family:
  Calibri;mso-hansi-font-family:Calibri;
  mso-fareast-language:RU"><o:p></o:p></span></p>
  </td>
  <td width=347 valign=top style="width:260.55pt;background:white;padding:0cm 5.4pt 0cm 5.4pt;
  height:12.75pt">
  <p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal"><span style="font-size:8.0pt;
  mso-fareast-language:RU">Юридический
  адрес: '.$klient['uridadress'].'</span><span style="mso-ascii-font-family:Calibri;mso-hansi-font-family:Calibri;
  mso-fareast-language:RU"><o:p></o:p></span></p>
  <p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal"><span style="font-size:8.0pt;
  mso-fareast-language:RU">Фактический
  адрес: '.$klient['fakadress'].'</span><span style="mso-ascii-font-family:Calibri;mso-hansi-font-family:Calibri;
  mso-fareast-language:RU"><o:p></o:p></span></p>
  </td>
 </tr>
 <tr style="mso-yfti-irow:3;height:12.75pt">
  <td width=359 nowrap valign=top style="width:269.35pt;background:white;
  padding:0cm 5.4pt 0cm 5.4pt;height:12.75pt">
  <p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal"><span style="font-size:8.0pt;
  mso-fareast-language:RU">ИНН
  '.$savoir['inn'].'&nbsp;</span><span lang=EN-US style="font-size:8.0pt;mso-ansi-language:
  EN-US;mso-fareast-language:RU">/';
  if ($savoir['kpp'] > 0){
  $str .= "<span style='font-size: 7pt;font-familyy:
  Times;color:black'>КПП:</span>";
  }
  $str .= $savoir['kpp'].'</span><span style="mso-ascii-font-family:
  Calibri;mso-hansi-font-family:Calibri;
  mso-fareast-language:RU"><o:p></o:p></span></p>
  </td>
  <td width=347 valign=top style="width:260.55pt;background:white;padding:0cm 5.4pt 0cm 5.4pt;
  height:12.75pt">
  <p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal"><span style="font-size:8.0pt;
  mso-fareast-language:RU">ИНН
  '.$klient['inn'].'</span><span lang=EN-US style="font-size:8.0pt;
  mso-ansi-language:EN-US;mso-fareast-language:
  RU">/&nbsp;</span><span style="font-size:8.0pt;
  mso-fareast-language:RU">КПП
  '.$klient['kpp'].'</span><span style="mso-ascii-font-family:Calibri;mso-hansi-font-family:Calibri;
  mso-fareast-language:RU"><o:p></o:p></span></p>
  </td>
 </tr>
 <tr style="mso-yfti-irow:4;height:12.75pt">
  <td width=359 nowrap valign=top style="width:269.35pt;background:white;
  padding:0cm 5.4pt 0cm 5.4pt;height:12.75pt">
  <p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal"><span class=GramE><span style="font-size:8.0pt;
  mso-fareast-language:RU">Р</span></span><span
  style="font-size:8.0pt;mso-fareast-language:RU">/счет № '.$savoir['r_schet'].'</span><span
  style="mso-ascii-font-family:Calibri;
  mso-hansi-font-family:Calibri;
  mso-fareast-language:RU"><o:p></o:p></span></p>
  </td>
  <td width=347 valign=top style="width:260.55pt;background:white;padding:0cm 5.4pt 0cm 5.4pt;
  height:12.75pt">
  <p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal"><span class=GramE><span style="font-size:8.0pt;
  mso-fareast-language:RU">Р</span></span><span
  style="font-size:8.0pt;mso-fareast-language:RU">/счет № '.$klient['r_schet'].'</span><span
  style="mso-ascii-font-family:Calibri;
  mso-hansi-font-family:Calibri;
  mso-fareast-language:RU"><o:p></o:p></span></p>
  </td>
 </tr>
 <tr style="mso-yfti-irow:5;height:12.75pt">
  <td width=359 nowrap valign=top style="width:269.35pt;background:white;
  padding:0cm 5.4pt 0cm 5.4pt;height:12.75pt">
  <p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal"><span style="font-size:8.0pt;
  mso-fareast-language:RU">в 
  '.$savoir['bank'].'</span><span
  style="mso-ascii-font-family:Calibri;
  mso-hansi-font-family:Calibri;
  mso-fareast-language:RU"><o:p></o:p></span></p>
  </td>
  <td width=347 valign=top style="width:260.55pt;background:white;padding:0cm 5.4pt 0cm 5.4pt;
  height:12.75pt">
  <p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal"><span style="font-size:8.0pt;
  mso-fareast-language:RU">в
  '.$klient['bank'].'</span><span style="mso-ascii-font-family:Calibri;
  mso-hansi-font-family:Calibri;
  mso-fareast-language:RU"><o:p></o:p></span></p>
  </td>
 </tr>
 <tr style="mso-yfti-irow:6;height:12.75pt">
  <td width=359 nowrap valign=top style="width:269.35pt;background:white;
  padding:0cm 5.4pt 0cm 5.4pt;height:12.75pt">
  <p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal"><span class=SpellE><span class=GramE><span style="font-size:8.0pt;
  
  mso-fareast-language:RU">кор</span></span></span><span class=GramE><span
  style="font-size:8.0pt;mso-fareast-language:RU">/счет</span></span><span
  style="font-size:8.0pt;mso-fareast-language:RU"> № '.$savoir['k_schet'].'</span><span
  style="mso-ascii-font-family:Calibri;
  mso-hansi-font-family:Calibri;
  mso-fareast-language:RU"><o:p></o:p></span></p>
  </td>
  <td width=347 valign=top style="width:260.55pt;background:white;padding:0cm 5.4pt 0cm 5.4pt;
  height:12.75pt">
  <p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal"><span class=SpellE><span class=GramE><span style="font-size:8.0pt;
  
  mso-fareast-language:RU">кор</span></span></span><span class=GramE><span
  style="font-size:8.0pt;mso-fareast-language:RU">/счет</span></span><span
  style="font-size:8.0pt;mso-fareast-language:RU"> № '.$klient['k_schet'].'</span><span style="mso-ascii-font-family:
  Calibri;mso-hansi-font-family:Calibri;
  mso-fareast-language:RU"><o:p></o:p></span></p>
  </td>
 </tr>
 <tr style="mso-yfti-irow:7;height:12.75pt">
  <td width=359 nowrap valign=top style="width:269.35pt;background:white;
  padding:0cm 5.4pt 0cm 5.4pt;height:12.75pt">
  <p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal"><span style="font-size:8.0pt;
  mso-fareast-language:RU">БИК
  '.$savoir['bik'].'</span><span style="mso-ascii-font-family:Calibri;mso-hansi-font-family:Calibri;
  mso-fareast-language:RU"><o:p></o:p></span></p>
  </td>
  <td width=347 valign=top style="width:260.55pt;background:white;padding:0cm 5.4pt 0cm 5.4pt;
  height:12.75pt">
  <p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;text-align:
  justify;line-height:normal"><span style="font-size:8.0pt;
  mso-fareast-language:RU">БИК
  '.$klient['bik'].'</span><span style="mso-ascii-font-family:Calibri;mso-hansi-font-family:Calibri;
  mso-fareast-language:RU"><o:p></o:p></span></p>
  </td>
 </tr>
 <tr style="mso-yfti-irow:8;height:12.75pt">
  <td width=359 nowrap valign=top style="width:269.35pt;background:white;
  padding:0cm 5.4pt 0cm 5.4pt;height:12.75pt"></td>
  <td width=359 nowrap valign=top style="width:269.35pt;background:white;
  padding:0cm 5.4pt 0cm 5.4pt;height:12.75pt"></td>
 </tr>
 <tr style="mso-yfti-irow:9;height:12.75pt">
  <td width=359 nowrap valign=top style="width:269.35pt;background:white;
  padding:0cm 5.4pt 0cm 5.4pt;height:12.75pt">
  <p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal"><span style="font-size:8.0pt;
  mso-fareast-language:RU">__________________________
  / '.$savoir['fio'].'</span><span style="mso-ascii-font-family:Calibri;mso-hansi-font-family:Calibri;
  mso-fareast-language:RU"><o:p></o:p></span></p>
  </td>
  <td width=359 nowrap valign=top style="width:269.35pt;background:white;
  padding:0cm 5.4pt 0cm 5.4pt;height:12.75pt">
  <p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal"><span style="font-size:8.0pt;
  mso-fareast-language:RU">__________________________
  / ';
$str .= strstr($personlis['fio']," ", true);
$str .= mb_substr(strstr($personlis['fio']," "),0,2,'UTF-8');
$str .= ".";
$str .= mb_substr(strstr($srachnah," "),0,2,'UTF-8');
$str .= ".";
$str .= '</span><span style="mso-ascii-font-family:
  Calibri;mso-hansi-font-family:Calibri;
  mso-fareast-language:RU"><o:p></o:p></span></p>
  </td>
 </tr>
 <tr style="mso-yfti-irow:10;mso-yfti-lastrow:yes;height:12.75pt">
  <td width=359 nowrap valign=top style="width:269.35pt;background:white;
  padding:0cm 5.4pt 0cm 5.4pt;height:12.75pt">
  <p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal"><span style="font-size:8.0pt;
  mso-fareast-language:RU">МП</span><span
  style="mso-ascii-font-family:Calibri;
  mso-hansi-font-family:Calibri;
  mso-fareast-language:RU"><o:p></o:p></span></p>
  </td>
  <td width=359 nowrap valign=top style="width:269.35pt;background:white;
  padding:0cm 5.4pt 0cm 5.4pt;height:12.75pt">
  <p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:
  normal"><span style="font-size:8.0pt;
  mso-fareast-language:RU">МП</span><span
  style="mso-ascii-font-family:Calibri;
  mso-hansi-font-family:Calibri;
  mso-fareast-language:RU"><o:p></o:p></span></p>
  </td>
 </tr>
</table>';

if($_GET['p']==1){
$str .= '<img src="/img/'.$savoir['mp'].'" style="
    width: 150px;
    position: relative;
    margin-bottom: -90px;
    top: -100px;
">
<img src="/img/'.$savoir['pc'].'" style="
    width: 101px;
    position: relative;
    margin-bottom: -90px;
    margin-left: -120px;
    top: -170px;
">'; 
}


$str .= '<p class=MsoNormal style="margin-bottom:0cm;margin-bottom:.0001pt;line-height:
normal"><span style="font-size:8.0pt;

color:black;mso-fareast-language:RU">&nbsp;</span><span style="mso-ascii-font-family:
Calibri;mso-hansi-font-family:Calibri;
color:black;mso-fareast-language:RU"><o:p></o:p></span></p>

<p class=MsoNormal><o:p>&nbsp;</o:p></p>

</div>

</body>

</html>';

echo $str;

fwrite($fp, $str);

fclose($fp);
?>