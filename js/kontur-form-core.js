/* Общие правила отображения формы и сохранённого счёта. */
(function (root, factory) {
  if (typeof module === 'object' && module.exports) module.exports = factory();
  else root.KonturForm = factory();
}(typeof window === 'undefined' ? this : window, function () {
  'use strict';
  function marks(tariff) {
    var result = {};
    ((tariff || {}).additionalInfo || []).forEach(function (m) { result[m.id] = m.value; });
    return result;
  }
  function wholesale(tariff) { return String(marks(tariff).WholesaleAllowed).toLowerCase() === 'true'; }
  function tariffType(tariff) { return marks(tariff).TariffType || ''; }
  function title(setting) {
    return setting.name || setting.title || {'Subscription.Duration':'Длительность подписки','Resource.Multiplier':'Количество ресурсов'}[setting.type] || 'Параметр тарифа';
  }
  function groups(offers, scale, query) {
    query = (query || '').trim().toLocaleLowerCase('ru');
    var buckets = [{title:'Основные тарифы',types:['Main']},{title:'Рабочие места',types:['Workplace']},
      {title:'Прочие услуги',types:['Other']},{title:'Другие тарифы',types:[]}];
    buckets.forEach(function (b) { b.items = []; });
    (offers || []).forEach(function (o) {
      var meta = scale[o.tariffId] || {}, type = tariffType(meta), name = meta.title || o.title || o.tariffId;
      if (type === 'Modifier' || !name.toLocaleLowerCase('ru').includes(query)) return;
      var bucket = buckets.find(function (b) { return b.types.includes(type); }) || buckets[3];
      bucket.items.push({id:o.tariffId,title:name});
    });
    buckets.forEach(function (b) { b.items.sort(function (a,c) { return a.title.localeCompare(c.title,'ru'); }); });
    return buckets.filter(function (b) { return b.items.length; });
  }
  function defaultValues(setting, offer) {
    var allowed = (setting.availableValues || []).map(function (v) { return String(v.id); });
    var duration = (offer || {}).initialDuration || {}, value = null;
    if (setting.type === 'Subscription.Duration' && setting.unit === 2) {
      var months = Number(duration.months || 0) + 12 * Number(duration.years || 0);
      if (months > 0) value = String(months);
    }
    if (value !== null && allowed.includes(value)) return [value];
    if ((setting.minCount || 0) > 0 && allowed.length === 1) return allowed;
    if (setting.type === 'Resource.Multiplier' && setting.minCount === 1 && validateSetting(setting, ['1']) === null) return ['1'];
    return [];
  }
  function resourceSetting(offer) {
    return (offer.settings || []).find(function(s) { return s.type === 'Resource.Multiplier' && s.minCount === 1 && s.maxCount === 1 && s.availableValuesRange && s.availableValuesRange.valueType === 'Int'; });
  }
  function settingValues(setting, offer, saved) {
    var stored = saved && (saved.settings || []).find(function(s) { return s.type === setting.type; });
    var values = saved ? (stored ? stored.values.map(String) : []) : defaultValues(setting, offer);
    if (setting === resourceSetting(offer)) {
      if (!values.length) values = defaultValues(setting, offer);
      // Старое число одинаковых пакетов включаем в общий объём, без двух счётчиков.
      if (saved && saved.quantity > 1 && values.length === 1) values = [String(Number(values[0]) * saved.quantity)];
    }
    return values;
  }
  function bound(range, side) {
    if (!range) return null;
    var value = range[side + 'Value'];
    if (value === undefined) value = range[side];
    if (value === null || value === undefined) return null;
    return typeof value === 'object' ? {value:Number(value.limit),inclusive:value.includeLimit !== false} : {value:Number(value),inclusive:true};
  }
  function validateSetting(setting, values) {
    var name = title(setting), min = setting.minCount || 0, max = setting.maxCount === null || setting.maxCount === undefined ? 100 : setting.maxCount;
    if (values.length < min) return 'Заполните параметр «' + name + '»';
    if (values.length > max) return 'Выберите не более ' + max + ' значений';
    var allowed = (setting.availableValues || []).map(function (v) { return String(v.id); });
    if (allowed.length && values.some(function (v) { return !allowed.includes(String(v)); })) return 'Сохранённое значение больше недоступно. Выберите актуальное.';
    var range = setting.availableValuesRange;
    if (range) {
      var lower = bound(range,'min'), upper = bound(range,'max');
      for (var i=0; i<values.length; i++) {
        var v = Number(values[i]);
        if (!Number.isFinite(v)) return 'Укажите число';
        if (range.valueType === 'Int' && !Number.isInteger(v)) return 'Укажите целое число';
        if (lower && (v < lower.value || (!lower.inclusive && v === lower.value))) return 'Минимальное значение: ' + (lower.inclusive ? '' : 'больше ') + lower.value;
        if (upper && (v > upper.value || (!upper.inclusive && v === upper.value))) return 'Максимальное значение: ' + (upper.inclusive ? '' : 'меньше ') + upper.value;
      }
    }
    return null;
  }
  function restore(current) {
    if (!current) return null;
    var items = (current.items || []).filter(function (i) { return i.kind === 'position' || i.kind === 'modifier'; });
    if (!items.length) return null;
    var nodes = {}, product = current.product_id || items[0].product_id;
    items.forEach(function (i) {

      var quantity = i.quantity === null ? 1 : Number(i.quantity);
      if (!Number.isInteger(quantity) || quantity < 1 || quantity > 500) throw new Error('Некорректное сохранённое количество');
      var node = {productId:i.product_id,tariffId:i.tariff_id,quantity:quantity,settings:[],modifiers:[]};
      if (i.custom_price !== null && i.custom_price !== undefined) node.customPrice = i.custom_price;
      nodes[i.id] = node;
    });
    (current.properties || []).forEach(function (p) {
      var node = nodes[p.item_id]; if (!node) return;
      var s = node.settings.find(function (v) { return v.type === p.property_type; });
      if (!s) { s = {type:p.property_type,values:[]}; node.settings.push(s); }
      s.values.push(String(p.value_text));
    });
    var roots = [];
    items.forEach(function (i) {
      if (i.parent_id) {
        if (!nodes[i.parent_id]) throw new Error('Не удалось восстановить дополнение тарифа');
        if(nodes[i.parent_id].productId!==nodes[i.id].productId)throw new Error('Продукт дополнения не совпадает с тарифом');
        nodes[i.parent_id].modifiers.push(nodes[i.id]);
      } else roots.push(nodes[i.id]);
    });
    return {product_id:product,products:Array.from(new Set(roots.map(function(t){return t.productId;}))),tariffs:roots};
  }
  function compact(nodes, scale) {
    var result = [], keys = [];
    nodes.forEach(function (node) {
      node.modifiers = compact(node.modifiers || [],scale);
      var key = JSON.stringify(Object.assign({},node,{quantity:1})), index = keys.indexOf(key);
      if (index < 0 || !wholesale((scale || {})[node.tariffId])) { keys.push(key); result.push(node); }
      else result[index].quantity += node.quantity;
    });
    return result;
  }
  function quoteGroup(roots, items) {
    var result = [];
    function merge(group,parent) {
      var item = Object.assign({},group[0],{parent_id:parent,amount:group.reduce(function (sum,i) { return sum + Number(i.amount || 0); },0)});
      result.push(item);
      var children = items.filter(function(i) { return group.some(function(p){return i.parent_id === p.id;}); }), ids=[];
      children.forEach(function(i){if(!ids.includes(i.tariff_id))ids.push(i.tariff_id);});
      ids.forEach(function(id){merge(children.filter(function(i){return i.tariff_id === id;}),item.id);});
      return item;
    }
    var root=merge(roots,null);
    return {root:root,items:result,total:result.reduce(function(sum,i){return sum+Number(i.amount || 0);},0)};
  }
  function billingState(state, nonRepayable) {
    if (state === 2 && nonRepayable === true) return 'Безвозмездный';
    return state === null || state === undefined ? 'Ожидаем данные об оплате' : ({0:'Не оплачен',1:'Оплата отменена',2:'Оплачен',3:'Условно оплачен'}[state] || 'Статус оплаты уточняется');
  }
  function quantityLabel(item, properties) {
    var resource = (properties || []).find(function(p) { return p.property_type === 'Resource.Multiplier'; });
    var count = item.quantity === null || item.quantity === undefined ? 1 : Number(item.quantity);
    if (resource) count *= Number(resource.value_text);
    var unit = resource && (item.tariff_id || '').indexOf('kf_org_1000_') === 0 ? ' пак.' : '';
    return Number.isFinite(count) ? count.toLocaleString('ru-RU') + unit : '—';
  }
  function canSaveNumber(query, bills, inn) {
    return query !== inn && /\d/.test(query) && !/\s/.test(query) &&
      !(bills || []).some(function(bill) { return String(bill.number).toLocaleLowerCase('ru') === query.toLocaleLowerCase('ru'); });
  }
  function invoiceGroups(current) {
    var items=current.items || [], byId=new Map(items.map(function(item){return [item.id,item];}));
    function product(item) {
      var parent=byId.get(item.parent_id);
      return item.product_id || (parent && parent.product_id) || current.product_id || '';
    }
    var groups=new Map();
    items.forEach(function(item){
      var id=product(item);
      if (!groups.has(id)) groups.set(id,{id:id,title:(current.product_names || {})[id] || 'Продукт Контура',items:[]});
      groups.get(id).items.push(item);
    });
    return Array.from(groups.values());
  }
  function invoiceError(message) {
    if(typeof message==='string' && message.startsWith('Тарифы в заказе не сочетаются:'))
      return 'Эти продукты нельзя объединить в один счёт. Измените состав или выставьте отдельные счета.';
    if(typeof message==='string' && message.startsWith('Не смогли определить общего вендора по продуктам:'))
      return 'Не удалось объединить выбранные продукты в один счёт: Контур не нашёл общего продавца. Измените состав или выставьте отдельные счета.';
    return message;
  }
  async function prepareCatalog(api,product,onPrepare) {
    var data=await api('catalog',undefined,{product:product});
    if(data.needs_account && data.can_prepare_account){
      if(onPrepare)onPrepare();
      await api('account',{product_id:product});
      data=await api('catalog',undefined,{product:product});
    }
    if(!data.account_id && !data.can_quote_without_account)throw new Error(data.account_message || 'Лицевой счёт пока не подтверждён. Повторите проверку позже.');
    return data;
  }
  return {invoiceError:invoiceError,compact:compact,wholesale:wholesale,quoteGroup:quoteGroup,groups:groups,invoiceGroups:invoiceGroups,defaultValues:defaultValues,resourceSetting:resourceSetting,settingValues:settingValues,validateSetting:validateSetting,bound:bound,title:title,restore:restore,billingState:billingState,quantityLabel:quantityLabel,canSaveNumber:canSaveNumber,prepareCatalog:prepareCatalog};
}));
