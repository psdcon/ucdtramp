var Push={API_KEY:"AIzaSyB4__3jfqqrcViXDrpcmSfr5JkX_ObL6bY",GCM_ENDPOINT:"https://android.googleapis.com/gcm/send",pushError:document.querySelector(".material-checkbox__error"),pushButton:document.querySelector(".material-checkbox__box"),pushLabel:document.querySelector(".material-checkbox__message"),pushTestBtn:document.querySelector(".btn-send-me-push"),pushShowBtn:document.querySelector(".btn-toggle-push"),isPushEnabled:!1,enabledLabel:"Push Notifications are enabled",disabledLabel:"Enable Push Notifications",init:function(){this.bindUIActions(),"serviceWorker"in navigator?navigator.serviceWorker.register("service-worker.js").then(Push.initialiseState):Push.log("Service workers aren't supported in this browser.")},bindUIActions:function(){Push.pushButton.addEventListener("change",function(){Push.isPushEnabled?Push.unsubscribe():Push.subscribe()})},initialiseState:function(){return Push.pushShowBtn.classList.add("fadeIn"),"showNotification"in ServiceWorkerRegistration.prototype?"denied"===Notification.permission?void Push.log("Notifications have been blocked by the user."):"PushManager"in window?void navigator.serviceWorker.ready.then(function(e){e.pushManager.getSubscription().then(function(e){Push.pushButton.disabled=!1,e&&(Push.sendSubscriptionToServer(e),Push.isOn())})["catch"](function(e){Push.log("Error during getSubscription()",e)})}):void Push.log("Push messaging isn't supported."):void Push.log("Notifications aren't supported.")},isOn:function(){Push.isPushEnabled=!0,Push.pushButton.checked=!0,Push.pushLabel.textContent=Push.enabledLabel,Push.pushTestBtn.classList.remove("fadeOut"),Push.pushTestBtn.classList.add("fadeIn")},isOff:function(){Push.isPushEnabled=!1,Push.pushButton.checked=!1,Push.pushLabel.textContent=Push.disabledLabel,Push.pushTestBtn.classList.add("fadeOut"),Push.pushTestBtn.classList.remove("fadeIn")},subscribe:function(){navigator.serviceWorker.ready.then(function(e){e.pushManager.subscribe({userVisibleOnly:!0}).then(function(e){return Push.isOn(),Push.sendSubscriptionToServer(e)})["catch"](function(e){"denied"===Notification.permission?(Push.log("Permission for Notifications was denied"),Push.isOff(),Push.pushButton.disabled=!0):(Push.log("Unable to subscribe to push.",e),Push.isOff())})})},unsubscribe:function(){navigator.serviceWorker.ready.then(function(e){e.pushManager.getSubscription().then(function(e){return e?(fetch("forum.ajax.php",{method:"POST",headers:{"Content-type":"application/x-www-form-urlencoded; charset=UTF-8"},body:"action=deleteSubscription&subscriptionId="+Push.subscriptionId}).then(function(e){return e.text()}).then(function(e){""!==e&&Push.log("Subscription unsuccessful",e)})["catch"](function(e){Push.log("Subscription failed",e)}),void e.unsubscribe().then(function(e){Push.isOff()})["catch"](function(e){Push.log("Unsubscribe error: ",e),Push.isOff()})):void Push.isOff()})["catch"](function(e){Push.log("Error while unsubscribing from push messaging.",e)})})},log:function(e,s){console.log(e,s);var n="undefined"==typeof s?"<br><strong>Sorry!</strong> "+e:"<br>"+e+": "+s;this.pushError.innerHTML=this.pushError.innerHTML+n},sendSubscriptionToServer:function(e){var s=Push.endpointWorkaround(e),n=s.split("/"),t=n[n.length-1];Push.subscriptionId=t,fetch("forum.ajax.php",{method:"post",headers:{"Content-type":"application/x-www-form-urlencoded; charset=UTF-8"},body:"action=saveSubscription&subscriptionId="+t+"&forumUser="+readCookie("forumUser")}).then(function(e){return e.text()}).then(function(e){""!==e&&Push.log("Recieved unexpected message from server",e)})["catch"](function(e){Push.log("Error sending subscription to server",e)})},endpointWorkaround:function(e){if(0!==e.endpoint.indexOf(Push.GCM_ENDPOINT))return e.endpoint;var s=e.endpoint;return e.subscriptionId&&-1===e.endpoint.indexOf(e.subscriptionId)&&(s=e.endpoint+"/"+e.subscriptionId),s}};Push.init(),$(".btn-send-me-push").click(function(){console.log("Sending push: "+Push.subscriptionId),$.ajax({type:"POST",url:"forum.ajax.php",data:"action=testSubscription&subscriptionId="+Push.subscriptionId,dataType:"text",success:function(e){""!==e&&Push.log("Test failed",e)}})});