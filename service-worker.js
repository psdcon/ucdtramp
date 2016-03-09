'use strict';

self.addEventListener('push', function (event) {
    // console.log('Received a push message', event);

    var title = 'Yay! New forum post.';
    var body = 'There\'s been a new post on the forum. Tap this message to view it.';
    var icon = "https:\/\/ucdtramp.com\/images\/favicon\/android-chrome-96x96.png";
    var tag = 'simple-push-notification-tag';

    event.waitUntil(
        self.registration.showNotification(title, {
            body: body,
            icon: icon,
            // vibrate: [200, 100, 200, 100, 200, 100, 200],
            tag: tag
        })
    );
});


self.addEventListener('notificationclick', function (event) {
    // Android doesn't close the notification when you click on it
    // See: http://crbug.com/463146
    event.notification.close();

    // This looks to see if the page is already open and focuses if it is
    event.waitUntil(clients.matchAll({
        type: "window"
    }).then(function (clientList) {
        for (var i = 0; i < clientList.length; i++) {
            var client = clientList[i];
            if (client.url.indexOf('forum') > 0 && 'focus' in client)
                return client.focus();
        }
        if (clients.openWindow)
            return clients.openWindow('/forum');
    }));
});