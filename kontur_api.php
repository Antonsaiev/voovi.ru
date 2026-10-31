<?php
require_once __DIR__.'/kontur_common.php';
header('Cache-Control: no-store');
header('Content-Type: application/json; charset=utf-8');
session_start();
$rand = isset($_GET['rand']) ? $_GET['rand'] : '';
list($user, $invoice) = kontur_context($rand);
$action = isset($_GET['action']) ? $_GET['action'] : 'state';
$methods = array('state' => 'GET', 'status' => 'GET', 'catalog' => 'GET', 'availability' => 'GET', 'account' => 'POST', 'calculate' => 'POST', 'issue' => 'POST', 'search' => 'GET', 'attach' => 'POST');
if (!is_string($action) || !isset($methods[$action]) || $_SERVER['REQUEST_METHOD'] !== $methods[$action]) {
    kontur_fail('Неверное действие или метод', 405);
}
if ($methods[$action] === 'POST') {
    $csrf = isset($_SERVER['HTTP_X_KONTUR_CSRF']) ? $_SERVER['HTTP_X_KONTUR_CSRF'] : '';
    if (empty($_SESSION['kontur_csrf']) || !hash_equals($_SESSION['kontur_csrf'], $csrf)) {
        kontur_fail('Обновите страницу и повторите действие', 403);
    }
}
if (empty($user['secret_token']) || preg_match('/[\r\n]/', $user['secret_token'])) {
    kontur_fail('У пользователя не настроен доступ к API Voovi', 403);
}
session_write_close();
$base = rtrim(getenv('api_s_voovi') ?: 'https://s.voovi.ru', '/');
$url = $base.'/api/v1/kontur/'.rawurlencode($rand).'/'.($action === 'state' ? '' : $action);
if ($action === 'catalog') {
    $url .= '?'.http_build_query(array('product' => isset($_GET['product']) ? $_GET['product'] : '', 'payer' => isset($_GET['payer']) ? $_GET['payer'] : ''));
}
if ($action === 'search') {
    $url .= '?'.http_build_query(array('q' => isset($_GET['q']) ? $_GET['q'] : ''));
}
if ($action === 'availability') {
    $url .= '?'.http_build_query(array('products' => isset($_GET['products']) ? $_GET['products'] : ''));
}
$body = $methods[$action] === 'POST' ? file_get_contents('php://input', false, null, 0, 262145) : '';
if (strlen($body) > 262144) { kontur_fail('Слишком большой состав счёта', 413); }
$ctx = stream_context_create(array('http' => array(
    'method' => $methods[$action],
    'header' => "Authorization: ".$user['secret_token']."\r\nContent-Type: application/json\r\nAccept: application/json\r\n",
    'content' => $body, 'timeout' => 110, 'ignore_errors' => true, 'follow_location' => 0,
)));
$result = @file_get_contents($url, false, $ctx);
if ($result === false) {
    if ($action === 'account') {
        kontur_fail('Результат подготовки лицевого счёта пока не подтверждён. Обновите проверку позже; повторная регистрация автоматически не отправляется.', 502);
    }
    kontur_fail('Нет ответа сервера. Если выполнялось выставление, сначала проверьте сохранённый результат.', 502);
}
$status = 502;
if (isset($http_response_header[0]) && preg_match('/\s(\d{3})\s/', $http_response_header[0], $m)) { $status = intval($m[1]); }
$decoded = json_decode($result, true);
if (!is_array($decoded)) {
    $message = $action === 'issue'
        ? 'Не удалось получить результат выставления. Проверьте сохранённое состояние; повторно счёт не отправляйте.'
        : 'Не удалось получить данные для расчёта. Счёт этим действием не выставлялся. Попробуйте ещё раз или обратитесь к администратору.';
    kontur_fail($message, 502);
}
http_response_code($status);
echo json_encode($decoded, JSON_UNESCAPED_UNICODE);
