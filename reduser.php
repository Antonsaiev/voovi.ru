<?php
include 'conf.php';

function h($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

function post_value($name, $fallback = '') {
    return isset($_POST[$name]) ? trim((string)$_POST[$name]) : (string)$fallback;
}

function lower_value($value) {
    return function_exists('mb_strtolower') ? mb_strtolower((string)$value, 'UTF-8') : strtolower((string)$value);
}

if (!defined('AVATAR_MAX_BYTES')) {
    define('AVATAR_MAX_BYTES', 8 * 1024 * 1024);
}
if (!defined('AVATAR_MAX_LABEL')) {
    define('AVATAR_MAX_LABEL', '8 МБ');
}

if (isset($_COOKIE['id']) and isset($_COOKIE['hash']))
{
    $userdata = mysql_fetch_assoc(mysql_query("SELECT * FROM users WHERE users_id = '".intval($_COOKIE['id'])."' LIMIT 1"));
    if(($userdata['users_hash'] !== $_COOKIE['hash']) or ($userdata['users_id'] !== $_COOKIE['id']))
    {
        setcookie('id', '', time() - 60*24*30*12, '/', VOOVI_COOKIE_DOMAIN);
        setcookie('hash', '', time() - 60*24*30*12, '/', VOOVI_COOKIE_DOMAIN);
        setcookie('errors', '1', time() + 60*24*30*12, '/', VOOVI_COOKIE_DOMAIN);
        header('Location: index.php');
        exit();
    }
}
else
{
    setcookie('errors', '2', time() + 60*24*30*12, '/', VOOVI_COOKIE_DOMAIN);
    header('Location: index.php');
    exit();
}

$profileUserId = intval($userdata['users_id']);
if (isset($_GET['id']) && intval($_GET['id']) > 0 && intval($_GET['id']) !== intval($userdata['users_id'])) {
    if ($userdata['adm'] == 1) {
        $profileUserId = intval($_GET['id']);
    } else {
        header('Location: reduser.php');
        exit();
    }
}

$profileUser = mysql_fetch_assoc(mysql_query("SELECT * FROM users WHERE users_id = '".$profileUserId."' LIMIT 1"));
if (!$profileUser) {
    header('Location: us_manager.php');
    exit();
}

$isAdminEditingUser = intval($profileUser['users_id']) !== intval($userdata['users_id']);
$canManageUslugiAccess = intval($userdata['adm']) === 1 && $isAdminEditingUser;

$uslugiList = array();
$validUslugiIds = array();
$selectedUslugiIds = array();
$uslugiAccessLoaded = !$canManageUslugiAccess;

if ($canManageUslugiAccess) {
    $uslugiQuery = mysql_query("
        SELECT id, name, inn, ogrn
        FROM uslugi
        WHERE del != '1'
        ORDER BY name ASC
    ");
    if ($uslugiQuery) {
        $uslugiAccessLoaded = true;
        while ($service = mysql_fetch_assoc($uslugiQuery)) {
            $serviceId = intval($service['id']);
            if ($serviceId <= 0) {
                continue;
            }
            $service['id'] = $serviceId;
            $validUslugiIds[$serviceId] = true;
            $uslugiList[] = $service;
        }
    }

    $accessQuery = mysql_query("
        SELECT uslugi
        FROM users_access
        WHERE users = '".$profileUserId."'
        GROUP BY uslugi
    ");
    if ($accessQuery) {
        while ($access = mysql_fetch_assoc($accessQuery)) {
            $accessId = intval($access['uslugi']);
            if ($accessId > 0) {
                $selectedUslugiIds[$accessId] = true;
            }
        }
    } else {
        $uslugiAccessLoaded = false;
    }
}

$errors = array();
$messages = array();
$postedUslugiIds = array();
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_profile']) && $canManageUslugiAccess && !$uslugiAccessLoaded) {
    $errors[] = 'Не удалось загрузить список услуг. Доступы не сохранены.';
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_profile']) && $canManageUslugiAccess) {
    $rawUslugiIds = isset($_POST['uslugi_access']) && is_array($_POST['uslugi_access']) ? $_POST['uslugi_access'] : array();
    foreach ($rawUslugiIds as $rawUslugiId) {
        $serviceId = intval($rawUslugiId);
        if ($serviceId > 0 && isset($validUslugiIds[$serviceId])) {
            $postedUslugiIds[$serviceId] = true;
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_profile'])) {
    $fName = post_value('f_name', $profileUser['f_name']);
    $lName = post_value('l_name', $profileUser['l_name']);
    $oName = post_value('o_name', $profileUser['o_name']);
    $mail = post_value('mail', $profileUser['mail']);
    $tel = post_value('tel', $profileUser['tel']);
    $position = post_value('pos_pos', $profileUser['pos_pos']);
    $passwordHash = '';
    $avatarName = '';

    if ($fName === '' && $lName === '') {
        $errors[] = 'Укажите имя или фамилию.';
    }

    if ($mail !== '' && !filter_var($mail, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Проверьте формат e-mail.';
    }

    $oldPassword = isset($_POST['old_pass']) ? trim($_POST['old_pass']) : '';
    $newPassword = isset($_POST['new_pass']) ? trim($_POST['new_pass']) : '';
    $confirmPassword = isset($_POST['confirm_pass']) ? trim($_POST['confirm_pass']) : '';
    if ($oldPassword !== '' || $newPassword !== '' || $confirmPassword !== '') {
        if ($isAdminEditingUser) {
            if ($newPassword === '' || $confirmPassword === '') {
                $errors[] = 'Для смены пароля заполните новый пароль и подтверждение.';
            } elseif ($newPassword !== $confirmPassword) {
                $errors[] = 'Новый пароль и подтверждение не совпадают.';
            } else {
                $passwordHash = md5(md5($newPassword));
            }
        } else {
            if ($oldPassword === '' || $newPassword === '' || $confirmPassword === '') {
                $errors[] = 'Для смены пароля заполните все три поля.';
            } elseif (md5(md5($oldPassword)) !== $profileUser['users_password']) {
                $errors[] = 'Старый пароль указан неверно.';
            } elseif ($newPassword !== $confirmPassword) {
                $errors[] = 'Новый пароль и подтверждение не совпадают.';
            } else {
                $passwordHash = md5(md5($newPassword));
            }
        }
    }

    if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] !== UPLOAD_ERR_NO_FILE) {
        if ($_FILES['avatar']['error'] === UPLOAD_ERR_INI_SIZE || $_FILES['avatar']['error'] === UPLOAD_ERR_FORM_SIZE) {
            $errors[] = 'Фото должно быть не больше '.AVATAR_MAX_LABEL.'.';
        } elseif ($_FILES['avatar']['error'] !== UPLOAD_ERR_OK) {
            $errors[] = 'Не удалось загрузить фото.';
        } elseif ($_FILES['avatar']['size'] > AVATAR_MAX_BYTES) {
            $errors[] = 'Фото должно быть не больше '.AVATAR_MAX_LABEL.'.';
        } else {
            $imageInfo = getimagesize($_FILES['avatar']['tmp_name']);
            $allowedTypes = array(
                'image/jpeg' => 'jpg',
                'image/png' => 'png',
                'image/gif' => 'gif',
            );
            if (!$imageInfo || !isset($allowedTypes[$imageInfo['mime']])) {
                $errors[] = 'Можно загрузить только JPG, PNG или GIF.';
            } else {
                $avatarName = 'user_'.$profileUser['users_id'].'_'.date('YmdHis').'.'.$allowedTypes[$imageInfo['mime']];
            }
        }
    }

    if (count($errors) === 0) {
        if ($avatarName !== '') {
            $avatarPath = __DIR__.'/img/'.$avatarName;
            if (!move_uploaded_file($_FILES['avatar']['tmp_name'], $avatarPath)) {
                $errors[] = 'Не удалось сохранить фото.';
            }
        }
    }

    if (count($errors) === 0) {
        $updates = array(
            "`f_name`='".mysql_real_escape_string($fName)."'",
            "`l_name`='".mysql_real_escape_string($lName)."'",
            "`o_name`='".mysql_real_escape_string($oName)."'",
            "`mail`='".mysql_real_escape_string($mail)."'",
            "`tel`='".mysql_real_escape_string($tel)."'",
            "`pos_pos`='".mysql_real_escape_string($position)."'",
        );
        if ($avatarName !== '') {
            $updates[] = "`img`='".mysql_real_escape_string($avatarName)."'";
        }
        if ($passwordHash !== '') {
            $updates[] = "`users_password`='".mysql_real_escape_string($passwordHash)."'";
        }

        $updateSql = "UPDATE users SET ".implode(', ', $updates)." WHERE users_id = '".$profileUserId."' LIMIT 1";
        if (mysql_query($updateSql)) {
            $messages[] = 'Профиль сохранен.';
            if ($passwordHash !== '') {
                $messages[] = 'Пароль изменен.';
            }
            if ($canManageUslugiAccess) {
                if (mysql_query("DELETE FROM users_access WHERE users = '".$profileUserId."'")) {
                    $accessSaved = true;
                    foreach ($postedUslugiIds as $serviceId => $isSelected) {
                        if (!mysql_query("INSERT INTO users_access (`users`, `uslugi`) VALUES ('".$profileUserId."', '".$serviceId."')")) {
                            $accessSaved = false;
                            break;
                        }
                    }
                    if ($accessSaved) {
                        $selectedUslugiIds = $postedUslugiIds;
                        $messages[] = 'Доступ к услугам обновлен.';
                    } else {
                        $errors[] = 'Не удалось сохранить доступ к услугам: '.mysql_error();
                    }
                } else {
                    $errors[] = 'Не удалось обновить доступ к услугам: '.mysql_error();
                }
            }
            $profileUser = mysql_fetch_assoc(mysql_query("SELECT * FROM users WHERE users_id = '".$profileUserId."' LIMIT 1"));
            if (intval($profileUser['users_id']) === intval($userdata['users_id'])) {
                $userdata = $profileUser;
            }
        } else {
            $errors[] = 'Ошибка сохранения: '.mysql_error();
        }
    }
}

$displayFName = post_value('f_name', $profileUser['f_name']);
$displayLName = post_value('l_name', $profileUser['l_name']);
$displayOName = post_value('o_name', $profileUser['o_name']);
$displayMail = post_value('mail', $profileUser['mail']);
$displayTel = post_value('tel', $profileUser['tel']);
$displayPosition = post_value('pos_pos', $profileUser['pos_pos']);
$displaySelectedUslugiIds = $selectedUslugiIds;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_profile']) && $canManageUslugiAccess && count($errors) > 0) {
    $displaySelectedUslugiIds = $postedUslugiIds;
}
$avatarFile = basename($profileUser['img'] ? $profileUser['img'] : 'icon-user-default.png');
if ($avatarFile === '' || !file_exists(__DIR__.'/img/'.$avatarFile)) {
    $avatarFile = 'icon-user-default.png';
}
$avatarSrc = '/img/'.rawurlencode($avatarFile);
$fullName = trim($profileUser['f_name'].' '.$profileUser['l_name']);
if ($fullName === '') {
    $fullName = $profileUser['users_login'];
}
$pageTitle = $isAdminEditingUser ? 'Редактирование пользователя' : 'Профиль пользователя';
$pageSubtitle = $isAdminEditingUser ? 'Контактные данные и пароль выбранного пользователя.' : 'Контактные данные, фотография и пароль текущего пользователя.';
$cancelUrl = $isAdminEditingUser ? '/profile.php?id='.intval($profileUser['users_id']) : '/check.php';
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Style-Type" content="text/css">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo h($pageTitle); ?></title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #f3f6f8;
            color: #26313d;
        }
        .profile-page {
            max-width: 1100px;
            margin: 76px auto 42px;
            padding: 0 15px;
        }
        .profile-heading {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            gap: 20px;
            margin-bottom: 18px;
        }
        .profile-heading h1 {
            margin: 0 0 6px;
            font-size: 28px;
            line-height: 1.2;
            font-weight: 700;
            color: #1f2d3a;
        }
        .profile-heading p {
            margin: 0;
            color: #6d7b88;
            font-size: 14px;
        }
        .profile-login {
            display: inline-flex;
            align-items: center;
            min-height: 32px;
            padding: 7px 11px;
            border: 1px solid #d9e0e6;
            border-radius: 6px;
            background: #fff;
            color: #4b5b68;
            font-size: 13px;
            white-space: nowrap;
        }
        .profile-layout {
            display: grid;
            grid-template-columns: 300px minmax(0, 1fr);
            gap: 18px;
            align-items: start;
        }
        .profile-panel {
            background: #fff;
            border: 1px solid #dfe6ec;
            border-radius: 8px;
            box-shadow: 0 8px 22px rgba(31, 45, 58, 0.06);
        }
        .profile-photo-panel {
            padding: 22px;
            text-align: center;
        }
        .profile-avatar {
            width: 168px;
            height: 168px;
            display: block;
            margin: 0 auto 16px;
            object-fit: cover;
            border-radius: 50%;
            border: 4px solid #f0f4f7;
            background: #eef3f6;
        }
        .profile-name {
            margin: 0 0 4px;
            font-size: 19px;
            font-weight: 700;
            color: #1f2d3a;
        }
        .profile-role {
            margin: 0 0 18px;
            color: #73818f;
            font-size: 13px;
        }
        .profile-file-input {
            width: 1px;
            height: 1px;
            position: absolute;
            overflow: hidden;
            clip: rect(0, 0, 0, 0);
        }
        .profile-upload-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            width: 100%;
            min-height: 38px;
            margin: 0;
            border: 1px solid #b9c7d3;
            border-radius: 6px;
            background: #f9fbfc;
            color: #2f4354;
            font-weight: 600;
            cursor: pointer;
        }
        .profile-upload-btn:hover {
            background: #eef4f8;
        }
        .profile-file-name {
            margin-top: 10px;
            min-height: 18px;
            color: #7a8793;
            font-size: 12px;
            word-break: break-word;
        }
        .profile-form-panel {
            padding: 24px 26px 26px;
        }
        .profile-section-title {
            margin: 0 0 16px;
            padding-bottom: 10px;
            border-bottom: 1px solid #edf1f4;
            font-size: 17px;
            font-weight: 700;
            color: #223241;
        }
        .profile-section-title:not(:first-child) {
            margin-top: 26px;
        }
        .profile-form-panel label {
            color: #536372;
            font-size: 13px;
            font-weight: 600;
        }
        .profile-form-panel .form-control {
            height: 38px;
            border-color: #d5dde4;
            border-radius: 6px;
            box-shadow: none;
            color: #26313d;
        }
        .profile-form-panel textarea.form-control {
            min-height: 76px;
            resize: vertical;
        }
        .profile-form-panel .form-control:focus {
            border-color: #6aa7d6;
            box-shadow: 0 0 0 3px rgba(106, 167, 214, 0.16);
        }
        .profile-actions {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 24px;
            padding-top: 18px;
            border-top: 1px solid #edf1f4;
        }
        .profile-save-btn {
            min-width: 148px;
            min-height: 40px;
            border: 0;
            border-radius: 6px;
            background: #2f7fb8;
            color: #fff;
            font-weight: 700;
        }
        .profile-save-btn:hover,
        .profile-save-btn:focus {
            background: #276b9c;
            color: #fff;
        }
        .profile-secondary-btn {
            min-height: 40px;
            border-radius: 6px;
            border-color: #cdd7df;
            background: #fff;
            color: #3f5263;
            font-weight: 600;
        }
        .profile-alerts {
            margin-bottom: 16px;
        }
        .profile-alerts .alert {
            margin-bottom: 8px;
            border-radius: 6px;
        }
        .profile-access-tools {
            display: grid;
            grid-template-columns: minmax(0, 1fr) auto auto;
            gap: 8px;
            margin-bottom: 12px;
        }
        .profile-access-tools .btn {
            min-height: 38px;
            border-radius: 6px;
            font-weight: 600;
        }
        .profile-access-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 8px;
            max-height: 340px;
            overflow: auto;
            padding: 2px 2px 4px;
        }
        .profile-access-option {
            display: flex;
            align-items: flex-start;
            gap: 9px;
            min-width: 0;
            min-height: 44px;
            margin: 0;
            padding: 10px 11px;
            border: 1px solid #dfe6ec;
            border-radius: 6px;
            background: #fbfcfd;
            color: #26313d;
            cursor: pointer;
        }
        .profile-access-option:hover {
            border-color: #b9d2e6;
            background: #f3f8fc;
        }
        .profile-access-option input {
            flex: 0 0 auto;
            margin: 3px 0 0;
        }
        .profile-access-option span {
            min-width: 0;
        }
        .profile-access-option strong {
            display: block;
            font-size: 13px;
            line-height: 1.25;
            word-break: break-word;
        }
        .profile-access-option small {
            display: block;
            margin-top: 3px;
            color: #7a8793;
            font-size: 11px;
            line-height: 1.25;
            word-break: break-word;
        }
        .profile-empty-access {
            margin: 0;
            padding: 14px;
            border: 1px dashed #cbd7e0;
            border-radius: 6px;
            color: #6d7b88;
            background: #fbfcfd;
        }
        @media (max-width: 860px) {
            .profile-heading {
                display: block;
            }
            .profile-login {
                margin-top: 12px;
            }
            .profile-layout {
                grid-template-columns: 1fr;
            }
            .profile-form-panel {
                padding: 20px;
            }
            .profile-access-tools {
                grid-template-columns: 1fr;
            }
            .profile-access-grid {
                grid-template-columns: 1fr;
                max-height: none;
            }
        }
    </style>
