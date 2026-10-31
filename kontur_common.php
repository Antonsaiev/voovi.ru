<?php
// Используем авторизацию и права CRM; токены API остаются на сервере.
require_once __DIR__.'/conf.php';

function kontur_h($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

function kontur_fail($message, $status) {
    http_response_code($status);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(array('error' => $message), JSON_UNESCAPED_UNICODE);
    exit;
}

function kontur_context($rand) {
    if (!isset($_COOKIE['id'], $_COOKIE['hash']) || !is_string($_COOKIE['id']) || !is_string($_COOKIE['hash'])) {
        kontur_fail('Войдите в Voovi', 403);
    }
    $q = mysql_query("SELECT * FROM users WHERE users_id = '".intval($_COOKIE['id'])."' LIMIT 1");
    $user = $q ? mysql_fetch_assoc($q) : false;
    if (!$user || (string)$user['users_id'] !== $_COOKIE['id'] ||
        !hash_equals((string)$user['users_hash'], $_COOKIE['hash']) ||
        (string)$user['del_users'] !== '0' || (string)$user['tip'] === '88' || (string)$user['uvl'] === '1') {
        kontur_fail('Нет доступа', 403);
    }
    if (!is_string($rand) || $rand === '' || strlen($rand) > 366) {
        kontur_fail('Укажите сохранённый счёт Voovi', 400);
    }
    $escaped = mysql_real_escape_string($rand);
    $q = mysql_query("SELECT * FROM schet WHERE rand = '".$escaped."' AND del = '0' ORDER BY id");
    $rows = array();
    if ($q) { while ($row = mysql_fetch_assoc($q)) { $rows[] = $row; } }
    if (!$rows) { kontur_fail('Счёт Voovi не найден', 404); }
    $products = array();
    foreach ($rows as $row) {
        if ($row['idkli'] !== $rows[0]['idkli']) {
            kontur_fail('Нет доступа к направлению или расходятся клиенты счёта', 403);
        }
        $products[intval($row['produkt'])] = true;
    }
    // Проверяем все направления одним запросом, независимо от числа строк счёта.
    $access = mysql_query("SELECT DISTINCT p.id FROM produkti p INNER JOIN users_access ua ON ua.uslugi = p.parent WHERE p.id IN (".implode(',', array_keys($products)).") AND ua.users = '".intval($user['users_id'])."'");
    if (!$access || mysql_num_rows($access) !== count($products)) {
        kontur_fail('Нет доступа к направлению или расходятся клиенты счёта', 403);
    }
    return array($user, $rows[0]);
}
