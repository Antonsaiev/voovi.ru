<?php
include __DIR__.'/conf.php';
require_once __DIR__.'/invoice_fields_storage.php';
header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');
$user=voovi_schet_authenticated_user();
if ($_SERVER['REQUEST_METHOD']!=='POST') { http_response_code(405); header('Allow: POST'); echo json_encode(array('error'=>'Используйте поле в карточке счёта.')); exit; }
try {
    $rand=isset($_POST['rand']) && is_string($_POST['rand']) ? $_POST['rand'] : '';
    voovi_schet_request_key(isset($_POST['token']) ? $_POST['token'] : '', 'invoice-fields:'.$rand,$user);
    $result=voovi_invoice_field_save($rand,isset($_POST['field']) && is_string($_POST['field']) ? $_POST['field'] : '',
        isset($_POST['value']) ? $_POST['value'] : null,isset($_POST['expected']) && is_string($_POST['expected']) ? json_decode($_POST['expected'],true) : null);
    echo json_encode($result);
} catch(VooviSchetException $error) { http_response_code(409); echo json_encode(array('error'=>$error->getMessage())); }
