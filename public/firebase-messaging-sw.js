// Firebase Cloud Messaging was removed. If a browser still has the old service
// worker registered, this version unregisters it.
self.addEventListener('install', function () { self.skipWaiting(); });
self.addEventListener('activate', function () { self.registration.unregister(); });
