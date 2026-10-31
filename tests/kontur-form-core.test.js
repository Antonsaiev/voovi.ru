'use strict';
const assert = require('node:assert/strict');
const core = require('../js/kontur-form-core.js');
const duration = {type:'Subscription.Duration',name:'Длительность',unit:2,minCount:1,maxCount:1,availableValues:[{id:'6'},{id:'12'},{id:'15'}]};
assert.deepEqual(core.defaultValues(duration,{initialDuration:{months:6}}),['6']);
assert.deepEqual(core.defaultValues(duration,{initialDuration:{years:1,months:3}}),['15']);
assert.deepEqual(core.defaultValues(duration,{}),[]);
assert.deepEqual(core.defaultValues({...duration,availableValues:[{id:'12'}]},{}),['12']);
assert.equal(core.validateSetting(duration,['18']),'Сохранённое значение больше недоступно. Выберите актуальное.');
assert.equal(core.validateSetting(duration,['15']),null);
const range={type:'Resource.Multiplier',minCount:1,maxCount:1,availableValuesRange:{valueType:'Int',min:{limit:'1',includeLimit:true},max:{limit:'10',includeLimit:false}}};
assert.equal(core.validateSetting(range,['1']),null);
assert.ok(core.validateSetting(range,['10']));
assert.ok(core.validateSetting(range,['1.5']));
assert.ok(core.validateSetting(range,[]));
// Два старых счётчика сводятся в один объём; срок и другие параметры не умножаются.
const resourceOffer={settings:[range]};
assert.equal(core.resourceSetting(resourceOffer),range);
assert.deepEqual(core.settingValues(range,resourceOffer,null),['1']);
assert.deepEqual(core.settingValues(range,resourceOffer,{quantity:1,settings:[]}),['1']);
assert.deepEqual(core.settingValues(range,resourceOffer,{quantity:3,settings:[{type:range.type,values:['2']}]}),['6']);
assert.deepEqual(core.settingValues(duration,{settings:[duration]},{quantity:3,settings:[{type:duration.type,values:['12']}]}),['12']);
const tooMany=core.settingValues(range,resourceOffer,{quantity:3,settings:[{type:range.type,values:['4']}]});
assert.ok(core.validateSetting(range,tooMany));
assert.deepEqual(core.defaultValues({...range,availableValuesRange:{...range.availableValuesRange,min:{limit:'2',includeLimit:true}}},{}),[]);
assert.equal(core.resourceSetting({settings:[duration]}),undefined);
const index={a:{title:'Основной',additionalInfo:[{id:'TariffType',value:'Main'}]},b:{title:'Модификатор',additionalInfo:[{id:'TariffType',value:'Modifier'}]},c:{title:'Услуга',additionalInfo:[{id:'TariffType',value:'Other'}]}};
assert.deepEqual(core.groups([{tariffId:'a'},{tariffId:'b'},{tariffId:'c'}],index,'').map(g=>g.items.map(i=>i.id)),[['a'],['c']]);
assert.equal(core.groups([{tariffId:'a'},{tariffId:'c'}],index,'УСЛУ')[0].items[0].id,'c');
const saved={product_id:'KepEgais',items:[{id:1,parent_id:null,kind:'position',product_id:'KepEgais',tariff_id:'main',quantity:'1.00000000',custom_price:null},{id:2,parent_id:1,kind:'modifier',product_id:'KepEgais',tariff_id:'extra',quantity:'1.00000000',custom_price:'200.00'},{id:3,kind:'accounting_tariff'}],properties:[{item_id:1,property_type:'Subscription.Duration',value_text:'15'},{item_id:2,property_type:'Option',value_text:'value'}]};
const restored=core.restore(saved);
assert.equal(restored.tariffs[0].settings[0].values[0],'15');
assert.equal(restored.tariffs[0].modifiers[0].customPrice,'200.00');
assert.equal(restored.tariffs.length,1);
assert.deepEqual(core.defaultValues(duration,{initialDuration:{months:6}}),['6']);
assert.equal(core.billingState(2,true),'Безвозмездный');
assert.equal(core.billingState(2,false),'Оплачен');
assert.equal(core.restore({...saved,items:[{...saved.items[0],quantity:2}]}).tariffs[0].quantity,2);
console.log('Kontur form presentation rules: OK');
(async function(){
  const calls=[];
  const prepared=await core.prepareCatalog(async(action,body,query)=>{
    calls.push({action,body,query});
    if(calls.length===1)return {needs_account:true,can_prepare_account:true,account_id:null};
    if(action==='account')return {account_id:'account'};
    return {account_id:'account',offers:[{tariffId:'tariff'}]};
  },'AlcoReport');
  assert.equal(prepared.account_id,'account');
  assert.deepEqual(calls.map(c=>c.action),['catalog','account','catalog']);
  assert.equal(calls[1].body.product_id,'AlcoReport');
  const existing=[];
  await core.prepareCatalog(async action=>{existing.push(action);return {account_id:'account'};},'AlcoReport');
  assert.deepEqual(existing,['catalog']);
  let rejectedCalls=0;
  await assert.rejects(core.prepareCatalog(async()=>{rejectedCalls++;return {needs_account:true,can_prepare_account:false,account_message:'unsupported'};},'Other'),/unsupported/);
  assert.equal(rejectedCalls,1);
  const unknown=[];
  await assert.rejects(core.prepareCatalog(async action=>{unknown.push(action);if(action==='account')throw new Error('unknown');return {needs_account:true,can_prepare_account:true};},'AlcoReport'),/unknown/);
  assert.deepEqual(unknown,['catalog','account']);
  console.log('Automatic account preparation workflow: OK');
}()).catch(error=>{console.error(error);process.exitCode=1;});

