<?php if (isset($userdata['adm']) && (string)$userdata['adm']==='1') { ?>
<div class="modal fade" id="invoice-manager-modal" tabindex="-1" role="dialog" aria-labelledby="invoice-manager-title" data-rand="<?php echo htmlspecialchars($_GET['rand'],ENT_QUOTES,'UTF-8'); ?>">
    <div class="modal-dialog" role="document"><div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Закрыть"><span aria-hidden="true">&times;</span></button>
            <h3 class="modal-title" id="invoice-manager-title">Сменить менеджера счёта</h3>
        </div>
        <form id="invoice-manager-form">
            <div class="modal-body">
                <p class="komnete-manager-current">Сейчас: <strong id="invoice-manager-current">Загружаем…</strong></p>
                <label for="invoice-manager-select">Новый менеджер</label>
                <select id="invoice-manager-select" class="form-control" name="manager" required disabled><option value="">Выберите менеджера</option></select>
                <p id="invoice-manager-kontur" class="komnete-manager-note" hidden>Менеджер также изменится у связанного документа Контура в базе Voovi.</p>
                <div id="invoice-manager-shared" class="komnete-manager-warning" hidden>
                    <p>Этот документ Контура связан и с другими счетами Voovi:</p>
                    <ul id="invoice-manager-related"></ul>
                    <label><input type="checkbox" id="invoice-manager-shared-confirm"> Подтверждаю смену менеджера общего документа Контура</label>
                    <p>Менеджеры остальных счетов Voovi останутся прежними.</p>
                </div>
                <p class="komnete-manager-note">Номер счёта сохранится. Уже начисленная зарплата не пересчитывается.</p>
                <p id="invoice-manager-error" class="text-danger" role="alert" hidden></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Отмена</button>
                <button type="submit" class="btn btn-primary" id="invoice-manager-save" disabled>Сменить менеджера</button>
            </div>
        </form>
    </div></div>
</div>
<script src="js/invoice-manager.js?v=1"></script>
<?php } ?>
