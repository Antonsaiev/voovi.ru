<?
/************************************************************************
UebiMiau is a GPL'ed software developed by 

 - Aldoir Ventura - aldoir@users.sourceforge.net
 - http://uebimiau.sourceforge.net

Fell free to contact, send donations or anything to me :-)
São Paulo - Brasil
*************************************************************************/

require("./inc/inc.php");

function mail_connect() {
	global $UM,$sid,$tid,$lid;
	if(!$UM->mail_connect()) { redirect("error.php?err=1&sid=$sid&tid=$tid&lid=$lid\r\n"); exit; }
	if(!$UM->mail_auth(true)) { redirect("badlogin.php?sid=$sid&tid=$tid&lid=$lid\r\n"); exit; }
}

$headers = null;
$folder_key = base64_encode(strtolower($folder));
if(!array_key_exists("headers",$sess)) $sess["headers"] = array();
	
if(array_key_exists($folder_key,$sess["headers"]))
	$headers = $sess["headers"][$folder_key];

if( !is_array($headers) 
	|| isset($decision)
	|| isset($refr)) {

	mail_connect();

	$sess["auth"] = true;

	if(isset($start_pos) && isset($end_pos)) {

		for($i=$start_pos;$i<$end_pos;$i++) {
			if(isset(${"msg_$i"})) {
				if ($decision == "delete") {
					$UM->mail_delete_msg($headers[$i],$prefs["save-to-trash"],$prefs["st-only-read"]);
				} else {
					$UM->mail_move_msg($headers[$i],$aval_folders);
				}
				$expunge = true;
			}
		}

		if($expunge) {

			if($prefs["save-to-trash"])
				unset($sess["headers"][base64_encode("trash")]);
			if ($decision == "move")
				unset($sess["headers"][base64_encode(strtolower($aval_folders))]);

			//some servers, don't hide deleted messages until you don't disconnect
			$SS->Save($sess);
			$UM->mail_disconnect();

			mail_connect();

			if ($back) {
				$back_to = $start_pos;
			}
		}

		unset($sess["headers"][$folder_key]);

	} elseif (isset($refr) && array_key_exists("headers",$sess)) {
		unset($sess["headers"][$folder_key]);
	}

	$boxes = $UM->mail_list_boxes();
	$sess["folders"] = $boxes;

	require("./get_message_list.php");

	require("./apply_filters.php");

	$UM->mail_disconnect();

	if($require_update) {
		mail_connect();
		require("./get_message_list.php");
		$UM->mail_disconnect();
	}

	if($check_first_login && !$prefs["first-login"]) {
		$prefs["first-login"] = 1;
		save_prefs($prefs);
		$SS->Save($sess);
		redirect("preferences.php?sid=$sid&tid=$tid&lid=$lid&folder=".urlencode($folder));
		exit;
	}
	$SS->Save($sess);
}

if(!is_array($headers = $sess["headers"][$folder_key])) { redirect("error.php?err=3&sid=$sid&tid=$tid&lid=$lid\r\n"); exit; }

array_qsort2($headers,$sortby,$sortorder);
reset($headers);

$sess["headers"][$folder_key] = $headers;
$SS->Save($sess);



if(!isset($pag) || !is_numeric(trim($pag))) $pag = 1;
$refreshurl = "messages.php?sid=$sid&tid=$tid&lid=$lid&folder=".urlencode($folder)."&pag=$pag";

if (isset($back_to)) {
	if (count($headers) > $back_to) {
		redirect("readmsg.php?folder=".urlencode($folder)."&pag=$pag&ix=$back_to&sid=$sid&tid=$tid&lid=$lid");
		exit;
	}
}
redirect("$refreshurl");

?>
