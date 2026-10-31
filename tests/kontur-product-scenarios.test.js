'use strict';
const assert = require('node:assert/strict');
const form = require('../js/kontur-form-core.js');

// Формат снимка совпадает с ответом API; цены служат только тестовыми данными.
const wholesale = {additionalInfo:[{id:'WholesaleAllowed',value:'True'}]};
const duration = {property_type:'Subscription.Duration',value_text:'6'};
const cases = [
  {name:'КЭП и сопровождение',product:'KepEgais',units:1,main:'2500.00',modifier:'1999.99',total:4499.99},
  {name:'Две КЭП с сопровождением',product:'KepEgais',units:2,main:'2500.00',modifier:'1999.99',total:8999.98},
  {name:'Тринадцать носителей',product:'SoftCarriers',units:13,main:'2400.00',modifier:null,total:31200},
];
for (const scenario of cases) {
  const items=[], properties=[];
  for(let i=0;i<scenario.units;i++){
    const id=i*2+1;
    items.push({id,parent_id:null,kind:'position',product_id:scenario.product,tariff_id:'main',
      quantity:'1.00000000',custom_price:null,amount:scenario.main});
    if(scenario.product==='KepEgais')properties.push({...duration,item_id:id});
    if(scenario.modifier!==null)items.push({id:id+1,parent_id:id,kind:'modifier',product_id:scenario.product,
      tariff_id:'support',quantity:'1.00000000',custom_price:null,amount:scenario.modifier});
  }
  const restored=form.restore({product_id:scenario.product,items,properties});
  const grouped=form.compact(restored.tariffs,{main:wholesale});
  assert.equal(grouped.length,1,scenario.name);
  assert.equal(grouped[0].quantity,scenario.units,scenario.name);
  const quote=form.quoteGroup(items.filter(i=>i.kind==='position'),items);
  assert.equal(Number(quote.total.toFixed(2)),scenario.total,scenario.name);
  if(scenario.modifier!==null){
    assert.equal(grouped[0].modifiers.length,1);
    assert.equal(grouped[0].modifiers[0].quantity,1);
    assert.deepEqual(grouped[0].settings,[{type:'Subscription.Duration',values:['6']}]);
  }
}

// Одинаковые коды тарифов разных продуктов не объединяются при восстановлении.
const mixed={product_id:null,items:[
  {id:1,parent_id:null,kind:'position',product_id:'KepEgais',tariff_id:'main',quantity:1,custom_price:null},
  {id:2,parent_id:1,kind:'modifier',product_id:'KepEgais',tariff_id:'support',quantity:1,custom_price:null},
  {id:3,parent_id:null,kind:'position',product_id:'Diadoc',tariff_id:'main',quantity:1,custom_price:null},
  {id:4,parent_id:null,kind:'position',product_id:'Ofd',tariff_id:'consulting',quantity:1,custom_price:'1234.56'},
],properties:[{...duration,item_id:1}]};
const restored=form.restore(mixed);
assert.deepEqual(restored.products,['KepEgais','Diadoc','Ofd']);
const compacted=form.compact(restored.tariffs,{main:wholesale,consulting:wholesale});
assert.equal(compacted.length,3);
assert.equal(compacted[1].productId,'Diadoc');
assert.equal(compacted[2].customPrice,'1234.56');
assert.equal(compacted[0].modifiers[0].tariffId,'support');

// После исправления состава удалённый продукт и его свойства не возвращаются.
const remaining=form.restore({...mixed,items:mixed.items.filter(i=>i.product_id==='Ofd')});
assert.deepEqual(remaining.products,['Ofd']);
assert.deepEqual(remaining.tariffs[0].settings,[]);
assert.deepEqual(remaining.tariffs[0].modifiers,[]);
assert.match(form.invoiceError('Не смогли определить общего вендора по продуктам: KepEgais,Diadoc'),/Измените состав/);
// В одном счёте группируем тарифы и дополнения; учётная строка другого продукта не теряется.
const groupedInvoice={product_names:{KepEgais:'КЭП.АлкоЕГАИС',Diadoc:'Диадок',Ofd:'Контур.ОФД'},items:[
  {id:1,parent_id:null,kind:'position',product_id:'KepEgais',amount:'2500.00'},
  {id:2,parent_id:null,kind:'position',product_id:'Diadoc',amount:'100.00'},
  {id:3,parent_id:1,kind:'modifier',product_id:'KepEgais',amount:'1999.99'},
  {id:4,parent_id:null,kind:'accounting_tariff',product_id:'KepEgais'},
  {id:5,parent_id:null,kind:'accounting_tariff',product_id:'Ofd'},
  {id:6,parent_id:2,kind:'modifier',amount:'50.00'},
]};
const beforeGrouping=JSON.stringify(groupedInvoice);
const displayGroups=form.invoiceGroups(groupedInvoice);
assert.deepEqual(displayGroups.map(g=>[g.id,g.title,g.items.map(i=>i.id)]),[
  ['KepEgais','КЭП.АлкоЕГАИС',[1,3,4]],['Diadoc','Диадок',[2,6]],['Ofd','Контур.ОФД',[5]],
]);
assert.equal(JSON.stringify(groupedInvoice),beforeGrouping);
assert.deepEqual(form.invoiceGroups({}),[]);
assert.equal(form.invoiceGroups({items:[{id:1,product_id:'Unknown'}]})[0].title,'Продукт Контура');
console.log('Product scenarios: quantities, modifiers, mixed products, open price, draft recovery — OK');
