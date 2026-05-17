<?php
# Подключаем конфиг
include 'conf.php';

$dir="/var/www/voovi/data/www/voovi.ru/voicecatalog/";

if($_GET["telip"]!="")
{
    $koment = "UPDATE users SET `iptel`='" . $_GET['telip'] . "' WHERE users_id='" . $_GET['users'] . "'";
    mysql_query($koment) or die(mysql_error($link));
}

else {
    $rowss = "";

    $service_port = "7777";
    $address = "192.168.5.101";
    $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
    if ($socket < 0) {
        echo "socket_create() failed: reason: " . socket_strerror($socket) . "\n";
    } else {
        // echo "OK.\n";
    }

    // echo "Attempting to connect to '$address' on port '$service_port'...";
    $result = socket_connect($socket, $address, $service_port);
    if ($result < 0) {
        echo "socket_connect() failed.\nReason: ($result) " . socket_strerror($result) . "\n";
    } else {
        //  echo "OK.\n";
    }

    if (isset ($_GET["nuberzvon"])) {

        if ($_GET["rand"] != "") {
            $userdatas = mysql_fetch_assoc(mysql_query("SELECT ns FROM schet WHERE rand = '" . $_GET["rand"] . "' LIMIT 1"));
            $ns = $userdatas["ns"];
        } else {
            $ns = "";
        }
        $row = "";
        $azaza = "INSERT INTO `telefonia` (
           `ns`,
		   `idkli`,
			`kto`,
			`idkto`,
			`towhom`,
			`vid_call`,
			`date_answer`
		) VALUES (
		'" . $ns . "',
		'" . $_GET['idkli'] . "',
		'" . $_GET['who'] . "',
		'" . $_GET['id'] . "',
		'" . $_GET['nuberzvon'] . "',
		'2',
		'" . $_GET['date_call'] . "'
		)";
        mysql_query($azaza) or die(mysql_error($link));
        if ($_GET["stat"] == "call") {

            $in = "Action: Login\r\n";
            $in .= "Username: savoiratc\r\n";
            $in .= "Secret: 4Ressurection\r\n\r\n";
            $in .= "Action:  Originate\r\n";
            $in .= "Channel: PJSIP/" . $_GET["who"] . "\r\n";
            $in .= "Callerid: Phoenix-call <" . $_GET["nuberzvon"] . ">\r\n";
            $in .= "Timeout: 15000\r\n";
            $in .= "Context: from-internal\r\n";
            $in .= "Exten: " . $_GET["nuberzvon"] . "\r\n";
            $in .= "Priority: 1\r\n\r\n";
            $in .= "Async: yes\r\n\r\n";
            $in .= "Action: Logoff\r\n\r\n";
            $out = '';
//            $in = "Action: Login\r\n";
//            $in .= "Username: savoiratc\r\n";
//            $in .= "Secret: 4Ressurection\r\n\r\n";
//            $in .= "Action:  Originate\r\n";
//            $in .= "Channel:  PJSIP/".$_GET["nuberzvon"]."@trunk_4\r\n";
//            $in .= "Callerid: Phoenix-call <".$_GET["nuberzvon"].">\r\n";
//            $in .= "Timeout: 50000\r\n";
//            $in .= "Context: from-internal\r\n";
//            $in .= "Exten: ".$_GET['who']."\r\n";
//            $in .= "Priority: 1\r\n\r\n";
//            $in .= "Async: yes\r\n\r\n";
//            $in .= "Action: Logoff\r\n\r\n";
//            $out = '';
            socket_write($socket, $in, strlen($in));
            while ($out = socket_read($socket, 128)) {

                $all[] = $out;
                //echo $out;
            }
            fclose($socket);
            // echo $out;

            for ($i = 1; $i < count($all); $i++) {
                if (strpos($all[$i], 'PJSIP/'.$_GET["who"] .'') !== false) {
                    $str = strpos($all[$i], 'PJSIP/'.$_GET["who"] .'');
                    $row = substr($all[$i], $str, 18);
                    if (strpos($all[$i], 'PJSIP/'.$_GET["who"] .'') !== false) {
                        echo $row;
                        break;
                    }
                }
            }

            // $fps = fopen($dir.substr($row,6).".txt", "w+");
            $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
            if ($socket < 0) {
                echo "socket_create() failed: reason: " . socket_strerror($socket) . "\n";
            } else {
                // echo "OK.\n";
            }

            // echo "Attempting to connect to '$address' on port '$service_port'...";
            $result = socket_connect($socket, $address, $service_port);
            if ($result < 0) {
                echo "socket_connect() failed.\nReason: ($result) " . socket_strerror($result) . "\n";
            } else {
                //  echo "OK.\n";
            }
            $koment = "UPDATE telefonia SET `callmessage`='" . date('YHm') . substr($row, 6) . ".wav" . "' WHERE towhom='" . $_GET["nuberzvon"] . "' and date_answer='" . $_GET["date_call"] . "'";
            mysql_query($koment) or die(mysql_error($link));
            $inout = "Action: Login\r\n";
            $inout .= "Username: savoiratc\r\n";
            $inout .= "Secret: 4Ressurection\r\n\r\n";
            $inout .= "Action:  Monitor\r\n";
            $inout .= "Channel: " . $row . "\r\n";
            $inout .= "File: " . substr($row, 6) . "\r\n";
            $inout .= "Format: wav\r\n";
            $inout .= "Mix: 1\r\n";
            $inout .= "Priority: 1\r\n\r\n";
            $inout .= "Async: yes\r\n\r\n";
            $inout .= "Action: Logoff\r\n\r\n";
            $outini = '';
            socket_write($socket, $inout, strlen($inout));
            while ($outini = socket_read($socket, 128)) {
                //fwrite($fps,$outini);
                //fwrite($fps,$socket);
                $all[] = $outini;
                // echo $inout;
            }
            //fclose($fps);
            fclose($socket);
        }

    }
    if ($_GET["stat"] == "hangup") {
        // $fp = fopen($dir.substr($_GET["chanell"],6).".wav", "w+");
        $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        if ($socket < 0) {
            echo "socket_create() failed: reason: " . socket_strerror($socket) . "\n";
        } else {
            // echo "OK.\n";
        }

        // echo "Attempting to connect to '$address' on port '$service_port'...";
        $result = socket_connect($socket, $address, $service_port);
        if ($result < 0) {
            echo "socket_connect() failed.\nReason: ($result) " . socket_strerror($result) . "\n";
        } else {
            //  echo "OK.\n";
        }
        $save = "Action: Login\r\n";
        $save .= "Username: savoiratc\r\n";
        $save .= "Secret: 4Ressurection\r\n\r\n";
        $save .= "Action:  StopMonitor\r\n";
        $save .= "Channel: " . $_GET["chanell"] . "\r\n";
        $save .= "Priority: 1\r\n\r\n";
        $save .= "Async: yes\r\n\r\n";
        $save .= "Action: Logoff\r\n\r\n";
        $inout = '';
        socket_write($socket, $save, strlen($save));
        while ($inout = socket_read($socket, 128)) {

            $all[] = $inout;
            // echo $out;
        }
        fclose($socket);
        $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        if ($socket < 0) {
            echo "socket_create() failed: reason: " . socket_strerror($socket) . "\n";
        } else {
            // echo "OK.\n";
        }

        // echo "Attempting to connect to '$address' on port '$service_port'...";
        $result = socket_connect($socket, $address, $service_port);
        if ($result < 0) {
            echo "socket_connect() failed.\nReason: ($result) " . socket_strerror($result) . "\n";
        } else {
            //  echo "OK.\n";
        }
        $in = "Action: Login\r\n";
        $in .= "Username: savoiratc\r\n";
        $in .= "Secret: 4Ressurection\r\n\r\n";
        $in .= "Action:  Hangup\r\n";
        $in .= "Channel: " . $_GET["chanell"] . "\r\n";;
        $in .= "Priority: 1\r\n\r\n";
        $in .= "Async: yes\r\n\r\n";
        $in .= "Action: Logoff\r\n\r\n";
        $out = '';
        socket_write($socket, $in, strlen($in));
        while ($out = socket_read($socket, 128)) {

            $all[] = $out;
            // echo $out;
        }
        fclose($socket);
        // file_put_contents($dir.substr($_GET["chanell"],6).'.wav',file_get_contents('https://cdrapi:cdrapi321@192.168.5.101:8443/recapi?filename='.substr($_GET["chanell"],6).'.wav',false,stream_context_create($arrContextOptions)));
        $dirs = "/var/www/voovi/data/www/voovi.ru/voicecatalog/";
        $d = date('YHm');
        $orig_fn = substr($_GET["chanell"], 6) . ".wav";
        $full_fn = "https://cdrapi:cdrapi321@192.168.5.101:8443/recapi?filename={$orig_fn}";
        $out_fn = $dirs . $d . $orig_fn;
        $cmd = "wget -O {$out_fn} {$full_fn} --no-check-certificate 2>&1";
        $ee = [];
        exec($cmd, $ee);
        sleep(5);
    }
    if ($_GET["stat"] == "mute_voice") {
        $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        if ($socket < 0) {
            echo "socket_create() failed: reason: " . socket_strerror($socket) . "\n";
        } else {
            // echo "OK.\n";
        }

        // echo "Attempting to connect to '$address' on port '$service_port'...";
        $result = socket_connect($socket, $address, $service_port);
        if ($result < 0) {
            echo "socket_connect() failed.\nReason: ($result) " . socket_strerror($result) . "\n";
        } else {
            //  echo "OK.\n";
        }
        $in_Park = "Action: Login\r\n";
        $in_Park .= "Username: savoiratc\r\n";
        $in_Park .= "Secret: 4Ressurection\r\n\r\n";
        $in_Park .= "Action: PauseCall\r\n";
        $in_Park .= "Channel:PJSIP/" . $_GET["whomute"] . "\r\n";
        $in_Park .= "Direction:in\r\n";
        $in_Park .= "State:on\r\n";
        $in_Park .= "Async: yes\r\n\r\n";
        $in_Park .= "Action: Logoff\r\n\r\n";
        $out_Park = '';
        socket_write($socket, $in_Park, strlen($in_Park));
        while ($out_Park = socket_read($socket, 128)) {

            $all[] = $out_Park;
            //echo '<br>'.$out_Park;
            // echo $out;
        }
        fclose($socket);
    }
    if ($_GET["stat"] == "un_voice") {
        $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        if ($socket < 0) {
            echo "socket_create() failed: reason: " . socket_strerror($socket) . "\n";
        } else {
            // echo "OK.\n";
        }

        // echo "Attempting to connect to '$address' on port '$service_port'...";
        $result = socket_connect($socket, $address, $service_port);
        if ($result < 0) {
            echo "socket_connect() failed.\nReason: ($result) " . socket_strerror($result) . "\n";
        } else {
            //  echo "OK.\n";
        }
        $in_Park = "Action: Login\r\n";
        $in_Park .= "Username: savoiratc\r\n";
        $in_Park .= "Secret: 4Ressurection\r\n\r\n";
        $in_Park .= "Action: PauseCall\r\n";
        $in_Park .= "Channel:PJSIP/" . $_GET["whomute"] . "\r\n";
        $in_Park .= "Direction:in\r\n";
        $in_Park .= "State:off\r\n";
        $in_Park .= "Async: yes\r\n\r\n";
        $in_Park .= "Action: Logoff\r\n\r\n";
        $out_Park = '';
        socket_write($socket, $in_Park, strlen($in_Park));
        while ($out_Park = socket_read($socket, 128)) {

            $all[] = $out_Park;
            //echo '<br>'.$out_Park;
            // echo $out;
        }
        fclose($socket);
    }
    if ($_GET["stat"] == "perevod") {


        $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        if ($socket < 0) {
            echo "socket_create() failed: reason: " . socket_strerror($socket) . "\n";
        } else {
            // echo "OK.\n";
        }

// echo "Attempting to connect to '$address' on port '$service_port'...";
        $result = socket_connect($socket, $address, $service_port);
        if ($result < 0) {
            echo "socket_connect() failed.\nReason: ($result) " . socket_strerror($result) . "\n";
        } else {
            //  echo "OK.\n";
        }
        $in_Park = "Action: Login\r\n";
        $in_Park .= "Username: savoiratc\r\n";
        $in_Park .= "Secret: 4Ressurection\r\n\r\n";
        $in_Park .= "Action: PauseCall\r\n";
        $in_Park .= "Channel:PJSIP/" . $_GET["whoreplay"] . "\r\n";
        $in_Park .= "Direction:in\r\n";
        $in_Park .= "State:on\r\n";
        $in_Park .= "Async: yes\r\n\r\n";
        $in_Park .= "Action: Logoff\r\n\r\n";
        $out_Park = '';
        socket_write($socket, $in_Park, strlen($in_Park));
        while ($out_Park = socket_read($socket, 128)) {

            $all[] = $out_Park;
            //echo '<br>'.$out_Park;
            // echo $out;
        }
        fclose($socket);
        $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        if ($socket < 0) {
            echo "socket_create() failed: reason: " . socket_strerror($socket) . "\n";
        } else {
            // echo "OK.\n";
        }

// echo "Attempting to connect to '$address' on port '$service_port'...";
        $result = socket_connect($socket, $address, $service_port);
        if ($result < 0) {
            echo "socket_connect() failed.\nReason: ($result) " . socket_strerror($result) . "\n";
        } else {
            //  echo "OK.\n";
        }
        $in = "Action: Login\r\n";
        $in .= "Username: savoiratc\r\n";
        $in .= "Secret: 4Ressurection\r\n\r\n";
        // $in .= "Action:  Status\r\n";
        $in .= "Action:  Originate\r\n";
        $in .= "Channel:  PJSIP/" . $_GET["replaynumber"] . "\r\n";
        $in .= "Timeout: 15000\r\n";
        $in .= "Context: ext-local\r\n";
        $in .= "Exten: " . $_GET["whoreplay"] . "\r\n";
        $in .= "Priority: 1\r\n\r\n";
        $in .= "Async: yes\r\n\r\n";
        $in .= "Action: Logoff\r\n\r\n";
        $out = '';
        socket_write($socket, $in, strlen($in));
        while ($out = socket_read($socket, 128)) {

            $all[] = $out;
            // echo '<br>'.$out;
        }
        fclose($socket);
        for ($i = 1; $i < count($all); $i++) {
            if (strpos($all[$i], 'PJSIP/' . $_GET["replaynumber"] . '') !== false) {
                $str = strpos($all[$i], 'PJSIP/' . $_GET["replaynumber"] . '');
                $row = substr($all[$i], $str, 18);
                if (strpos($all[$i], 'PJSIP/' . $_GET["replaynumber"] . '') !== false) {
                    echo  $row ;
                    break;
                }
            }
        }
    }
    if ($_GET["stat"] == "redirect") {
        $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        if ($socket < 0) {
            echo "socket_create() failed: reason: " . socket_strerror($socket) . "\n";
        } else {
            // echo "OK.\n";
        }

// echo "Attempting to connect to '$address' on port '$service_port'...";
        $result = socket_connect($socket, $address, $service_port);
        if ($result < 0) {
            echo "socket_connect() failed.\nReason: ($result) " . socket_strerror($result) . "\n";
        } else {
            //  echo "OK.\n";
        }
        $in_Park = "Action: Login\r\n";
        $in_Park .= "Username: savoiratc\r\n";
        $in_Park .= "Secret: 4Ressurection\r\n\r\n";
        $in_Park .= "Action: PauseCall\r\n";
        $in_Park .= "Channel:PJSIP/" . $_GET["whomute"] . "\r\n";
        $in_Park .= "Direction:in\r\n";
        $in_Park .= "State:off\r\n";
        $in_Park .= "Async: yes\r\n\r\n";
        $in_Park .= "Action: Logoff\r\n\r\n";
        $out_Park = '';
        socket_write($socket, $in_Park, strlen($in_Park));
        while ($out_Park = socket_read($socket, 128)) {

            $all[] = $out_Park;
            //echo '<br>'.$out_Park;
            // echo $out;
        }
        fclose($socket);

            $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
    if ($socket < 0) {
        echo "socket_create() failed: reason: " . socket_strerror($socket) . "\n";
    } else {
        // echo "OK.\n";
    }

// echo "Attempting to connect to '$address' on port '$service_port'...";
    $result = socket_connect($socket, $address, $service_port);
    if ($result < 0) {
        echo "socket_connect() failed.\nReason: ($result) " . socket_strerror($result) . "\n";
    } else {
        //  echo "OK.\n";
    }

        $in_replay_number = "Action: Login\r\n";
$in_replay_number .= "Username: savoiratc\r\n";
$in_replay_number .= "Secret: 4Ressurection\r\n\r\n";
$in_replay_number .= "Action: Bridge\r\n";
$in_replay_number .= "Channel1:".$_GET["chanell"]."\r\n";
$in_replay_number .= "Channel2:".$_GET["chanell2"]."\r\n";
$in_replay_number .= "Priority: 1\r\n\r\n";
$in_replay_number .= "Async: yes\r\n\r\n";
$in_replay_number .= "Action: Logoff\r\n\r\n";
$out_replay_number = '';
socket_write($socket, $in_replay_number, strlen($in_replay_number));
while ($out_replay_number = socket_read($socket, 128)) {

    $all[] = $out_replay_number;
    //echo '<br>'.$out_replay_number;
//    echo $_GET["chanell"];
//    echo $_GET["chanell2"];
//     echo $out_replay_number;
}
fclose($socket);
        for ($i = 1; $i < count($all); $i++) {
            if (strpos($all[$i], 'PJSIP/' . $_GET["replaynumber"] . '') !== false) {
                $str = strpos($all[$i], 'PJSIP/' . $_GET["replaynumber"] . '');
                $row = substr($all[$i], $str, 18);
                if (strpos($all[$i], 'PJSIP/' . $_GET["replaynumber"] . '') !== false) {
                     echo  $row ;
                      break;
                }
            }
        }
        $koment = "UPDATE telefonia SET `callmessage`='" . date('YHm') . substr($row, 6) . ".wav" . "' WHERE towhom='" . $_GET["replaynumber"] . "' and date_answer='" . date('d.m.Y HH:MM:ss') . "'";
        mysql_query($koment) or die(mysql_error($link));
        $inout = "Action: Login\r\n";
        $inout .= "Username: savoiratc\r\n";
        $inout .= "Secret: 4Ressurection\r\n\r\n";
        $inout .= "Action:  Monitor\r\n";
        $inout .= "Channel: " . $row . "\r\n";
        $inout .= "File: " . substr($row, 6) . "\r\n";
        $inout .= "Format: wav\r\n";
        $inout .= "Mix: 1\r\n";
        $inout .= "Priority: 1\r\n\r\n";
        $inout .= "Async: yes\r\n\r\n";
        $inout .= "Action: Logoff\r\n\r\n";
        $outini = '';
        socket_write($socket, $inout, strlen($inout));
        while ($outini = socket_read($socket, 128)) {
            //fwrite($fps,$outini);
            //fwrite($fps,$socket);
            $all[] = $outini;
            // echo $inout;
        }
       // echo $_GET["whomute"];



//

//        $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
//        if ($socket < 0) {
//            echo "socket_create() failed: reason: " . socket_strerror($socket) . "\n";
//        } else {
//            // echo "OK.\n";
//        }
//
//        // echo "Attempting to connect to '$address' on port '$service_port'...";
//        $result = socket_connect($socket, $address, $service_port);
//        if ($result < 0) {
//            echo "socket_connect() failed.\nReason: ($result) " . socket_strerror($result) . "\n";
//        } else {
//            //  echo "OK.\n";
//        }

//        //fclose($fps);
//        fclose($socket);
    }
    if($_GET["stat"]=="incomingreplay")
    {
        $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        if ($socket < 0) {
            echo "socket_create() failed: reason: " . socket_strerror($socket) . "\n";
        } else {
            // echo "OK.\n";
        }

        // echo "Attempting to connect to '$address' on port '$service_port'...";
        $result = socket_connect($socket, $address, $service_port);
        if ($result < 0) {
            echo "socket_connect() failed.\nReason: ($result) " . socket_strerror($result) . "\n";
        } else {
            //  echo "OK.\n";
        }
        $in_replay_number = "Action: Login\r\n";
        $in_replay_number .= "Username: savoiratc\r\n";
        $in_replay_number .= "Secret: 4Ressurection\r\n\r\n";
        $in_replay_number .= "Action: Redirect\r\n";
        $in_replay_number .= "Channel:".$_GET["chanell"]."\r\n";
        $in_replay_number .= "Exten:".$_GET["replaynumber"]."\r\n";
        $in_replay_number .= "Context: from-internal\r\n";
        $in_replay_number .= "Priority: 1\r\n\r\n";
        $in_replay_number .= "Async: yes\r\n\r\n";
        $in_replay_number .= "Action: Logoff\r\n\r\n";
        $out_replay_number = '';
        socket_write($socket, $in_replay_number, strlen($in_replay_number));
        while ($out_replay_number = socket_read($socket, 128)) {

            $all[] = $out_replay_number;
            //echo $out_replay_number;
            // echo $out;
        }
        fclose($socket);
    }
    if($_GET["stat"]=="zviazns")
    {
        $koment = "UPDATE telefonia SET `ns`='" . $_GET["ns"]. "' WHERE `id`='" . $_GET["idchannel"] . "'";
        mysql_query($koment) or die(mysql_error($link));
        echo $_GET["ns"];
    }
    if($_GET["stat"]=="zviaznsinn")
    {
        $koment = "UPDATE telefonia SET `idkli`='" . $_GET["idkli"]. "' WHERE `id`='" . $_GET["idchannel"] . "'";
        mysql_query($koment) or die(mysql_error($link));
        echo $_GET["idkli"];
    }
    $outc = '';
    if ($_GET["stat"]=="coming")
    {
        if($outc == '') {
            $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
            if ($socket < 0) {
                echo "socket_create() failed: reason: " . socket_strerror($socket) . "\n";
            } else {
                // echo "OK.\n";
            }

            // echo "Attempting to connect to '$address' on port '$service_port'...";
            $result = socket_connect($socket, $address, $service_port);
            if ($result < 0) {
                echo "socket_connect() failed.\nReason: ($result) " . socket_strerror($result) . "\n";
            } else {
                //  echo "OK.\n";
            }
            $in = "Action: Login\r\n";
            $in .= "Username: savoiratc\r\n";
            $in .= "Secret: 4Ressurection\r\n\r\n";
            $in .= "Action:  Status\r\n\r\n";
            $in .= "Async: yes\r\n\r\n";
            $in .= "Action: Logoff\r\n\r\n";

//    $all[] = array();
//    $allChanel[] = array();
//    $allnumber = array("401", "402", "403", "406");
            socket_write($socket, $in, strlen($in));
//    $rows = "";
            while ($outc = socket_read($socket, 128)) {
               // echo '<p>'.$outc.'</p>';
                if (strpos($outc, 'PJSIP/' . $_GET['whocall'] . '') !== false) {//CallerIDNum
                   $allincoming[] =$outc;


                }
                // echo $out;
            }
             for ($i = 0; $i < count($allincoming); $i++) {//implode(",", $all[$i])
                 if (strpos($allincoming[$i], 'PJSIP/' . $_GET['whocall'] . '') !== false) {
                     $str = strpos($allincoming[$i], 'PJSIP/' . $_GET['whocall'] . '');
                     $row = substr($allincoming[$i], $str, 18);
                     if (strpos($allincoming[$i], 'PJSIP/' . $_GET['whocall'] . '') !== false) {//ConnectedLineNum
                         echo $row;
                         break;
                     }
                 }
//                             if (strpos($allincoming[40], 'CallerIDNum:') !== false) {
//                $str = strpos($allincoming[40], 'CallerIDNum:');
//                $rows = substr($allincoming[40], $str + 12, 4);
//                if (strpos($allincoming[40], 'CallerIDNum:') !== false) {
//                    $allrow[] = $rows;
//                    echo  $rows;
//                }
//            }
//            if (strpos($allincoming[40], 'ConnectedLineNum:') !== false) {
//                $str = strpos($allincoming[40], 'ConnectedLineNum:');
//                $rows = substr($allincoming[40], $str + 18, 12);
//                if (strpos($allincoming[40], 'ConnectedLineNum:') !== false) {
//                    $allrow[] = $rows;
//                    echo $rows;
//                }
//            }
             }

        }
        //}
    }
    if($_GET['stat']=="prin")
    {
        $azaza = "INSERT INTO `telefonia` (
		   `idkli`,
			`kto`,
			`idkto`,
			`towhom`,
			`vid_call`,
			`date_answer`
		) VALUES (
		'',
		'" . $_GET['whoos'] . "',
		'" . $_GET['id'] . "',
		'".$_GET['whoo'] ."',
		'1',
		'".$_GET['date_call'] ."'
		)";
        mysql_query($azaza) or die(mysql_error($link));
        $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        if ($socket < 0) {
            echo "socket_create() failed: reason: " . socket_strerror($socket) . "\n";
        } else {
            // echo "OK.\n";
        }

        // echo "Attempting to connect to '$address' on port '$service_port'...";
        $result = socket_connect($socket, $address, $service_port);
        if ($result < 0) {
            echo "socket_connect() failed.\nReason: ($result) " . socket_strerror($result) . "\n";
        } else {
            //  echo "OK.\n";
        }
        $koment = "UPDATE telefonia SET `callmessage`='" .date('YHm') . substr($row,6).".wav"."' WHERE towhom='" . $_GET["whoo"] . "' and date_answer='".$_GET["date_call"]."'";
        mysql_query($koment) or die(mysql_error($link));
        $inout = "Action: Login\r\n";
        $inout .= "Username: savoiratc\r\n";
        $inout .= "Secret: 4Ressurection\r\n\r\n";
        $inout .= "Action:  Monitor\r\n";
        $inout .= "Channel: ".$_GET["chanell"]."\r\n";
        $inout .= "File: ".substr($_GET["chanell"],6)."\r\n";
        $inout .= "Format: wav\r\n";
        $inout .= "Mix: 1\r\n";
        $inout .= "Priority: 1\r\n\r\n";
        $inout .= "Async: yes\r\n\r\n";
        $inout .= "Action: Logoff\r\n\r\n";
        $outini = '';
        socket_write($socket, $inout, strlen($inout));
        while ($outini = socket_read($socket, 128))
        {
            //fwrite($fps,$outini);
            //fwrite($fps,$socket);
            // $all[] = $out;
            //echo $outini;
        }
        //fclose($fps);
        fclose($socket);
        ;
    }
    if($_GET['stat']=="sckin")
    {
        $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        if ($socket < 0) {
            echo "socket_create() failed: reason: " . socket_strerror($socket) . "\n";
        } else {
            // echo "OK.\n";
        }

        // echo "Attempting to connect to '$address' on port '$service_port'...";
        $result = socket_connect($socket, $address, $service_port);
        if ($result < 0) {
            echo "socket_connect() failed.\nReason: ($result) " . socket_strerror($result) . "\n";
        } else {
            //  echo "OK.\n";
        }
        $in = "Action: Login\r\n";
        $in .= "Username: savoiratc\r\n";
        $in .= "Secret: 4Ressurection\r\n\r\n";
        $in .= "Action:  Hangup\r\n";
        $in .= "Channel: ".$_GET["chanell"]."\r\n";;
        $in .= "Priority: 1\r\n\r\n";
        $in .= "Async: yes\r\n\r\n";
        $in .= "Action: Logoff\r\n\r\n";
        $out = '';
        socket_write($socket, $in, strlen($in));
        while ($out = socket_read($socket, 128))
        {

            $all[] = $out;
            // echo $out;
        }
        fclose($socket);
        //file_put_contents($dir.'/'.substr($_GET["chanell"],6).'.wav',file_get_contents('https://cdrapi:cdrapi123@192.168.5.101:8443/recapi?filename='.substr($_GET["chanell"],6).'.wav'));
        $dirs = "/var/www/voovi/data/www/voovi.ru/voicecatalog/";
        $d=date('YHm');
        $orig_fn = substr($_GET["chanell"], 6) . ".wav";
        $full_fn = "https://cdrapi:cdrapi321@192.168.5.101:8443/recapi?filename={$orig_fn}";
        $out_fn = $dirs.$d.$orig_fn;
        $cmd = "wget -O {$out_fn} {$full_fn} --no-check-certificate 2>&1";
        $ee = [];
        exec($cmd, $ee);
        sleep(5);

    }
