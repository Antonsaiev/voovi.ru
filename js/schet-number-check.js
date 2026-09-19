(function ($) {
    $(function () {
        var input = $('#nomerschetks');
        var warning = $('#schet-number-warning');
        if (!input.length || !warning.length) {
            return;
        }

        var form = input.closest('form');
        var submitButtons = form.find(':submit');
        var retryButton = $('<button type="button" class="btn btn-default btn-sm schet-number-retry">Повторить проверку</button>')
            .insertAfter(warning).hide();
        var timer;
        var request;
        var revision = 0;
        var currentNumber = null;
        var checkedNumber = null;
        var pending = false;

        function showError() {
            pending = false;
            input.attr('aria-busy', 'false');
            warning.addClass('schet-number-check-error')
                .text('Не удалось проверить номер счета. Повторите проверку, чтобы продолжить.')
                .show();
            retryButton.show();
        }

        function checkNumber(delay) {
            var number = $.trim(input.val());
            if (number === currentNumber && (pending || checkedNumber === number)) {
                return;
            }

            var currentRevision = ++revision;
            currentNumber = number;
            checkedNumber = null;
            pending = !!number;
            submitButtons.prop('disabled', pending);
            input.attr('aria-busy', pending ? 'true' : 'false');
            window.clearTimeout(timer);
            if (request) {
                request.abort();
                request = null;
            }
            input.removeClass('schet-number-duplicate');
            warning.hide().text('').removeClass('schet-number-check-error');
            retryButton.hide();
            if (!number) {
                checkedNumber = '';
                return;
            }

            warning.addClass('schet-number-check-error').text('Проверяем номер счета…').show();
            timer = window.setTimeout(function () {
                request = $.ajax({
                    url: '/schet_number_check.php',
                    dataType: 'json',
                    cache: false,
                    timeout: 10000,
                    data: {
                        number: number,
                        exclude_rand: input.attr('data-exclude-rand') || ''
                    }
                }).done(function (data) {
                    if (currentRevision !== revision || $.trim(input.val()) !== number) {
                        return;
                    }
                    if (!data || typeof data.exists !== 'boolean') {
                        showError();
                        return;
                    }
                    pending = false;
                    checkedNumber = number;
                    submitButtons.prop('disabled', false);
                    input.attr('aria-busy', 'false');
                    warning.hide().text('').removeClass('schet-number-check-error');
                    if (data.exists) {
                        input.addClass('schet-number-duplicate');
                        warning.text('Внимание: счет с номером «' + number + '» уже есть в базе. Вы можете продолжить и выставить счет с этим номером.').show();
                    }
                }).fail(function (xhr, status) {
                    if (status === 'abort' || currentRevision !== revision || $.trim(input.val()) !== number) {
                        return;
                    }
                    showError();
                });
            }, delay);
        }

        input.on('input', function () {
            checkNumber(300);
        });
        input.on('change blur', function () {
            checkNumber(0);
        });
        retryButton.on('click', function () {
            checkNumber(0);
        });
        // Also guard submission via Enter before the form shows its loading overlay.
        form[0].schetNumberCanSubmit = function () {
            var number = $.trim(input.val());
            if (!number || checkedNumber === number) {
                return true;
            }
            checkNumber(0);
            return false;
        };
        checkNumber(0);
    });
}(jQuery));
