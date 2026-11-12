<?php
require_once __DIR__.'/kontur_common.php';
header('Cache-Control: no-store');
session_start();
$rand = isset($_GET['rand']) ? $_GET['rand'] : '';
list($user, $invoice) = kontur_context($rand);
if (empty($_SESSION['kontur_csrf'])) {
    $_SESSION['kontur_csrf'] = bin2hex(openssl_random_pseudo_bytes(32));
}
$csrf = $_SESSION['kontur_csrf'];
session_write_close();
$back = '/kartklient.php?'.http_build_query(array('id' => $invoice['idkli']));
?>
<!doctype html>
<html lang="ru"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Счёт Контура · Voovi</title><link rel="stylesheet" href="/css/kontur-invoice.css?v=16"></head>
<body><main id="kontur-app" data-inn="<?php echo kontur_h($invoice['inn']); ?>" data-rand="<?php echo kontur_h($rand); ?>" data-csrf="<?php echo kontur_h($csrf); ?>">
<header><a href="<?php echo kontur_h($back); ?>">← Карточка клиента</a><span class="brand">VOOVI <small> / Контур</small></span></header>
<div class="title"><p class="eyebrow">СЧЁТ КОНТУРА</p><h1>Счёт Контура</h1><p><?php echo kontur_h($invoice['name']); ?> · ИНН <?php echo kontur_h($invoice['inn']); ?></p><p class="muted">Счёт Voovi № <?php echo kontur_h($invoice['ns']); ?></p></div>
<div id="message" role="status" aria-live="polite"></div>
<section id="result" class="card" hidden></section>
<section class="card manual-link" id="manual-link">
<details id="manual-details"><summary>Присоединить существующий счёт Контура</summary>
<label for="invoice-search">Номер счёта, ИНН или название плательщика</label>
<input type="search" id="invoice-search" autocomplete="off" placeholder="Найти счёт Контура…">
<p id="invoice-search-status" class="hint" role="status">Поиск по загруженным счетам Контура.</p>
<div id="invoice-search-results" class="manual-results"></div>
<button type="button" id="save-invoice-number" class="secondary" hidden></button>
</details></section>
<section class="card" id="selection"><div class="section-title"><span>01</span><h2>Добавление продуктов</h2></div>
<p class="hint" id="product-help">Выберите продукт и добавьте тарифы. Для добавления другого продукта выберите его здесь — позиции в счёте сохранятся.</p>
<label id="product-label" class="sr-only">Продукт Контура</label>
<div class="product-picker" id="product-picker">
<input type="hidden" id="product" value="">
<button type="button" id="product-toggle" class="product-toggle" role="combobox" aria-labelledby="product-label product-value" aria-haspopup="listbox" aria-expanded="false" aria-controls="product-options" disabled><span id="product-value">Выберите продукт</span><span aria-hidden="true">⌄</span></button>
<div id="product-menu" class="product-menu" hidden>
<input type="search" id="product-search" placeholder="Найти продукт…" aria-label="Поиск продукта Контура" autocomplete="off" aria-controls="product-options">
<div id="product-options" class="product-options" role="listbox" aria-label="Продукты Контура"></div>
<p id="product-empty" class="hint" hidden>Продукт не найден. Попробуйте другое название.</p>
</div></div>
<p id="account-status" class="hint" role="status"></p>
</section>
<section class="card" id="composition"><div class="section-title"><span>02</span><h2>Состав счёта</h2><span id="position-count" class="count" hidden></span></div>
<div class="product-picker tariff-picker" id="tariff-picker">
<button type="button" id="tariff-toggle" class="product-toggle" role="combobox" aria-label="Найти и добавить тариф" aria-haspopup="listbox" aria-expanded="false" aria-controls="tariff-options" disabled><span>＋ Найти и добавить тариф</span><span aria-hidden="true">⌄</span></button>
<div id="tariff-menu" class="product-menu" hidden>
<input type="search" id="tariff-search" placeholder="Введите название тарифа…" aria-label="Поиск тарифа Контура" autocomplete="off" aria-controls="tariff-options">
<div id="tariff-options" class="product-options" role="listbox" aria-label="Доступные тарифы"></div>
<p id="tariff-empty" class="hint" hidden>Тариф не найден. Попробуйте другое название.</p>
</div></div>
<div id="empty-composition" class="empty-composition"><p>Добавьте тариф через поиск</p><p class="hint">Его доступные параметры и дополнения появятся в составе счёта.</p></div>
<div id="positions"></div>
<button id="add-position" class="secondary" type="button" disabled hidden>+ Добавить ещё тариф</button>
<div class="invoice-footer"><div id="quote" aria-live="polite"><p class="muted">Стоимость рассчитается автоматически после выбора тарифа и его параметров.</p></div>
<div class="issue-area">
<button id="issue" type="button" disabled>Выставить счёт Контур</button>
</div></div></section>
</main><script src="/js/kontur-form-core.js?v=14" defer></script><script src="/js/kontur-invoice.js?v=29" defer></script></body></html>
