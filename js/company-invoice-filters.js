/* One filter state for the summary and its detail list. */
(function (root) {
    root.VooviCompanyInvoiceFilters = function ($, userId) {
        var request = null, generation = 0;
        var $pane = $('#companyRemotePane').length ? $('#companyRemotePane') : $(document);
        if (!root.vooviCompanyDetailGuardInstalled) {
            root.vooviCompanyDetailGuardInstalled = true;
            $.ajaxPrefilter(function (options, _, xhr) {
                if (!/(^|\/)tablschetosn\.php(?:\?|$)/.test(options.url || '')) return;
                var $target = $('#companyRemotePane').length ? $('#companyRemotePane') : $(document);
                var version = $target.data('companyInvoiceGeneration');
                var previous = $target.data('companyInvoiceDetailRequest');
                if (previous) previous.abort();
                $target.data('companyInvoiceDetailRequest', xhr);
                var success = options.success;
                options.success = function () {
                    if (version === $target.data('companyInvoiceGeneration') && typeof success === 'function') success.apply(this, arguments);
                };
            });
        }
        function reload() {
            var current = generation = root.vooviCompanyInvoiceGeneration = (root.vooviCompanyInvoiceGeneration || 0) + 1;
            if (request) request.abort();
            // Also invalidate a detail request that started under the previous filters.
            $pane.data('companyInvoiceGeneration', current);
            if ($pane.data('companyInvoiceDetailRequest')) $pane.data('companyInvoiceDetailRequest').abort();
            $('#tablosn').empty();
            var start = $('#datestart').val(), finish = $('#datefinish').val();
            if (!start || !finish || start > finish) {
                $('#tablschetog').text('Укажите корректный период: начало не позже окончания.');
                $('#modal-shadowkube, #kube').hide();
                return;
            }
            $('#tablschetog').text('Загрузка...');
            request = $.ajax({url: 'tablschetog.php', cache: false,
                data: {users: userId, ogrn: $('#getOrg').val(), tip: $('#tip').val(), datas: start, dataf: finish},
                success: function (html) { if (current === generation && current === $pane.data('companyInvoiceGeneration')) $('#tablschetog').html(html); },
                error: function (_, status) { if (status !== 'abort' && current === generation) $('#tablschetog').text('Не удалось загрузить счета. Измените фильтр для повтора.'); },
                complete: function () { if (current === generation) $('#modal-shadowkube, #kube').hide(); }
            });
        }
        $('#getOrg, #tip, #datestart, #datefinish').off('change.companyInvoices').on('change.companyInvoices', reload);
        $('#scales').off('change.companyInvoices').on('change.companyInvoices', function () {
            if (this.checked) {
                var now = new Date(), year = now.getFullYear(), month = now.getMonth();
                function day(value) { return value < 10 ? '0' + value : String(value); }
                $('#datestart').val(year + '-' + day(month + 1) + '-01');
                $('#datefinish').val(year + '-' + day(month + 1) + '-' + day(new Date(year, month + 1, 0).getDate()));
            }
            $('#datestart, #datefinish').prop('disabled', this.checked);
            reload();
        });
        $('#scalesl').css('color', '#536570');
        $(document).off('ajaxSuccess.companyInvoices').on('ajaxSuccess.companyInvoices', function (_, xhr, options) {
            if (/(^|\/)otkazschet\.php(?:\?|$)/.test(options.url || '') && $('#getOrg').length) reload();
        });
        reload();
    };
}(window));