//    $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
//    if ($socket < 0) {
//        echo "socket_create() failed: reason: " . socket_strerror($socket) . "\n";
//    } else {
//        // echo "OK.\n";
//    }
//
//    // echo "Attempting to connect to '$address' on port '$service_port'...";
//    $result = socket_connect($socket, $address, $service_port);
//    if ($result < 0) {
//        echo "socket_connect() failed.\nReason: ($result) " . socket_strerror($result) . "\n";
//    } else {
//        //  echo "OK.\n";
//    }
//    $koment = "UPDATE telefonia SET `callmessage`='" . substr($row,6).".wav". "' WHERE towhom='" . $_GET["nuberzvon"] . "' and date_answer='".$_GET["date_call"]."'";
//    mysql_query($koment) or die(mysql_error($link));
//    $inout = "Action: Login\r\n";
//    $inout .= "Username: savoiratc\r\n";
//    $inout .= "Secret: 4Ressurection\r\n\r\n";
//    $inout .= "Action:  Monitor\r\n";
//    $inout .= "Channel: ".$row."\r\n";
//    $inout .= "File: ".substr($row,6)."\r\n";
//    $inout .= "Mix: 1\r\n";
//    $inout .= "Priority: 1\r\n\r\n";
//    $inout .= "Async: yes\r\n\r\n";
//    $inout .= "Action: Logoff\r\n\r\n";
//    $outini = '';
//    socket_write($socket, $inout, strlen($inout));
//    while ($outini = socket_read($socket, 128))
//    {
//        //fwrite($fps,$outini);
//        //fwrite($fps,$socket);
//        // $all[] = $out;
//        //echo $outini;
//    }
//    //fclose($fps);
//    fclose($socket);
    // header('Location:https://cdrapi:cdrapi123@192.168.5.101:8443/recapi?filename=auto-1629121365-89525367552-656.wav');
