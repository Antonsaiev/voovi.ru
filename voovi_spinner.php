<?php
$vooviLoadingDefaultTitle = 'Загружаем данные';
$vooviLoadingDefaultText = 'Пожалуйста, подождите.';
$vooviLoadingTitleValue = isset($vooviLoadingTitle) && $vooviLoadingTitle !== '' ? $vooviLoadingTitle : $vooviLoadingDefaultTitle;
$vooviLoadingTextValue = isset($vooviLoadingText) && $vooviLoadingText !== '' ? $vooviLoadingText : $vooviLoadingDefaultText;
$vooviLoadingIsVisible = !empty($vooviLoadingVisible);
?>
<style type="text/css">
    .schet-loading-overlay {
        display: none;
        position: fixed;
        top: 0;
        right: 0;
        bottom: 0;
        left: 0;
        z-index: 99999;
        align-items: center;
        justify-content: center;
        padding: 24px;
        background: rgba(15, 23, 42, 0.55);
        text-align: center;
        box-sizing: border-box;
    }
    .schet-loading-overlay.is-visible,
    .schet-loading-overlay[style*="display: block"],
    .schet-loading-overlay[style*="display:block"],
    .schet-loading-overlay[style*="display: flex"],
    .schet-loading-overlay[style*="display:flex"] {
        display: flex !important;
    }
    .schet-loading-card {
        display: block;
        width: 340px;
        max-width: 86%;
        padding: 28px 24px;
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 18px 54px rgba(0, 0, 0, 0.26);
        font-family: Arial, Helvetica, sans-serif;
        box-sizing: border-box;
    }
    .schet-loading-spinner {
        width: 56px;
        height: 56px;
        margin: 0 auto 18px;
        border: 4px solid #e7edf3;
        border-top-color: #26BB84;
        border-right-color: #3f77ae;
        border-radius: 50%;
        animation: schet-loading-spin 0.8s linear infinite;
    }
    .schet-loading-title {
        margin-bottom: 8px;
        color: #1f2937;
        font-size: 20px;
        font-weight: bold;
        line-height: 1.3;
    }
    .schet-loading-text {
        color: #64748b;
        font-size: 14px;
        line-height: 1.45;
    }
    @keyframes schet-loading-spin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
</style>
<div
    id="modal-shadowkube"
    class="schet-loading-overlay<?php if ($vooviLoadingIsVisible) { echo ' is-visible'; } ?>"
    data-default-title="<?php echo htmlspecialchars($vooviLoadingDefaultTitle, ENT_QUOTES, 'UTF-8'); ?>"
    data-default-text="<?php echo htmlspecialchars($vooviLoadingDefaultText, ENT_QUOTES, 'UTF-8'); ?>"
>
    <div id="kube" class="schet-loading-card">
        <div class="schet-loading-spinner"></div>
        <div id="schetLoadingTitle" class="schet-loading-title"><?php echo htmlspecialchars($vooviLoadingTitleValue, ENT_QUOTES, 'UTF-8'); ?></div>
        <div id="schetLoadingText" class="schet-loading-text"><?php echo htmlspecialchars($vooviLoadingTextValue, ENT_QUOTES, 'UTF-8'); ?></div>
    </div>
</div>
<script type="text/javascript">
    (function(window, document) {
        function setSpinnerText(title, text) {
            var overlay = document.getElementById('modal-shadowkube');
            var titleNode = document.getElementById('schetLoadingTitle');
            var textNode = document.getElementById('schetLoadingText');
            var defaultTitle = overlay ? overlay.getAttribute('data-default-title') : 'Загружаем данные';
            var defaultText = overlay ? overlay.getAttribute('data-default-text') : 'Пожалуйста, подождите.';

            if (titleNode) {
                titleNode.textContent = title || defaultTitle;
            }
            if (textNode) {
                textNode.textContent = text || defaultText;
            }
        }

        window.vooviShowSpinner = function(title, text) {
            var overlay = document.getElementById('modal-shadowkube');
            var spinner = document.getElementById('kube');
            setSpinnerText(title, text);
            if (overlay) {
                if (overlay.className.indexOf('is-visible') === -1) {
                    overlay.className += ' is-visible';
                }
                overlay.style.display = 'flex';
            }
            if (spinner) {
                spinner.style.display = 'block';
            }
        };
        window.vooviHideSpinner = function() {
            var overlay = document.getElementById('modal-shadowkube');
            var spinner = document.getElementById('kube');
            if (overlay) {
                overlay.className = overlay.className.replace(/\bis-visible\b/g, '').replace(/\s{2,}/g, ' ');
                overlay.style.display = 'none';
            }
            if (spinner) {
                spinner.style.display = 'block';
            }
            setSpinnerText();
        };
        window.addEventListener('pageshow', function(event) {
            if (event.persisted && window.vooviHideSpinner) {
                window.vooviHideSpinner();
            }
        });
    })(window, document);
</script>
