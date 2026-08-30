<?php
error_reporting(0);

include 'conf.php';

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Pragma: no-cache');

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

function savoir_portfolio_count($userdata) {
    $userId = (int)$userdata['users_id'];
    $inogrn = mysql_real_escape_string($userdata['inogrn']);
    $accessJoin = "";
    $accessWhere = "";

    if ((string)$userdata['inogrn'] === '89097565645') {
        $accessJoin = " INNER JOIN users_access ua ON ua.uslugi = p.parent AND ua.users = '".$userId."'";
    } else {
        $accessWhere = " WHERE p.parent = '".$inogrn."'";
    }

    return savoir_count_value("
        SELECT COUNT(*) AS c
        FROM (
            SELECT DISTINCT
                s.ns, s.kolichschet, s.d, s.m, s.y, s.nomerschet, s.nomerschetks,
                s.ogrn, s.prodlen, s.generac, s.name, s.lico, s.rand, s.otdel,
                s.filial, s.god, s.nomerdog, s.data, s.produkt, s.price, s.kto,
                s.inn, s.kpp, s.idkli, s.goroddd, s.akt_date, s.otk, s.koment,
                s.oplachen, s.oplachenks, s.priceks, s.doljen, s.gotov, s.akt,
                s.url, s.groupi, s.install, s.gr
            FROM schet s
            WHERE s.del = '0' AND s.akt != '1' AND s.otk != '1'
        ) portfolio
        INNER JOIN produkti p ON p.id = portfolio.produkt
        ".$accessJoin.$accessWhere
    );
}

function savoir_totals_row($sql) {
    $result = mysql_query($sql);
    if (!$result) {
        return array('price' => 0, 'priceks' => 0);
    }

    $row = mysql_fetch_assoc($result);
    return array(
        'price' => isset($row['price']) ? $row['price'] : 0,
        'priceks' => isset($row['priceks']) ? $row['priceks'] : 0,
    );
}

function savoir_salary_totals($userId) {
    $userId = (int)$userId;
    $period = mysql_real_escape_string(date('ym'));

    $shipped = savoir_totals_row("
        SELECT COALESCE(SUM(shipped_rows.price), 0) AS price,
               COALESCE(SUM(shipped_rows.priceks), 0) AS priceks
        FROM (
            SELECT DISTINCT price, priceks, rand, akt, kto, akt_date, cher
            FROM schet
            WHERE akt = '1' AND cher = '0' AND kto = '".$userId."'
              AND akt_date = '".$period."' AND del = '0'
        ) shipped_rows
    ");

    $paid = savoir_totals_row("
        SELECT
            (
                SELECT COALESCE(SUM(paid_price_rows.price), 0)
                FROM (
                    SELECT DISTINCT price, rand, kto, m, y, oplachenks, del, akt, otk, cher
                    FROM schet
                    WHERE akt = '0' AND cher = '0' AND oplachenks = '1'
                      AND kto = '".$userId."' AND del = '0'
                ) paid_price_rows
            ) AS price,
            (
                SELECT COALESCE(SUM(paid_priceks_rows.priceks), 0)
                FROM (
                    SELECT DISTINCT priceks, rand, kto, m, y, oplachenks, del, akt, otk, cher
                    FROM schet
                    WHERE akt = '0' AND cher = '0' AND oplachenks = '1'
                      AND kto = '".$userId."' AND del = '0'
                ) paid_priceks_rows
            ) AS priceks
    ");

    $unpaid = savoir_totals_row("
        SELECT
            (
                SELECT COALESCE(SUM(unpaid_price_rows.price), 0)
                FROM (
                    SELECT DISTINCT price, rand, kto, m, y, oplachenks, del, akt, otk, cher
                    FROM schet
                    WHERE akt = '0' AND otk = '0' AND cher = '0' AND oplachenks = '0'
                      AND kto = '".$userId."' AND del = '0'
                ) unpaid_price_rows
            ) AS price,
            (
                SELECT COALESCE(SUM(unpaid_priceks_rows.priceks), 0)
                FROM (
                    SELECT DISTINCT priceks, rand, kto, m, y, oplachenks, del, akt, otk, cher
                    FROM schet
                    WHERE akt = '0' AND otk = '0' AND oplachenks = '0' AND cher = '0'
                      AND kto = '".$userId."' AND del = '0'
                ) unpaid_priceks_rows
            ) AS priceks
    ");

    return array(
        'salary_shipped_price' => $shipped['price'],
        'salary_shipped_priceks' => $shipped['priceks'],
        'salary_paid_price' => $paid['price'],
        'salary_paid_priceks' => $paid['priceks'],
        'salary_unpaid_price' => $unpaid['price'],
        'salary_unpaid_priceks' => $unpaid['priceks'],
    );
}

$counts = array();

if ((int)$userdata['tip'] < 87) {
    $userId = (int)$userdata['users_id'];
    $counts['unpaid'] = savoir_count_value("SELECT COUNT(*) AS c FROM schet WHERE oplachen != '1' AND oplachenks != '1' AND gotov = '0' AND akt = '0' AND otk = '0'");
    $counts['portfolio'] = savoir_portfolio_count($userdata);
    $counts['messages'] = savoir_count_value("
        SELECT COUNT(*) AS c
        FROM dialog_messages
        WHERE owner_id != '".$userId."'
          AND view_flag = 0
          AND dialog_id IN (
              SELECT id FROM dialogs WHERE user1 = '".$userId."' OR user2 = '".$userId."'
          )
    ");
    $counts['reminders'] = savoir_count_value("SELECT COUNT(*) AS c FROM napomin WHERE yes = '0' AND users = '".$userId."'");
}

$salaryTotals = savoir_salary_totals($userId);
foreach ($salaryTotals as $salaryKey => $salaryValue) {
    $counts[$salaryKey] = $salaryValue;
}

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
