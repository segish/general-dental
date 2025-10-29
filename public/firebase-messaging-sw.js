importScripts('https://www.gstatic.com/firebasejs/8.10.0/firebase-app.js');
importScripts('https://www.gstatic.com/firebasejs/8.10.0/firebase-messaging.js');
firebase.initializeApp({apiKey: "AIzaSyDJFXRLBd4awSk2ZehFSXlwP5QBvFG0120",authDomain: "push-notification-7cf97.firebaseapp.com",projectId: "push-notification-7cf97",storageBucket: "push-notification-7cf97.appspot.com", messagingSenderId: "166480773743", appId: "1:166480773743:web:857ae28b430495e0db039a"});
const messaging = firebase.messaging();
messaging.setBackgroundMessageHandler(function (payload) { return self.registration.showNotification(payload.data.title, { body: payload.data.body ? payload.data.body : '', icon: payload.data.icon ? payload.data.icon : '' }); });
