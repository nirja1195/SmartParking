const fs=require('node:fs');
(async()=>{
 const tabs=await(await fetch('http://127.0.0.1:9223/json/list')).json();
 const socket=new WebSocket(tabs.find(t=>t.type==='page').webSocketDebuggerUrl);
 await new Promise(r=>socket.addEventListener('open',r,{once:true}));
 let id=0;const pending=new Map();
 socket.addEventListener('message',e=>{const m=JSON.parse(e.data);if(pending.has(m.id)){const p=pending.get(m.id);pending.delete(m.id);m.error?p.reject(m.error):p.resolve(m.result);}});
 const send=(method,params={})=>new Promise((resolve,reject)=>{pending.set(++id,{resolve,reject});socket.send(JSON.stringify({id,method,params}));});
 const run=async expression=>{const r=await send('Runtime.evaluate',{expression,returnByValue:true,awaitPromise:true});if(r.exceptionDetails)throw new Error(r.exceptionDetails.exception?.description||r.exceptionDetails.text);return r.result.value;};
 const wait=ms=>new Promise(r=>setTimeout(r,ms));
 const visit=async url=>{await send('Page.navigate',{url});await wait(1800);await run('document.fonts.ready');};
 await send('Page.enable');await send('Network.clearBrowserCookies');
 await visit('http://127.0.0.1:8000/admin/login');
 await run(`document.querySelector('#email').value='admin@admin.com';document.querySelector('#password').value=${JSON.stringify(process.env.SMARTPARK_SETUP_PASSWORD)};document.querySelector('.login-form').requestSubmit()`);
 await wait(1800);
 for(const [name,width,height] of [['desktop',1536,1024],['mobile',390,844]]){
   await send('Emulation.setDeviceMetricsOverride',{width,height,deviceScaleFactor:1,mobile:name==='mobile'});
   await visit('http://127.0.0.1:8000/admin/parking-slots');
   console.log(name,await run(`JSON.stringify({url:location.pathname,width:innerWidth,scrollWidth:document.documentElement.scrollWidth,slots:document.querySelectorAll('[data-slot]').length,images:[...document.images].every(i=>i.complete&&i.naturalWidth),floors:[...document.querySelectorAll('.floor-panel h2')].map(x=>x.textContent)})`));
   const img=await send('Page.captureScreenshot',{format:'jpeg',quality:80,fromSurface:true,captureBeyondViewport:false});fs.writeFileSync('scratch/parking-slots-'+name+'.jpg',Buffer.from(img.data,'base64'));
 }
 console.log('Slot edit dialog',await run(`document.querySelector('[data-slot]')?.click();document.querySelector('#slot-dialog').open`));
 await run(`document.querySelector('#slot-dialog').close()`);
 console.log('Add dialog',await run(`document.querySelector('[data-create]').click();document.querySelector('#slot-dialog').open&&document.querySelector('#slot-form').elements._method.value==='POST'`));
 await run(`document.querySelector('#slot-dialog').close()`);
 console.log('Live refresh endpoint',await run(`fetch(location.href,{headers:{Accept:'application/json'}}).then(r=>r.json()).then(d=>({board:d.board.includes('slot-card'),summary:d.summary.includes('summary-card')}))`));
 const first=await run(`JSON.parse(document.querySelector('[data-slot]').dataset.slot).slot_number`);
 await visit('http://127.0.0.1:8000/admin/parking-slots?search='+encodeURIComponent(first));
 console.log('Search narrowed results',await run(`document.querySelectorAll('[data-slot]').length`));
 await run(`[...document.forms].find(f=>f.action.endsWith('/admin/logout')).requestSubmit()`);await wait(700);
 socket.close();
})().catch(e=>{console.error(e);process.exit(1)});
