<?php
if (!in_array(strtolower((string)getenv('DEBUG')),array('1','true'),true)) { throw new RuntimeException('DEBUG fixture required'); }
if (isset($argv[1]) && $argv[1] === 'legacy') {
    require dirname(__DIR__).'/mail/inc/class.smtp.php';
    class DebugMailProbe extends phpmailer { function create_header() { throw new RuntimeException('Must stop before transport'); } }
} else {
    require dirname(__DIR__).'/class/class.phpmailer.php';
    class DebugMailProbe extends PHPMailer { function CreateHeader() { throw new RuntimeException('Must stop before transport'); } }
}
$mail=new DebugMailProbe(); $mail->AddAddress('fixture@example.invalid'); $mail->Body='test';
if ($mail->Send() !== false) { throw new RuntimeException('Real mail must be disabled'); }
echo "PHP DEBUG mail blocked before SMTP — PASS\n";
