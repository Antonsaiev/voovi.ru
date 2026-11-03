<?php
// Уведомление о расхождении не должно отправляться при каждом просмотре счёта.
function voovi_status_notice($ns, $statusSchet, $statusJoinTable, $rand = '', $directory = null, $sender = null, $now = null)
{
    if ((string)$statusSchet === (string)$statusJoinTable) { return false; }
    $directory = $directory === null ? __DIR__.'/log/status-notices' : $directory;
    $now = $now === null ? time() : $now;
    if (!is_dir($directory) && !@mkdir($directory, 0700, true) && !is_dir($directory)) {
        error_log('Status notice: journal unavailable'); return false;
    }
    $key = hash('sha256', json_encode(array($rand !== '' ? (string)$rand : (string)$ns, (string)$statusSchet, (string)$statusJoinTable)));
    $statePath = $directory.'/'.$key.'.json';
    $stream = @fopen($directory.'/'.$key.'.lock', 'c+');
    if (!$stream) { error_log('Status notice: journal cannot be opened'); return false; }
    if (!flock($stream, LOCK_EX | LOCK_NB)) { fclose($stream); return false; }
    $raw = is_file($statePath) ? @file_get_contents($statePath) : false;
    $state = $raw === false ? null : json_decode($raw, true);
    // Повреждённую отметку не считаем разрешением повторно отправлять письмо.
    if ($raw !== false && !is_array($state)) { flock($stream, LOCK_UN); fclose($stream); return false; }
    $save = function ($value) use ($statePath) {
        $temporary = $statePath.'.'.uniqid('', true).'.tmp';
        $data = json_encode($value);
        $ok = @file_put_contents($temporary, $data) === strlen($data);
        if ($ok) { @chmod($temporary, 0600); $ok = @rename($temporary, $statePath); }
        if (!$ok) { @unlink($temporary); }
        return $ok;
    };
    $sent = false;
    if (!is_array($state) || (!in_array(isset($state['status']) ? $state['status'] : '', array('sent','sending'), true) &&
            $now - (isset($state['attempt']) ? $state['attempt'] : 0) >= 3600)) {
        // После обрыва между mail() и записью результата sending не повторяем автоматически.
        $state = array('status'=>'sending', 'attempt'=>$now);
        if ($save($state)) {
            $subject = 'Проверьте статус счёта '.$ns;
            $subject = '=?UTF-8?B?'.base64_encode($subject).'?=';
            $headers = "Content-Type: text/html; charset=utf-8\r\nMIME-Version: 1.0\r\nFrom: it.savoir@yandex.ru\r\n";
            $body = 'Счёт: '.htmlspecialchars((string)$ns, ENT_QUOTES, 'UTF-8').'<br>Статус в счёте: '.
                htmlspecialchars((string)$statusSchet, ENT_QUOTES, 'UTF-8').'<br>Последний статус в истории: '.
                htmlspecialchars((string)$statusJoinTable, ENT_QUOTES, 'UTF-8').
                '<br>Проверьте расхождение. Это уведомление отправляется один раз для данного расхождения.';
            $sender = $sender === null ? 'mail' : $sender;
            $sent = (bool)call_user_func($sender, 'infosavoir@yandex.ru', $subject, $body, $headers);
            $state['status'] = $sent ? 'sent' : 'failed';
            $save($state);
        }
    }
    flock($stream, LOCK_UN); fclose($stream);
    return $sent;
}

function sendErrorStatusSchet($ns, $statusSchet, $statusJoinTable, $rand = '')
{
    return voovi_status_notice($ns, $statusSchet, $statusJoinTable, $rand);
}
