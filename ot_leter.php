<?
include 'conf.php';
//ini_set("display_errors",1);
//error_reporting(E_ALL);
//print( "<pre>");
//
//$dirs="/var/www/voovi/data/www/voovi.ru/voicecatalog/";
//
//
////$dir="voicecatalog/";
//$orig_fn = "406-0000058c.wav";
////print_r($orig_fn);
//$full_fn = "https://cdrapi:cdrapi321@192.168.5.101:8443/recapi?filename={$orig_fn}";
////print_r( $full_fn);
////https://cdrapi:cdrapi321@192.168.5.101:8443/recapi?filename=406-0000058c.wav
//
////$arrContextOptions=array(
////    "ssl"=>array(
////        "verify_peer"=>false,
////        "verify_peer_name"=>false,
////    ),
////);
//
////$r = file_get_contents($full_fn, false, stream_context_create($arrContextOptions));
//$r = get_console( $full_fn );
//
////print_r( $r );
//
////$r=file_get_contents("https://cdrapi:cdrapi321@192.168.5.101:8443/recapi?filename={$orig_fn}");
//
////print_r(strlen($r));
////echo bin2hex($r);
////$fn=$dir.$orig_fn;
////file_put_contents( $fn, $r );
//print( "</pre>");
//
//exit(1);
//
//function get_console($url)
//{
//    $dirs="/var/www/voovi/data/www/voovi.ru/voicecatalog/";
//    $orig_fn = "406-0000058c.wav";
//   // print_r($orig_fn);
//    $out_fn=$dirs."test_".$orig_fn;
//    $full_fn = "https://cdrapi:cdrapi321@192.168.5.101:8443/recapi?filename={$orig_fn}";
//
//    $cmd="wget -O {$out_fn} {$full_fn} --no-check-certificate 2>&1";
//    //$cmd = "ls -l /tmp/*";
//   // print_r($cmd);
//    $ee=[];
//    exec($cmd,$ee);
//   // print_r($ee);
//    $ee = [];
//    exec( "ls -l {$dirs} | grep test", $ee );
//   // print_r( $ee );
//    ////file_put_contents($out_fn,"eiluha;qEOUPBEIUAWPBWAEOP480Q2OFPQEW[WQFPE");
//}
//function get_web_page( $url )
//{
//    $options = array(
//        CURLOPT_RETURNTRANSFER => true,     // return web page
//        CURLOPT_HEADER         => false,    // don't return headers
//        CURLOPT_FOLLOWLOCATION => true,     // follow redirects
//        CURLOPT_ENCODING       => "",       // handle all encodings
//        CURLOPT_USERAGENT      => "spider", // who am i
//        CURLOPT_AUTOREFERER    => true,     // set referer on redirect
//        CURLOPT_CONNECTTIMEOUT => 120,      // timeout on connect
//        CURLOPT_TIMEOUT        => 120,      // timeout on response
//        CURLOPT_MAXREDIRS      => 10,       // stop after 10 redirects
//        CURLOPT_SSL_VERIFYPEER => 0,     // Disabled SSL Cert checks
//        CURLOPT_SSL_VERIFYHOST => 0,
//        CURLOPT_USERPWD => "cdrapi:cdrapi321",
//    );
//
//    $ch      = curl_init( $url );
//    curl_setopt_array( $ch, $options );
//    $content = curl_exec( $ch );
//    print_r( bin2hex( $content ));
//    $err     = curl_errno( $ch );
//    print_r( $err );
//    $errmsg  = curl_error( $ch );
//    print_r( $errmsg );
//    $header  = curl_getinfo( $ch );
//    curl_close( $ch );
//
//    $header['errno']   = $err;
//    $header['errmsg']  = $errmsg;
//    $header['content'] = $content;
//    return $header;
//}


//file_put_contents($dir."auto-1642766208-403-89283405192.wav",file_get_contents("https://cdrapi:cdrapi321@192.168.5.101:8443/recapi?filedir=monitor@filename=auto-1642766208-403-89283405192.wav"));
/*$to = "menafis64@gmail.com";
$subject = "Письмо с сайта";
$charset = "utf-8";
$headerss ="Content-type: text/html; charset=$charset\r\n";
$headerss.="MIME-Version: 1.0\r\n";
$headerss.="Date: ".date("D, d M Y h:i:s O')."\r\n";
$msg = "Имя";
mail($to, $subject, $msg, $headerss);
/komnete.php?id=1&rand=2888920200731&p=0&ogrn=309265011700041&kli=20428&lico=0&gr=0&nomerschet=72&produkt=348&inn=261809572092


date_modify($nextday, '+1 day');
echo $nextday;*/
//$nextday=date("Y-m-d");
//$date = new DateTime($nextday);
//$date->modify('+1 day');
//$day=$date->format('Y-m-d');
//$qazaza = mysql_query("SELECT schet.produkt,schet.date_op,schet.ogrn,schet.idkli,schet.lico,schet.rand,schet.gr,schet.inn,schet.name as naim,schet.nomerschet,produkti.name,users.f_name,users.l_name,users.o_name FROM `schet` inner join produkti on schet.produkt=produkti.id inner join users on users.users_id=schet.kto WHERE schet.date_op!='' and(schet.produkt='121' or schet.produkt='120' or schet.produkt='386' or schet.produkt='144' or schet.produkt='336' or schet.produkt='118' or schet.produkt='372' or schet.produkt='335' or schet.produkt='393' or schet.produkt='122' or schet.produkt='119' or schet.produkt='139')");
//while($rowazaza = mysql_fetch_array($qazaza)) {
//	$date2 = new DateTime($rowazaza['date_op']);
//    $date2->modify('+1 month');
//    $day2=$date2->format('Y-m-d');
//	if($nextday==$day2)
//	{
//		$kto=$rowazaza['f_name'].' '.$rowazaza['l_name'].' '.$rowazaza['o_name'];
//$to = "infosavoir@ya.ru";
//$subject = $rowazaza['name']." ,постоплата ".$kto;
//$charset = "utf-8";
//$headerss ="Content-type: text/html; charset=$charset\r\n";
//$headerss.="MIME-Version: 1.0\r\n";
//$headerss.="Date: ".date('D, d M Y h:i:s O')."\r\n";
//$headers .= "From:it.savoir<it.savoir@yandex.ru>\r\n";
//$headers .= "Reply-To:it.savoir@yandex.ru\r\n";
//$msg = "Постоплата "."
//<html>
//<head>
//</head>
//<body>
//    <p><a href='https://voovi.ru/komnete.php?id=1&rand=".$rowazaza['rand']."&p=0&ogrn=".$rowazaza['ogrn']."&kli=".$rowazaza['idkli']."&lico=".$rowazaza['lico']."&gr=".$rowazaza['gr']."&nomerschet=".$rowazaza['nomerschet']."&produkt=".$rowazaza['produkt']."&inn=".$rowazaza['inn']."'>счет ".$rowazaza['naim']."</a></p>
//</body>
//</html>
//"." Лысенко С.";
//mail($to, $subject, $msg, $headerss);
//	}
//}
//header( 'Location:https://cdrapi:cdrapi321@192.168.5.101:8443/recapi?filename=406-0000058c.wav');
?>