<?php
include 'conf.php';
require_once 'tarif_identity.php';

if (!function_exists('new_tarif_h')) {
    function new_tarif_h($value)
    {
        return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
    }

    function new_tarif_collect_form_data($productId)
    {
        return array(
            'name' => isset($_POST['name']) ? trim($_POST['name']) : '',
            'value' => isset($_POST['comment']) ? trim($_POST['comment']) : '',
            'comment' => isset($_POST['comment']) ? trim($_POST['comment']) : '',
            'product_id' => $productId,
            'price' => isset($_POST['price']) ? trim($_POST['price']) : '',
            'group_tarif' => isset($_POST['group_tarif']) ? $_POST['group_tarif'] : '0',
            'product_model' => isset($_POST['product_model']) ? $_POST['product_model'] : '0',
            'gen' => isset($_POST['gen']) ? 1 : 0,
        );
    }

    function new_tarif_render_options($options, $valueKey, $labelKey, $selectedValue, $emptyTitle)
    {
        echo '<option value="0">' . new_tarif_h($emptyTitle) . '</option>';
        foreach ($options as $option) {
            $value = $option[$valueKey];
            $selected = (string)$value === (string)$selectedValue ? ' selected' : '';
            echo '<option value="' . new_tarif_h($value) . '"' . $selected . '>' . new_tarif_h($option[$labelKey]) . '</option>';
        }
    }

    function new_tarif_render_doc_table($tip, $productId)
    {
        $tip = (int)$tip;
        $productId = (int)$productId;
        $query = mysql_query("SELECT * FROM doki WHERE tip = '" . $tip . "' ORDER BY name");

        echo '<table class="table table-condensed tarif-doc-table">';
        while ($row = mysql_fetch_assoc($query)) {
            $documentId = (int)$row['id'];
            $result = mysql_query("SELECT count(*) FROM proddoc WHERE produkt = '" . $productId . "' AND document = " . $documentId);
            $checked = mysql_result($result, 0) > 0;
            echo '<tr class="js-doc-row' . ($checked ? ' is-selected' : '') . '">';
            echo '<td class="tarif-doc-check"><input type="checkbox" class="js-doc-check" data-tip="' . $tip . '" value="' . $documentId . '"' . ($checked ? ' checked' : '') . '></td>';
            echo '<td>' . new_tarif_h($row['name']) . '</td>';
            echo '</tr>';
        }
        echo '</table>';
    }
}

$productId = isset($_GET['parent']) ? (int)$_GET['parent'] : 0;
$productResult = mysql_query("SELECT * FROM produkti WHERE id = " . $productId . " LIMIT 1");
$product = mysql_fetch_assoc($productResult);

if (!$product) {
    die('Продукт не найден');
}

if (isset($_COOKIE['id']) and isset($_COOKIE['hash'])) {
    $userdata = mysql_fetch_assoc(mysql_query("SELECT * FROM users WHERE users_id = '" . intval($_COOKIE['id']) . "' LIMIT 1"));

    if (($userdata['users_hash'] !== $_COOKIE['hash']) or ($userdata['users_id'] !== $_COOKIE['id'])) {
        setcookie('id', '', time() - 60 * 24 * 30 * 12, '/');
        setcookie('hash', '', time() - 60 * 24 * 30 * 12, '/');
        setcookie('errors', '1', time() + 60 * 24 * 30 * 12, '/');
        header('Location: index.php');
        exit();
    }
} else {
    setcookie('errors', '2', time() + 60 * 24 * 30 * 12, '/');
    header('Location: index.php');
    exit();
}

