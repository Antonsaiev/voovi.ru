<?php
include __DIR__.'/conf.php';
require_once __DIR__.'/invoice_manager.php';
header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');
$user=voovi_schet_authenticated_user();
if (!voovi_invoice_manager_allowed($user)) {
    http_response_code(403); echo json_encode(array('error'=>'Смена менеджера доступна только руководителю.')); exit;
}
try {
    $rand=isset($_GET['rand']) ? $_GET['rand'] : (isset($_POST['rand']) ? $_POST['rand'] : '');
    if ($_SERVER['REQUEST_METHOD']==='POST') {
        voovi_schet_request_key(isset($_POST['token']) ? $_POST['token'] : '', 'invoice-manager:'.$rand, $user);
        $result=voovi_invoice_manager_change($rand,isset($_POST['manager']) ? $_POST['manager'] : '',$user,isset($_POST['version']) ? $_POST['version'] : '',isset($_POST['shared_confirm']) && $_POST['shared_confirm']==='1');
    } elseif ($_SERVER['REQUEST_METHOD']==='GET' && !isset($_GET['lico'])) {
        $state=voovi_invoice_manager_state($rand);
        $options=array();
        $rows=voovi_schet_query("SELECT users_id,f_name,l_name,o_name FROM users WHERE del_users=0 AND uvl<>'1' AND tip<>88 ORDER BY f_name,l_name,o_name");
        while ($row=mysql_fetch_assoc($rows)) $options[]=array('id'=>$row['users_id'],'name'=>trim($row['f_name'].' '.$row['l_name'].' '.$row['o_name']));
        $result=array('current'=>$state['current'],'manager_id'=>$state['manager_id'],'version'=>$state['version'],
            'related'=>$state['related'],'has_kontur'=>count($state['bills'])>0,'managers'=>$options,
            'token'=>voovi_schet_form_token('invoice-manager:'.$rand,$user));
    } else { http_response_code(405); header('Allow: GET, POST'); throw new VooviSchetException('Используйте окно смены менеджера в карточке счёта.'); }
    echo json_encode($result);
} catch (VooviSchetException $error) {
    if (http_response_code()<400) http_response_code(409);
    echo json_encode(array('error'=>$error->getMessage()));
}
