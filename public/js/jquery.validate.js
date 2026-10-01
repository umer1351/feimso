var firebaseConfig = {
    apiKey: $.decrypt($.cookie('XSRF-TOKEN-AK')),
    authDomain: $.decrypt($.cookie('XSRF-TOKEN-AD')),
    databaseURL: $.decrypt($.cookie('XSRF-TOKEN-DU')),
    projectId: $.decrypt($.cookie('XSRF-TOKEN-PI')),
    storageBucket: $.decrypt($.cookie('XSRF-TOKEN-SB')),
    messagingSenderId: $.decrypt($.cookie('XSRF-TOKEN-MS')),
    appId: $.decrypt($.cookie('XSRF-TOKEN-AI')),
    measurementId: $.decrypt($.cookie('XSRF-TOKEN-MI'))
};

window.firebaseConfig = firebaseConfig;
window.firebaseInitError = null;

try {
    var requiredFirebaseKeys = ['apiKey', 'authDomain', 'projectId', 'appId'];
    var missingFirebaseKeys = requiredFirebaseKeys.filter(function (key) {
        return !firebaseConfig[key];
    });

    if (missingFirebaseKeys.length) {
        throw new Error('Missing Firebase config: ' + missingFirebaseKeys.join(', '));
    }

    if (!firebase.apps.length) {
        firebase.initializeApp(firebaseConfig);
    }
} catch (error) {
    window.firebaseInitError = error;
    console.error('Firebase initialization failed.', error, firebaseConfig);
}
