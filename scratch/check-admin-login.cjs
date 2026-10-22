const fs = require('node:fs');
(async () => {
 const tabs = await (await fetch('http://127.0.0.1:9223/json/list')).json();
 const page = tabs.find(t=>t.type==='page');
 const socket = new WebSocket(page.webSocketDebuggerUrl);
 await new Promise(resolve=>socket.addEventListener('open',resolve,{once:true}));
 let id=0; const pending=new Map();
 socket.addEventListener('message',event=>{const message=JSON.parse(event.data);if(message.id&&pending.has(message.id)){const {resolve,reject}=pending.get(message.id);pending.delete(message.id);message.error?reject(message.error):resolve(message.result);}});
 const send=(method,params={})=>new Promise((resolve,reject)=>{pending.set(++id,{resolve,reject});socket.send(JSON.stringify({id,method,params}));});
 const evaluate=async expression=>{const r=await send('Runtime.evaluate',{expression,returnByValue:true,awaitPromise:true});if(r.exceptionDetails)throw new Error(r.exceptionDetails.text);return r.result.value;};
 const visit=async url=>{await send('Page.navigate',{url});await new Promise(resolve=>setTimeout(resolve,1300));await evaluate('document.fonts.ready');};
 await send('Page.enable');
 await send('Network.clearBrowserCookies');
 for (const [name,width,height] of [['desktop',1536,1024],['mobile',390,844]]) {
   await send('Emulation.setDeviceMetricsOverride',{width,height,deviceScaleFactor:1,mobile:name==='mobile'});
   await visit('http://127.0.0.1:8000/admin/login');
   console.log(name,await evaluate('JSON.stringify({width:innerWidth,scrollWidth:document.documentElement.scrollWidth,images:[...document.images].every(i=>i.complete&&i.naturalWidth>0),fonts:document.fonts.check("14px Inter"),passwordEmpty:document.querySelector("#password").value===""})'));
   const screenshot=await send('Page.captureScreenshot',{format:'jpeg',quality:85,captureBeyondViewport:true,fromSurface:true});fs.writeFileSync(`scratch/admin-login-${name}.jpg`,Buffer.from(screenshot.data,'base64'));
 }
 await evaluate('document.querySelector(".password-toggle").click()');
 console.log('Password visibility toggle',await evaluate('document.querySelector("#password").type === "text"'));
 await evaluate('document.querySelector(".password-toggle").click()');
 if(process.env.SMARTPARK_SETUP_PASSWORD) {
   await evaluate('document.querySelector("#email").value="admin@admin.com"');
   await evaluate(`document.querySelector("#password").value=${JSON.stringify(process.env.SMARTPARK_SETUP_PASSWORD)}`);
   await evaluate('document.querySelector(".login-form").requestSubmit()');
   await new Promise(resolve=>setTimeout(resolve,1800));
   console.log('Login redirect',await evaluate('location.pathname'));
   console.log('Dashboard rendered',await evaluate('document.body.innerText.includes("Admin Login CMS")'));
   console.log('Dashboard status',await evaluate('fetch(location.href).then(r=>r.status)'));
   await send('Emulation.setDeviceMetricsOverride',{width:1440,height:1000,deviceScaleFactor:1,mobile:false});
   await visit('http://127.0.0.1:8000/admin/login-page');
   console.log('Editor reachable',await evaluate('document.body.innerText.includes("Save login page")'));
   const editor=await send('Page.captureScreenshot',{format:'jpeg',quality:75,fromSurface:true});fs.writeFileSync('scratch/admin-login-editor.jpg',Buffer.from(editor.data,'base64'));
   await evaluate(`[...document.forms].find(f=>f.action.endsWith('/admin/login-page')).requestSubmit()`);
   await new Promise(resolve=>setTimeout(resolve,1200));
   console.log('Editor save confirmed',await evaluate('document.body.innerText.includes("Admin login page updated successfully.")'));
   await evaluate(`[...document.forms].find(f=>f.action.endsWith('/admin/logout')).requestSubmit()`);
   await new Promise(resolve=>setTimeout(resolve,900));
   console.log('Logout redirect',await evaluate('location.pathname'));
 }
 socket.close();
})().catch(error=>{console.error(error);process.exit(1);});
