(()=>{var e=navigator.serviceWorker.ready;function n(e){for(var n=(e+"=".repeat((4-e.length%4)%4)).replace(/\-/g,"+").replace(/_/g,"/"),t=window.atob(n),o=new Uint8Array(t.length),r=0;r<t.length;++r)o[r]=t.charCodeAt(r);return o}document.addEventListener("DOMContentLoaded",(function(){!function(){if(!1 in navigator)return;if(!1 in window)return;navigator.serviceWorker.register("serviceworker.js",{scope:"/"}).then((function(){console.log("serviceWorker installed!"),function(){if(!e)return;new Promise((function(e,n){var t=Notification.requestPermission((function(n){e(n)}));t&&t.then(e,n)})).then((function(t){if("granted"!==t)throw new Error("We weren't granted permission.");e.then((function(e){var t={userVisibleOnly:!0,applicationServerKey:n(vapid_key)};return e.pushManager.subscribe(t)})).then((function(e){console.log("Received PushSubscription: ",JSON.stringify(e)),function(e){var n=document.querySelector("meta[name=csrf-token]").getAttribute("content");fetch("/wapi/push-noti",{method:"POST",body:JSON.stringify(e),headers:{Accept:"application/json","Content-Type":"application/json","X-CSRF-Token":n}}).then((function(e){return e.json()})).then((function(e){console.log(e)})).catch((function(e){console.log(e)}))}(e)}))}))}()})).catch((function(e){console.log("register fail"),console.log(e)}))}()}))})();
