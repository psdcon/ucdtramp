
var Push = {
    API_KEY : 'AIzaSyB4__3jfqqrcViXDrpcmSfr5JkX_ObL6bY',
    GCM_ENDPOINT : 'https://android.googleapis.com/gcm/send',

    pushError : document.querySelector('.material-checkbox__error'),
    pushButton: document.querySelector('.material-checkbox__box'),
    pushLabel: document.querySelector('.material-checkbox__message'),
    pushTestBtn: document.querySelector('.btn-send-me-push'),
    pushShowBtn: document.querySelector('.btn-toggle-push'),
    isPushEnabled : false,

    enabledLabel: 'Push Notifications are enabled',
    disabledLabel: 'Enable Push Notifications',

    init : function() {
        this.bindUIActions();

        // Check that service workers are supported, if so, progressively
        // enhance and add push messaging support, otherwise continue without it.
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register('service-worker.js')
                .then(Push.initialiseState);
        } else {
            Push.log('Service workers aren\'t supported in this browser.');
        }
    },
    bindUIActions: function(){
        Push.pushButton.addEventListener('change', function () {
            if (Push.isPushEnabled) {
                Push.unsubscribe();
            } else {
                Push.subscribe();
            }
        });
    },
    // Once the service worker is registered set the initial state
    initialiseState: function() {
        // Fade in the button
        Push.pushShowBtn.classList.add('fadeIn');

        // Are Notifications supported in the service worker?
        if (!('showNotification' in ServiceWorkerRegistration.prototype)) {
            Push.log('Notifications aren\'t supported.');
            return;
        }

        // Check the current Notification permission.
        // If its denied, it's a permanent block until the
        // user changes the permission
        if (Notification.permission === 'denied') {
            Push.log('Notifications have been blocked by the user.');
            return;
        }

        // Check if push messaging is supported
        if (!('PushManager' in window)) {
            Push.log('Push messaging isn\'t supported.');
            return;
        }

        // We need the service worker registration to check for a subscription
        navigator.serviceWorker.ready.then(function (serviceWorkerRegistration) {
            // Do we already have a push message subscription?
            serviceWorkerRegistration.pushManager.getSubscription()
                .then(function (subscription) {
                    // Enable any UI which subscribes / unsubscribes from push messages.
                    Push.pushButton.disabled = false;

                    if (!subscription) {
                        // We aren’t subscribed to push, so set UI to allow the user to enable push
                        return;
                    }

                    // Keep your server in sync with the latest subscription
                    Push.sendSubscriptionToServer(subscription);

                    // Set your UI to show they have subscribed for push messages
                    Push.isOn();
                })
                .catch(function (err) {
                    Push.log('Error during getSubscription()', err);
                });
        });
    },
    isOn: function(){
        Push.isPushEnabled = true;
        Push.pushButton.checked = true;
        Push.pushLabel.textContent = Push.enabledLabel;
        Push.pushTestBtn.classList.remove('fadeOut');
        Push.pushTestBtn.classList.add('fadeIn');
    },
    isOff: function(){
        Push.isPushEnabled = false;
        Push.pushButton.checked = false;
        Push.pushLabel.textContent = Push.disabledLabel;
        Push.pushTestBtn.classList.add('fadeOut');
        Push.pushTestBtn.classList.remove('fadeIn');
    },
    subscribe: function() {
        // Disable the button so it can't be changed while we process the permission request
        // Causes awkward cursor hover state
        // Push.pushButton.disabled = true;

        navigator.serviceWorker.ready.then(function (serviceWorkerRegistration) {
            serviceWorkerRegistration.pushManager.subscribe({
                    userVisibleOnly: true
                })
                .then(function (subscription) {
                    // The subscription was successful
                    Push.isOn();

                    // Send the subscription subscription.endpoint
                    // to your server and save it to send a push message
                    // at a later date
                    return Push.sendSubscriptionToServer(subscription);
                })
                .catch(function (e) {
                    if (Notification.permission === 'denied') {
                        // The user denied the notification permission which
                        // means we failed to subscribe and the user will need
                        // to manually change the notification permission to
                        // subscribe to push messages
                        Push.log('Permission for Notifications was denied');
                        Push.isOff();
                        Push.pushButton.disabled = true;
                    } else {
                        // A problem occurred with the subscription, this can
                        // often be down to an issue or lack of the gcm_sender_id
                        // and / or gcm_user_visible_only
                        Push.log('Unable to subscribe to push.', e);
                        Push.isOff();
                    }
                });
        });
    },
    unsubscribe: function() {
        navigator.serviceWorker.ready.then(function (serviceWorkerRegistration) {
            // To unsubscribe from push messaging, you need to get the
            // subcription object, which you can call unsubscribe() on.
            serviceWorkerRegistration.pushManager.getSubscription().then(
                function (pushSubscription) {
                    // Check we have a subscription to unsubscribe
                    if (!pushSubscription) {
                        // No subscription object, so set the state to allow the user to subscribe to push
                        Push.isOff();
                        return;
                    }

                    // Make a request to your server to remove
                    // the users data from your data store so you
                    // don't attempt to send them push messages anymore
                    fetch('forum.ajax.php', {
                        method: 'POST',
                        headers: {"Content-type": "application/x-www-form-urlencoded; charset=UTF-8"},
                        body: 'action=deleteSubscription&subscriptionId='+Push.subscriptionId
                    }).then(function(response) {
                        // No response
                        return (response.text());
                    }).then(function(text) {  
                        // Request successful
                        if (text !== '')
                            Push.log('Subscription unsuccessful', text);
                    }).catch(function(err) {
                        // Error :(
                        Push.log('Subscription failed', err);
                    });

                    // We have a subcription, so call unsubscribe on it
                    pushSubscription.unsubscribe().then(function (successful) {
                        Push.isOff();
                    }).catch(function (e) {
                        // We failed to unsubscribe, this can lead to
                        // an unusual state, so may be best to remove
                        // the subscription id from your data store and
                        // inform the user that you disabled push

                        Push.log('Unsubscribe error: ', e);
                        Push.isOff();
                    });
                }).catch(function (e) {
                    Push.log('Error while unsubscribing from push messaging.', e);
                });
        });
    },
    log: function (str, err) {
        console.log(str, err);

        var message = (typeof err == "undefined")? '<br><strong>Sorry!</strong> ' + str : '<br>' + str + ': ' + err ;
        this.pushError.innerHTML = this.pushError.innerHTML + message;
    },
    sendSubscriptionToServer: function(subscription) {
        // For compatibly of Chrome 43, get the endpoint via
        // Push.endpointWorkaround(subscription)
        var mergedEndpoint = Push.endpointWorkaround(subscription);

        // Split the consistnently constructed endpoint url which looks like
        // https://android.googleapis.com/gcm/send/APA91bGQ-ididid...
        // and pull the id off the end
        var endpointSections = mergedEndpoint.split('/');
        var subscriptionId = endpointSections[endpointSections.length - 1];
        Push.subscriptionId = subscriptionId;

        // Send the subscription.endpoint
        // to your server and save it to send a
        // push message at a later date        
        fetch('forum.ajax.php', {
            method: 'post',
            headers: {"Content-type": "application/x-www-form-urlencoded; charset=UTF-8"},
            body: 'action=saveSubscription&subscriptionId='+subscriptionId+"&forumUser="+readCookie('forumUser')
        }).then(function(response) {
            // No response
            return (response.text());
        }).then(function(text) {  
            // Request successful
            if (text !== '')
                Push.log('Recieved unexpected message from server', text);
        }).catch(function(err) {
            // Error :(
            Push.log('Error sending subscription to server', err);
        });
    },
    // This method handles the removal of subscriptionId
    // in Chrome 44 by concatenating the subscription Id
    // to the subscription endpoint
    endpointWorkaround: function(pushSubscription) {
        // Make sure we only mess with GCM
        if (pushSubscription.endpoint.indexOf(Push.GCM_ENDPOINT) !== 0) {
            return pushSubscription.endpoint;
        }

        var mergedEndpoint = pushSubscription.endpoint;
        // Chrome 42 + 43 will not have the subscriptionId attached
        // to the endpoint.
        if (pushSubscription.subscriptionId &&
            pushSubscription.endpoint.indexOf(pushSubscription.subscriptionId) === -1) {
            // Handle version 42 where you have separate subId and Endpoint
            mergedEndpoint = pushSubscription.endpoint + '/' +
                pushSubscription.subscriptionId;
        }
        return mergedEndpoint;
    },
};
Push.init();


// Awkwardly stuck-in-down-here test for a push notification
$('.btn-send-me-push').click(function(){
    console.log("Sending push: "+Push.subscriptionId);
    $.ajax({
        type: 'POST',
        url : 'forum.ajax.php',
        data: 'action=testSubscription'+
            '&subscriptionId='+ Push.subscriptionId,
        dataType: 'text', // server return type
        success: function(response){
            if (response !== '')
                Push.log('Test failed', response);
        }
    });
});