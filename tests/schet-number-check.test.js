const assert = require('node:assert/strict');
const fs = require('node:fs');
const vm = require('node:vm');

let value = 'unknown';
const handlers = {};
const attrs = {};
let text = '';
let shown = false;
const calls = [];
const input = {
    length: 1, val: () => value,
    attr(name, next) { if (next === undefined) return attrs[name]; attrs[name] = next; return this; },
    addClass() { return this; }, removeClass() { return this; },
    on(name, handler) { handlers[name] = handler; return this; },
    closest() { throw Error('Проверка номера не должна блокировать форму'); }
};
const warning = {
    length: 1, hide() { shown = false; return this; }, show() { shown = true; return this; },
    text(next) { text = next; return this; }, removeClass() { return this; }
};
function $(selector) {
    if (typeof selector === 'function') return selector();
    return selector === '#nomerschetks' ? input : warning;
}
$.trim = value => value.trim();
$.ajax = () => {
    const callbacks = {abort() {}};
    callbacks.done = fn => { callbacks.success = fn; return callbacks; };
    callbacks.fail = fn => { callbacks.failure = fn; return callbacks; };
    calls.push(callbacks);
    return callbacks;
};
vm.runInNewContext(fs.readFileSync(__dirname + '/../js/schet-number-check.js', 'utf8'), {
    jQuery: $, window: {setTimeout(fn) { fn(); return 1; }, clearTimeout() {}}
});
calls[0].failure();
assert.equal(attrs['aria-busy'], 'false');
assert.equal(shown, false, 'Недоступность поиска не должна создавать ошибку формы');
value = 'another-unknown';
handlers.input();
calls[1].success({exists: false});
assert.equal(shown, false);
value = 'known';
handlers['change blur']();
calls[2].success({exists: true});
assert.equal(shown, true);
assert.match(text, /можете продолжить сохранение/i);
console.log('Invoice number lookup is advisory and never blocks saving: OK');
