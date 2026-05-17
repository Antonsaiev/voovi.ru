<!--<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">-->
<!--<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">-->
<!--<head>-->
<!--    <title></title>-->
<!--    <meta http-equiv="content-type" content="text/html" charset="utf-8" />-->
<!--    <meta http-equiv="Content-Style-Type" content="text/css" />-->
<!--    <meta name="viewport" content="width=device-width, initial-scale=1">-->
<!--    <link href="css/bootstrap.min.css" rel="stylesheet">-->
<!--    <script type="text/javascript" src="https://flashphoner.com/downloads/builds/flashphoner_client/wcs_api-2.0/current/flashphoner.js"></script>-->
<!--    <script type="text/javascript" src="js/click-to-call.js"></script>-->
<!--<body onload="init_page()">-->
<!--<div>-->
<!--<button id="callButton" type="button" onclick="connectAndCall('+79880889246')">Call</button>-->
<!--<button id="hangupButton" type="button" onclick="hangup()">Hangup</button>-->
<!--</div>-->
<!--<div id="remoteMedia" style="visibility: hidden"></div>-->
<!--<div id="localMedia" style="visibility: hidden"></div>-->
<!--<p id="status"></p>-->
<!--<script>-->
<!--    var SESSION_STATUS = Flashphoner.constants.SESSION_STATUS;-->
<!--    var CALL_STATUS = Flashphoner.constants.CALL_STATUS;-->
<!--    var localMedia;-->
<!--    var remoteMedia;-->
<!--    var outCall;-->
<!---->
<!--    //init API-->
<!--    function init_page() {-->
<!--        Flashphoner.init();-->
<!--        localMedia = document.getElementById("localMedia");-->
<!--        remoteMedia = document.getElementById("remoteMedia");-->
<!--    }-->
<!---->
<!--    //call-->
<!--    function connectAndCall(number) {-->
<!---->
<!--        //if already connected, make a call-->
<!--        if (Flashphoner.getSessions().length > 0) {-->
<!--            call(number, Flashphoner.getSessions()[0]);-->
<!--        } else {-->
<!---->
<!--            //SIP credentials-->
<!--            var sipOptions = {-->
<!--                login: "406",-->
<!--                authenticationName: "406",-->
<!--                password: "savoir#9ej295",-->
<!--                domain: "https://192.168.5.101/",-->
<!--                outboundProxy: "https://192.168.5.101/",-->
<!--                port: "8089",-->
<!--                registerRequired: true-->
<!--            };-->
<!---->
<!--            var connectionOptions = {-->
<!--                urlServer: "wss://192.168.5.101:8445/ws",-->
<!--                sipOptions: sipOptions-->
<!--            };-->
<!--            //create new connection to WCS server-->
<!--            Flashphoner.createSession(connectionOptions).on(Flashphoner.constants.SESSION_STATUS.ESTABLISHED, function (session) {-->
<!--                setStatus("Session", Flashphoner.constants.SESSION_STATUS.ESTABLISHED);-->
<!--                //session connected, place call-->
<!--                call(number, session);-->
<!--            }).on(Flashphoner.constants.SESSION_STATUS.DISCONNECTED, function () {-->
<!--                setStatus("Session", Flashphoner.constants.SESSION_STATUS.DISCONNECTED);-->
<!--                onHangup();-->
<!--            }).on(Flashphoner.constants.SESSION_STATUS.FAILED, function () {-->
<!--                setStatus("Session", Flashphoner.constants.SESSION_STATUS.FAILED);-->
<!--                onHangup();-->
<!--            });-->
<!--        }-->
<!--    }-->
<!---->
<!--    function call(number, session) {-->
<!---->
<!--        //disable call button-->
<!--        document.getElementById("callButton").disabled=true;-->
<!---->
<!--        var constraints = {-->
<!--            audio: true,-->
<!--            video: false-->
<!--        };-->
<!---->
<!--        //prepare outgoing call-->
<!--        outCall = session.createCall({-->
<!--            callee: number,-->
<!--            visibleName: "Click To Call",-->
<!--            localVideoDisplay: localMedia,-->
<!--            remoteVideoDisplay: remoteMedia,-->
<!--            constraints: constraints,-->
<!--            receiveAudio: true,-->
<!--            receiveVideo: false-->
<!--        }).on(Flashphoner.constants.CALL_STATUS.RING, function () {-->
<!--            setStatus("Call", Flashphoner.constants.CALL_STATUS.RING);-->
<!--        }).on(Flashphoner.constants.CALL_STATUS.ESTABLISHED, function () {-->
<!--            setStatus("Call", Flashphoner.constants.CALL_STATUS.ESTABLISHED);-->
<!--        }).on(Flashphoner.constants.CALL_STATUS.FINISH, function () {-->
<!--            setStatus("Call", Flashphoner.constants.CALL_STATUS.FINISH);-->
<!--            onHangup();-->
<!--        }).on(Flashphoner.constants.CALL_STATUS.FAILED, function () {-->
<!--            setStatus("Call", Flashphoner.constants.CALL_STATUS.FAILED);-->
<!--            onHangup();-->
<!--        });-->
<!---->
<!--        outCall.call();-->
<!--    }-->
<!---->
<!--    function hangup() {-->
<!--        if (outCall) {-->
<!--            outCall.hangup();-->
<!--        }-->
<!--    }-->
<!---->
<!--    function onHangup(){-->
<!--        //will be invoked on hangup-->
<!--    }-->
<!---->
<!--    function setStatus(callOrSession,status){-->
<!--        document.getElementById("status").innerHTML= callOrSession +" "+status;-->
<!--    }-->
<!--</script>-->
<!--</body>-->
<!--</html>-->
<?php
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
//
//$in_replay_number = "Action: Login\r\n";
//$in_replay_number .= "Username: savoiratc\r\n";
//$in_replay_number .= "Secret: 4Ressurection\r\n\r\n";
//$in_replay_number .= "Action: Bridge\r\n";
//$in_replay_number .= "Channel1:PJSIP/401-0000139e\r\n";
//$in_replay_number .= "Channel2:PJSIP/trunk_4-0000139c\r\n";
//$in_replay_number .= "Priority: 1\r\n\r\n";
//$in_replay_number .= "Async: yes\r\n\r\n";
//$in_replay_number .= "Action: Logoff\r\n\r\n";
//$out_replay_number = '';
//socket_write($socket, $in_replay_number, strlen($in_replay_number));
//while ($out_replay_number = socket_read($socket, 128)) {
//
//    $all[] = $out_replay_number;
//    echo '<br>'.$out_replay_number;
//    // echo $out;
//}
//fclose($socket);


