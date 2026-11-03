/* Мгновенный поиск без перезагрузки страницы и потери фокуса. */
(function () {
    'use strict';
    var form = document.querySelector('.links-search');
    var input = document.getElementById('links-query');
    var results = document.getElementById('links-results');
    var status = document.getElementById('links-search-status');
    if (!form || !input || !results || !window.fetch || !window.AbortController) return;

    var timer;
    var controller;
    var revision = 0;

    function invalidate() {
        clearTimeout(timer);
        revision += 1;
        if (controller) controller.abort();
        results.setAttribute('aria-busy', 'true');
        results.inert = true;
        status.textContent = 'Ищем…';
    }

    async function search(pageUrl) {
        var current = revision;
        controller = new AbortController();
        var url = new URL(pageUrl || form.action, window.location.href);
        if (!pageUrl) {
            url.search = new URLSearchParams(new FormData(form)).toString();
            var linkedPage = new URL(window.location.href).searchParams.get('pagee');
            if (linkedPage) url.searchParams.set('pagee', linkedPage);
        }
        var visibleUrl = new URL(url);
        url.searchParams.set('fragment', '1');
        try {
            var response = await fetch(url, {credentials: 'same-origin', signal: controller.signal});
            if (!response.ok) throw new Error('search');
            var data = await response.json();
            if (typeof data.html !== 'string') throw new Error('search');
            if (current !== revision) return;
            results.innerHTML = data.html;
            window.history.replaceState(null, '', visibleUrl.pathname + visibleUrl.search);
            status.textContent = '';
        } catch (error) {
            if (current !== revision || error.name === 'AbortError') return;
            status.textContent = 'Не удалось выполнить поиск. Нажмите Enter, чтобы повторить.';
        } finally {
            if (current === revision) {
                results.setAttribute('aria-busy', 'false');
                // При ошибке старые результаты остаются недоступны для действий.
                results.inert = status.textContent !== '';
            }
        }
    }

    input.addEventListener('input', function () {
        invalidate();
        timer = setTimeout(function () { search(); }, 300);
    });
    form.addEventListener('submit', function (event) {
        event.preventDefault();
        invalidate();
        search();
    });
    results.addEventListener('click', function (event) {
        var link = event.target.closest('.links-pagination a');
        if (!link || event.ctrlKey || event.metaKey || event.shiftKey || event.altKey || event.button !== 0) return;
        event.preventDefault();
        invalidate();
        search(link.href);
    });
})();