$serviceId = (int)$product['parent'];
$message = '';
$messageType = 'success';
$showDocSettings = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = isset($_POST['action']) ? $_POST['action'] : '';

    if ($action === 'create_tarif' || $action === 'update_tarif') {
        $data = new_tarif_collect_form_data($productId);

        if ($data['name'] === '') {
            $message = 'Укажите название тарифа.';
            $messageType = 'danger';
        } elseif (voovi_tarif_nullable_int($data['group_tarif']) === null) {
            $message = 'Выберите группу тарифа.';
            $messageType = 'danger';
        } else {
            if ($action === 'create_tarif') {
                voovi_tarif_create($data);
                mysql_query("INSERT INTO aktivn (data, deistvie, users) VALUES ('" . date("d.m.Y; H:i:s") . "', 'Новый тариф', '" . $userdata['users_id'] . "')") or die(mysql_error($link));
                $message = 'Новый тариф добавлен.';
            } else {
                $parentId = isset($_POST['tarif_parent_id']) ? (int)$_POST['tarif_parent_id'] : 0;
                voovi_tarif_create_version($parentId, $data);
                mysql_query("INSERT INTO aktivn (data, deistvie, users) VALUES ('" . date("d.m.Y; H:i:s") . "', 'Тариф изменен', '" . $userdata['users_id'] . "')") or die(mysql_error($link));
                $message = 'Тариф обновлен: старая версия отправлена в архив, новая версия создана.';
            }
        }
    } elseif ($action === 'archive_tarif') {
        $parentId = isset($_POST['tarif_parent_id']) ? (int)$_POST['tarif_parent_id'] : 0;
        voovi_tarif_archive_parent($parentId);
        mysql_query("INSERT INTO aktivn (data, deistvie, users) VALUES ('" . date("d.m.Y; H:i:s") . "', 'Тариф архивирован', '" . $userdata['users_id'] . "')") or die(mysql_error($link));
        $message = 'Тариф отправлен в архив.';
    } elseif ($action === 'add_doc_type') {
        $docName = isset($_POST['doc_name']) ? trim($_POST['doc_name']) : '';
        $docTip = isset($_POST['doc_tip']) ? (int)$_POST['doc_tip'] : 1;
        if ($docName !== '') {
            mysql_query("INSERT INTO doki (name, tip) VALUES ('" . mysql_real_escape_string($docName) . "', '" . $docTip . "')") or die(mysql_error($link));
            $message = 'Тип документа добавлен.';
        }
    }
}

$groupOptions = array();
$groupQuery = mysql_query("SELECT * FROM group_tarif WHERE uslugi_id = '" . $serviceId . "' AND (del_group_tarif = 'False' OR del_group_tarif = '0' OR del_group_tarif = '' OR del_group_tarif IS NULL) ORDER BY name_group");
while ($group = mysql_fetch_assoc($groupQuery)) {
    $groupOptions[] = $group;
}