//
//$in_Atxfer_number = "Action: Login\r\n";
//$in_Atxfer_number .= "Username: savoiratc\r\n";
//$in_Atxfer_number .= "Secret: 4Ressurection\r\n\r\n";
//$in_Atxfer_number .= "Action: Atxfer\r\n";
//$in_Atxfer_number .= "Channel: PJSIP/trunk_4-000012f8\r\n";
//$in_Atxfer_number .= "Exten: 401\r\n";
//$in_Atxfer_number .= "Context: from-internal\r\n";
//$in_Atxfer_number .= "Priority: 63\r\n\r\n";
//$in_Atxfer_number .= "Result: \r\n\r\n";
//$in_Atxfer_number .= "Async: yes\r\n\r\n";
//$in_Atxfer_number .= "Action: Logoff\r\n\r\n";
//$out_Atxfer_number = '';
//socket_write($socket, $in_Atxfer_number, strlen($in_Atxfer_number));
//while ($out_Atxfer_number = socket_read($socket, 128)) {
//
//    $all[] = $out_Atxfer_number;
//    echo '<br>'.$out_Atxfer_number;
//    // echo $out;
//}
//fclose($socket);





//$in_replay_number = "Action: Login\r\n";
//$in_replay_number .= "Username: savoiratc\r\n";
//$in_replay_number .= "Secret: 4Ressurection\r\n\r\n";
//$in_replay_number .= "Action: PauseCall\r\n";
//$in_replay_number .= "Channel:PJSIP/406\r\n";
//$in_replay_number .= "State:on\r\n";
//$in_replay_number .= "Async: yes\r\n\r\n";
//$in_replay_number .= "Action: Logoff\r\n\r\n";
//$out_replay_number = '';
//socket_write($socket, $in_replay_number, strlen($in_replay_number));
//while ($out_replay_number = socket_read($socket, 128)) {
//
//    $all[] = $out_replay_number;
//    echo '<br>'.$out_replay_number;
//    // echo $out;
//}
//fclose($socket);



