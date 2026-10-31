// Сохранённые реквизиты поставщика не подменяют документ подтверждённой связи.
const assert = require('node:assert/strict');
const fs = require('node:fs');
const path = require('node:path');
const vm = require('node:vm');

class Element {
  constructor(tag) { this.tagName=tag;this.children=[];this.listeners={};this.attributes={};this._text=''; }
  set textContent(value) { this._text=String(value);this.children=[]; }
  get textContent() { return this._text+this.children.map(child=>child.textContent).join(' '); }
  appendChild(child) { this.children.push(child);child.parentNode=this;return child; }
  setAttribute(key,value) { this.attributes[key]=value; }
  removeAttribute(key) { delete this.attributes[key]; }
  addEventListener(name,callback) { this.listeners[name]=callback; }
  remove() { this.parentNode.children=this.parentNode.children.filter(child=>child!==this); }
  querySelector(selector) {
    for (const child of this.children) {
      if (selector[0]==='.' ? (child.className || '').split(' ').includes(selector.slice(1)) : child.tagName===selector) return child;
      const found=child.querySelector(selector);if (found) return found;
    }
    return null;
  }
}

async function load(invoice,error,lookup=false) {
  const card=new Element('details'),summary=card.appendChild(new Element('summary'));
  const status=summary.appendChild(new Element('span'));status.className='kontur-card-status';
  const link=summary.appendChild(new Element('a'));link.className='kontur-card-billing';link.href='https://billy-partners.kontur.ru/billinfo/old';
  const content=card.appendChild(new Element('div'));content.className='kontur-card-content';
  const header=new Element('span');header.textContent='old-number';
  const headerLabel=new Element('b');headerLabel.textContent='Счёт поставщика';
  const add=new Element('div');add.hidden=false;
  add.appendChild(new Element('a')).textContent='Добавить счёт Контура';
  add.appendChild(new Element('span'));
  card.dataset={rand:'legacy-group',legacy:JSON.stringify({bill_number:'old-number',confirmed_amount:100,billing_state:2})};
  if (lookup) card.dataset.lookup='1';
  card.hidden=lookup;
  card.open=true;
  const calls=[];
  vm.runInNewContext(fs.readFileSync(path.join(__dirname,'../js/kontur-card.js'),'utf8'),{
    document:{getElementById:id=>({'kontur-invoice-card':card,'kontur-header-reference':header,
      'kontur-header-label':headerLabel,'kontur-invoice-add':add})[id] || null,createElement:tag=>new Element(tag)},
    window:{KonturForm:{invoiceGroups:current=>current.groups || [],quantityLabel:()=>1,
      invoiceError:value=>value,billingState:state=>state===2?'Оплачен':'Не оплачен'}},
    fetch:async(url,options)=>{calls.push({url,options});return {ok:!error,json:async()=>({invoice,error})};},
    encodeURIComponent
  });
  if (!lookup) card.listeners.toggle();
  await new Promise(resolve=>setImmediate(resolve));
  return {card,content,header,headerLabel,add,status,calls};
}

(async function () {
  const current={status:'created',bill_number:'new-number',billing_state:0,confirmed_amount:200,
    billing_url:'https://billy-partners.kontur.ru/billinfo/new',
    current:{groups:[{title:'Новый продукт',items:[{tariff_name:'Новый тариф',amount:200}]}]}};
  const updated=await load(current);
  assert.equal(updated.calls.length,1);
  assert.match(updated.calls[0].url,/action=status/);
  assert.equal(updated.calls[0].options.cache,'no-store');
  assert.match(updated.content.textContent,/Новый тариф/);
  assert.match(updated.content.textContent,/Оплата: Не оплачен/);
  assert.equal(updated.header.textContent,'new-number');
  assert.equal(updated.header.querySelector('a').href,current.billing_url);
  assert.equal(updated.card.querySelector('.kontur-card-billing').href,current.billing_url);
  updated.card.listeners.toggle();
  await new Promise(resolve=>setImmediate(resolve));
  assert.equal(updated.calls.length,1);

  const pending=await load({status:'pending',bill_number:'future-number',billing_state:null,confirmed_amount:null});
  assert.equal(pending.card.querySelector('.kontur-card-billing'),null);
  assert.equal(pending.header.querySelector('a'),null);
  assert.match(pending.status.textContent,/Ожидаем данные счёта/);
  assert.doesNotMatch(pending.content.textContent,/Оплата:/);

  const missing=await load(null);
  assert.match(missing.content.textContent,/больше не привязан/);
  assert.doesNotMatch(missing.content.textContent,/Показаны сохранённые сведения Voovi|Оплата:/);
  assert.ok(missing.content.querySelector('button'));

  const failed=await load(null,'Нет доступа');
  assert.match(failed.content.textContent,/Нет доступа/);
  assert.doesNotMatch(failed.content.textContent,/Оплата: Оплачен/);
  assert.ok(failed.content.querySelector('button'));
  const discovered=await load(current,null,true);
  assert.equal(discovered.calls.length,1);
  assert.equal(discovered.card.hidden,false);
  assert.equal(discovered.add.hidden,true);
  assert.equal(discovered.headerLabel.textContent,'Контур');
  assert.match(discovered.content.textContent,/Новый тариф/);
  discovered.card.listeners.toggle();
  await new Promise(resolve=>setImmediate(resolve));
  assert.equal(discovered.calls.length,1,'Раскрытие распознанного старого счёта повторило запрос');
  for (const error of [null,'Недоступен локальный API']) {
    const foreign=await load(null,error,true);
    assert.equal(foreign.calls.length,1);
    assert.equal(foreign.card.hidden,true);
    assert.equal(foreign.add.hidden,false);
    assert.equal(foreign.headerLabel.textContent,'Счёт поставщика');
    assert.equal(foreign.header.textContent,'old-number');
    assert.equal(foreign.add.querySelector('a').textContent,'Добавить счёт Контура');
    assert.doesNotMatch(foreign.content.textContent,/больше не привязан|Ожидаем|Ошибка|Недоступен/);
  }
  for (const status of ['pending','draft','rejected']) {
    const editable=await load({status,bill_number:'future-number'},null,true);
    assert.equal(editable.card.hidden,true);
    assert.equal(editable.add.hidden,false);
    assert.equal(editable.add.querySelector('a').textContent,'Открыть счёт Контур');
    assert.match(editable.add.querySelector('span').textContent,/Ожидаем|Черновик|Не удалось/);
    assert.equal(editable.headerLabel.textContent,'Контур');
  }
  console.log('Kontur card requires its saved link and preserves explicit errors: OK');
}()).catch(error=>{console.error(error);process.exitCode=1;});
