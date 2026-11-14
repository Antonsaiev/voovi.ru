(function () {
  'use strict';

  window.renderKonturDocuments = function (container, rand, csrf, billingLink) {
    var block = document.createElement('span');
    block.className = 'kontur-documents';
    if (container.tagName !== 'SPAN') block.classList.add('kontur-documents-standalone');
    container.appendChild(block);

    function describe(control, label) {
      control.setAttribute('aria-label', label);
      control.dataset.tooltip = label;
    }

    function icon(control, label, mark) {
      control.classList.add('kontur-document-icon');
      describe(control, label);
      control.innerHTML = mark;
      block.appendChild(control);
      return control;
    }

    function button(label, mark) {
      var control = document.createElement('button');
      control.type = 'button';
      return icon(control, label, mark);
    }

    if (billingLink) icon(billingLink, 'Открыть в Биллинге Контура', '<img src="/img/billing-favicon.ico" alt="">');
    var download = button('Скачать счёт в PDF',
      '<svg aria-hidden="true" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M13 3H6v18h12V8zM13 3v5h5M9 12h6m-6 4h6"/></svg>');
    var send = button('Проверяем статус Диадока…',
      '<img src="/img/diadoc-16.png" alt=""><span class="kontur-document-check" aria-hidden="true">✓</span>');
    var current, busy = false, statusQueued = false;

    function setAvailable(control, available) {
      control.setAttribute('aria-disabled', String(!available));
    }

    async function request(action, body) {
      var options = {cache: 'no-store', credentials: 'same-origin'};
      if (body !== undefined) {
        options.method = 'POST';
        options.headers = {'Content-Type': 'application/json', 'X-Kontur-CSRF': csrf};
        options.body = JSON.stringify(body);
      }
      var response = await fetch('/kontur_api.php?rand=' + encodeURIComponent(rand) + '&action=' + action, options);
      if (response.ok && action === 'document-pdf') return response.blob();
      var result = await response.json();
      if (!response.ok) throw new Error(result.error || 'Запрос к Биллингу не выполнен');
      return result;
    }

    function show(result) {
      current = result;
      send.classList.toggle('kontur-document-sent', result.sent === true);
      describe(send, result.can_send ? 'Отправить в Диадок' :
        result.status === 'Не отправлен' ? 'Отправка в Диадок недоступна' : 'Диадок: ' + result.status);
    }

    async function run(control, label, action) {
      if (busy) return;
      busy = true;
      setAvailable(download, false);
      setAvailable(send, false);
      describe(control, label);
      try {
        await action();
      } catch (error) {
        describe(control, error.message);
        if (control === download || label === 'Передаём счёт в Биллинг…') window.alert(error.message);
      } finally {
        busy = false;
        setAvailable(download, true);
        setAvailable(send, Boolean(current && current.can_send));
        if (statusQueued) {
          statusQueued = false;
          status();
        }
      }
    }

    function status() {
      if (busy) {
        statusQueued = true;
        return;
      }
      return run(send, 'Проверяем статус в Биллинге…', async function () {
        current = null;
        show(await request('documents'));
      });
    }

    download.addEventListener('click', function () {
      run(download, 'Получаем PDF из Биллинга…', async function () {
        var file = await request('document-pdf', {});
        var url = URL.createObjectURL(file);
        var link = document.createElement('a');
        link.href = url;
        link.download = 'kontur-invoice.pdf';
        link.click();
        setTimeout(function () { URL.revokeObjectURL(url); }, 30000);
        describe(download, 'Скачать счёт в PDF');
      });
    });
    send.addEventListener('click', function () {
      if (busy || !current || !current.can_send ||
          !window.confirm('Отправить счёт в Диадок получателю «' + current.recipient.name + '»?')) return;
      var payload = {key: current.key, box: current.recipient.box, department: current.recipient.department};
      run(send, 'Передаём счёт в Биллинг…', async function () {
        current = null;
        show(await request('document-send', payload));
        setTimeout(status, 2500);
      });
    });
    status();
    return status;
  };
})();
