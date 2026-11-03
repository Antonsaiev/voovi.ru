/* Смена менеджера: загружаем актуальные связи при каждом открытии окна. */
(function ($) {
    'use strict';
    var modal = $('#invoice-manager-modal'), form = $('#invoice-manager-form');
    var select = $('#invoice-manager-select'), save = $('#invoice-manager-save');
    var error = $('#invoice-manager-error'), snapshot = null, busy = false, generation = 0;
    function message(xhr) {
        return xhr.responseJSON && xhr.responseJSON.error || 'Не удалось сохранить. Повторите попытку.';
    }
    function showError(text) { error.text(text).prop('hidden', false); }
    modal.on('show.bs.modal', function () {
        var currentGeneration = ++generation;
        snapshot = null; busy = false;
        error.prop('hidden', true); save.prop('disabled', true);
        select.empty().append($('<option>').val('').text('Выберите менеджера')).prop('disabled', true);
        $('#invoice-manager-current').text('Загружаем…');
        $('#invoice-manager-kontur, #invoice-manager-shared').prop('hidden', true);
        $('#invoice-manager-shared-confirm').prop('checked', false);
        $.ajax({url: 'schetkto.php', data: {rand: modal.attr('data-rand')}, dataType: 'json'})
            .done(function (data) {
                if (currentGeneration !== generation) return;
                snapshot = data;
                $('#invoice-manager-current').text(data.current);
                data.managers.forEach(function (manager) {
                    select.append($('<option>').val(manager.id).text(manager.name));
                });
                select.val(data.manager_id || '').prop('disabled', false);
                $('#invoice-manager-kontur').prop('hidden', !data.has_kontur);
                var related = $('#invoice-manager-related').empty();
                data.related.forEach(function (invoice) { related.append($('<li>').text(invoice.ns)); });
                $('#invoice-manager-shared').prop('hidden', data.related.length === 0);
                save.prop('disabled', false);
            })
            .fail(function (xhr) { if (currentGeneration === generation) showError(message(xhr)); });
    });
    form.on('submit', function (event) {
        event.preventDefault();
        if (busy || !snapshot) return;
        if (!select.val()) { showError('Выберите нового менеджера.'); return; }
        if (snapshot.related.length && !$('#invoice-manager-shared-confirm').prop('checked')) {
            showError('Подтвердите смену менеджера общего документа Контура.'); return;
        }
        busy = true; save.prop('disabled', true); error.prop('hidden', true);
        $.ajax({url: 'schetkto.php', type: 'POST', dataType: 'json', data: {
            rand: modal.attr('data-rand'), manager: select.val(), token: snapshot.token,
            version: snapshot.version, shared_confirm: $('#invoice-manager-shared-confirm').prop('checked') ? '1' : '0'
        }}).done(function () { window.location.reload(); })
            .fail(function (xhr) { showError(message(xhr)); busy = false; save.prop('disabled', false); });
    });
})(jQuery);
