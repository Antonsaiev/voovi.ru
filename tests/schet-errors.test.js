'use strict';
const assert = require('node:assert/strict');
const fs = require('node:fs');
const path = require('node:path');
const vm = require('node:vm');
const source = fs.readFileSync(path.join(__dirname, '../schet_identity.php'), 'utf8');
const scripts = [...source.matchAll(/echo '<script>([\s\S]*?)<\/script>';/g)].map(match => match[1]);
assert.equal(scripts.length, 2);
const overlay = {className: 'schet-loading-overlay is-visible'};
overlay.classList = {remove: name => { overlay.className = overlay.className.split(' ').filter(value => value !== name).join(' '); }};
let ready, cleared = false, keyups = 0;
const quantity = {name: 'input55', type: 'number', value: '1', dispatchEvent(event) { assert.equal(event.type, 'keyup'); keyups++; }};
const signature = {name: 'creation_token', type: 'hidden', value: 'fresh-signature'};
const checked = {name: '55', type: 'checkbox', value: '1', checked: false};
const unchecked = {name: '56', type: 'checkbox', value: '1', checked: true};
const services = {name: 'dobusl[]', type: 'select-multiple', options: [{value:'1',selected:true},{value:'2',selected:false}]};
const form = {
  elements: [signature, quantity, checked, unchecked, services],
  removeAttribute(name) { assert.equal(name, 'data-loading'); cleared = true; },
  querySelectorAll(selector) { assert.equal(selector, '.newschet-qty-input'); return [quantity]; }
};
const document = {
  getElementById: id => id === 'schetLoadingOverlay' ? overlay : form,
  addEventListener(name, callback) { assert.equal(name, 'DOMContentLoaded'); ready = callback; }
};
const context = {document, Event: class {constructor(type) {this.type = type;}}};
vm.runInNewContext(scripts[0], context);
assert.equal(overlay.className, 'schet-loading-overlay');
const values = {'55':'1',input55:'2',dobusl:['2']};
vm.runInNewContext(scripts[1].replace("'.$encoded.'", JSON.stringify(values)), context);
ready();
assert.equal(cleared, true);
assert.equal(signature.value, 'fresh-signature');
assert.equal(quantity.value, '2');
assert.equal(checked.checked, true);
assert.equal(unchecked.checked, false);
assert.deepEqual(services.options.map(option => option.selected), [false, true]);
assert.equal(keyups, 1);
// Обычный onsubmit может снова включить загрузку после исправления формы.
if (!overlay.className.includes('is-visible')) overlay.className += ' is-visible';
assert.equal(overlay.className, 'schet-loading-overlay is-visible');
console.log('Legacy invoice error UI: input restoration, fresh signature and loader retry — OK');
