<?php
# подключаем конфиг
include 'conf.php';

# проверка авторизации
if (isset($_COOKIE['id']) and isset($_COOKIE['hash']))
{
    $userdata = mysql_fetch_assoc(mysql_query("SELECT * FROM users WHERE users_id = '".intval($_COOKIE['id'])."' LIMIT 1"));

    if(($userdata['users_hash'] !== $_COOKIE['hash']) or ($userdata['users_id'] !== $_COOKIE['id']))
    {
        setcookie('id', '', time() - 60*24*30*12, '/');
        setcookie('hash', '', time() - 60*24*30*12, '/');
        setcookie('errors', '1', time() + 60*24*30*12, '/');
        header('Location: index.php'); exit();
    }
}
else
{
    setcookie('errors', '2', time() + 60*24*30*12, '/');
    header('Location: index.php'); exit();
}

function newusluga_h($value)
{
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

function newusluga_product_icon_url($value)
{
    $value = trim((string)$value);
    if ($value === '' || $value === '0' || $value === '1') {
        return '/img/product_icons_20x20.png';
    }
    if (preg_match('/^url\((["\']?)(.*?)\1\)$/', $value, $matches)) {
        $value = trim($matches[2]);
    }
    if ($value === '') {
        return '/img/product_icons_20x20.png';
    }
    if ($value[0] !== '/') {
        $value = '/'.ltrim($value, '/');
    }
    if (strpos($value, '/img/') !== 0) {
        return '/img/product_icons_20x20.png';
    }

    return $value;
}

function newusluga_product_icon_is_sprite($url)
{
    return $url === '/img/product_icons_20x20.png';
}

$isEmbedded = isset($_GET['rand']) || (isset($_GET['head']) && $_GET['head'] == 1);
$clientId = isset($_GET['id']) ? intval($_GET['id']) : 0;
$parentId = isset($_GET['parent']) ? intval($_GET['parent']) : 0;
$headParam = isset($_GET['head']) ? $_GET['head'] : '';
$client = array();
$service = array();
$currentInvoice = array();
$currentTarif = array();

if ($isEmbedded) {
    $clientResult = mysql_query("SELECT * FROM `ogrn` WHERE id =".$clientId);
    $client = mysql_fetch_array($clientResult);
} else {
    $inn = isset($_GET['inn']) ? mysql_real_escape_string($_GET['inn']) : '';
    $kpp = isset($_GET['kpp']) ? mysql_real_escape_string($_GET['kpp']) : '';
    $clientResult = mysql_query("SELECT * FROM `ogrn` WHERE inn ='".$inn."' AND kpp ='".$kpp."' ");
    $client = mysql_fetch_array($clientResult);
}

$serviceResult = mysql_query("SELECT * FROM `uslugi` WHERE id = ".$parentId);
$service = mysql_fetch_array($serviceResult);

if (isset($_GET['rand'])) {
    $rand = mysql_real_escape_string($_GET['rand']);
    $invoiceResult = mysql_query("SELECT * FROM `schet` WHERE rand = '".$rand."' ORDER BY id");
    $currentInvoice = mysql_fetch_array($invoiceResult);
    if (!empty($currentInvoice['prod'])) {
        $tarifResult = mysql_query("SELECT * FROM `tarif` WHERE id = ".intval($currentInvoice['prod']));
        $currentTarif = mysql_fetch_array($tarifResult);
    }
}

$idkli = isset($_GET['kli']) && $_GET['kli'] !== '' ? $_GET['kli'] : $clientId;

$products = array();
$productQuery = mysql_query("SELECT * from produkti WHERE parent = '".$parentId."' AND del = '0' ORDER BY name");
while($row = mysql_fetch_array($productQuery)) {
    $products[] = $row;
}
$posts = count($products);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ru" lang="ru">
<head>
    <title></title>
    <meta http-equiv="content-type" content="text/html" charset="utf-8" />
    <meta http-equiv="Content-Style-Type" content="text/css" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link href="css/bootstrap.min.css" rel="stylesheet" />
    <style type="text/css">
        html,
        body {
            max-width: 100%;
            overflow-x: hidden;
        }
        body.newusluga-modern {
            margin: 0;
            background: #f3f6f8;
            color: #26313d;
            font-family: "Helvetica Neue", Arial, sans-serif;
        }
        .newusluga-modern *,
        .newusluga-modern *:before,
        .newusluga-modern *:after {
            box-sizing: border-box;
        }
        .newusluga-page {
            width: 100%;
            max-width: 1180px;
            margin: 74px auto 34px;
            padding: 0 16px;
        }
        .newusluga-page.is-embedded {
            margin-top: 18px;
        }
        .newusluga-titlebar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            min-height: 74px;
            margin-bottom: 14px;
            padding: 18px 20px;
            border: 1px solid #dfe6ec;
            border-radius: 8px;
            background: #fff;
            box-shadow: 0 8px 22px rgba(31, 45, 58, 0.06);
        }
        .newusluga-title-main {
            min-width: 0;
        }
        .newusluga-eyebrow {
            margin-bottom: 4px;
            color: #526170;
            font-size: 12px;
            font-weight: 700;
            line-height: 1.2;
            text-transform: uppercase;
        }
        .newusluga-titlebar h1 {
            margin: 0;
            color: #26313d;
            font-size: 24px;
            font-weight: 700;
            line-height: 1.25;
        }
        .newusluga-client {
            margin-top: 5px;
            color: #526170;
            font-size: 14px;
            line-height: 1.35;
        }
        .newusluga-client a,
        .newusluga-service-card a,
        .newusluga-product-name {
            color: #2f7fb8;
            text-decoration: none;
        }
        .newusluga-client a:hover,
        .newusluga-service-card a:hover,
        .newusluga-product:hover .newusluga-product-name {
            color: #276b9c;
            text-decoration: none;
        }
        .newusluga-back {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 36px;
            padding: 8px 13px;
            border: 1px solid #cfd8e3;
            border-radius: 6px;
            background: #fff;
            color: #26313d;
            font-size: 13px;
            font-weight: 700;
            line-height: 1.2;
            text-decoration: none;
            white-space: nowrap;
        }
        .newusluga-back:hover,
        .newusluga-back:focus {
            border-color: #2f7fb8;
            color: #2f7fb8;
            text-decoration: none;
            outline: 0;
        }
        .newusluga-summary {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 10px;
            margin-bottom: 14px;
        }
        .newusluga-service-card {
            min-width: 0;
            padding: 14px 16px;
            border: 1px solid #dfe6ec;
            border-radius: 8px;
            background: #fff;
            box-shadow: 0 8px 22px rgba(31, 45, 58, 0.05);
        }
        .newusluga-service-label {
            margin-bottom: 4px;
            color: #6b7886;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
        }
        .newusluga-service-value {
            color: #26313d;
            font-size: 15px;
            font-weight: 700;
            line-height: 1.3;
            overflow-wrap: anywhere;
        }
        .newusluga-products {
            padding: 16px;
            border: 1px solid #dfe6ec;
            border-radius: 8px;
            background: #fff;
            box-shadow: 0 8px 22px rgba(31, 45, 58, 0.05);
        }
        .newusluga-products-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            margin-bottom: 14px;
        }
        .newusluga-products-title {
            margin: 0;
            color: #26313d;
            font-size: 18px;
            font-weight: 700;
            line-height: 1.3;
        }
        .newusluga-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 10px;
        }
        .newusluga-product {
            display: flex;
            align-items: center;
            gap: 11px;
            min-height: 58px;
            padding: 11px 12px;
            border: 1px solid #dfe6ec;
            border-radius: 8px;
            background: #f8fafb;
            color: #26313d;
            text-decoration: none;
            transition: border-color 0.15s ease, background 0.15s ease, box-shadow 0.15s ease;
        }
        .newusluga-product:hover,
        .newusluga-product:focus {
            border-color: #9fc5df;
            background: #fff;
            box-shadow: 0 8px 18px rgba(47, 127, 184, 0.12);
            text-decoration: none;
            outline: 0;
        }
        .newusluga-product.is-active {
            border-color: #26bb84;
            background: #eefbf6;
        }
        .newusluga-product.is-extra {
            border-color: #d8c95f;
            background: #fffbea;
        }
        .newusluga-product-icon {
            flex: 0 0 34px;
            width: 34px;
            height: 34px;
            border: 1px solid #dfe6ec;
            border-radius: 8px;
            background-color: #fff;
            background-repeat: no-repeat;
            background-position: center;
            background-size: contain;
        }
        .newusluga-product-icon.is-sprite {
            background-size: auto;
        }
        .newusluga-product-text {
            min-width: 0;
        }
        .newusluga-product-name {
            display: block;
            font-size: 15px;
            font-weight: 700;
            line-height: 1.3;
            overflow-wrap: anywhere;
        }
        .newusluga-product-state {
            display: block;
            margin-top: 3px;
            color: #6b7886;
            font-size: 12px;
            line-height: 1.2;
        }
        .newusluga-empty {
            margin: 0;
            padding: 18px;
            border: 1px dashed #cfd8e3;
            border-radius: 8px;
            color: #6b7886;
            font-size: 14px;
            text-align: center;
        }
        @media (max-width: 860px) {
            .newusluga-page {
                margin-top: 64px;
                padding: 0 10px;
            }
            .newusluga-page.is-embedded {
                margin-top: 10px;
            }
            .newusluga-titlebar,
            .newusluga-products-head {
                align-items: flex-start;
                flex-direction: column;
            }
            .newusluga-summary {
                grid-template-columns: 1fr;
            }
            .newusluga-back {
                width: 100%;
            }
        }
        @media (max-width: 520px) {
            .newusluga-titlebar,
            .newusluga-products,
            .newusluga-service-card {
                padding: 13px;
            }
            .newusluga-titlebar h1 {
                font-size: 20px;
            }
            .newusluga-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body class="newusluga-modern">
<?php
if (!$isEmbedded) {
    include 'header.php';
}
?>
<div class="newusluga-page<?php if ($isEmbedded) { echo ' is-embedded'; } ?>">
    <div class="newusluga-titlebar">
        <div class="newusluga-title-main">
            <div class="newusluga-eyebrow">Выставление счета</div>
            <h1>Новый продукт</h1>
            <div class="newusluga-client">
                для
                <?php if (!$isEmbedded && $clientId > 0) { ?>
                    <a href="/kartklient.php?id=<?php echo newusluga_h($clientId); ?>"><?php echo newusluga_h($client['naim']); ?></a>
                <?php } else { ?>
                    <?php echo newusluga_h($client['naim']); ?>
                <?php } ?>
            </div>
        </div>
        <?php if (isset($_GET['rand'])) { ?>
            <a class="newusluga-back" href="/helloogrn.php?id=<?php echo newusluga_h($_GET['id']); ?>&amp;ogrn=<?php echo newusluga_h($_GET['ogrn']); ?>&amp;parent=<?php echo newusluga_h($_GET['tip']); ?>&amp;rand=<?php echo newusluga_h($_GET['rand']); ?>">
                <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>&nbsp;Назад
            </a>
        <?php } elseif (!$isEmbedded && $clientId > 0) { ?>
            <a class="newusluga-back" href="/kartklient.php?id=<?php echo newusluga_h($clientId); ?>">
                <span class="glyphicon glyphicon-folder-open" aria-hidden="true"></span>&nbsp;Карточка клиента
            </a>
        <?php } ?>
    </div>

    <div class="newusluga-summary">
        <div class="newusluga-service-card">
            <div class="newusluga-service-label">Продукты от</div>
            <div class="newusluga-service-value"><?php echo newusluga_h($service['name']); ?></div>
        </div>
        <div class="newusluga-service-card">
            <div class="newusluga-service-label">Организация</div>
            <div class="newusluga-service-value"><?php echo newusluga_h($client['inn']); ?><?php if (!empty($client['kpp'])) { ?> / <?php echo newusluga_h($client['kpp']); ?><?php } ?></div>
        </div>
        <div class="newusluga-service-card">
            <div class="newusluga-service-label">Доступно</div>
            <div class="newusluga-service-value"><?php echo newusluga_h($posts); ?> продуктов</div>
        </div>
    </div>

    <div class="newusluga-products">
        <div class="newusluga-products-head">
            <h2 class="newusluga-products-title">Выбор продукта</h2>
        </div>

        <?php if (count($products) > 0) { ?>
            <div class="newusluga-grid">
                <?php foreach ($products as $row) {
                    $params = array(
                        'id' => $idkli,
                        'parent' => $row['id'],
                        'ogrn' => isset($_GET['ogrn']) ? $_GET['ogrn'] : '',
                        'inn' => isset($_GET['inn']) ? $_GET['inn'] : '',
                        'kpp' => isset($_GET['kpp']) ? $_GET['kpp'] : '',
                        'tip' => $parentId,
                        'head' => $headParam
                    );
                    if (isset($_GET['rand'])) {
                        $params['rand'] = $_GET['rand'];
                    }
                    $href = '/newschet.php?'.http_build_query($params);
                    $isActive = (!empty($currentTarif['parent']) && $currentTarif['parent'] == $row['id']) || (isset($_GET['tarif']) && $_GET['tarif'] == $row['id']);
                    $isExtra = isset($_GET['dop'.$row['id']]);
                    $iconValue = !empty($row['icon']) ? $row['icon'] : $row['tel'];
                    $iconUrl = newusluga_product_icon_url($iconValue);
                    $iconIsSprite = newusluga_product_icon_is_sprite($iconUrl);
                    $position = $iconIsSprite ? "7px -300px" : "center";
                    ?>
                    <a class="newusluga-product<?php if ($isActive) { echo ' is-active'; } ?><?php if ($isExtra) { echo ' is-extra'; } ?>" href="<?php echo newusluga_h($href); ?>">
                        <span class="newusluga-product-icon<?php if ($iconIsSprite) { echo ' is-sprite'; } ?>" style="background-image: url('<?php echo newusluga_h($iconUrl); ?>'); background-position: <?php echo newusluga_h($position); ?>;"></span>
                        <span class="newusluga-product-text">
                            <span class="newusluga-product-name"><?php echo newusluga_h($row['name']); ?></span>
                            <?php if ($isActive) { ?>
                                <span class="newusluga-product-state">Выбран сейчас</span>
                            <?php } elseif ($isExtra) { ?>
                                <span class="newusluga-product-state">Добавлен как доп. продукт</span>
                            <?php } ?>
                        </span>
                    </a>
                <?php } ?>
            </div>
        <?php } else { ?>
            <p class="newusluga-empty">Для выбранного направления нет доступных продуктов.</p>
        <?php } ?>
    </div>

</div>

<?php
# левая колонка сайта
include 'left_sitebar.php';
?>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
</body>
</html>
