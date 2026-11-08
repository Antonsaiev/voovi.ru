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
                function hideDetailSpinner() {
                    if (xhr !== $target.data('companyInvoiceDetailRequest')) return;
                    if (root.vooviHideSpinner) root.vooviHideSpinner();
                    else $('#modal-shadowkube, #kube').hide();
                }
                options.success = function () {
                    try {
                        if (version === $target.data('companyInvoiceGeneration') && typeof success === 'function') success.apply(this, arguments);
                    } finally {
                        hideDetailSpinner();
                    }
                };
                var complete = options.complete;
                options.complete = function () {
                    hideDetailSpinner();
                    if (typeof complete === 'function') complete.apply(this, arguments);
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
        $('#currentMonth').off('click.companyInvoices').on('click.companyInvoices', function () {
            var now = new Date(), year = now.getFullYear(), month = now.getMonth();
            function day(value) { return value < 10 ? '0' + value : String(value); }
            $('#datestart').val(year + '-' + day(month + 1) + '-01');
            $('#datefinish').val(year + '-' + day(month + 1) + '-' + day(new Date(year, month + 1, 0).getDate()));
            reload();
        });
        $(document).off('ajaxSuccess.companyInvoices').on('ajaxSuccess.companyInvoices', function (_, xhr, options) {
            if (/(^|\/)otkazschet\.php(?:\?|$)/.test(options.url || '') && $('#getOrg').length) reload();
        });
        // Renewal creation opens another tab. Returning here must not leave
        // the old source invoice actionable in a cached detail list.
        $(document).off('visibilitychange.companyInvoices').on('visibilitychange.companyInvoices', function () {
            if (!document.hidden && $('#getOrg').length) reload();
        });
        reload();
    };
}(window));
