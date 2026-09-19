<?php
error_reporting(0);
require_once 'conf.php';

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');

if (!isset($_COOKIE['id'], $_COOKIE['hash']) || !is_string($_COOKIE['id']) || !is_string($_COOKIE['hash'])) {
    http_response_code(403);
    echo json_encode(array('error' => 'auth'));
    exit;
}

$userResult = mysql_query("SELECT users_id, users_hash FROM users WHERE users_id = '".intval($_COOKIE['id'])."' LIMIT 1");
$user = $userResult ? mysql_fetch_assoc($userResult) : false;
if (!$user || (string)$user['users_id'] !== $_COOKIE['id'] || $user['users_hash'] !== $_COOKIE['hash']) {
    http_response_code(403);
    echo json_encode(array('error' => 'auth'));
    exit;
}

$number = isset($_GET['number']) && is_string($_GET['number']) ? trim($_GET['number']) : '';
if ($number === '') {
    echo json_encode(array('exists' => false));
    exit;
}

// Include shipped and deleted invoices, but ignore drafts and refusals.
$where = "TRIM(nomerschetks) = '".mysql_real_escape_string($number)."' AND cher = '0' AND otk = '0'";
$excludeRand = isset($_GET['exclude_rand']) && is_string($_GET['exclude_rand']) ? $_GET['exclude_rand'] : '';
if ($excludeRand !== '') {
    // An invoice can contain several rows; do not match itself while editing.
    $where .= " AND rand <> '".mysql_real_escape_string($excludeRand)."'";
}

$result = mysql_query("SELECT id FROM schet WHERE ".$where." LIMIT 1");
if (!$result) {
    http_response_code(500);
    echo json_encode(array('error' => 'query'));
    exit;
}

echo json_encode(array('exists' => (bool)mysql_fetch_assoc($result)));