//
//$in_replay_number = "Action: Login\r\n";
//$in_replay_number .= "Username: savoiratc\r\n";
//$in_replay_number .= "Secret: 4Ressurection\r\n\r\n";
//$in_replay_number .= "Action: Park\r\n";
//$in_replay_number .= "Channel:PJSIP/trunk_4-0000139c\r\n";
//$in_replay_number .= "Channel2:PJSIP/406\r\n";
//$in_replay_number .= "Timeout: 1000000\r\n\r\n";
//$in_replay_number .= "Async: yes\r\n\r\n";
//$in_replay_number .= "Action: Logoff\r\n\r\n";
//$out_replay_number = '';
//socket_write($socket, $in_replay_number, strlen($in_replay_number));
//while ($out_replay_number = socket_read($socket, 128)) {
//
//    $all[] = $out_replay_number;
//    echo '<br>'.$out_replay_number;
//    // echo $out;
//}
//fclose($socket);
//
//$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
//if ($socket < 0) {
//    echo "socket_create() failed: reason: " . socket_strerror($socket) . "\n";
//} else {
//    // echo "OK.\n";
//}
//
//// echo "Attempting to connect to '$address' on port '$service_port'...";
//$result = socket_connect($socket, $address, $service_port);
//if ($result < 0) {
//    echo "socket_connect() failed.\nReason: ($result) " . socket_strerror($result) . "\n";
//} else {
//    //  echo "OK.\n";
//}
//        $in = "Action: Login\r\n";
//        $in .= "Username: savoiratc\r\n";
//        $in .= "Secret: 4Ressurection\r\n\r\n";
//        //$in .= "Action:  Status\r\n";
//        $in .= "Action:  Originate\r\n";
//        $in .= "Channel:  PJSIP/401\r\n";
//        //Application:AppDial
//       // $in .= "Callerid: Phoenix-call <89880889246>\r\n";
//        $in .= "Timeout: 15000\r\n";
//        $in .= "Context: from-internal\r\n";
//        $in .= "Exten: 406\r\n";
//        $in .= "Priority: 1\r\n\r\n";
//        $in .= "Async: yes\r\n\r\n";
//        $in .= "Action: Logoff\r\n\r\n";
//        $out = '';
//        socket_write($socket, $in, strlen($in));
//        while ($out = socket_read($socket, 128))
//        {
//
//            $all[] = $out;
//            echo '<br>'.$out;
//        }
//        fclose($socket);



//$in_replay_number = "Action: Login\r\n";
//$in_replay_number .= "Username: savoiratc\r\n";
//$in_replay_number .= "Secret: 4Ressurection\r\n\r\n";
//$in_replay_number .= "Action: PauseCall\r\n";
//$in_replay_number .= "Channel:PJSIP/406\r\n";
//$in_replay_number .= "Direction:in\r\n";
//$in_replay_number .= "State:on\r\n";
//$in_replay_number .= "Async: yes\r\n\r\n";
//$in_replay_number .= "Action: Logoff\r\n\r\n";
//$out_replay_number = '';
//socket_write($socket, $in_replay_number, strlen($in_replay_number));
//while ($out_replay_number = socket_read($socket, 128)) {
//
//    $all[] = $out_replay_number;
//    echo '<br>'.$out_replay_number;
//    // echo $out;
//}
//fclose($socket);


//$in_replay_number = "Action: Login\r\n";
//$in_replay_number .= "Username: savoiratc\r\n";
//$in_replay_number .= "Secret: 4Ressurection\r\n\r\n";
//$in_replay_number .= "Action: Originate\r\n";
//$in_replay_number .= "Channel:PJSIP/401\r\n";
//$in_replay_number .= "Application: PickupChan\r\n";
//$in_replay_number .= "Data:PJSIP/trunk_4-000012c4\r\n";
//$in_replay_number .= "Priority:1\r\n";
//$in_replay_number .= "Async: yes\r\n\r\n";
//$in_replay_number .= "Action: Logoff\r\n\r\n";
//$out_replay_number = '';
//socket_write($socket, $in_replay_number, strlen($in_replay_number));
//while ($out_replay_number = socket_read($socket, 128)) {
//
//    $all[] = $out_replay_number;
//    echo '<br>'.$out_replay_number;
//    // echo $out;
//}
//fclose($socket);

//macro-dial
//////macro-dialout-trunk
//        $in = "Action: Login\r\n";
//        $in .= "Username: savoiratc\r\n";
//        $in .= "Secret: 4Ressurection\r\n\r\n";
//       // $in .= "Action:  Status\r\n";
//        $in .= "Action:  Originate\r\n";
//        $in .= "Channel:  PJSIP/89880889246@trunk_4\r\n";
//        //Application:AppDial
//       // $in .= "Callerid: Phoenix-call <89880889246>\r\n";
//        $in .= "Timeout: 15000\r\n";
//        $in .= "Context: ext-local\r\n";
//        $in .= "Exten: 406\r\n";
//        $in .= "Priority: 1\r\n\r\n";
//        $in .= "Async: yes\r\n\r\n";
//        $in .= "Action: Logoff\r\n\r\n";
//        $out = '';
//        socket_write($socket, $in, strlen($in));
//        while ($out = socket_read($socket, 128))
//        {
//
//            $all[] = $out;
//            echo '<br>'.$out;
//        }
//        fclose($socket);

////?>