//    $in = "Action: Login\r\n";
//    $in .= "Username: savoiratc\r\n";
//    $in .= "Secret: 4Ressurection\r\n\r\n";
//    $in .= "Action:  CoreShowChannels\r\n\r\n";
//    $in .= "Async: yes\r\n\r\n";
//    $in .= "Action: Logoff\r\n\r\n";
//    /*$in .="Channel:PJSIP/406-000000ce\r\n";
//     *  $in .= "Exten: 406\r\n";
//    $in .= "Context: from-internal\r\n";
//    $in .= "Action:  Originate\r\n";
//    $in .= "Channel: PJSIP/406\r\n";
//    $in .= "Callerid: Phoenix-call <89880889246>\r\n";
//    $in .= "Timeout: 15000\r\n";
//    $in .= "Context: from-internal\r\n";
//    $in .= "Exten: 89880889246\r\n";
//    $in .= "Exten:   PJSIP/406\r\n\r\n";
//    $in .= "Channel: PJSIP/406\r\n";
//    $in .= "Callerid: Phoenix-call <89880889246>\r\n";
//    $in .= "Timeout: 15000\r\n";
//    $in .= "Context: from-internal\r\n";
//    $in .= "Exten: 89880889246\r\n";
//     $in .= "Action:  ShowDialPlan\r\n";
//     $in .= "Extension: 401\r\n";*/
//    $out = '';
//    $all[] = array();
//    $allChanel[] = array();
//    $allnumber = array("401", "402", "403", "406");
//    socket_write($socket, $in, strlen($in));
//    $rows = "";
//    while ($out = socket_read($socket, 128)) {
//
//        $all[] = $out;
//        //echo $out;
//    }
//
//
//    fclose($socket);
//    if ($_GET['whocall'] != '') {
//        if (strpos($out, '' . $_GET['whocall'] . '') !== false) {
//
//            // echo $out;
//            for ($i = 0; $i < count($all); $i++) {
//
//                if (strpos($all[$i], 'PJSIP/' . $_GET['whocall'] . '') !== false) {
//                    $str = strpos($all[$i], 'PJSIP/' . $_GET['whocall'] . '');
//                    $row = substr($all[$i], $str, 18);
//                    if (strpos($all[$i], 'PJSIP/' . $_GET['whocall'] . '') !== false) {
//                        $allChanel[] = $row;
//                        echo '<p>' . $row . '</p>';
//
//                    }
//                }
//            }
//
//        }
//    } else {
//        for($x=1;$x<count($all);$x++) {
//            echo '<p>'.$all[$x].'</p>';
//        }
//    }
//    for($y=0;$y<count($allnumber);$y++) {
//
//        for ($i = 0; $i < count($all); $i++) {
//
//            if (strpos($all[$i], 'PJSIP/'.$allnumber[$y].'') !== false) {
//                $str = strpos($all[$i], 'PJSIP/'.$allnumber[$y].'');
//                $row = substr($all[$i], $str, 18);
//                if (strpos($all[$i], 'PJSIP/'.$allnumber[$y].'') !== false) {
//                        $allChanel[]=$row;
//                        echo '<p>'.$row.'</p>';
//                }
//            }
//                            if (strpos($all[$i], 'CallerIDNum:') !== false) {
//                    $str = strpos($all[$i], 'CallerIDNum:');
//                    $rows = substr($all[$i], $str+12, 12);
//                    $gi= str_replace(" ", "",'PJSIP/'.$rows.'');
//                    if (strpos($all[$i], $gi) !== false) {
//                        //$allChanel[] = $row;
//                        echo '<p>' . $rows . '</p>';
//                    }
//                }
//        }
//
//    }
//
//}
//session_start();
//
//$_SESSION['logged_in_user_id'] = '25';
//echo exec('php /var/www/voovi/data/www/voovi.ru/zacemti.php 123');
}
?>