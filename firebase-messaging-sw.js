importScripts('https://www.gstatic.com/firebasejs/8.10.0/firebase-app.js');
importScripts('https://www.gstatic.com/firebasejs/8.10.0/firebase-messaging.js');
firebase.initializeApp({apiKey: "AIzaSyC68C2zfcMZ_4U6aKLgl3Z_MwET09qfd-U",authDomain: "atga-plc.firebaseapp.com",projectId: "agta-plc",storageBucket: "agta-plc.appspot.com", messagingSenderId: "242554080067", appId: "1:242554080067:web:aa7ea0f517cf936001bdf6"});
const messaging = firebase.messaging();
messaging.setBackgroundMessageHandler(function (payload) { return self.registration.showNotification(payload.data.title, { body: payload.data.body ? payload.data.body : '', icon: payload.data.icon ? payload.data.icon : '' }); });
