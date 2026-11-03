const test = require('node:test');
const assert = require('node:assert/strict');
const {createSaver} = require('../js/invoice-fields-autosave.js');
const tick = () => new Promise(resolve => setImmediate(resolve));
test('serializes typing and closing payload cannot be overwritten by older save', async () => {
    const requests = [], completions = [];
    const saver = createSaver({initialValue:'old', expected:['old'], send: payload => {
        requests.push(payload); return new Promise(resolve => completions.push(resolve));
    }});
    saver.set('first'); saver.flush(); saver.set('final'); saver.flush();
    assert.equal(requests.length,1);
    assert.deepEqual(saver.closingPayload(),{value:'final',expected:['old','first']});
    completions[0](); await tick();
    assert.deepEqual(requests[1],{value:'final',expected:['first']});
    completions[1](); await tick(); assert.equal(saver.closingPayload(),null);
});
test('clearing and returning to initial value while saving are preserved', async () => {
    const requests=[], done=[];
    const saver=createSaver({initialValue:'old',expected:['old'],send:p=>{requests.push(p); return new Promise(r=>done.push(r));}});
    saver.set('changed'); saver.flush(); saver.set('old');
    assert.equal(saver.closingPayload().value,'old');
    done[0]();await tick();assert.equal(requests[1].value,'old');
    done[1]();await tick();saver.set('');saver.flush();assert.equal(requests[2].value,'');
});
test('failure keeps unsaved data available for retry', async () => {
    let fail=true; const statuses=[];
    const saver=createSaver({initialValue:'',expected:[''],onState:s=>statuses.push(s),send:()=>fail?Promise.reject(new Error('offline')):Promise.resolve()});
    saver.set('address');saver.flush();await tick();
    assert.equal(saver.closingPayload().value,'address');assert.equal(statuses.at(-1),'error');
    fail=false;saver.flush();await tick();assert.equal(saver.closingPayload(),null);
});
test('pagehide sends last keystroke without waiting for blur or debounce', () => {
    const {mount} = require('../js/invoice-fields-autosave.js');
    const pageEvents={}, documentEvents={}, sent=[], stored=new Map();
    const element = value => ({value,events:{},classList:{toggle(){}},addEventListener(name,handler){this.events[name]=handler;}});
    const address=element(''), owner=element('0');
    const elements={fadres:address,vladele:owner,'invoice-address-status':element(''),'invoice-owner-status':element(''),
        'invoice-fields-config':{textContent:JSON.stringify({rand:'123',user:'3',token:'test',initial:{address:[''],owner:['0']}})}};
    const fake={document:{getElementById:id=>elements[id],addEventListener:(name,fn)=>documentEvents[name]=fn},
        URLSearchParams,localStorage:{getItem:key=>stored.get(key)||null,setItem:(key,value)=>stored.set(key,value),removeItem:key=>stored.delete(key)},
        addEventListener:(name,fn)=>pageEvents[name]=fn,setTimeout:()=>1,clearTimeout(){},navigator:{sendBeacon:(url,body)=>{sent.push([url,body]);return true;}}};
    mount(fake); address.value='Последний ввод'; address.events.input(); pageEvents.pagehide();
    assert.equal(sent.length,1);assert.equal(sent[0][1].get('value'),'Последний ввод');
    assert.equal(sent[0][1].get('field'),'address');assert.ok(stored.size);
});
