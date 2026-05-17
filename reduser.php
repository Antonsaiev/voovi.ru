<?php
include 'conf.php';

function h($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

function post_value($name, $fallback = '') {
    return isset($_POST[$name]) ? trim((string)$_POST[$name]) : (string)$fallback;
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

$errors = array();
$messages = array();

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
        if ($_FILES['avatar']['error'] !== UPLOAD_ERR_OK) {
            $errors[] = 'Не удалось загрузить фото.';
        } elseif ($_FILES['avatar']['size'] > 2 * 1024 * 1024) {
            $errors[] = 'Фото должно быть не больше 2 МБ.';
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
                <div id="avatar-file-name" class="profile-file-name">JPG, PNG или GIF до 2 МБ</div>
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

    if (!input || !preview || !fileName) {
        return;
    }

    input.addEventListener('change', function () {
        var file = input.files && input.files[0];
        if (!file) {
            fileName.textContent = 'JPG, PNG или GIF до 2 МБ';
            return;
        }

        fileName.textContent = file.name;
        if (window.FileReader && file.type.indexOf('image/') === 0) {
            var reader = new FileReader();
            reader.onload = function (event) {
                preview.src = event.target.result;
            };
            reader.readAsDataURL(file);
        }
    });
}());
</script>
</body>
</html>
