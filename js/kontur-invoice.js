/* Запросы идут через сессию Voovi; токены API не передаются в браузер. */
(function () {
  'use strict';
  var app = document.getElementById('kontur-app');
  if (!app) return;
  var form = window.KonturForm;
  var rand = app.dataset.rand, csrf = app.dataset.csrf;
  var catalogs = {}, offers = [], scale = {}, positions = [], quoteVersion = null, products = [];
  var busy = false, locked = false, revision = 0, calculating = false, manualAllowed = true;
  var payerId = null, catalogReady = false, calculateTimer = null, expiryTimer = null;
  var filteredProducts = [], activeProduct = -1, filteredTariffs = [], activeTariff = -1;
  var fieldSequence = 0, lastCalculationAt = 0, availabilityRunning=false, lastRejection=null, restoringDraft=false;
  var $ = function (id) { return document.getElementById(id); };
  function el(tag, text, cls) {
    var node = document.createElement(tag);
    if (text !== undefined && text !== null) node.textContent = String(text);
    if (cls) node.className = cls;
    return node;
  }
  function clear(node) { while (node.firstChild) node.removeChild(node.firstChild); }
  function money(value) {
    return value === null || value === undefined ? 'Уточняется' : Number(value).toLocaleString('ru-RU', {minimumFractionDigits: 2, maximumFractionDigits: 2}) + ' ₽';
  }
  function message(text, type) {
    clear($('message'));
    if (text) {
      var box=el('div',text,'message '+(type || ''));
      $('message').appendChild(box);
    }
  }
  function loadingPrices(loading) {
    positions.forEach(function(p){
      p.total.classList.toggle('is-loading',loading);
      if(loading){p.total.textContent='';p.total.setAttribute('aria-label','Рассчитываем стоимость');p.total.setAttribute('aria-busy','true');}
      else {p.total.removeAttribute('aria-label');p.total.removeAttribute('aria-busy');if(!p.total.textContent)p.total.textContent='—';}
    });
  }
  function invalidate() {
    if(!restoringDraft)lastRejection=null;
    clearTimeout(calculateTimer); clearTimeout(expiryTimer);
    revision++; quoteVersion = null;
    positions.forEach(function (p) { p.total.textContent = ''; p.controller.clearPrices(); });
    loadingPrices(true);
    quoteHint('Состав изменён. Обновляем стоимость…');
    if (!busy) message('');
    scheduleCalculation(); updateControls();
  }
  async function api(action, data, query) {
    var url = '/kontur_api.php?rand=' + encodeURIComponent(rand) + '&action=' + action;
    Object.keys(query || {}).forEach(function (key) { url += '&' + key + '=' + encodeURIComponent(query[key]); });
    var options = {credentials: 'same-origin', cache: 'no-store', headers: {}};
    if (data !== undefined) {
      options.method = 'POST'; options.headers['Content-Type'] = 'application/json';
      options.headers['X-Kontur-CSRF'] = csrf; options.body = JSON.stringify(data);
    }
    var response = await fetch(url, options);
    var body;
    try { body = await response.json(); } catch (_) {
      throw new Error(action === 'issue' ? 'Не удалось получить результат выставления. Проверяем сохранённое состояние; повторно счёт не отправляйте.' : 'Не удалось получить данные для расчёта. Счёт этим действием не выставлялся. Попробуйте ещё раз.');
    }
    if (!response.ok) throw new Error(body.error || body.detail || 'Запрос не выполнен');
    return body;
  }
  async function run(fn, recalculate) {
    if (busy) return;
    busy = true; app.setAttribute('aria-busy', 'true');
    app.querySelectorAll('button,select,input').forEach(function (n) { n.disabled = true; });
    message('Загружаем…', 'wait');
    try { await fn(); } catch (error) { message(error.message, 'error'); }
    finally {
      busy = false; app.removeAttribute('aria-busy');
      updateControls(); if (recalculate) scheduleCalculation();
    }
  }
  function updateControls() {
    $('selection').classList.toggle('has-product',Boolean($('product').value));
    app.querySelectorAll('button,select,input').forEach(function (n) { n.disabled = busy || locked; });
    $('manual-link').querySelectorAll('button,input').forEach(function(n){n.disabled=busy || calculating || !manualAllowed;});
    $('product-toggle').disabled = busy || locked || !payerId;
    $('tariff-toggle').disabled = busy || locked || !offers.length || !catalogReady || positions.length >= 30;
    $('add-position').disabled = $('tariff-toggle').disabled;
    $('add-position').hidden = positions.length === 0;
    $('empty-composition').hidden = positions.length > 0;
    $('position-count').textContent = positions.length + ' поз.';
    $('position-count').hidden = positions.length === 0;
    $('issue').disabled = busy || locked || calculating || !quoteVersion;
    app.querySelectorAll('.quantity-stepper').forEach(function(stepper){
      var input=stepper.querySelector('input'), value=Number(input.value);
      stepper.querySelectorAll('button').forEach(function(button){
        button.disabled=busy || locked || (Number(button.dataset.step)<0 ? value<=Number(input.min || 1) : value>=Number(input.max || Infinity));
      });
    });
  }
  function quoteHint(text, error) {
    clear($('quote')); $('quote').appendChild(el('p', text, error ? 'quote-error' : 'quote-pending'));
    if (error && !locked) {
      var retry = el('button', 'Повторить расчёт', 'secondary retry-calculation'); retry.type = 'button';
      retry.addEventListener('click', function () { invalidate(); }); $('quote').appendChild(retry);
    }
  }
  function composition() {

    if (!positions.length) throw new Error('Добавьте тариф в состав счёта.');
    var tariffs = [], errors = [];
    positions.forEach(function (p) { try { tariffs.push({product_id:p.productId,tariff:p.controller.collect()}); } catch (e) { errors.push(e.message); } });
    if (errors.length) throw new Error(errors[0]);
    return {positions:tariffs};
  }
  function scheduleCalculation() {
    clearTimeout(calculateTimer);
    if (busy || locked || quoteVersion) return;
    if(lastRejection){loadingPrices(false);positions.forEach(function(p){p.controller.clearPrices(false);});quoteHint('Измените состав счёта — стоимость пересчитается автоматически.');return;}
    try { composition(); }
    catch (error) { loadingPrices(false); quoteHint(error.message); return; }
    loadingPrices(true);
    quoteHint(calculating ? 'Обновляем стоимость после изменений…' : 'Рассчитываем стоимость…');
    if (!calculating) calculateTimer = setTimeout(calculateLatest, Math.max(400, 1000 - (Date.now() - lastCalculationAt)));
  }
  async function calculateLatest() {
    if (busy || locked || calculating) return;
    var payload;
    try { payload = composition(); } catch (error) { loadingPrices(false); quoteHint(error.message); return; }
    var rev = revision; calculating = true; lastCalculationAt = Date.now(); updateControls();
    try {
      var data = await api('calculate', payload);
      if (rev !== revision || locked) return;
      showInvoice(data.invoice); quoteVersion = data.invoice.quote_version;
      clear($('quote'));
      $('quote').appendChild(el('p',new Set(positions.map(function(p){return p.productId;})).size>1?'Итого с налогами (предварительно)':'Итого с налогами','hint'));
      $('quote').appendChild(el('div', money(data.invoice.quote_amount), 'total'));
      var version = data.invoice.current;
      if (version && version.amount_without_vat !== null) $('quote').appendChild(el('p', 'Без НДС: ' + money(version.amount_without_vat) + ' · НДС: ' + money(version.vat_amount), 'hint'));
      if (!version) throw new Error('Состав изменён в другой вкладке. Дождитесь нового расчёта.');
      var roots=(version.items || []).filter(function(i){return !i.parent_id && i.kind==='position';}), offset=0;
      positions.forEach(function(p,index){
        var count=payload.positions[index].tariff.quantity || 1, group=form.quoteGroup(roots.slice(offset,offset+count),version.items);
        offset+=count;p.total.textContent=money(group.total);p.controller.applyQuote(group.root,group.items);
      });
      loadingPrices(false);
      message('Стоимость рассчитана.');
      expiryTimer = setTimeout(invalidate, 14 * 60 * 1000);
    } catch (error) {
      if (rev === revision && !locked) { loadingPrices(false); quoteVersion = null; quoteHint(error.message, true); message('Не удалось рассчитать стоимость. Состав остался в форме; счёт не выставлялся.', 'error'); }
    } finally {
      calculating = false; updateControls();
      if (rev !== revision) scheduleCalculation();
    }
  }
  function option(select, value, text) {
    var node = el('option', text); node.value = value; select.appendChild(node);
  }
  function indexScale(items) {
    (items || []).forEach(function (t) { if (t && typeof t === 'object') { scale[t.tariffId] = t; indexScale(t.modifiers); } });
  }
  function settingName(s) {
    return form.title(s);
  }
  function canChooseProduct(p){return p.availability && p.availability.available === true;}
  async function checkProducts(){
    if(availabilityRunning || !payerId || !products.length)return;
    availabilityRunning=true;
    try{
      while(true){
        var pending=products.filter(function(p){return !p.availability;}), query=$('product-search').value.trim().toLocaleLowerCase('ru');
        if(!pending.length)break;
        pending.sort(function(a,b){return Number(b.title.toLocaleLowerCase('ru').includes(query))-Number(a.title.toLocaleLowerCase('ru').includes(query));});
        var batch=pending.slice(0,8), data;
        try{data=await api('availability',undefined,{products:batch.map(function(p){return p.id;}).join(',')});}
        catch(error){batch.forEach(function(p){p.availability={available:null,reason:'Не удалось проверить доступность'};});if(!$('product-menu').hidden)renderProducts();break;}
        batch.forEach(function(p){p.availability=data.products[p.id] || {available:null,reason:'Не удалось проверить доступность'};});
        if(!$('product-menu').hidden)renderProducts();
        if(!products.some(function(p){return !p.availability;}))break;
        await new Promise(function(resolve){setTimeout(resolve,350);});
      }
    }finally{availabilityRunning=false;}
  }
  function renderProducts() {
    var query = $('product-search').value.trim().toLocaleLowerCase('ru');
    filteredProducts = products.filter(function (p) { return p.title.toLocaleLowerCase('ru').includes(query); });
    filteredProducts.sort(function(a,b){
      function rank(p){return canChooseProduct(p)?0:(!p.availability || p.availability.available===null?1:2);}
      return rank(a)-rank(b) || a.title.localeCompare(b.title,'ru');
    });
    clear($('product-options')); activeProduct = -1; $('product-search').removeAttribute('aria-activedescendant');
    filteredProducts.forEach(function (p, index) {
      var item = el('button', null, 'product-option');item.appendChild(el('span',p.title));
      var reason=!p.availability?'Проверяем доступность…':(p.availability.available===true?'':p.availability.reason);
      if(reason)item.appendChild(el('small',reason,'product-availability'));
      item.setAttribute('aria-disabled',String(!canChooseProduct(p)));
      item.classList.toggle('unavailable',!canChooseProduct(p)); item.type = 'button'; item.id = 'product-option-' + index;
      item.setAttribute('role', 'option'); item.setAttribute('aria-selected', String(p.id === $('product').value)); item.tabIndex = -1;
      item.addEventListener('click', function () { chooseProduct(p); }); $('product-options').appendChild(item);
    });
    $('product-empty').hidden = filteredProducts.length > 0;
  }
  function closeProducts(focus) {
    $('product-menu').hidden = true; $('product-toggle').setAttribute('aria-expanded', 'false');
    if (focus) $('product-toggle').focus();
  }
  function openProducts() {
    if ($('product-toggle').disabled) return;
    closeTariffs(false);
    $('product-menu').hidden = false; $('product-toggle').setAttribute('aria-expanded', 'true');
    $('product-search').value = '';products.forEach(function(p){if(p.availability && p.availability.available===null)p.availability=null;});renderProducts();checkProducts();$('product-search').focus();
  }
  function chooseProduct(p) {
    if(!canChooseProduct(p))return;
    var changed = $('product').value !== p.id;
    $('product').value = p.id; $('product-value').textContent = p.title; closeProducts(true);
    if (changed) $('product').dispatchEvent(new Event('change'));
  }
  $('product-toggle').addEventListener('click', function () { if ($('product-menu').hidden) openProducts(); else closeProducts(true); });
  $('product-toggle').addEventListener('keydown', function (event) { if (event.key === 'ArrowDown') { event.preventDefault(); openProducts(); } });
  $('product-search').addEventListener('input', renderProducts);
  $('product-search').addEventListener('keydown', function (event) {
    if (event.key === 'Escape') { event.preventDefault(); closeProducts(true); }
    if (event.key === 'Enter') { event.preventDefault(); var choice=activeProduct<0?filteredProducts.find(canChooseProduct):filteredProducts[activeProduct];if(choice)chooseProduct(choice); }
    if (event.key === 'ArrowDown' || event.key === 'ArrowUp') {
      event.preventDefault(); if (!filteredProducts.length) return;
      var availableIndexes=filteredProducts.map(function(p,i){return canChooseProduct(p)?i:-1;}).filter(function(i){return i>=0;});
      if(!availableIndexes.length)return;
      var at=availableIndexes.indexOf(activeProduct);activeProduct=availableIndexes[(at+(event.key==='ArrowDown'?1:-1)+availableIndexes.length)%availableIndexes.length];
      Array.from($('product-options').children).forEach(function (n, i) { n.classList.toggle('active', i === activeProduct); });
      var active = $('product-options').children[activeProduct]; active.scrollIntoView({block: 'nearest'}); $('product-search').setAttribute('aria-activedescendant', active.id);
    }
  });
  document.addEventListener('click', function (event) { if (!$('product-picker').contains(event.target)) closeProducts(false); });
  $('product-picker').addEventListener('focusout', function (event) { if (!event.relatedTarget || !$('product-picker').contains(event.relatedTarget)) closeProducts(false); });
  function field(input, labelText, hint) {
    var label = el('label', labelText), id = 'kontur-field-' + (++fieldSequence);
    input.id = id; label.htmlFor = id; label.appendChild(input);
    if (hint) label.appendChild(el('span', hint, 'field-hint'));
    var error = el('span', '', 'field-error'); error.id = id + '-error'; error.hidden = true;
    input.setAttribute('aria-describedby', error.id); label.appendChild(error);
    return {node:label,input:input,error:function (text) {
      error.textContent = text || ''; error.hidden = !text;
      if (text) input.setAttribute('aria-invalid','true'); else input.removeAttribute('aria-invalid');
    }};
  }
  function quantityField(input, labelText) {
    var control=field(input,labelText), wrapper=el('div',null,'quantity-field'), label=control.node, stepper=el('div',null,'quantity-stepper');
    label.removeChild(input);wrapper.appendChild(label);
    [-1,1].forEach(function(delta){
      var button=el('button',delta<0?'‹':'›','quantity-arrow');button.type='button';button.dataset.step=delta;
      button.setAttribute('aria-label',delta<0?'Уменьшить количество':'Увеличить количество');button.setAttribute('aria-controls',input.id);
      button.addEventListener('click',function(){
        var value=Number(input.value);if(!Number.isInteger(value))value=Number(input.min || 1);
        input.value=Math.max(Number(input.min || 1),Math.min(Number(input.max || Infinity),value+delta));
        input.dispatchEvent(new Event('input',{bubbles:true}));
      });
      if(delta<0){stepper.appendChild(button);stepper.appendChild(input);}else stepper.appendChild(button);
    });
    wrapper.appendChild(stepper);control.node=wrapper;
    return control;
  }
  function makeTariff(id, depth, saved, context) {
    var scale=context.scale;
    function offer(tid){return context.offers.find(function(o){return o.tariffId===tid;});}
    function name(tid){return (scale[tid] || {}).title || tid;}
    function modifiers(tid){return ((scale[tid] || {}).modifiers || []).map(function(m){return typeof m==='string'?m:m.tariffId;});}
    var spec = offer(id), node = el('div', null, 'tariff-fields'), controls = [], children = [], priceControl = null;
    if (!spec) {
      node.appendChild(el('p', 'Тариф «' + name(id) + '» больше недоступен. Удалите его или выберите другой.', 'quote-error'));
      return {node:node,collect:function () { throw new Error('В составе есть недоступный тариф'); },clearPrices:function(){},applyQuote:function(){}};
    }
    var grid = el('div', null, 'tariff-settings'); node.appendChild(grid);
    var cost = spec.initialCost || {}, quantityControl = null, resource = form.resourceSetting(spec);
    if (form.wholesale(scale[id]) && !resource) {
      var count = el('input'); count.type='number'; count.min='1'; count.max='500'; count.step='1'; count.value=saved ? saved.quantity : 1;
      quantityControl=quantityField(count,depth ? 'Количество на один основной тариф' : 'Количество');
      grid.classList.add('has-quantity');
      grid.appendChild(quantityControl.node);
      count.addEventListener('input',invalidate);count.addEventListener('change',invalidate);
    }
    if (cost.basePriceType === 2) {
      var price = el('input'); price.type = 'number'; price.min = cost.minimumCost || 0; price.step = '0.01';
      price.value = saved && saved.customPrice !== undefined ? saved.customPrice : (cost.minimumCost === undefined ? '' : cost.minimumCost);
      priceControl = field(price, 'Базовая цена, ₽', 'Минимум по тарифу: ' + money(cost.minimumCost) + '. Итог с налогами — в расчёте.');
      grid.appendChild(priceControl.node); price.addEventListener('input', invalidate);price.addEventListener('change',invalidate);
    }
    (spec.settings || []).forEach(function (setting) {
      var values = form.settingValues(setting, spec, saved);
      var input, available = setting.availableValues || [], hint = '';
      if (available.length) {
        input = el('select'); input.multiple = (setting.maxCount || 1) > 1;
        if (!input.multiple) option(input, '', 'Выберите значение');
        available.forEach(function (v) { option(input, v.id, v.title || v.id); });
        values.forEach(function (v) { if (!available.some(function(a){return String(a.id) === v;})) option(input,v,v + ' (недоступно)'); });
        Array.from(input.options).forEach(function (o) { o.selected = values.includes(o.value); });
      } else {
        input = el('input'); input.type = setting.availableValuesRange ? 'number' : 'text';
        if (setting.availableValuesRange) {
          input.step = setting.availableValuesRange.valueType === 'Int' ? '1' : 'any';
          var lower = form.bound(setting.availableValuesRange,'min'), upper = form.bound(setting.availableValuesRange,'max');
          if (lower && lower.inclusive) input.min = lower.value;
          if (upper && upper.inclusive) input.max = upper.value;
          hint = (lower ? (lower.inclusive ? 'От ' : 'Больше ') + lower.value : '') + (upper ? (lower ? ' · ' : '') + (upper.inclusive ? 'До ' : 'Меньше ') + upper.value : '');
        }
        input.value = values.join(', ');
      }
      var label = settingName(setting) + (setting.type === 'Subscription.Duration' && setting.unit === 2 ? ', мес.' : '') + ((setting.minCount || 0) > 0 ? ' *' : '');
      if (setting === resource) {
        label = id.indexOf('kf_org_1000_') === 0 ? 'Количество пакетов по 1000 организаций' : 'Количество ресурсов';
        setting=Object.assign({},setting,{name:label});
      }
      var control = setting.type === 'Resource.Multiplier' && resource && input.type === 'number' ? quantityField(input,label) : field(input,label,hint);
      if (setting.type === 'Resource.Multiplier' && resource) control.node.classList.add('resource-quantity');
      if(input.tagName==='SELECT' && !input.multiple){
        var maxLength=available.reduce(function(max,v){return Math.max(max,String(v.title || v.id).length);},0);
        if(maxLength<=18){control.node.classList.add('compact-setting');control.node.style.setProperty('--setting-width',Math.max(130,Math.min(220,maxLength*8+44))+'px');}
      }
      control.spec = setting; controls.push(control); grid.appendChild(control.node);
      input.addEventListener('change', invalidate); input.addEventListener('input', invalidate);
    });
    (saved && saved.settings || []).filter(function (s) { return !(spec.settings || []).some(function (v) { return v.type === s.type; }); }).forEach(function (setting) {
      var input = el('input'); input.type = 'text'; input.value = setting.values.join(', '); input.readOnly = true;
      var c = field(input, settingName(setting)); c.obsolete = true; controls.push(c); grid.appendChild(c.node);
    });
    if (!grid.children.length) grid.remove();
    var marks = (scale[id] || {}).additionalInfo || [];
    if (marks.some(function (m) { return m.id === 'IsNonRepayable' && String(m.value).toLowerCase() === 'true'; })) node.appendChild(el('p', 'Срок этого тарифа может начаться сразу после выставления счёта.', 'special'));
    var extras = el('details', null, 'extras'), summary = el('summary', 'Дополнения к тарифу'), selectedCount = el('span', '', 'extras-count'); summary.appendChild(selectedCount); extras.appendChild(summary);
    var ids = modifiers(id).filter(function (v) { return offer(v); });
    (saved && saved.modifiers || []).forEach(function (m) { if (!ids.includes(m.tariffId)) ids.push(m.tariffId); });
    function updateExtras() { var count = children.filter(function(c){return c.check.checked;}).length; selectedCount.textContent = count ? 'Выбрано: ' + count : ''; }
    if (depth < 5) ids.forEach(function (modifierId) {
      var stored = saved && (saved.modifiers || []).find(function (m) { return m.tariffId === modifierId; });
      var wrapper = el('div', null, 'modifier'), label = el('label', null, 'check'), check = el('input'); check.type = 'checkbox'; check.checked = Boolean(stored);check.setAttribute('aria-label',name(modifierId));
      label.appendChild(check); label.appendChild(el('span', name(modifierId)));
      var amount = el('span', '', 'modifier-amount');amount.id='kontur-extra-price-'+(++fieldSequence);amount.setAttribute('aria-live','polite');check.setAttribute('aria-describedby',amount.id);label.appendChild(amount); wrapper.appendChild(label);
      var holder = el('div'), sub = null; holder.hidden = !check.checked; wrapper.appendChild(holder); extras.appendChild(wrapper);
      function ensureSub() { if (!sub) { sub = makeTariff(modifierId, depth+1, stored, context); holder.appendChild(sub.node); } }
      if (stored) { ensureSub(); extras.open = true; }
      if (modifierId === 'kep_egais_reissue_3') {
        var help = el('p', 'До трёх смен или отзывов сертификата за его срок действия. Замена сохраняет первоначальный срок окончания. ', 'hint');
        var terms = el('a','Условия Контура'); terms.href = 'https://kontur.ru/Files/userfiles/file/products/ca-site/instruction/do-01-10-2025-sertum_price.pdf'; terms.target='_blank'; terms.rel='noopener'; help.appendChild(terms); wrapper.appendChild(help);
      }
      check.addEventListener('change',function(){ holder.hidden=!check.checked; if(check.checked)ensureSub(); updateExtras(); invalidate(); });
      children.push({id:modifierId,check:check,amount:amount,sub:function(){return sub;},collect:function(){ensureSub();return sub.collect();}});
    });
    updateExtras(); if (children.length) node.appendChild(extras);
    return {node:node,collect:function () {
      var output = {tariffId:id,quantity:1}, settings = [], errors = [];
      if (!quantityControl && !resource && saved && saved.quantity > 1) errors.push('Для сохранённого количества тариф больше недоступен');
      if (quantityControl) {
        var count=Number(quantityControl.input.value), invalidCount=!Number.isInteger(count) || count<1 || count>500;
        quantityControl.error(invalidCount ? 'Укажите целое число от 1 до 500' : null);
        if(invalidCount)errors.push('Проверьте количество тарифа «'+name(id)+'»');else output.quantity=count;
      }
      if (priceControl) {
        var value = priceControl.input.value, invalid = value === '' || !Number.isFinite(Number(value)) || Number(value) < Number(cost.minimumCost || 0);
        priceControl.error(invalid ? 'Укажите цену не ниже ' + money(cost.minimumCost) : null);
        if (invalid) errors.push('Проверьте цену тарифа «' + name(id) + '»'); else output.customPrice = value;
      }
      controls.forEach(function(c){
        if(c.obsolete){ c.error('Параметр больше не поддерживается предложением. Удалите тариф и добавьте актуальный.'); errors.push('Проверьте сохранённые параметры тарифа'); return; }
        var values = c.input.tagName === 'SELECT' ? Array.from(c.input.selectedOptions).map(function(o){return o.value;}).filter(Boolean) : (c.input.value.trim() ? [c.input.value.trim()] : []);
        var error = form.validateSetting(c.spec,values); c.error(error); if(error)errors.push(error);
        if(values.length)settings.push({type:c.spec.type,values:values});
      });
      if(settings.length)output.settings=settings;
      var selected = [];
      children.filter(function(c){return c.check.checked;}).forEach(function(c){try{selected.push(c.collect());}catch(e){errors.push(e.message);}});
      if(selected.length)output.modifiers=selected;
      if(errors.length)throw new Error(errors[0]); return output;
    },clearPrices:function(pending){
      children.forEach(function(c){c.amount.textContent=pending===false?'':(c.check.checked?'Расчёт…':'');if(c.sub())c.sub().clearPrices(pending);});
    },applyQuote:function(item,items){
      children.forEach(function(c){
        var child=items.find(function(i){return i.parent_id===item.id && i.tariff_id===c.id;});
        if(c.check.checked && child){c.amount.textContent=money(child.amount);if(c.sub())c.sub().applyQuote(child,items);}
      });
    }};
  }
  function addPosition(id, saved, productId) {
    productId=productId || $('product').value;
    var context=catalogs[productId];
    if(!context)throw new Error('Не удалось загрузить тарифы продукта');
    var product=products.find(function(p){return p.id===productId;});
    function name(tid){return (context.scale[tid] || {}).title || tid;}
    if (positions.length >= 30) { message('В одном счёте можно добавить до 30 позиций.','wait'); return; }
    var wrapper = el('div',null,'position'), top = el('div',null,'position-top'), heading = el('div');
    heading.appendChild(el('h3',name(id),'position-name'));heading.appendChild(el('span',product?product.title:productId,'position-product'));top.appendChild(heading);
    var actions = el('div',null,'position-actions'), total = el('span','—','position-price'), remove = el('button','Удалить','secondary remove'); remove.type='button'; actions.appendChild(total);actions.appendChild(remove);top.appendChild(actions);wrapper.appendChild(top);
    var controller = makeTariff(id,0,saved,context), settingsGrid=controller.node.querySelector(':scope > .tariff-settings'), quantity= settingsGrid && settingsGrid.querySelector(':scope > .quantity-field:not(.resource-quantity)');
    if(quantity){
      top.insertBefore(quantity,actions);top.classList.add('with-quantity');settingsGrid.classList.remove('has-quantity');
      if(!settingsGrid.children.length)settingsGrid.remove();
    }
    wrapper.appendChild(controller.node);
    var position = {productId:productId,total:total,controller:controller};
    remove.addEventListener('click',function(){positions=positions.filter(function(p){return p!==position;});wrapper.remove();invalidate();});
    positions.push(position); $('positions').appendChild(wrapper); invalidate();
  }
  function renderTariffs() {
    var groups = form.groups(offers,scale,$('tariff-search').value); filteredTariffs=[];activeTariff=-1;
    clear($('tariff-options')); $('tariff-search').removeAttribute('aria-activedescendant');
    groups.forEach(function(group){
      $('tariff-options').appendChild(el('div',group.title,'tariff-group'));
      group.items.forEach(function(t){
        var index=filteredTariffs.length;filteredTariffs.push(t);
        var item=el('button',t.title,'product-option tariff-option');item.id='tariff-option-'+index;item.type='button';item.setAttribute('role','option');item.tabIndex=-1;
        item.addEventListener('click',function(){closeTariffs(true);addPosition(t.id);});$('tariff-options').appendChild(item);
      });
    });
    $('tariff-empty').hidden=filteredTariffs.length>0;
  }
  function closeTariffs(focus) { $('tariff-menu').hidden=true;$('tariff-toggle').setAttribute('aria-expanded','false');if(focus)$('tariff-toggle').focus(); }
  function openTariffs() { if($('tariff-toggle').disabled)return;closeProducts(false);$('tariff-menu').hidden=false;$('tariff-toggle').setAttribute('aria-expanded','true');$('tariff-search').value='';renderTariffs();$('tariff-search').focus(); }
  $('tariff-toggle').addEventListener('click',function(){if($('tariff-menu').hidden)openTariffs();else closeTariffs(true);});
  $('tariff-toggle').addEventListener('keydown',function(e){if(e.key==='ArrowDown'){e.preventDefault();openTariffs();}});
  $('tariff-search').addEventListener('input',renderTariffs);
  $('tariff-search').addEventListener('keydown',function(e){
    if(e.key==='Escape'){e.preventDefault();closeTariffs(true);}
    if(e.key==='Enter'){e.preventDefault();var t=filteredTariffs[activeTariff<0?0:activeTariff];if(t){closeTariffs(true);addPosition(t.id);}}
    if(e.key==='ArrowDown'||e.key==='ArrowUp'){
      e.preventDefault();if(!filteredTariffs.length)return;
      activeTariff=(activeTariff+(e.key==='ArrowDown'?1:-1)+filteredTariffs.length)%filteredTariffs.length;
      Array.from($('tariff-options').querySelectorAll('[role=option]')).forEach(function(n,i){n.classList.toggle('active',i===activeTariff);});
      var active=$('tariff-option-'+activeTariff);active.scrollIntoView({block:'nearest'});$('tariff-search').setAttribute('aria-activedescendant',active.id);
    }
  });
  document.addEventListener('click',function(e){if(!$('tariff-picker').contains(e.target))closeTariffs(false);});
  $('tariff-picker').addEventListener('focusout',function(e){if(!e.relatedTarget||!$('tariff-picker').contains(e.relatedTarget))closeTariffs(false);});
  function showInvoice(invoice) {
    if (!invoice) return;
    locked = ['sending', 'created', 'unknown', 'pending'].indexOf(invoice.status) >= 0;
    if(invoice.status==='rejected')lastRejection=invoice.error;
    manualAllowed = !locked || invoice.status === 'pending';
    $('manual-link').hidden = !manualAllowed;
    if(invoice.status === 'pending'){$('manual-details').open=true;$('invoice-search').value=invoice.bill_number || ''; }
    var labels = {pending: 'Ожидаем данные счёта', draft: 'Черновик', sending: 'Создаём счёт…', created: 'Счёт создан', rejected: 'Не удалось выставить счёт', unknown: 'Создание счёта не подтверждено'};
    var box = $('result'); clear(box); box.hidden = !locked;
    $('selection').hidden = locked; $('composition').hidden = locked;
    if (!locked) return;
    box.appendChild(el('span', labels[invoice.status] || 'Статус счёта уточняется', 'pill'));
    if(invoice.status==='pending')box.appendChild(el('p','Номер сохранён. Данные счёта появятся после загрузки из Контура.','hint'));
    (invoice.warnings || []).forEach(function(text){box.appendChild(el('p',text,'manual-warning'));});
    var facts = el('div', null, 'facts'), values = [];
    if (invoice.bill_number) values.push(['Номер счёта', invoice.bill_number]);
    var confirmed = invoice.confirmed_amount !== null && invoice.confirmed_amount !== undefined;
    if (confirmed || invoice.quote_amount !== null && invoice.quote_amount !== undefined) {
      values.push([confirmed ? 'Сумма счёта' : 'Предварительная сумма', money(confirmed ? invoice.confirmed_amount : invoice.quote_amount)]);
    }
    if (invoice.billing_state !== null && invoice.billing_state !== undefined) {
      values.push(['Оплата', form.billingState(invoice.billing_state, invoice.current && invoice.current.is_non_repayable)]);
    }
    values.forEach(function (v) {
      var fact = el('p', null, 'fact'); fact.appendChild(el('small', v[0])); fact.appendChild(el('strong', v[1])); facts.appendChild(fact);
    }); box.appendChild(facts);
    if (invoice.status === 'created' && !invoice.bill_number) box.appendChild(el('p', 'Номер счёта появится позже.', 'hint'));
    if (invoice.billing_url && invoice.status!=='rejected') {
      var billing = el('a','Открыть в Биллинге Контура','billing-link'); billing.href=invoice.billing_url;billing.target='_blank';billing.rel='noopener';box.appendChild(billing);
    }
    if (locked && invoice.current) {
      var current = invoice.current;
      var savedBox=el('div',null,'readonly-composition');box.appendChild(savedBox);savedBox.appendChild(el('h2', 'Состав счёта'));
      var table = el('table'), head = el('tr');
      ['Продукт', 'Тариф', 'Количество', 'Сумма'].forEach(function (text) { head.appendChild(el('th', text)); }); table.appendChild(head);
      (current.items || []).forEach(function (item) {
        var row = el('tr'), product=products.find(function(p){return p.id===item.product_id;});row.appendChild(el('td',(current.product_names || {})[item.product_id] || (product?product.title:'Продукт Контура'))); row.appendChild(el('td', (item.parent_id ? '↳ ' : '') + (item.tariff_name || item.tariff_id)));
        var properties=(current.properties || []).filter(function(p){return p.item_id===item.id;});
        row.appendChild(el('td', form.quantityLabel(item,properties))); row.appendChild(el('td', money(item.amount))); table.appendChild(row);
      });
      savedBox.appendChild(table);
      (current.properties || []).filter(function(p){return p.property_type !== 'Resource.Multiplier';}).forEach(function (p) { var item=(current.items || []).find(function(i){return i.id===p.item_id;});savedBox.appendChild(el('p',(item ? (item.tariff_name || item.tariff_id)+' · ' : '')+settingName({type: p.property_type}) + ': ' + p.value_text, 'hint')); });
      if((current.services || []).length || (current.discounts || []).length){
        var detail=el('details'), heading=el('summary','Услуги, скидки и налоги');detail.appendChild(heading);
        (current.services || []).forEach(function(s){detail.appendChild(el('p',s.name+' · '+money(s.amount)+(s.vat_rate===null?'':' · ставка НДС '+s.vat_rate+'%'),'detail-line'));});
        (current.discounts || []).forEach(function(d){detail.appendChild(el('p',(d.title || d.key || 'Скидка')+(d.amount===null?'':' · '+money(d.amount))+(d.percent===null?'':' · '+d.percent+'%'),'detail-line'));});
        savedBox.appendChild(detail);
      }
    }
  }

  async function loadCatalog() {
    closeTariffs(false);
    catalogReady=false; offers = []; scale = {};
    $('account-status').textContent = 'Загружаем тарифы…';
    try {
      var productId=$('product').value, cached=catalogs[productId];
      var data = cached || await form.prepareCatalog(api,productId,function(){
        $('account-status').textContent = 'Подготавливаем продукт…';
        message('Подготавливаем продукт…', 'wait');
      });
      payerId = data.payer_id; catalogReady=Boolean(data.account_id || data.can_quote_without_account);
      offers = data.offers || []; if(cached)scale=cached.scale;else indexScale(data.scale);
      catalogs[productId]={payer_id:payerId,account_id:data.account_id,offers:offers,scale:scale,can_quote_without_account:data.can_quote_without_account};
      $('account-status').textContent = 'Тарифы загружены.';
      var selected=products.find(function(p){return p.id===productId;});
      if(selected)selected.availability={available:offers.length>0,reason:offers.length?'':'Нет доступных тарифов для этого клиента'};
      if (!offers.length) {
        message('Для этого клиента нет доступных тарифов выбранного продукта.', 'wait');
      } else message('Найдите тариф и добавьте его в счёт.');
    } catch (error) { $('account-status').textContent = error.message; throw error; }
  }
  $('product').addEventListener('change', function () { run(function(){return loadCatalog();}, true).then(function(){if(catalogReady && offers.length)openTariffs();}); });
  $('add-position').addEventListener('click', openTariffs);
  var invoiceSearchRevision=0, invoiceSearchTimer;
  function attachInvoice(data) {
    if(busy || calculating || !manualAllowed)return;
    clearTimeout(calculateTimer);clearTimeout(expiryTimer);invoiceSearchRevision++;
    run(async function(){
      var result=await api('attach',data);
      showInvoice(result.invoice);quoteVersion=null;
      message(result.invoice.status==='pending'?'Номер счёта сохранён. Ожидаем данные из Контура.':'Счёт Контура присоединён.');
    });
  }
  function renderInvoiceSearch(data, query) {
    var results=$('invoice-search-results');clear(results);
    var bills=data.bills || [];
    $('invoice-search-status').textContent=bills.length?'Выберите счёт для присоединения.':'Счёт не найден среди загруженных.';
    if(bills.length){
      var table=el('table'), head=el('tr');['Номер','Плательщик','Сумма',''].forEach(function(t){head.appendChild(el('th',t));});table.appendChild(head);
      bills.forEach(function(bill){
        var row=el('tr');row.appendChild(el('td',bill.number));
        var payer=el('td',bill.payer_name || 'Плательщик не указан');payer.appendChild(el('small','ИНН '+(bill.payer_inn || 'не указан'),'hint'));
        (bill.warnings || []).forEach(function(text){payer.appendChild(el('small',text,'manual-warning'));});row.appendChild(payer);row.appendChild(el('td',money(bill.amount)));
        var cell=el('td'), button=el('button',(bill.warnings || []).length?'Привязать с отличиями':'Присоединить','secondary');button.type='button';
        button.addEventListener('click',function(){attachInvoice({bill_id:bill.bill_id,confirm_mismatch:(bill.warnings || []).length>0});});cell.appendChild(button);row.appendChild(cell);table.appendChild(row);
      });results.appendChild(table);
    }
    $('save-invoice-number').hidden=!form.canSaveNumber(query,bills,app.dataset.inn);
    $('save-invoice-number').textContent='Сохранить номер «'+query+'» и дождаться данных';
    updateControls();
  }
  $('invoice-search').addEventListener('input',function(){
    var query=this.value.trim(), current=++invoiceSearchRevision;
    clearTimeout(invoiceSearchTimer);clear($('invoice-search-results'));$('save-invoice-number').hidden=true;
    if(query.length<2){$('invoice-search-status').textContent='Введите хотя бы два символа.';return;}
    $('invoice-search-status').textContent='Ищем счёт…';
    invoiceSearchTimer=setTimeout(async function(){
      try{var data=await api('search',undefined,{q:query});if(current===invoiceSearchRevision)renderInvoiceSearch(data,query);}
      catch(error){if(current===invoiceSearchRevision)$('invoice-search-status').textContent=error.message;}
    },350);
  });
  $('save-invoice-number').addEventListener('click',function(){attachInvoice({number:$('invoice-search').value.trim()});});
  $('manual-details').addEventListener('toggle',function(){
    if(!this.open)return;
    if(!$('invoice-search').value.trim())$('invoice-search').value=app.dataset.inn || '';
    if($('invoice-search').value.trim())$('invoice-search').dispatchEvent(new Event('input'));
  });
  $('issue').addEventListener('click', function () { run(async function () {
    if (!quoteVersion || calculating) throw new Error('Дождитесь расчёта стоимости');
    message('Создаём счёт…', 'wait');
    clearTimeout(calculateTimer); clearTimeout(expiryTimer);
    var data;
    try { data = await api('issue', {quote_version: quoteVersion, confirm: true}); }
    catch (error) {
      try { var saved = await api('status'); if (saved.invoice) showInvoice(saved.invoice); } catch (_) { /* preserve original failure */ }
      quoteVersion = null;
      quoteHint('Не удалось подтвердить создание счёта. Не выставляйте его повторно.', true);
      throw error;
    }
    showInvoice(data.invoice);
    if (!locked) { quoteVersion = null; quoteHint('Измените состав счёта — стоимость пересчитается автоматически.'); }
    message(form.invoiceError(data.invoice.error) || '', 'error');
  }); });
  run(async function () {
    var data = await api('state');
    payerId = data.payer ? data.payer.abonentId : null;
    products = (data.products || []).map(function (p) { return {id: p.productId, title: p.fullProductName || p.productName}; });
    products.sort(function (a, b) { return a.title.localeCompare(b.title, 'ru'); });
    showInvoice(data.invoice);
    if(!locked && payerId)checkProducts();
    if (!data.invoice && data.schet.legacy_number && data.schet.legacy_number !== '0') {
      locked = true; $('selection').hidden = true; $('composition').hidden = true;
      message('Счёт Контура № ' + data.schet.legacy_number + ' уже выставлен.', 'wait');
    } else if (locked) message(form.invoiceError(data.invoice.error) || '', 'error');
    else if (!payerId) {
      $('account-status').textContent = data.payer_error || 'Клиент Контура не определён.';
      message($('account-status').textContent, 'error');
    } else {
      var savedDraft = form.restore(data.invoice && data.invoice.current);
      if (savedDraft) {
        var restoredProducts=savedDraft.products.map(function(id){
          var product=products.find(function(p){return p.id===id;});
          if(!product)throw new Error('Продукт сохранённого счёта больше недоступен. Проверьте состав.');
          return product;
        });
        for(var product of restoredProducts){
          $('product').value=product.id;$('product-value').textContent=product.title;
          await loadCatalog();
        }
        restoringDraft=true;
        try{
          restoredProducts.forEach(function(product){
            var tariffs=savedDraft.tariffs.filter(function(t){return t.productId===product.id;});
            form.compact(tariffs,catalogs[product.id].scale).forEach(function(t){addPosition(t.tariffId,t,product.id);});
          });
        }finally{restoringDraft=false;}
        if(lastRejection)message(form.invoiceError(lastRejection),'error');
        else message('Обновляем стоимость счёта…');
      } else message('Выберите продукт Контура.');
    }
  },true);
}());
