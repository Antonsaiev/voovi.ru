(function ($) {
    $(function () {
        var input = $('#nomerschetks');
        var warning = $('#schet-number-warning');
        if (!input.length || !warning.length) {
            return;
        }
        var timer;
        var request;
        var revision = 0;
        var checkedNumber = null;

        function checkNumber(delay) {
            var number = $.trim(input.val());
            if (number === checkedNumber) {
                return;
            }
            checkedNumber = number;
            var currentRevision = ++revision;
            window.clearTimeout(timer);
            if (request) {
                request.abort();
            }
            input.removeClass('schet-number-duplicate').attr('aria-busy', number ? 'true' : 'false');
            warning.hide().text('').removeClass('schet-number-check-error');
            if (!number) {
                return;
            }
            timer = window.setTimeout(function () {
                request = $.ajax({
                    url: '/schet_number_check.php', dataType: 'json', cache: false, timeout: 10000,
                    data: {number: number, exclude_rand: input.attr('data-exclude-rand') || ''}
                }).done(function (data) {
                    if (currentRevision !== revision || $.trim(input.val()) !== number) {
                        return;
                    }
                    input.attr('aria-busy', 'false');
                    if (data && data.exists === true) {
                        input.addClass('schet-number-duplicate');
                        warning.text('Счёт с номером «' + number + '» уже есть в базе. Вы можете продолжить сохранение.').show();
                    }
                }).fail(function () {
                    if (currentRevision === revision) {
                        input.attr('aria-busy', 'false');
                    }
                });
            }, delay);
        }
        // Поиск носит информационный характер: сохранение от него не зависит.
        input.on('input', function () { checkNumber(300); });
        input.on('change blur', function () { checkNumber(0); });
        checkNumber(0);
    });
}(jQuery));
