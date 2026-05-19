<?php
# Подключаем конфиг
include 'conf.php';

function h($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

# Функция для генерации случайной строки
function generateCode($length=6) {
    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPRQSTUVWXYZ0123456789";
    $code = "";
    $clen = strlen($chars) - 1;
    while (strlen($code) < $length) {
        $code .= $chars[mt_rand(0,$clen)];
    }
    return $code;
}

$loginValue = isset($_POST['login']) ? trim((string)$_POST['login']) : '';
$loginMessage = '';
$loginMessageType = 'info';
$hasLoginError = false;

# Если есть куки с ошибкой то выводим их в переменную и удаляем куки
if (isset($_COOKIE['errors'])) {
    $errors = $_COOKIE['errors'];
    if (isset($error[$errors])) {
        $loginMessage = $error[$errors];
        $loginMessageType = $errors == '2' ? 'info' : 'error';
    }
    setcookie('errors', '', time() - 60*24*30*12, "/", VOOVI_COOKIE_DOMAIN);
    setcookie('errors', '', time() - 60*24*30*12, "/");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $passwordValue = isset($_POST['password']) ? (string)$_POST['password'] : '';

    if ($loginValue === '' || trim($passwordValue) === '') {
        $loginMessage = 'Введите логин и пароль.';
        $loginMessageType = 'error';
        $hasLoginError = true;
    } else {
        # Вытаскиваем из БД запись, у которой логин равняеться введенному
        $userQuery = mysql_query("SELECT users_id, users_password FROM `users` WHERE `del_users` = '0' AND `users_login`='".mysql_real_escape_string($loginValue)."' LIMIT 1");
        $data = $userQuery ? mysql_fetch_assoc($userQuery) : false;

        # Соавниваем пароли
        if($data && $data['users_password'] === md5(md5($passwordValue)))
        {
            # Генерируем случайное число и шифруем его
            $hash = md5(generateCode(10));

            # Записываем в БД новый хеш авторизации и IP
            mysql_query("UPDATE users SET users_hash='".$hash."' WHERE users_id='".$data['users_id']."'") or die("MySQL Error: " . mysql_error());

            # Ставим куки
            setcookie("id", $data['users_id'], time()+60*60*24*30, "/", VOOVI_COOKIE_DOMAIN);
            setcookie("hash", $hash, time()+60*60*24*30, "/", VOOVI_COOKIE_DOMAIN);

            # Переадресовываем браузер на страницу проверки нашего скрипта
            header("Location: check.php"); exit();
        }
        else
        {
            $loginMessage = 'Проверьте логин и пароль и попробуйте еще раз.';
            $loginMessageType = 'error';
            $hasLoginError = true;
        }
    }
}

# проверка авторизации
if (isset($_COOKIE['id']) and isset($_COOKIE['hash']))
{
    $userdata = mysql_fetch_assoc(mysql_query("SELECT * FROM users WHERE users_id = '".intval($_COOKIE['id'])."' LIMIT 1"));

    if(!$userdata || ($userdata['users_hash'] !== $_COOKIE['hash']) || ($userdata['users_id'] !== $_COOKIE['id']))
    {
        setcookie('id', '', time() - 60*24*30*12, '/', VOOVI_COOKIE_DOMAIN);
        setcookie('hash', '', time() - 60*24*30*12, '/', VOOVI_COOKIE_DOMAIN);
        setcookie('id', '', time() - 60*24*30*12, '/');
        setcookie('hash', '', time() - 60*24*30*12, '/');
        if ($loginMessage === '') {
            $loginMessage = $error[1];
            $loginMessageType = 'error';
        }
        $hasLoginError = true;
    }
    else
    {
        header('Location: check.php'); exit();
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <title>VooVi System</title>
    <meta charset="utf-8">
    <meta http-equiv="Content-Style-Type" content="text/css">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link rel="shortcut icon" href="/favicon.ico">
    <style>
        html,
        body {
            max-width: 100%;
            min-height: 100%;
            overflow-x: hidden;
        }
        body {
            margin: 0;
            background:
                linear-gradient(120deg, rgba(24, 38, 52, 0.62), rgba(47, 127, 184, 0.26)),
                url('/img/lux.jpg') center center / cover no-repeat fixed;
            color: #26313d;
            font-family: "Helvetica Neue", Arial, sans-serif;
        }
        .auth-page {
            box-sizing: border-box;
            width: 100%;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 36px 20px;
        }
        .auth-page *,
        .auth-page *:before,
        .auth-page *:after {
            box-sizing: border-box;
        }
        .auth-shell {
            width: 100%;
            max-width: 440px;
            margin: 0 auto;
        }
        .auth-panel {
            border: 1px solid #dfe6ec;
            border-radius: 8px;
            background: rgba(255, 255, 255, 0.94);
            box-shadow: 0 16px 36px rgba(18, 32, 45, 0.22);
        }
        .auth-form-panel {
            padding: 30px;
        }
        .auth-form-heading {
            margin-bottom: 22px;
            padding-bottom: 14px;
            border-bottom: 1px solid #edf1f4;
        }
        .auth-form-heading h1 {
            margin: 0 0 6px;
            color: #1f2d3a;
            font-size: 24px;
            line-height: 1.2;
            font-weight: 700;
        }
        .auth-form-heading p {
            margin: 0;
            color: #6d7b88;
            font-size: 14px;
            line-height: 1.45;
        }
        .auth-alert {
            display: flex;
            gap: 10px;
            align-items: flex-start;
            margin: 0 0 16px;
            padding: 12px 13px;
            border: 1px solid #f0caca;
            border-radius: 8px;
            background: #fff5f5;
            color: #9f3131;
            font-size: 13px;
            line-height: 1.45;
        }
        .auth-alert.is-info {
            border-color: #d9e7f1;
            background: #f3f7fa;
            color: #2f6f9f;
        }
        .auth-alert .glyphicon {
            width: 18px;
            margin-top: 1px;
            text-align: center;
        }
        .auth-field {
            margin-bottom: 16px;
        }
        .auth-field label {
            display: block;
            margin-bottom: 7px;
            color: #536372;
            font-size: 13px;
            font-weight: 600;
        }
        .auth-input-wrap {
            position: relative;
        }
        .auth-input-wrap .glyphicon {
            position: absolute;
            top: 50%;
            left: 14px;
            z-index: 2;
            color: #8797a4;
            transform: translateY(-50%);
            pointer-events: none;
        }
        .auth-control {
            height: 44px;
            padding-left: 42px;
            border-color: #d5dde4;
            border-radius: 6px;
            box-shadow: none;
            color: #26313d;
            font-size: 15px;
        }
        .auth-control:focus {
            border-color: #6aa7d6;
            box-shadow: 0 0 0 3px rgba(106, 167, 214, 0.16);
        }
        .auth-control.is-invalid {
            border-color: #df8b8b;
            background: #fffafa;
        }
        .auth-control.is-invalid:focus {
            border-color: #cf6262;
            box-shadow: 0 0 0 3px rgba(207, 98, 98, 0.14);
        }
        .auth-submit {
            position: relative;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            width: 100%;
            min-height: 44px;
            margin-top: 4px;
            border: 0;
            border-radius: 6px;
            background: #2f7fb8;
            color: #fff;
            font-weight: 700;
        }
        .auth-submit:disabled,
        .auth-submit.is-loading {
            cursor: wait;
            opacity: 0.82;
        }
        .auth-submit:hover,
        .auth-submit:focus {
            background: #276b9c;
            color: #fff;
        }
        .auth-submit-spinner {
            display: none;
            width: 18px;
            height: 18px;
            border: 2px solid rgba(255, 255, 255, 0.45);
            border-top-color: #fff;
            border-radius: 50%;
            animation: auth-submit-spin 0.75s linear infinite;
        }
        .auth-submit.is-loading .auth-submit-spinner {
            display: inline-block;
        }
        .auth-submit.is-loading .auth-submit-text {
            display: none;
        }
        @keyframes auth-submit-spin {
            to {
                transform: rotate(360deg);
            }
        }
        .auth-footnote {
            margin: 16px 0 0;
            padding-top: 15px;
            border-top: 1px solid #edf1f4;
            color: #7a8793;
            font-size: 12px;
            line-height: 1.45;
            text-align: center;
        }
        @media (max-width: 760px) {
            .auth-page {
                padding: 18px 12px;
            }
            .auth-form-panel {
                padding: 24px 22px;
            }
        }
        @media (max-width: 500px) {
            .auth-form-panel {
                padding: 22px 18px;
            }
        }
    </style>
</head>
<body>
    <main class="auth-page">
        <div class="auth-shell">
            <section class="auth-panel auth-form-panel">
                <div class="auth-form-heading">
                    <h1>Вход в систему</h1>
                    <p>Введите логин и пароль от учетной записи.</p>
                </div>

                <?php if ($loginMessage !== ''): ?>
                    <div class="auth-alert <?php echo $loginMessageType === 'info' ? 'is-info' : ''; ?>" role="alert" id="loginMessage">
                        <span class="glyphicon <?php echo $loginMessageType === 'info' ? 'glyphicon-info-sign' : 'glyphicon-exclamation-sign'; ?>" aria-hidden="true"></span>
                        <span><?php echo h($loginMessage); ?></span>
                    </div>
                <?php endif; ?>

                <form method="post" action="" class="auth-form" id="authForm" novalidate>
                    <div class="auth-field">
                        <label for="login">Логин</label>
                        <div class="auth-input-wrap">
                            <span class="glyphicon glyphicon-user" aria-hidden="true"></span>
                            <input
                                id="login"
                                name="login"
                                class="form-control auth-control<?php echo $hasLoginError ? ' is-invalid' : ''; ?>"
                                type="text"
                                value="<?php echo h($loginValue); ?>"
                                autocomplete="username"
                                required
                                autofocus
                                aria-invalid="<?php echo $hasLoginError ? 'true' : 'false'; ?>"
                                <?php echo $loginMessage !== '' ? 'aria-describedby="loginMessage"' : ''; ?>
                            >
                        </div>
                    </div>

                    <div class="auth-field">
                        <label for="password">Пароль</label>
                        <div class="auth-input-wrap">
                            <span class="glyphicon glyphicon-lock" aria-hidden="true"></span>
                            <input
                                id="password"
                                name="password"
                                class="form-control auth-control<?php echo $hasLoginError ? ' is-invalid' : ''; ?>"
                                type="password"
                                autocomplete="current-password"
                                required
                                aria-invalid="<?php echo $hasLoginError ? 'true' : 'false'; ?>"
                                <?php echo $loginMessage !== '' ? 'aria-describedby="loginMessage"' : ''; ?>
                            >
                        </div>
                    </div>

                    <button class="btn auth-submit" id="authSubmit" name="submit" type="submit" value="1">
                        <span class="auth-submit-spinner" aria-hidden="true"></span>
                        <span class="auth-submit-text">Войти</span>
                    </button>
                </form>

                <p class="auth-footnote">Если доступ не подходит, обратитесь к администратору системы.</p>
            </section>
        </div>
    </main>
    <script>
        (function () {
            var form = document.getElementById('authForm');
            var button = document.getElementById('authSubmit');

            if (!form || !button) {
                return;
            }

            form.addEventListener('submit', function () {
                button.className += ' is-loading';
                button.disabled = true;
                button.setAttribute('aria-busy', 'true');
            });
        }());
    </script>
</body>
</html>
