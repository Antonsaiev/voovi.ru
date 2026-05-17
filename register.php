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

    if(!$userdata || ($userdata['users_hash'] !== $_COOKIE['hash']) || ($userdata['users_id'] !== $_COOKIE['id']) || ($userdata['adm'] != 1))
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

$errors = array();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit']))
{
    $familia = post_value('familia');
    $name = post_value('name');
    $otchestvo = post_value('otchestvo');
    $tel = post_value('tel');
    $mail = post_value('mail');
    $otv = post_value('otv');
    $login = post_value('login');
    $rawPassword = isset($_POST['password']) ? trim((string)$_POST['password']) : '';

    if(!preg_match("/^[a-zA-Z0-9]+$/", $login))
    {
        $errors[] = "Логин может состоять только из букв английского алфавита и цифр";
    }

    if(strlen($login) < 3 or strlen($login) > 30)
    {
        $errors[] = "Логин должен быть не меньше 3-х символов и не больше 30";
    }

    if ($rawPassword === '')
    {
        $errors[] = "Укажите пароль";
    }

    if ($mail !== '' && !filter_var($mail, FILTER_VALIDATE_EMAIL))
    {
        $errors[] = "Проверьте формат e-mail";
    }

    $query = mysql_query("SELECT COUNT(users_id) FROM users WHERE users_login='".mysql_real_escape_string($login)."'") or die ("<br>Invalid query: " . mysql_error());
    if(mysql_result($query, 0) > 0)
    {
        $errors[] = "Пользователь с таким логином уже существует в базе данных";
    }

    if(count($errors) == 0)
    {
        $password = md5(md5($rawPassword));
        $insertSql = "INSERT INTO users SET "
            ."f_name='".mysql_real_escape_string($familia)."', "
            ."l_name='".mysql_real_escape_string($name)."', "
            ."o_name='".mysql_real_escape_string($otchestvo)."', "
            ."tel='".mysql_real_escape_string($tel)."', "
            ."mail='".mysql_real_escape_string($mail)."', "
            ."otvetstven='".mysql_real_escape_string($otv)."', "
            ."users_login='".mysql_real_escape_string($login)."', "
            ."users_password='".mysql_real_escape_string($password)."'";

        mysql_query($insertSql) or die ("<br>Invalid query: " . mysql_error());
        header("Location: index.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Style-Type" content="text/css">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Регистрация пользователя</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #f3f6f8;
            color: #26313d;
        }
        .register-page {
            max-width: 1100px;
            margin: 76px auto 42px;
            padding: 0 15px;
        }
        .register-heading {
            margin-bottom: 18px;
        }
        .register-heading h1 {
            margin: 0 0 6px;
            font-size: 28px;
            line-height: 1.2;
            font-weight: 700;
            color: #1f2d3a;
        }
        .register-heading p {
            margin: 0;
            color: #6d7b88;
            font-size: 14px;
        }
        .register-layout {
            display: grid;
            grid-template-columns: 300px minmax(0, 1fr);
            gap: 18px;
            align-items: start;
        }
        .register-panel {
            background: #fff;
            border: 1px solid #dfe6ec;
            border-radius: 8px;
            box-shadow: 0 8px 22px rgba(31, 45, 58, 0.06);
        }
        .register-info-panel {
            padding: 24px 22px;
        }
        .register-avatar {
            width: 116px;
            height: 116px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 18px;
            border: 4px solid #f0f4f7;
            border-radius: 50%;
            background: #eef3f6;
            color: #6c8aa2;
            font-size: 42px;
        }
        .register-info-title {
            margin: 0 0 6px;
            text-align: center;
            font-size: 19px;
            font-weight: 700;
            color: #1f2d3a;
        }
        .register-info-text {
            margin: 0 0 18px;
            text-align: center;
            color: #73818f;
            font-size: 13px;
            line-height: 1.45;
        }
        .register-info-list {
            margin: 18px 0 0;
            padding: 16px 0 0;
            border-top: 1px solid #edf1f4;
            list-style: none;
        }
        .register-info-list li {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 12px;
            color: #526373;
            font-size: 13px;
        }
        .register-info-list .glyphicon {
            width: 18px;
            color: #2f7fb8;
        }
        .register-form-panel {
            padding: 24px 26px 26px;
        }
        .register-section-title {
            margin: 0 0 16px;
            padding-bottom: 10px;
            border-bottom: 1px solid #edf1f4;
            font-size: 17px;
            font-weight: 700;
            color: #223241;
        }
        .register-section-title:not(:first-child) {
            margin-top: 26px;
        }
        .register-form-panel label {
            color: #536372;
            font-size: 13px;
            font-weight: 600;
        }
        .register-form-panel .form-control {
            height: 38px;
            border-color: #d5dde4;
            border-radius: 6px;
            box-shadow: none;
            color: #26313d;
        }
        .register-form-panel .form-control:focus {
            border-color: #6aa7d6;
            box-shadow: 0 0 0 3px rgba(106, 167, 214, 0.16);
        }
        .register-field-note {
            margin-top: 6px;
            color: #7a8793;
            font-size: 12px;
            line-height: 1.35;
        }
        .register-actions {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 24px;
            padding-top: 18px;
            border-top: 1px solid #edf1f4;
        }
        .register-save-btn {
            min-width: 170px;
            min-height: 40px;
            border: 0;
            border-radius: 6px;
            background: #2f7fb8;
            color: #fff;
            font-weight: 700;
        }
        .register-save-btn:hover,
        .register-save-btn:focus {
            background: #276b9c;
            color: #fff;
        }
        .register-secondary-btn {
            min-height: 40px;
            border-radius: 6px;
            border-color: #cdd7df;
            background: #fff;
            color: #3f5263;
            font-weight: 600;
        }
        .register-alerts {
            margin-bottom: 16px;
        }
        .register-alerts .alert {
            margin-bottom: 8px;
            border-radius: 6px;
        }
        @media (max-width: 860px) {
            .register-layout {
                grid-template-columns: 1fr;
            }
            .register-form-panel {
                padding: 20px;
            }
        }
        @media (max-width: 520px) {
            .register-actions {
                display: block;
            }
            .register-actions .btn {
                width: 100%;
                margin-bottom: 8px;
            }
        }
    </style>
</head>
<body>
<?php include 'header.php'; ?>

<main class="register-page">
    <div class="register-heading">
        <div>
            <h1>Регистрация пользователя</h1>
            <p>Создание учетной записи для сотрудника.</p>
        </div>
    </div>

    <?php if (count($errors) > 0): ?>
        <div class="register-alerts">
            <?php foreach ($errors as $error): ?>
                <div class="alert alert-danger"><?php echo h($error); ?></div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form method="post" action="">
        <input type="hidden" name="submit" value="1">
        <div class="register-layout">
            <aside class="register-panel register-info-panel">
                <div class="register-avatar">
                    <span class="glyphicon glyphicon-user" aria-hidden="true"></span>
                </div>
                <div class="register-info-title">Новый пользователь</div>
                <p class="register-info-text">После сохранения сотрудник появится в общей базе пользователей.</p>
                <ul class="register-info-list">
                    <li><span class="glyphicon glyphicon-lock" aria-hidden="true"></span>Логин только латиницей и цифрами</li>
                    <li><span class="glyphicon glyphicon-ok" aria-hidden="true"></span>Доступ создает только администратор</li>
                    <li><span class="glyphicon glyphicon-envelope" aria-hidden="true"></span>E-mail можно указать сразу</li>
                </ul>
            </aside>

            <section class="register-panel register-form-panel">
                <h2 class="register-section-title">Данные сотрудника</h2>
                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="familia">Фамилия</label>
                            <input id="familia" class="form-control" type="text" name="familia" value="<?php echo h(post_value('familia')); ?>" autocomplete="off">
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="name">Имя</label>
                            <input id="name" class="form-control" type="text" name="name" value="<?php echo h(post_value('name')); ?>" autocomplete="off">
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="otchestvo">Отчество</label>
                            <input id="otchestvo" class="form-control" type="text" name="otchestvo" value="<?php echo h(post_value('otchestvo')); ?>" autocomplete="off">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="tel">Телефон</label>
                            <input id="tel" class="form-control" type="tel" name="tel" value="<?php echo h(post_value('tel')); ?>" autocomplete="off">
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="mail">E-mail</label>
                            <input id="mail" class="form-control" type="email" name="mail" value="<?php echo h(post_value('mail')); ?>" autocomplete="off">
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="otv">Ответственный</label>
                            <select id="otv" class="form-control" name="otv">
                                <option value="0"<?php echo post_value('otv') === '1' ? '' : ' selected'; ?>>Нет</option>
                                <option value="1"<?php echo post_value('otv') === '1' ? ' selected' : ''; ?>>Да</option>
                            </select>
                            <div class="register-field-note">Да - доступны расширенные действия со счетами.</div>
                        </div>
                    </div>
                </div>

                <h2 class="register-section-title">Доступ</h2>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="login">Логин</label>
                            <input id="login" class="form-control" type="text" name="login" value="<?php echo h(post_value('login')); ?>" autocomplete="off">
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="password">Пароль</label>
                            <input id="password" class="form-control" type="password" name="password" autocomplete="new-password">
                        </div>
                    </div>
                </div>

                <div class="register-actions">
                    <a class="btn register-secondary-btn" href="/check.php">Отмена</a>
                    <button class="btn register-save-btn" type="submit">Зарегистрировать</button>
                </div>
            </section>
        </div>
    </form>
</main>
</body>
</html>
