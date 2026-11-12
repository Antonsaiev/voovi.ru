<style>
table > tbody > tr > td.kart-savoir-partial-payment {
    background-color: #ffd293;
    color: #624600;
}
.kart-savoir-payments-link {
    color: inherit;
    text-decoration: underline;
}
#kart-savoir-payments {
    width: 900px;
    max-width: calc(100vw - 32px);
    border: 0;
    border-radius: 7px;
}
#kart-savoir-payments::backdrop { background: rgba(0, 0, 0, .45); }
#kart-savoir-payments iframe {
    display: block;
    width: 100%;
    height: 75vh;
    border: 0;
}
</style>
<dialog id="kart-savoir-payments" aria-label="Платежи по счёту">
    <div class="text-right">
        <button type="button" class="btn btn-default btn-xs kart-savoir-close-payments">Закрыть</button>
    </div>
    <a class="kart-savoir-add-payment" target="kart-savoir-payments-frame" hidden>Внести платёж</a>
    <iframe name="kart-savoir-payments-frame" title="Платежи по счёту"></iframe>
</dialog>
<script>
(function () {
    var dialog = document.getElementById('kart-savoir-payments');
    var frame = dialog.querySelector('iframe');
    var addPayment = dialog.querySelector('.kart-savoir-add-payment');

    document.addEventListener('click', function (event) {
        var link = event.target.closest('.kart-savoir-payments-link');
        if (!link || event.ctrlKey || event.metaKey || event.shiftKey || event.altKey || !dialog.showModal) {
            return;
        }
        event.preventDefault();
        var addPaymentUrl = link.getAttribute('data-add-payment');
        addPayment.hidden = !addPaymentUrl;
        if (addPaymentUrl) {
            addPayment.href = addPaymentUrl;
        }
        frame.src = link.href;
        dialog.showModal();
    });
    dialog.querySelector('.kart-savoir-close-payments').addEventListener('click', function () {
        dialog.close();
    });
    dialog.addEventListener('close', function () {
        frame.removeAttribute('src');
    });
})();
</script>