</head>
<body>
<?php include 'header.php'; ?>

<main class="profile-page">
    <div class="profile-heading">
        <div>
            <h1><?php echo h($pageTitle); ?></h1>
            <p><?php echo h($pageSubtitle); ?></p>
        </div>
        <span class="profile-login"><?php echo h($profileUser['users_login']); ?></span>
    </div>

    <?php if (count($errors) > 0 || count($messages) > 0): ?>
        <div class="profile-alerts">
            <?php foreach ($errors as $error): ?>
                <div class="alert alert-danger"><?php echo h($error); ?></div>
            <?php endforeach; ?>
            <?php foreach ($messages as $message): ?>
                <div class="alert alert-success"><?php echo h($message); ?></div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form method="post" enctype="multipart/form-data">
        <input type="hidden" name="save_profile" value="1">
        <input type="hidden" name="MAX_FILE_SIZE" value="<?php echo AVATAR_MAX_BYTES; ?>">
        <div class="profile-layout">
            <aside class="profile-panel profile-photo-panel">
                <img id="avatar-preview" class="profile-avatar" src="<?php echo h($avatarSrc); ?>" alt="<?php echo h($fullName); ?>">
                <div class="profile-name"><?php echo h($fullName); ?></div>
                <div class="profile-role"><?php echo h($profileUser['pos_pos']); ?></div>
                <label for="avatar" class="profile-upload-btn">
                    <span class="glyphicon glyphicon-camera" aria-hidden="true"></span>
                    Выбрать фото
                </label>
                <input id="avatar" class="profile-file-input" type="file" name="avatar" accept="image/png,image/jpeg,image/gif">
                <div id="avatar-file-name" class="profile-file-name">JPG, PNG или GIF до <?php echo h(AVATAR_MAX_LABEL); ?></div>
            </aside>

            <section class="profile-panel profile-form-panel">
                <h2 class="profile-section-title">Основные данные</h2>
                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="f_name">Имя</label>
                            <input id="f_name" class="form-control" type="text" name="f_name" value="<?php echo h($displayFName); ?>" autocomplete="off">
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="l_name">Фамилия</label>
                            <input id="l_name" class="form-control" type="text" name="l_name" value="<?php echo h($displayLName); ?>" autocomplete="off">
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="o_name">Отчество</label>
                            <input id="o_name" class="form-control" type="text" name="o_name" value="<?php echo h($displayOName); ?>" autocomplete="off">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="mail">E-mail</label>
                            <input id="mail" class="form-control" type="email" name="mail" value="<?php echo h($displayMail); ?>" autocomplete="off">
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="tel">Телефон</label>
                            <input id="tel" class="form-control" type="text" name="tel" value="<?php echo h($displayTel); ?>" autocomplete="off">
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="pos_pos">Должность</label>
                            <input id="pos_pos" class="form-control" type="text" name="pos_pos" value="<?php echo h($displayPosition); ?>" autocomplete="off">
                        </div>
                    </div>
                </div>

                <h2 class="profile-section-title">Смена пароля</h2>
                <div class="row">
                    <?php if (!$isAdminEditingUser): ?>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="old_pass">Старый пароль</label>
                                <input id="old_pass" class="form-control" type="password" name="old_pass" autocomplete="off">
                            </div>
                        </div>
                    <?php endif; ?>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="new_pass">Новый пароль</label>
                            <input id="new_pass" class="form-control" type="password" name="new_pass" autocomplete="off">
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="confirm_pass">Повторите пароль</label>
                            <input id="confirm_pass" class="form-control" type="password" name="confirm_pass" autocomplete="off">
                        </div>
                    </div>
                </div>

                <?php if ($canManageUslugiAccess): ?>
                    <h2 class="profile-section-title">Доступ к услугам</h2>
                    <?php if (count($uslugiList) > 0): ?>
                        <div class="profile-access-tools">
                            <input id="uslugi-access-search" class="form-control" type="search" placeholder="Найти услугу" autocomplete="off">
                            <button class="btn profile-secondary-btn" type="button" id="uslugi-access-all">Выбрать все</button>
                            <button class="btn profile-secondary-btn" type="button" id="uslugi-access-none">Снять все</button>
                        </div>
                        <div class="profile-access-grid" id="uslugi-access-list">
                            <?php foreach ($uslugiList as $service): ?>
                                <?php
                                $serviceMeta = array();
                                if (trim($service['inn']) !== '') {
                                    $serviceMeta[] = 'ИНН '.trim($service['inn']);
                                }
                                if (trim($service['ogrn']) !== '') {
                                    $serviceMeta[] = 'ОГРН '.trim($service['ogrn']);
                                }
                                ?>
                                <label class="profile-access-option" data-search="<?php echo h(lower_value($service['name'].' '.implode(' ', $serviceMeta))); ?>">
                                    <input type="checkbox" name="uslugi_access[]" value="<?php echo intval($service['id']); ?>"<?php echo isset($displaySelectedUslugiIds[intval($service['id'])]) ? ' checked' : ''; ?>>
                                    <span>
                                        <strong><?php echo h($service['name']); ?></strong>
                                        <?php if (count($serviceMeta) > 0): ?>
                                            <small><?php echo h(implode(' · ', $serviceMeta)); ?></small>
                                        <?php endif; ?>
                                    </span>
                                </label>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <p class="profile-empty-access">Нет доступных услуг для настройки.</p>
                    <?php endif; ?>
                <?php endif; ?>

                <div class="profile-actions">
                    <a class="btn profile-secondary-btn" href="<?php echo h($cancelUrl); ?>">Отмена</a>
                    <button class="btn profile-save-btn" type="submit">Сохранить</button>
                </div>
            </section>
        </div>
    </form>
