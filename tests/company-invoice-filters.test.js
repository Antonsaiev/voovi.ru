'use strict';
const assert = require('node:assert/strict');
const vm = require('node:vm');
const fs = require('node:fs');
const nodes = new Map();
const doc = {};
function node(key) {
  if (!nodes.has(key)) nodes.set(key, {value:'', content:'', data:{}, events:{}, length:1});
  return nodes.get(key);
}
function $(key) {
  const n = node(key);
  return {length:n.length,
    val(value) { if (value === undefined) return n.value; n.value=value; return this; },
    data(name, value) { if (value === undefined) return n.data[name]; n.data[name]=value; return this; },
    empty() { n.content=''; return this; },
    text(value) { n.content=value; return this; },
    html(value) { n.content=value; return this; },
    hide() { return this; }, prop() { return this; }, css() { return this; },
    off(name) { delete n.events[name]; return this; },
    on(name, callback) { n.events[name]=callback; return this; }
  };
}
const requests = [], prefilters = [];
$.ajaxPrefilter = callback => prefilters.push(callback);
$.ajax = options => {
  const xhr = {aborted:false, abort() { this.aborted=true; }};
  for (const prefilter of prefilters) prefilter(options, {}, xhr);
  requests.push({options,xhr});
  return xhr;
};
node('#datestart').value='2026-12-20';
node('#datefinish').value='2027-02-10';
node('#getOrg').value='12';
node('#tip').value='2';
const root = {};
vm.runInNewContext(fs.readFileSync(__dirname+'/../js/company-invoice-filters.js','utf8'), {window:root,document:doc,Date});
root.VooviCompanyInvoiceFilters($,'3');
assert.equal(requests[0].options.data.datas,'2026-12-20');
assert.equal(requests[0].options.data.dataf,'2027-02-10');
assert.equal(requests[0].options.data.ogrn,'12');
node('#tablosn').content='old detail';
let delivered=0;
const detail=$.ajax({url:'tablschetosn.php',success() { delivered++; }});
node('#getOrg').value='10';
node('#getOrg, #tip, #datestart, #datefinish').events['change.companyInvoices']();
assert.equal(node('#tablosn').content,'');
assert.equal(detail.aborted,true);
requests[1].options.success('stale detail');
assert.equal(delivered,0);
requests[0].options.success('stale summary');
assert.notEqual(node('#tablschetog').content,'stale summary');
requests[2].options.success('current summary');
assert.equal(node('#tablschetog').content,'current summary');
const beforeReturn=requests.length;
node('#tablosn').content='source before renewal';
doc.hidden=false;
node(doc).events['visibilitychange.companyInvoices']();
assert.equal(requests.length,beforeReturn+1);
assert.equal(node('#tablosn').content,'');
// A later category request cancels the previous category, too.
const first=$.ajax({url:'tablschetosn.php',success() { delivered++; }});
$.ajax({url:'tablschetosn.php',success() { delivered++; }});
assert.equal(first.aborted,true);
// Reinitializing the fragment cannot resurrect the old summary generation.
root.VooviCompanyInvoiceFilters($,'3');
requests[2].options.success('old fragment');
assert.notEqual(node('#tablschetog').content,'old fragment');
// Invalid ranges clear details and do not issue a request.
const before=requests.length;
node('#datestart').value='2027-03-01';
node('#tablosn').content='stale';
node('#getOrg, #tip, #datestart, #datefinish').events['change.companyInvoices']();
assert.equal(requests.length,before);
assert.equal(node('#tablosn').content,'');
assert.match(node('#tablschetog').content,/корректный период/);
console.log('Company filters: initial dates, organization change, stale responses and invalid ranges — OK');
