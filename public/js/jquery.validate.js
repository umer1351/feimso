// Historically this file held the Firebase SDK initialisation (despite its name);
// the config was passed in through cookies. The app now runs on MySQL through
// public/js/firebase-local.js, which needs no keys. Kept so existing
// <script> tags keep resolving.
if (window.firebase && !firebase.apps.length) {
    firebase.initializeApp({});
}
window.firebaseInitError = null;