</main>

<script>
(function () {
    var input = document.getElementById('avatar');
    var preview = document.getElementById('avatar-preview');
    var fileName = document.getElementById('avatar-file-name');
    var form = input ? input.form : null;
    var maxAvatarSize = <?php echo AVATAR_MAX_BYTES; ?>;
    var maxAvatarLabel = <?php echo json_encode(AVATAR_MAX_LABEL); ?>;
    var defaultFileText = 'JPG, PNG или GIF до ' + maxAvatarLabel;

    if (!input || !preview || !fileName) {
        return;
    }

    function setFileError(message) {
        fileName.textContent = message;
        fileName.style.color = '#c9413b';
    }

    function setFileText(message) {
        fileName.textContent = message;
        fileName.style.color = '';
    }

    function validateAvatarFile() {
        var file = input.files && input.files[0];
        if (!file) {
            setFileText(defaultFileText);
            return true;
        }
        if (file.size > maxAvatarSize) {
            setFileError('Фото должно быть не больше ' + maxAvatarLabel + '.');
            return false;
        }
        return true;
    }

    input.addEventListener('change', function () {
        var file = input.files && input.files[0];
        if (!file) {
            setFileText(defaultFileText);
            return;
        }

        if (!validateAvatarFile()) {
            return;
        }

        setFileText(file.name);
        if (window.FileReader && file.type.indexOf('image/') === 0) {
            var reader = new FileReader();
            reader.onload = function (event) {
                preview.src = event.target.result;
            };
            reader.readAsDataURL(file);
        }
    });

    if (form) {
        form.addEventListener('submit', function (event) {
            if (!validateAvatarFile()) {
                event.preventDefault();
            }
        });
    }
}());

