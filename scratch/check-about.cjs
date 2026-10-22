const fs = require('node:fs');
(async () => {
 const tabs = await (await fetch('http://127.0.0.1:9223/json/list')).json();
 const page = tabs.find(t => t.type === 'page');
 const socket = new WebSocket(page.webSocketDebuggerUrl);
 await new Promise(resolve => socket.addEventListener('open', resolve, {once:true}));
 let id = 0; const pending = new Map();
 socket.addEventListener('message', event => { const result = JSON.parse(event.data); if (result.id && pending.has(result.id)) { const {resolve,reject} = pending.get(result.id); pending.delete(result.id); result.error ? reject(result.error) : resolve(result.result); } });
 const send = (method, params = {}) => new Promise((resolve,reject) => { pending.set(++id, {resolve,reject}); socket.send(JSON.stringify({id,method,params})); });
 await send('Page.enable');
 for (const [name,width,height] of [['desktop',1024,1600],['mobile',390,844]]) {
   await send('Emulation.setDeviceMetricsOverride', {width,height,deviceScaleFactor:1,mobile:name==='mobile'});
   await send('Page.navigate', {url:'http://127.0.0.1:8000/about'});
   await new Promise(resolve => setTimeout(resolve,1200));
   await send('Runtime.evaluate', {expression:'document.fonts.ready',awaitPromise:true});
   const result = await send('Runtime.evaluate', {expression:'JSON.stringify({width:innerWidth,scrollWidth:document.documentElement.scrollWidth,images:[...document.images].every(i=>i.complete && i.naturalWidth>0),fonts:document.fonts.check("14px Inter"),icons:document.fonts.check("16px bootstrap-icons")})',returnByValue:true});
   console.log(name, result.result.value);
   const screenshot = await send('Page.captureScreenshot', {format:'jpeg',quality:80,captureBeyondViewport:true,fromSurface:true});
   fs.writeFileSync(`scratch/about-${name}.jpg`,Buffer.from(screenshot.data,'base64'));
 }
 socket.close();
})().catch(error => {console.error(error);process.exit(1);});
