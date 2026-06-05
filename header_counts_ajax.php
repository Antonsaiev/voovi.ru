<?php
error_reporting(0);

include 'conf.php';

header('Content-Type: application/json; charset=utf-8');

if (!isset($_COOKIE['id']) || !isset($_COOKIE['hash'])) {
    http_response_code(403);
    echo json_encode(array('error' => 'auth'), JSON_UNESCAPED_UNICODE);
    exit;
}

$userId = (int)$_COOKIE['id'];
$userdata = mysql_fetch_assoc(mysql_query("SELECT * FROM users WHERE users_id = '".$userId."' LIMIT 1"));

if (!$userdata || $userdata['users_hash'] !== $_COOKIE['hash']) {
    http_response_code(403);
    echo json_encode(array('error' => 'auth'), JSON_UNESCAPED_UNICODE);
    exit;
}

function savoir_count_value($sql) {
    $result = mysql_query($sql);
    if (!$result) {
        return 0;
    }
    $row = mysql_fetch_assoc($result);
    return isset($row['c']) ? (int)$row['c'] : 0;
}

function savoir_schet_count($where, $userdata) {
    $userId = (int)$userdata['users_id'];
    $inogrn = mysql_real_escape_string($userdata['inogrn']);
    $accessJoin = "";
    $accessWhere = "";

    if ((string)$userdata['inogrn'] === '89097565645') {
        $accessJoin = " INNER JOIN users_access ua ON ua.uslugi = p.parent AND ua.users = '".$userId."'";
    } else {
        $accessWhere = " AND p.parent = '".$inogrn."'";
    }

    return savoir_count_value("
        SELECT COUNT(DISTINCT IF(s.rand = '', s.id, s.rand)) AS c
        FROM schet s
        INNER JOIN produkti p ON p.id = s.produkt
        ".$accessJoin."
        WHERE ".$where.$accessWhere
    );
}

$counts = array();
$counts['turbo'] = savoir_schet_count("s.del = '0' AND s.turbo = '1' AND s.akt = '0' AND s.otk = '0' AND s.cher = '0'", $userdata);

if ($userdata['otvetstven'] == '1') {
    $counts['shipped'] = savoir_schet_count("s.del = '0' AND s.akt_date = '".date('ym')."' AND s.akt = '1' AND s.cher = '0'", $userdata);
}

$statusQuery = mysql_query("SELECT id FROM status WHERE inv = '0' AND del = '0' AND uslugi = '".mysql_real_escape_string($userdata['inogrn'])."' ORDER BY id DESC");
while ($statusRow = mysql_fetch_assoc($statusQuery)) {
    $statusId = (int)$statusRow['id'];
    $counts['status_'.$statusId] = savoir_schet_count("s.status = '".$statusId."' AND s.otk = '0' AND s.akt = '0' AND s.del = '0' AND s.cher = '0'", $userdata);
}

$counts['paid_ready'] = savoir_schet_count("s.del = '0' AND s.status = '' AND s.oplachenks = '1' AND s.gotov = '0' AND s.akt = '0' AND s.otk = '0' AND s.cher = '0'", $userdata);
$counts['my_unpaid'] = savoir_schet_count("s.del = '0' AND s.oplachenks != '1' AND s.oplachen != '1' AND s.kto = '".$userId."' AND s.gotov = '0' AND s.akt = '0' AND s.otk = '0' AND s.cher = '0'", $userdata);
$counts['doljenop'] = savoir_schet_count("s.del = '0' AND s.doljenop = '1' AND s.cher = '0'", $userdata);
$counts['doljen'] = savoir_schet_count("s.del = '0' AND s.doljen = '1' AND s.cher = '0'", $userdata);
$counts['postprod'] = savoir_schet_count("s.del = '0' AND s.postprod = '1' AND s.cher = '0' AND s.otk = '0'", $userdata);

echo json_encode(array('counts' => $counts), JSON_UNESCAPED_UNICODE);
exit;
?>