$modelOptions = array();
$modelQuery = mysql_query("SELECT product_type.name AS type_name, product_model.id AS id_product_model, product_model.name AS name_product_model FROM product_model INNER JOIN product_type ON product_type.id = product_model.product_type WHERE product_type.uslugi = '" . $serviceId . "' ORDER BY product_type.name, product_model.name");
while ($model = mysql_fetch_assoc($modelQuery)) {
    $model['label'] = $model['type_name'] . ' / ' . $model['name_product_model'];
    $modelOptions[] = $model;
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <title>Тарифы</title>
    <meta http-equiv="content-type" content="text/html" charset="utf-8" />
    <meta http-equiv="Content-Style-Type" content="text/css" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #f4f6f8;
        }

        .tarif-page {
            margin-top: 60px;
            margin-bottom: 40px;
        }

        .tarif-header {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            gap: 16px;
            margin-bottom: 18px;
        }

        .tarif-title {
            margin: 0;
            font-size: 22px;
            font-weight: 700;
            color: #1f2933;
        }

        .tarif-subtitle {
            margin-top: 5px;
            color: #6b7280;
            font-size: 13px;
        }

        .tarif-nav {
            display: flex;
            gap: 8px;
            white-space: nowrap;
        }

        .tarif-nav .btn {
            min-height: 32px;
            padding: 6px 12px;
            border-radius: 4px;
            font-size: 13px;
            line-height: 1.35;
        }

        .tarif-nav .glyphicon {
            margin-right: 4px;
        }

        .tarif-panel {
            background: #fff;
            border: 1px solid #dbe2ea;
            border-radius: 6px;
            box-shadow: 0 8px 20px rgba(15, 23, 42, 0.05);
            margin-bottom: 18px;
            padding: 18px;
        }

        .tarif-panel-title {
            margin: 0 0 12px;
            font-size: 15px;
            font-weight: 700;
            color: #2d3748;
        }

        .tarif-create-grid {
            display: grid;
            grid-template-columns: minmax(260px, 1.4fr) 150px minmax(190px, 0.8fr) minmax(220px, 0.9fr) 90px 124px 132px;
            gap: 12px;
            align-items: end;
        }

        .tarif-create-description {
            display: none;
            margin-top: 12px;
        }

        .tarif-create-description textarea {
            min-height: 74px;
            resize: vertical;
        }

        .tarif-description-toggle,
        .tarif-add-button {
            min-height: 34px;
            border-radius: 4px;
            font-size: 13px;
            line-height: 1.35;
        }

        .tarif-add-button {
            font-weight: 700;
            box-shadow: 0 6px 14px rgba(22, 163, 74, 0.18);
        }

        .tarif-add-button .glyphicon {
            margin-right: 5px;
        }

        .tarif-list {
            background: #fff;
            border: 1px solid #dbe2ea;
            border-radius: 6px;
            overflow: hidden;
            box-shadow: 0 8px 20px rgba(15, 23, 42, 0.05);
        }

        .tarif-list-head {
            display: grid;
            grid-template-columns: minmax(280px, 1.7fr) minmax(110px, 0.45fr) minmax(180px, 0.8fr) minmax(220px, 0.95fr) 80px 180px;
            gap: 14px;
            padding: 11px 16px;
            background: #eef3f7;
            border-bottom: 1px solid #dbe2ea;
            color: #52616f;
            font-size: 12px;
            font-weight: 700;
            line-height: 1.2;
            text-transform: uppercase;
        }

        .tarif-card {
            border-bottom: 1px solid #edf1f5;
        }

        .tarif-card:last-child {
            border-bottom: 0;
        }

        .tarif-card-main {
            display: grid;
            grid-template-columns: minmax(280px, 1.7fr) minmax(110px, 0.45fr) minmax(180px, 0.8fr) minmax(220px, 0.95fr) 80px 180px;
            gap: 14px;
            align-items: center;
            padding: 15px 16px;
            transition: background 0.15s ease;
        }

        .tarif-card-main:hover {
            background: #fbfcfd;
        }

        .tarif-card-actions {
            white-space: nowrap;
            text-align: right;
        }

        .tarif-card-actions .btn {
            min-height: 32px;
            padding: 6px 11px;
            border-radius: 4px;
            font-size: 13px;
            line-height: 1.35;
        }

        .tarif-name {
            font-size: 17px;
            font-weight: 700;
            line-height: 1.35;
            color: #1f2933;
            word-break: break-word;
        }

        .tarif-comment {
            max-width: 760px;
            margin-top: 4px;
            color: #6b7280;
            font-size: 13px;
            line-height: 1.35;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .tarif-price {
            display: inline-block;
            min-width: 86px;
            font-weight: 700;
            white-space: nowrap;
            color: #0f766e;
            font-size: 15px;
        }

        .tarif-gen-badge {
            display: inline-block;
            min-width: 42px;
            padding: 4px 8px;
            border-radius: 4px;
            background: #eef2f7;
            color: #64748b;
            font-size: 12px;
            font-weight: 700;
            line-height: 1.2;
            text-align: center;
        }

        .tarif-gen-badge.is-on {
            background: #e4f7ed;
            color: #12805c;
        }

        .tarif-muted {
            color: #7b8794;
            font-size: 12px;
        }

        .tarif-card-actions form {
            display: inline-block;
            margin: 0;
        }

        .tarif-edit-row {
            display: none;
            background: #fbfcfd;
            border-top: 1px solid #edf1f5;
        }

        .tarif-edit-form {
            padding: 16px;
        }

        .tarif-switch {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            margin: 0;
            font-weight: 400;
        }

        .tarif-doc-panel {
            background: #fff;
            border: 1px solid #dbe2ea;
            border-radius: 6px;
            padding: 14px;
            margin-bottom: 14px;
        }

        .tarif-doc-table tr {
            cursor: pointer;
        }

        .tarif-doc-table tr.is-selected {
            background: #edf7ed;
        }

        .tarif-doc-check {
            width: 30px;
        }

        @media (max-width: 1200px) {
            .tarif-create-grid {
                grid-template-columns: 1fr 1fr;
            }

            .tarif-list-head {
                display: none;
            }

            .tarif-card-main {
                grid-template-columns: 1fr 1fr;
                align-items: start;
            }
        }

        @media (max-width: 768px) {
            .tarif-header {
                align-items: flex-start;
                flex-direction: column;
            }

            .tarif-create-grid {
                grid-template-columns: 1fr;
            }

            .tarif-card-main {
                grid-template-columns: 1fr;
            }

            .tarif-card-actions {
                text-align: left;
            }
        }
    </style>
</head>
<body>
<?php include 'header.php'; ?>
<div class="container-fluid tarif-page">
    <div class="row">
        <div class="col-md-12">
            <div class="tarif-header">
                <div>
                    <h1 class="tarif-title">Тарифы для "<?php echo new_tarif_h($product['name']); ?>"</h1>
                    <div class="tarif-subtitle">Активные тарифы продукта</div>
                </div>
                <div class="tarif-nav">
                    <a class="btn btn-default btn-sm" href="new_produkt.php?parent=<?php echo $serviceId; ?>"><span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>Продукты</a>
                    <a class="btn btn-default btn-sm" href="new_usluga.php"><span class="glyphicon glyphicon-th-list" aria-hidden="true"></span>Компании</a>
                </div>
            </div>

            <?php if ($message !== '') : ?>
                <div class="alert alert-<?php echo new_tarif_h($messageType); ?>"><?php echo new_tarif_h($message); ?></div>
            <?php endif; ?>

            <div class="tarif-panel">
                <div class="tarif-panel-title">Добавить тариф</div>
                <form method="post">
                    <input type="hidden" name="action" value="create_tarif">
                    <div class="tarif-create-grid">
                        <div>
                            <label>Название</label>
                            <input class="form-control" type="text" name="name" required>
                        </div>
                        <div>
                            <label>Цена</label>
                            <input class="form-control" type="text" name="price">
                        </div>
                        <div>
                            <label>Группа</label>
                            <select class="form-control" name="group_tarif" required>
                                <?php new_tarif_render_options($groupOptions, 'id_group', 'name_group', 0, 'Выберите группу'); ?>
                            </select>
                        </div>
                        <div>
                            <label>Модель склада</label>
                            <select class="form-control" name="product_model">
                                <?php new_tarif_render_options($modelOptions, 'id_product_model', 'label', 0, 'Без модели'); ?>
                            </select>
                        </div>
                        <label class="tarif-switch">
                            <input type="checkbox" name="gen" value="1"> ЭЦП
                        </label>
                        <button type="button" class="btn btn-default tarif-description-toggle js-create-comment-toggle" data-target="#tarif-create-comment">Описание</button>
                        <button type="submit" class="btn btn-success tarif-add-button"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span>Добавить</button>
                    </div>
                    <div id="tarif-create-comment" class="tarif-create-description">
                        <label>Описание</label>
                        <textarea class="form-control" name="comment" rows="2"></textarea>
                    </div>
                </form>
            </div>

            <div class="tarif-list">
                <div class="tarif-list-head">
                    <div>Тариф</div>
                    <div>Цена</div>
                    <div>Группа</div>
                    <div>Модель</div>
                    <div>ЭЦП</div>
                    <div></div>
                </div>
                    <?php
                    $tarifQuery = mysql_query(voovi_tarif_parent_list_query($productId));
                    while ($row = mysql_fetch_assoc($tarifQuery)) :
                        $tarifParentId = (int)$row['tarif_parent_id'];
                        $tarifId = (int)$row['tarif_id'];
                        $name = $row['parent_name'] !== '' ? $row['parent_name'] : $row['tarif_name'];
                        $comment = $row['comment'] !== null && $row['comment'] !== '' ? $row['comment'] : $row['value'];
                        $groupValue = $row['parent_group_tarif'] !== null ? $row['parent_group_tarif'] : $row['tarif_group_tarif'];
                        $modelValue = $row['parent_product_model'] !== null ? $row['parent_product_model'] : $row['tarif_product_model'];
                        ?>
                        <div class="tarif-card">
                            <div class="tarif-card-main">
                            <div>
                                <div class="tarif-name" title="parent #<?php echo $tarifParentId; ?>"><?php echo new_tarif_h($name); ?></div>
                                <?php if ($comment !== '') : ?>
                                    <div class="tarif-comment"><?php echo new_tarif_h($comment); ?></div>
                                <?php endif; ?>
                            </div>
                            <div><span class="tarif-price"><?php echo new_tarif_h($row['price']); ?></span></div>
                            <div><?php echo new_tarif_h($row['name_group']); ?></div>
                            <div>
                                <?php if ($row['model_name'] !== null && $row['model_name'] !== '') : ?>
                                    <?php echo new_tarif_h($row['type_name'] . ' / ' . $row['model_name']); ?>
                                <?php else : ?>
                                    <span class="tarif-muted">Без модели</span>
                                <?php endif; ?>
                            </div>
                            <div>
                                <?php if ($tarifId > 0) : ?>
                                    <span class="tarif-gen-badge<?php echo (int)$row['gen'] === 1 ? ' is-on' : ''; ?>"><?php echo (int)$row['gen'] === 1 ? 'Вкл' : 'Выкл'; ?></span>
                                <?php else : ?>
                                    <span class="tarif-muted">Нет версии</span>
                                <?php endif; ?>
                            </div>
                            <div class="tarif-card-actions">
                                <button type="button" class="btn btn-default btn-sm js-edit-toggle" data-target="#tarif-edit-<?php echo $tarifParentId; ?>" data-form="#tarif-form-<?php echo $tarifParentId; ?>">Изменить</button>
                                <form method="post" class="js-archive-form">
                                    <input type="hidden" name="action" value="archive_tarif">
                                    <input type="hidden" name="tarif_parent_id" value="<?php echo $tarifParentId; ?>">
                                    <button type="submit" class="btn btn-danger btn-sm js-archive-button">В архив</button>
                                </form>
                            </div>
                            </div>
                            <div id="tarif-edit-<?php echo $tarifParentId; ?>" class="tarif-edit-row">
                                <form method="post" class="tarif-edit-form" id="tarif-form-<?php echo $tarifParentId; ?>">
                                    <input type="hidden" name="action" value="update_tarif">
                                    <input type="hidden" name="tarif_parent_id" value="<?php echo $tarifParentId; ?>">
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <label>Название</label>
                                            <input class="form-control" type="text" name="name" value="<?php echo new_tarif_h($name); ?>" required>
                                        </div>
                                        <div class="col-sm-2">
                                            <label>Цена</label>
                                            <input class="form-control" type="text" name="price" value="<?php echo new_tarif_h($row['price']); ?>">
                                        </div>
                                        <div class="col-sm-3">
                                            <label>Группа</label>
                                            <select class="form-control" name="group_tarif" required>
                                                <?php new_tarif_render_options($groupOptions, 'id_group', 'name_group', $groupValue, 'Выберите группу'); ?>
                                            </select>
                                        </div>
                                        <div class="col-sm-3">
                                            <label>Модель склада</label>
                                            <select class="form-control" name="product_model">
                                                <?php new_tarif_render_options($modelOptions, 'id_product_model', 'label', $modelValue, 'Без модели'); ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row" style="margin-top: 10px;">
                                        <div class="col-sm-9">
                                            <label>Описание</label>
                                            <textarea class="form-control" name="comment" rows="2"><?php echo new_tarif_h($comment); ?></textarea>
                                        </div>
                                        <div class="col-sm-3">
                                            <label class="tarif-switch" style="margin-top: 26px;">
                                                <input type="checkbox" name="gen" value="1" <?php echo (int)$row['gen'] === 1 ? 'checked' : ''; ?>> Генерация ЭЦП
                                            </label>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    <?php endwhile; ?>
            </div>
        </div>

        <?php if ($showDocSettings) : ?>
        <div class="col-md-3">
            <div class="tarif-doc-panel">
                <div class="tarif-panel-title">Документы ИП</div>
                <?php new_tarif_render_doc_table(1, $productId); ?>
            </div>
            <div class="tarif-doc-panel">
                <div class="tarif-panel-title">Документы организации</div>
                <?php new_tarif_render_doc_table(2, $productId); ?>
            </div>
            <div class="tarif-doc-panel">
                <div class="tarif-panel-title">Общие документы</div>
                <?php new_tarif_render_doc_table(3, $productId); ?>
            </div>
            <div class="tarif-doc-panel">
                <div class="tarif-panel-title">Добавить тип документа</div>
                <form method="post">
                    <input type="hidden" name="action" value="add_doc_type">
                    <input type="text" class="form-control" name="doc_name" placeholder="Название" style="margin-bottom: 8px;">
                    <select class="form-control" name="doc_tip" style="margin-bottom: 8px;">
                        <option value="1">ИП</option>
                        <option value="2">Организация</option>
                        <option value="3">Общая</option>
                    </select>
                    <button type="submit" class="btn btn-success btn-block">Добавить</button>
                </form>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<script src="js/bootstrap.min.js"></script>
<script>
$(function () {
    $('.js-create-comment-toggle').on('click', function () {
        var button = $(this);
        var target = $(button.data('target'));

        target.slideToggle(160);
        button.toggleClass('btn-primary btn-default');
    });

    $('.js-edit-toggle').on('click', function () {
        var button = $(this);
        var target = $(button.data('target'));
        var form = $(button.data('form'));

        if (target.is(':visible')) {
            var formElement = form.get(0);
            if (formElement.requestSubmit) {
                formElement.requestSubmit();
            } else {
                formElement.submit();
            }
            return;
        }

        $('.tarif-edit-row:visible').hide();
        $('.js-edit-toggle.is-saving')
            .removeClass('is-saving btn-primary')
            .addClass('btn-default')
            .text('Изменить');
        $('.js-archive-button.is-cancel')
            .removeClass('is-cancel btn-default')
            .addClass('btn-danger')
            .text('В архив');

        target.show();
        button
            .addClass('is-saving btn-primary')
            .removeClass('btn-default')
            .text('Сохранить');
        button.closest('.tarif-card-actions')
            .find('.js-archive-button')
            .addClass('is-cancel btn-default')
            .removeClass('btn-danger')
            .text('Отменить');
    });

    $('.js-archive-form').on('submit', function () {
        var cancelButton = $(this).find('.js-archive-button.is-cancel');
        if (cancelButton.length) {
            var card = $(this).closest('.tarif-card');
            card.find('.tarif-edit-row').hide();
            card.find('.js-edit-toggle')
                .removeClass('is-saving btn-primary')
                .addClass('btn-default')
                .text('Изменить');
            cancelButton
                .removeClass('is-cancel btn-default')
                .addClass('btn-danger')
                .text('В архив');

            return false;
        }

        return confirm('Отправить тариф в архив?');
    });

    $('.js-doc-row').on('click', function (event) {
        if (event.target.type !== 'checkbox') {
            $(this).find('.js-doc-check').trigger('click');
        }
    });

    $('.js-doc-check').on('change', function () {
        var checkbox = $(this);
        var checked = checkbox.is(':checked') ? 1 : 0;

        checkbox.closest('tr').toggleClass('is-selected', checked === 1);
        $.ajax({
            type: 'GET',
            url: 'proddoc.php',
            data: {
                id: checked,
                produkt: <?php echo $productId; ?>,
                tip: checkbox.data('tip'),
                document: checkbox.val()
            }
        });
    });
});
</script>
</body>
</html>
