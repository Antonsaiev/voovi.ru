(function (root, factory) {
    'use strict';
    if (typeof module === 'object' && module.exports) module.exports = factory();
    else factory().mount(root);
})(typeof window !== 'undefined' ? window : this, function () {
    'use strict';
    function createSaver(options) {
        var value=options.initialValue, saved=value, expected=options.expected.slice(), pending=null;
        function state(name, error) { if(options.onState) options.onState(name,error); }
        function payload() {
            if(value===saved && !pending) return null;
            var bases=expected.slice();
            if(pending && bases.indexOf(pending.value)<0) bases.push(pending.value);
            return {value:value,expected:bases};
        }
        function draft() { if(options.onDraft) options.onDraft(payload()); }
        function flush() {
            if(pending || value===saved) return;
            var request={value:value,expected:expected.slice()};
            pending=request; state('saving'); draft();
            options.send(request).then(function () {
                saved=request.value; expected=[saved]; pending=null; draft();
                if(value!==saved) flush(); else state('saved');
            },function (error) {
                // Ответ мог потеряться уже после записи: сохраняем возможное принятое значение.
                if(expected.indexOf(request.value)<0) expected.push(request.value);
                pending=null; draft(); state('error',error);
            });
        }
        return {set:function(next) { value=next; draft(); state(value===saved && !pending?'saved':'dirty'); },
            flush:flush,closingPayload:payload};
    }
    function mount(window) {
        var document=window.document, configElement=document.getElementById('invoice-fields-config');
        if(!configElement) return;
        var config=JSON.parse(configElement.textContent), savers=[];
        ['owner','address'].forEach(function(field) {
            var input=document.getElementById(field==='owner'?'vladele':'fadres');
            var status=document.getElementById('invoice-'+field+'-status');
            var key='voovi.invoice-fields.'+config.user+'.'+config.rand+'.'+field, timer, draft=null;
            try { draft=JSON.parse(window.localStorage.getItem(key)); } catch(ignore) {}
            if(!draft || typeof draft.value!=='string' || !Array.isArray(draft.expected)) draft=null;
            function body(payload) {
                var data=new window.URLSearchParams();
                data.set('rand',config.rand); data.set('field',field); data.set('value',payload.value);
                data.set('expected',JSON.stringify(payload.expected)); data.set('token',config.token);
                return data;
            }
            var saver=createSaver({initialValue:input.value,expected:draft?draft.expected:config.initial[field],
                send:function(payload) {
                    return window.fetch('invoice_fields.php',{method:'POST',credentials:'same-origin',body:body(payload),keepalive:true})
                        .then(function(response) {return response.json().then(function(data) {
                            if(!response.ok || !data.ok) throw new Error(data.error || 'Не удалось сохранить');
                        });});
                },
                onState:function(state,error) {
                    status.textContent={dirty:'Не сохранено',saving:'Сохраняем…',saved:'Сохранено',error:'Не удалось сохранить. Черновик оставлен в этом браузере.'}[state];
                    if(error && error.message && error.name!=='TypeError') status.textContent=error.message;
                    status.classList.toggle('is-error',state==='error');
                },
                onDraft:function(payload) {
                    try { if(payload) window.localStorage.setItem(key,JSON.stringify(payload)); else window.localStorage.removeItem(key); } catch(ignore) {}
                }
            });
            function change() {
                saver.set(input.value); window.clearTimeout(timer);
                if(field==='owner') saver.flush(); else timer=window.setTimeout(saver.flush,600);
            }
            input.addEventListener(field==='owner'?'change':'input',change);
            input.addEventListener('blur',function() { window.clearTimeout(timer); saver.flush(); });
            window.addEventListener('online',saver.flush);
            status.addEventListener('click',saver.flush);
            if(draft) { input.value=draft.value; saver.set(input.value); saver.flush(); }
            savers.push({saver:saver,body:body});
        });
        function leaving() {
            savers.forEach(function(item) {
                var payload=item.saver.closingPayload();
                if(!payload) return;
                var data=item.body(payload);
                if(window.navigator.sendBeacon && window.navigator.sendBeacon('invoice_fields.php',data)) return;
                window.fetch('invoice_fields.php',{method:'POST',credentials:'same-origin',body:data,keepalive:true}).catch(function() {});
            });
        }
        document.addEventListener('visibilitychange',function() { if(document.visibilityState==='hidden') leaving(); });
        window.addEventListener('pagehide',leaving);
    }
    return {createSaver:createSaver,mount:mount};
});
