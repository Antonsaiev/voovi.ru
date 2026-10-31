/* Просмотр сохранённого счёта внутри карточки Voovi, без запросов в Контур. */
(function () {
  'use strict';
  var card=document.getElementById('kontur-invoice-card');
  if (!card) return;
  var content=card.querySelector('.kontur-card-content');
  var loaded=false, loading=false, form=window.KonturForm;
  function el(tag,text,cls) {
    var node=document.createElement(tag);
    if (text !== undefined && text !== null) node.textContent=text;
    if (cls) node.className=cls;
    return node;
  }
  function money(value) {
    if (value === null || value === undefined || value === '') return '—';
    var amount=Number(String(value).replace(/\s/g,'').replace(',','.'));
    return Number.isFinite(amount)?amount.toLocaleString('ru-RU',{minimumFractionDigits:2,maximumFractionDigits:2})+' ₽':'—';
  }
  function render(invoice) {
    content.textContent='';
    var headerLabel=document.getElementById('kontur-header-label');
    if (headerLabel) headerLabel.textContent='Контур';
    var statuses={created:'Счёт создан',pending:'Ожидаем данные счёта',draft:'Черновик',sending:'Создаём счёт…',unknown:'Создание счёта не подтверждено',rejected:'Не удалось выставить счёт'};
    card.querySelector('.kontur-card-status').textContent=(statuses[invoice.status] || 'Счёт Контура')+(invoice.bill_number?' · № '+invoice.bill_number:'');
    var expandable=['created','sending','unknown'].indexOf(invoice.status)!==-1;
    card.hidden=!expandable;
    var add=document.getElementById('kontur-invoice-add');
    if (add) {
      add.hidden=expandable;
      if (!expandable) {
        add.querySelector('a').textContent='Открыть счёт Контур';
        add.querySelector('span').textContent=card.querySelector('.kontur-card-status').textContent;
      }
    }
    var validUrl=invoice.billing_url && /^https:\/\/billy-partners\.kontur\.ru\//.test(invoice.billing_url);
    var reference=document.getElementById('kontur-header-reference');
    if (reference) {
      reference.textContent='';
      if (invoice.bill_number && validUrl) {
        var number=el('a',invoice.bill_number);number.href=invoice.billing_url;number.target='_blank';number.rel='noopener';reference.appendChild(number);
      } else reference.textContent=invoice.bill_number || '—';
    }
    var confirmed=invoice.confirmed_amount !== null && invoice.confirmed_amount !== undefined;
    var hasTotal=confirmed || invoice.quote_amount !== null && invoice.quote_amount !== undefined;
    if (invoice.billing_state !== null && invoice.billing_state !== undefined) {
      content.appendChild(el('p','Оплата: '+form.billingState(invoice.billing_state,invoice.current && invoice.current.is_non_repayable),'kontur-card-note'));
    }
    (invoice.warnings || []).forEach(function(text){content.appendChild(el('p',text,'kontur-card-error'));});
    if (invoice.error) content.appendChild(el('p',form.invoiceError(invoice.error),'kontur-card-error'));
    var current=invoice.current || {}, groups=form.invoiceGroups(current), propertiesByItem={};
    (current.properties || []).forEach(function(p){(propertiesByItem[p.item_id] || (propertiesByItem[p.item_id]=[])).push(p);});
    if (groups.length || hasTotal) {
      var wrapper=el('div',null,'kontur-card-table'), table=el('table'), head=el('thead'), heading=el('tr');
      ['Тариф или услуга','Количество','Сумма'].forEach(function(title){heading.appendChild(el('th',title));});head.appendChild(heading);table.appendChild(head);
      groups.forEach(function(group){
        var body=el('tbody'), productRow=el('tr',null,'kontur-card-product'), productTitle=el('th',group.title);productTitle.colSpan=3;productTitle.scope='rowgroup';productRow.appendChild(productTitle);body.appendChild(productRow);
        group.items.forEach(function(item){
          var row=el('tr'), name=el('td',(item.parent_id?'↳ ':'')+(item.tariff_name || 'Тариф'));
          var properties=propertiesByItem[item.id] || [];
          properties.filter(function(p){return p.property_type !== 'Resource.Multiplier';}).forEach(function(p){
            name.appendChild(el('small',form.title({type:p.property_type})+': '+p.value_text,'kontur-card-note'));
          });
          row.appendChild(name);row.appendChild(el('td',form.quantityLabel(item,properties)));row.appendChild(el('td',money(item.amount)));body.appendChild(row);
        });
        table.appendChild(body);
      });
      if (!groups.length) {
        var body=el('tbody'), empty=el('tr'), emptyCell=el('td','Состав этого счёта не сохранён в Voovi.');emptyCell.colSpan=3;empty.appendChild(emptyCell);body.appendChild(empty);table.appendChild(body);
      }
      if (hasTotal) {
        var foot=el('tfoot'), total=el('tr'), title=el('th','Итого');title.colSpan=2;title.scope='row';
        total.appendChild(title);total.appendChild(el('td',money(confirmed?invoice.confirmed_amount:invoice.quote_amount)));foot.appendChild(total);table.appendChild(foot);
      }
      wrapper.appendChild(table);content.appendChild(wrapper);
    } else content.appendChild(el('p','Состав этого счёта не сохранён в Voovi.','kontur-card-note'));
    var link=card.querySelector('.kontur-card-billing');
    if (validUrl) {
      if (!link) { link=el('a','Открыть в Биллинге Контура','kontur-card-billing');card.querySelector('summary').appendChild(link); }
      link.href=invoice.billing_url;link.target='_blank';link.rel='noopener';
    } else if (link) link.remove();
  }
  async function load(lookup) {
    if (loading) return;
    loading=true;content.textContent='Загружаем счёт…';content.setAttribute('aria-busy','true');
    try {
      // Показываем сохранённую связь: данные могли дополниться с момента открытия страницы.
      var response=await fetch('/kontur_api.php?rand='+encodeURIComponent(card.dataset.rand)+'&action=status',{credentials:'same-origin',cache:'no-store'});
      var data=await response.json();
      if (!response.ok) throw new Error(data.error || 'Не удалось загрузить счёт. Попробуйте ещё раз.');
      var invoice=data.invoice;
      if (!invoice && lookup) return;
      if (!invoice) throw new Error('Счёт Контура больше не привязан. Обновите страницу.');
      render(invoice);loaded=true;
    } catch(error) {
      // Произвольный номер поставщика разрешён; неудача локального поиска не является ошибкой счёта.
      if (lookup) return;
      content.textContent='';content.appendChild(el('p',error.message,'kontur-card-error'));
      var retry=el('button','Повторить загрузку','btn btn-default');retry.type='button';retry.addEventListener('click',function(){load();});content.appendChild(retry);
    } finally { loading=false;content.removeAttribute('aria-busy'); }
  }
  card.addEventListener('toggle',function(){
    if (card.open && !loaded) load();
  });
  // Старые PHP-формы сохраняют только номер: общий API распознаёт его по локальной базе.
  if (card.dataset.lookup === '1') load(true);
}());