(function () {
    var search = document.getElementById('uslugi-access-search');
    var list = document.getElementById('uslugi-access-list');
    var selectAll = document.getElementById('uslugi-access-all');
    var selectNone = document.getElementById('uslugi-access-none');

    if (!list) {
        return;
    }

    function getOptions() {
        return list.querySelectorAll('.profile-access-option');
    }

    function getCheckboxes() {
        return list.querySelectorAll('input[type="checkbox"]');
    }

    if (search) {
        search.addEventListener('input', function () {
            var query = search.value.toLocaleLowerCase();
            var options = getOptions();
            for (var i = 0; i < options.length; i++) {
                var option = options[i];
                var haystack = (option.getAttribute('data-search') || option.textContent || '').toLocaleLowerCase();
                option.style.display = haystack.indexOf(query) === -1 ? 'none' : '';
            }
        });
    }

    if (selectAll) {
        selectAll.addEventListener('click', function () {
            var checkboxes = getCheckboxes();
            for (var i = 0; i < checkboxes.length; i++) {
                checkboxes[i].checked = true;
            }
        });
    }

    if (selectNone) {
        selectNone.addEventListener('click', function () {
            var checkboxes = getCheckboxes();
            for (var i = 0; i < checkboxes.length; i++) {
                checkboxes[i].checked = false;
            }
        });
    }
}());
</script>
</body>
</html>