const wholesaleScale={main:{additionalInfo:[{id:'WholesaleAllowed',value:'True'}]},extra:{additionalInfo:[{id:'WholesaleAllowed',value:'True'}]}};
assert.equal(core.wholesale(wholesaleScale.main),true);
assert.equal(core.wholesale({}),false);
const copied=JSON.parse(JSON.stringify(restored.tariffs));
const grouped=core.compact([...copied, ...JSON.parse(JSON.stringify(copied))],wholesaleScale);
assert.equal(grouped.length,1);assert.equal(grouped[0].quantity,2);assert.equal(grouped[0].modifiers[0].quantity,1);
assert.equal(core.compact(JSON.parse(JSON.stringify([...restored.tariffs,...restored.tariffs])),{}).length,2);
const quoteItems=[{id:1,parent_id:null,tariff_id:'main',amount:'100'},{id:2,parent_id:1,tariff_id:'extra',amount:'10'},{id:3,parent_id:null,tariff_id:'main',amount:'100'},{id:4,parent_id:3,tariff_id:'extra',amount:'10'}];
const quoteGroup=core.quoteGroup([quoteItems[0],quoteItems[2]],quoteItems);
assert.equal(quoteGroup.total,220);assert.equal(quoteGroup.root.amount,200);assert.equal(quoteGroup.items[1].amount,20);assert.equal(quoteGroup.items[1].parent_id,1);
console.log('Quantity restoration and prices: OK');

const mixedSaved={product_id:null,items:[...saved.items,{id:4,parent_id:null,kind:'position',product_id:'Other',tariff_id:'main',quantity:1}],properties:saved.properties};
const mixedRestored=core.restore(mixedSaved);
assert.deepEqual(mixedRestored.products,['KepEgais','Other']);
assert.equal(mixedRestored.tariffs[0].productId,'KepEgais');assert.equal(mixedRestored.tariffs[1].productId,'Other');
assert.equal(core.compact(mixedRestored.tariffs,wholesaleScale).length,2);
assert.throws(()=>core.restore({...saved,items:[saved.items[0],{...saved.items[1],product_id:'Other'}]}),/Продукт дополнения/);
console.log('Multiple product restoration: OK');

(async()=>{const c=await core.prepareCatalog(async()=>({account_id:null,can_quote_without_account:true}),'SoftCarriers');assert.equal(c.account_id,null);})().catch(error=>{console.error(error);process.exitCode=1;});

assert.match(core.invoiceError('Не смогли определить общего вендора по продуктам: KepEgais,Diadoc'),/Контур не нашёл общего продавца/);
assert.equal(core.invoiceError('Продукт недоступен партнеру'),'Продукт недоступен партнеру');

// Частичное совпадение номера не мешает сохранить другой, пока ещё не загруженный счёт.
assert.equal(core.canSaveNumber('s-123',[{number:'s-1234'}],'261809572092'),true);
assert.equal(core.canSaveNumber('s-123',[{number:'S-123'}],'261809572092'),false);
assert.equal(core.canSaveNumber('261809572092',[],'261809572092'),false);
assert.equal(core.canSaveNumber('Тестовый ИП',[],'261809572092'),false);
assert.equal(core.canSaveNumber('s-123',[],'261809572092'),true);

// Количество в обоих представлениях учитывает ресурс тарифа и сохраняет нулевое значение.
assert.equal(core.quantityLabel({quantity:0},[]),'0');
assert.equal(core.quantityLabel({quantity:null},[]),'1');
assert.equal(core.quantityLabel({quantity:2},[{property_type:'Resource.Multiplier',value_text:'3'}]),'6');
assert.equal(core.quantityLabel({tariff_id:'kf_org_1000_test',quantity:1},[{property_type:'Resource.Multiplier',value_text:'3'}]),'3 пак.');
assert.equal(core.quantityLabel({quantity:1},[{property_type:'Resource.Multiplier',value_text:'invalid'}]),'—');
console.log('Manual search and saved quantity presentation: OK');
