'use strict';
const assert = require('node:assert/strict');
const fs = require('node:fs');
const path = require('node:path');
const vm = require('node:vm');
const source = fs.readFileSync(path.join(__dirname, '../tablschetosn.php'), 'utf8');
const script = source.match(/<script>\s*(\(function \(\$\) \{[\s\S]*?\}\(jQuery\)\);)\s*<\/script>/)[1];
let handler, removed = 0, hidden = 0;
const messages = [], document = {};
const jquery = target => target === document ? {
  off(name) { assert.equal(name, 'ajaxError.vooviInvoiceRefusal'); removed++; return this; },
  on(name, callback) { assert.equal(name, 'ajaxError.vooviInvoiceRefusal'); handler = callback; }
} : {hide() { assert.equal(target, '#modal-shadowkube, #kube'); hidden++; }};
const context = {jQuery: jquery, document, window: {alert: text => messages.push(text)}};
vm.runInNewContext(script, context);
vm.runInNewContext(script, context);
assert.equal(removed, 2); // Повтор загрузки фрагмента не накапливает обработчики.
handler(null, {status: 400, responseJSON: {error: 'К счёту привязаны товары.'}}, {url: 'otkazschet.php'});
assert.equal(messages.pop(), 'К счёту привязаны товары.');
handler(null, {status: 400, responseText: '{"error":"Обновите форму"}'}, {url: '/otkazschet.php?rand=fixture'});
assert.equal(messages.pop(), 'Обновите форму');
handler(null, {status: 403, responseText: '<html>technical details</html>'}, {url: 'otkazschet.php'});
assert.match(messages.pop(), /войдите в Voovi/);
handler(null, {status: 0, responseText: 'private technical error'}, {url: 'otkazschet.php'});
assert.equal(messages.pop(), 'Не удалось получить результат отказа. Обновите список, прежде чем повторять действие.');
assert.equal(hidden, 4);
handler(null, {status: 500}, {url: 'other.php'});
assert.equal(messages.length, 0);
assert.equal(hidden, 4);
console.log('Invoice refusal UI: safe errors, uncertain response and fragment reload — OK');
