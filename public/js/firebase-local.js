/*
 * firebase-local.js — a drop-in replacement for the Firebase v8 "compat" SDK
 * (app, firestore, auth, storage, messaging) backed by this Laravel app and
 * MySQL instead of Google Firebase.
 *
 * The existing views call `firebase.firestore().collection(...).where(...)...`
 * directly; this file keeps that exact API so they work unchanged, while every
 * read and write goes to POST /fs/batch on our own server.
 *
 * Wire format for special values (mirrors App\Firestore\Codec):
 *   Timestamp -> {__fs:'ts', s, n}   GeoPoint -> {__fs:'geo', lat, lng}
 *   serverTimestamp() -> {__fs:'now'} FieldValue.delete() -> {__fs:'del'}
 */
(function (window) {
    'use strict';

    var BASE = (function () {
        if (window.FS_BASE_URL) return String(window.FS_BASE_URL).replace(/\/+$/, '');
        var s = document.currentScript && document.currentScript.src;
        if (s) return s.replace(/\/js\/firebase-local\.js.*$/, '');
        return window.location.origin;
    })();

    function csrfToken() {
        var m = document.querySelector('meta[name="csrf-token"]');
        return m ? m.getAttribute('content') : '';
    }

    function post(path, body, isForm) {
        var headers = {'X-CSRF-TOKEN': csrfToken(), 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json'};
        if (!isForm) headers['Content-Type'] = 'application/json';
        return fetch(BASE + path, {
            method: 'POST',
            credentials: 'same-origin',
            headers: headers,
            body: isForm ? body : JSON.stringify(body)
        }).then(function (res) {
            return res.json().catch(function () { return {}; }).then(function (json) {
                if (!res.ok) throw makeError(json.code || ('http-' + res.status), json.message || res.statusText);
                return json;
            });
        });
    }

    function makeError(code, message) {
        var e = new Error(message || code);
        e.code = code;
        return e;
    }

    // ------------------------------------------------------------ batching
    // Every Firestore call made in the same tick is sent as one HTTP request.
    var queue = [];
    var flushTimer = null;

    function enqueue(req) {
        return new Promise(function (resolve, reject) {
            queue.push({req: req, resolve: resolve, reject: reject});
            if (!flushTimer) flushTimer = setTimeout(flush, 0);
        });
    }

    function flush() {
        flushTimer = null;
        var items = queue.splice(0, 500);
        if (queue.length) flushTimer = setTimeout(flush, 0);
        post('/fs/batch', {requests: items.map(function (i) { return i.req; })}).then(function (json) {
            var results = json.results || [];
            items.forEach(function (item, idx) {
                var r = results[idx];
                if (r && r.ok) item.resolve(r);
                else {
                    var err = makeError(r ? r.code : 'unavailable', r ? r.message : 'No response');
                    if (window.console) console.warn('[firebase-local]', item.req, err.message);
                    item.reject(err);
                }
            });
        }, function (err) {
            items.forEach(function (item) { item.reject(err); });
        });
    }

    // --------------------------------------------------------------- types
    function Timestamp(seconds, nanoseconds) {
        this.seconds = Math.floor(seconds);
        this.nanoseconds = nanoseconds || 0;
    }
    Timestamp.prototype.toDate = function () { return new Date(this.toMillis()); };
    Timestamp.prototype.toMillis = function () { return this.seconds * 1000 + Math.floor(this.nanoseconds / 1e6); };
    Timestamp.prototype.isEqual = function (o) { return o instanceof Timestamp && o.seconds === this.seconds && o.nanoseconds === this.nanoseconds; };
    Timestamp.prototype.valueOf = function () { return String(this.seconds).padStart(12, '0') + '.' + String(this.nanoseconds).padStart(9, '0'); };
    Timestamp.prototype.toString = function () { return 'Timestamp(seconds=' + this.seconds + ', nanoseconds=' + this.nanoseconds + ')'; };
    Timestamp.prototype.toJSON = function () { return {seconds: this.seconds, nanoseconds: this.nanoseconds}; };
    Timestamp.now = function () { return Timestamp.fromMillis(Date.now()); };
    Timestamp.fromDate = function (d) { return Timestamp.fromMillis(d.getTime()); };
    Timestamp.fromMillis = function (ms) {
        var s = Math.floor(ms / 1000);
        return new Timestamp(s, Math.round((ms - s * 1000) * 1e6));
    };

    function GeoPoint(latitude, longitude) {
        this.latitude = Number(latitude);
        this.longitude = Number(longitude);
    }
    GeoPoint.prototype.isEqual = function (o) { return o instanceof GeoPoint && o.latitude === this.latitude && o.longitude === this.longitude; };
    GeoPoint.prototype.toJSON = function () { return {latitude: this.latitude, longitude: this.longitude}; };

    function Sentinel(tag) { this.__fs = tag; }
    var FieldValue = {
        serverTimestamp: function () { return new Sentinel('now'); },
        delete: function () { return new Sentinel('del'); }
    };
    var FieldPath = {documentId: function () { return '__name__'; }};

    function encode(v) {
        if (v === undefined) return undefined;
        if (v === null || typeof v !== 'object') return v;
        if (v instanceof Timestamp) return {__fs: 'ts', s: v.seconds, n: v.nanoseconds};
        if (v instanceof Date) return encode(Timestamp.fromDate(v));
        if (v instanceof GeoPoint) return {__fs: 'geo', lat: v.latitude, lng: v.longitude};
        if (v instanceof Sentinel) return {__fs: v.__fs};
        if (v instanceof DocumentReference) return v.path;
        if (v instanceof DocumentSnapshot) return {doc: v.id};
        if (Array.isArray(v)) return v.map(function (x) { var e = encode(x); return e === undefined ? null : e; });
        var out = {};
        Object.keys(v).forEach(function (k) {
            var e = encode(v[k]);
            if (e !== undefined) out[k] = e;
        });
        return out;
    }

    function decode(v) {
        if (v === null || typeof v !== 'object') return v;
        if (Array.isArray(v)) return v.map(decode);
        if (v.__fs === 'ts') return new Timestamp(v.s, v.n || 0);
        if (v.__fs === 'geo') return new GeoPoint(v.lat, v.lng);
        var out = {};
        Object.keys(v).forEach(function (k) { out[k] = decode(v[k]); });
        return out;
    }

    function getPath(obj, path) {
        var parts = String(path).split('.');
        for (var i = 0; i < parts.length; i++) {
            if (obj === null || obj === undefined) return undefined;
            obj = obj[parts[i]];
        }
        return obj;
    }

    var AUTO_ID_CHARS = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    function autoId() {
        var id = '';
        var bytes = new Uint8Array(20);
        (window.crypto || window.msCrypto).getRandomValues(bytes);
        for (var i = 0; i < 20; i++) id += AUTO_ID_CHARS.charAt(bytes[i] % AUTO_ID_CHARS.length);
        return id;
    }

    // ----------------------------------------------------------- snapshots
    function DocumentSnapshot(ref, raw) {
        this.ref = ref;
        this.id = ref.id;
        this.exists = !!raw;
        this._raw = raw ? raw.data : undefined;
        this.metadata = {hasPendingWrites: false, fromCache: false};
    }
    DocumentSnapshot.prototype.data = function () { return this._raw === undefined ? undefined : decode(this._raw); };
    DocumentSnapshot.prototype.get = function (path) { return this._raw === undefined ? undefined : decode(getPath(this._raw, path)); };
    DocumentSnapshot.prototype.isEqual = function (o) { return o && o.ref && o.ref.path === this.ref.path && JSON.stringify(o._raw) === JSON.stringify(this._raw); };

    function QueryDocumentSnapshot(ref, raw) { DocumentSnapshot.call(this, ref, raw); }
    QueryDocumentSnapshot.prototype = Object.create(DocumentSnapshot.prototype);

    function QuerySnapshot(query, docs, changes) {
        this.query = query;
        this.docs = docs;
        this.size = docs.length;
        this.empty = docs.length === 0;
        this._changes = changes;
        this.metadata = {hasPendingWrites: false, fromCache: false};
    }
    QuerySnapshot.prototype.forEach = function (cb, thisArg) { this.docs.forEach(cb, thisArg); };
    QuerySnapshot.prototype.docChanges = function () {
        return this._changes || this.docs.map(function (d, i) { return {type: 'added', doc: d, oldIndex: -1, newIndex: i}; });
    };

    // ----------------------------------------------------------- firestore
    function Firestore(app) { this.app = app; }
    Firestore.prototype.collection = function (path) { return new CollectionReference(this, path); };
    Firestore.prototype.doc = function (path) {
        var parts = String(path).split('/');
        return new CollectionReference(this, parts[0]).doc(parts.slice(1).join('/'));
    };
    Firestore.prototype.batch = function () { return new WriteBatch(this); };
    Firestore.prototype.runTransaction = function (fn) {
        // Reads go straight through; writes are committed together at the end.
        var tx = new Transaction(this);
        return Promise.resolve(fn(tx)).then(function (result) { return tx._batch.commit().then(function () { return result; }); });
    };
    Firestore.prototype.enablePersistence = function () { return Promise.resolve(); }; // GeoFirestore checks for this
    Firestore.prototype.settings = function () {};
    Firestore.prototype.collectionGroup = function () { throw makeError('unimplemented', 'collectionGroup is not supported'); };

    function Query(firestore, collection, spec) {
        this.firestore = firestore;
        this._c = collection;
        this._q = spec || {filters: [], orderBy: []};
    }
    function cloneSpec(q) { return JSON.parse(JSON.stringify(q)); }
    Query.prototype._with = function (fn) {
        var spec = cloneSpec(this._q);
        fn(spec);
        return new Query(this.firestore, this._c, spec);
    };
    Query.prototype.where = function (field, op, value) {
        return this._with(function (s) { s.filters.push([String(field), op, encode(value)]); });
    };
    Query.prototype.orderBy = function (field, dir) {
        return this._with(function (s) { s.orderBy.push([String(field), dir || 'asc']); });
    };
    Query.prototype.limit = function (n) { return this._with(function (s) { s.limit = n; delete s.limitToLast; }); };
    Query.prototype.limitToLast = function (n) { return this._with(function (s) { s.limit = n; s.limitToLast = true; }); };
    Query.prototype.offset = function (n) { return this._with(function (s) { s.offset = n; }); };
    ['startAt', 'startAfter', 'endAt', 'endBefore'].forEach(function (name) {
        Query.prototype[name] = function () {
            var args = Array.prototype.slice.call(arguments);
            return this._with(function (s) {
                if (args.length === 1 && args[0] instanceof DocumentSnapshot) s[name] = {doc: args[0].id};
                else s[name] = {values: args.map(encode)};
            });
        };
    });
    Query.prototype.get = function () {
        var self = this;
        return enqueue({t: 'query', c: this._c, q: this._q}).then(function (r) {
            return new QuerySnapshot(self, self._docsFrom(r.docs));
        });
    };
    Query.prototype._docsFrom = function (rawDocs) {
        var coll = new CollectionReference(this.firestore, this._c);
        return (rawDocs || []).map(function (d) { return new QueryDocumentSnapshot(coll.doc(d.id), d); });
    };
    Query.prototype.isEqual = function (o) { return o instanceof Query && o._c === this._c && JSON.stringify(o._q) === JSON.stringify(this._q); };
    Query.prototype.onSnapshot = function () {
        var self = this;
        var a = listenerArgs(arguments);
        var prev = null;
        return poll(function () {
            return enqueue({t: 'query', c: self._c, q: self._q}).then(function (r) {
                var key = JSON.stringify(r.docs);
                if (prev !== null && prev.key === key) return;
                var docs = self._docsFrom(r.docs);
                var changes = diffDocs(prev ? prev.docs : [], docs);
                prev = {key: key, docs: docs};
                a.next(new QuerySnapshot(self, docs, changes));
            });
        }, a.error);
    };

    function diffDocs(oldDocs, newDocs) {
        var oldIndex = {};
        oldDocs.forEach(function (d, i) { oldIndex[d.id] = i; });
        var newIds = {};
        var changes = [];
        newDocs.forEach(function (d, i) {
            newIds[d.id] = true;
            if (!(d.id in oldIndex)) changes.push({type: 'added', doc: d, oldIndex: -1, newIndex: i});
            else if (JSON.stringify(oldDocs[oldIndex[d.id]]._raw) !== JSON.stringify(d._raw)) {
                changes.push({type: 'modified', doc: d, oldIndex: oldIndex[d.id], newIndex: i});
            }
        });
        oldDocs.forEach(function (d, i) {
            if (!newIds[d.id]) changes.unshift({type: 'removed', doc: d, oldIndex: i, newIndex: -1});
        });
        return changes;
    }

    function listenerArgs(args) {
        args = Array.prototype.slice.call(args);
        if (args[0] && typeof args[0] === 'object' && typeof args[0].next !== 'function') args.shift(); // options
        if (args[0] && typeof args[0] === 'object') return {next: args[0].next.bind(args[0]), error: (args[0].error || function () {}).bind(args[0])};
        return {next: args[0], error: args[1] || function (e) { if (window.console) console.error(e); }};
    }

    // onSnapshot is emulated by polling; the interval backs off while the tab is hidden.
    var POLL_MS = 4000;
    function poll(tick, onError) {
        var stopped = false;
        var timer = null;
        function run() {
            if (stopped) return;
            if (document.hidden) { timer = setTimeout(run, POLL_MS * 4); return; }
            Promise.resolve().then(tick).catch(onError).then(function () {
                if (!stopped) timer = setTimeout(run, POLL_MS);
            });
        }
        run();
        return function unsubscribe() { stopped = true; clearTimeout(timer); };
    }

    function CollectionReference(firestore, path) {
        Query.call(this, firestore, path);
        this.id = path;
        this.path = path;
    }
    CollectionReference.prototype = Object.create(Query.prototype);
    CollectionReference.prototype.doc = function (id) {
        return new DocumentReference(this.firestore, this.path, id === undefined || id === null || id === '' ? autoId() : String(id));
    };
    CollectionReference.prototype.add = function (data) {
        var ref = this.doc();
        return ref.set(data).then(function () { return ref; });
    };

    function DocumentReference(firestore, collection, id) {
        this.firestore = firestore;
        this._c = collection;
        this.id = id;
        this.path = collection + '/' + id;
        this.parent = null;
    }
    DocumentReference.prototype.get = function () {
        var self = this;
        return enqueue({t: 'get', c: this._c, id: this.id}).then(function (r) { return new DocumentSnapshot(self, r.doc); });
    };
    DocumentReference.prototype._write = function (op, data, options) {
        var w = {op: op, collection: this._c, id: this.id};
        if (data !== undefined) w.data = encode(data);
        if (options && (options.merge || options.mergeFields)) w.merge = true;
        return enqueue({t: 'write', ops: [w]}).then(function () { return undefined; });
    };
    DocumentReference.prototype.set = function (data, options) { return this._write('set', data, options); };
    DocumentReference.prototype.update = function (a, b) {
        var data = a;
        if (typeof a === 'string') { // update('field', value, 'field2', value2...)
            data = {};
            var args = Array.prototype.slice.call(arguments);
            for (var i = 0; i + 1 < args.length; i += 2) data[args[i]] = args[i + 1];
        }
        return this._write('update', data);
    };
    DocumentReference.prototype.delete = function () { return this._write('delete'); };
    DocumentReference.prototype.collection = function () { throw makeError('unimplemented', 'Subcollections are not supported'); };
    DocumentReference.prototype.isEqual = function (o) { return o instanceof DocumentReference && o.path === this.path; };
    DocumentReference.prototype.onSnapshot = function () {
        var self = this;
        var a = listenerArgs(arguments);
        var prev;
        return poll(function () {
            return enqueue({t: 'get', c: self._c, id: self.id}).then(function (r) {
                var key = JSON.stringify(r.doc);
                if (key === prev) return;
                prev = key;
                a.next(new DocumentSnapshot(self, r.doc));
            });
        }, a.error);
    };

    function WriteBatch() { this._ops = []; }
    WriteBatch.prototype._push = function (op, ref, data, options) {
        var w = {op: op, collection: ref._c, id: ref.id};
        if (data !== undefined) w.data = encode(data);
        if (options && (options.merge || options.mergeFields)) w.merge = true;
        this._ops.push(w);
        return this;
    };
    WriteBatch.prototype.set = function (ref, data, options) { return this._push('set', ref, data, options); };
    WriteBatch.prototype.update = function (ref, data) { return this._push('update', ref, data); };
    WriteBatch.prototype.delete = function (ref) { return this._push('delete', ref); };
    WriteBatch.prototype.commit = function () {
        if (!this._ops.length) return Promise.resolve();
        return enqueue({t: 'write', ops: this._ops}).then(function () {});
    };

    function Transaction() { this._batch = new WriteBatch(); }
    Transaction.prototype.get = function (ref) { return ref.get(); };
    ['set', 'update', 'delete'].forEach(function (m) {
        Transaction.prototype[m] = function () { this._batch[m].apply(this._batch, arguments); return this; };
    });

    // ---------------------------------------------------------------- auth
    function User(u) {
        this.uid = u.uid;
        this.email = u.email || null;
        this.phoneNumber = u.phoneNumber || null;
        this.displayName = u.displayName || null;
        this.photoURL = u.photoURL || null;
        this.emailVerified = !!u.emailVerified;
    }
    User.prototype.getIdToken = function () { return Promise.resolve(''); };
    User.prototype.reload = function () { return Promise.resolve(); };

    function Auth(app) {
        var self = this;
        this.app = app;
        this.currentUser = null;
        this._listeners = [];
        this._ready = false;
        var initial = window.FS_AUTH_USER;
        if (initial && initial.uid) this.currentUser = new User(initial);
        this._readyPromise = post('/fs/auth/me', {}).then(function (r) {
            self.currentUser = r.user ? new User(r.user) : null;
        }, function () {}).then(function () {
            self._ready = true;
            self._emit();
        });
    }
    Auth.prototype._emit = function () {
        var u = this.currentUser;
        this._listeners.forEach(function (l) { try { l(u); } catch (e) { if (window.console) console.error(e); } });
    };
    Auth.prototype._signedIn = function (r) {
        this.currentUser = r.user ? new User(r.user) : null;
        this._emit();
        return {user: this.currentUser, additionalUserInfo: {isNewUser: !!r.isNewUser}, credential: null};
    };
    Auth.prototype.onAuthStateChanged = function (cb) {
        var self = this;
        this._listeners.push(cb);
        if (this._ready) setTimeout(function () { cb(self.currentUser); }, 0);
        return function () { self._listeners = self._listeners.filter(function (l) { return l !== cb; }); };
    };
    Auth.prototype.signInWithEmailAndPassword = function (email, password) {
        return post('/fs/auth/login', {email: email, password: password}).then(this._signedIn.bind(this));
    };
    Auth.prototype.createUserWithEmailAndPassword = function (email, password) {
        return post('/fs/auth/register', {email: email, password: password}).then(this._signedIn.bind(this));
    };
    Auth.prototype.signOut = function () {
        var self = this;
        return post('/fs/auth/logout', {}).then(function (r) {
            // The session (and its CSRF token) was regenerated; keep the page's
            // token in sync so follow-up $.ajax calls don't get a 419.
            var m = document.querySelector('meta[name="csrf-token"]');
            if (m && r && r.csrf) m.setAttribute('content', r.csrf);
            self.currentUser = null;
            self._emit();
        });
    };
    Auth.prototype.sendPasswordResetEmail = function (email) {
        return post('/fs/auth/password-reset', {email: email}).then(function () {});
    };
    Auth.prototype.signInWithPhoneNumber = function (phoneNumber) {
        var self = this;
        return post('/fs/auth/phone/send', {phone: phoneNumber}).then(function (r) {
            return {
                verificationId: r.verificationId,
                confirm: function (code) {
                    return post('/fs/auth/phone/verify', {verificationId: r.verificationId, code: code}).then(self._signedIn.bind(self));
                }
            };
        });
    };
    Auth.prototype.signInWithPopup = function () {
        return Promise.reject(makeError('auth/operation-not-allowed', 'Social sign-in is not configured on this server.'));
    };
    Auth.prototype.signInWithRedirect = Auth.prototype.signInWithPopup;
    Auth.prototype.getRedirectResult = function () { return Promise.resolve({user: null}); };
    Auth.prototype.setPersistence = function () { return Promise.resolve(); };
    Auth.prototype.useDeviceLanguage = function () {};

    function GoogleAuthProvider() { this.providerId = 'google.com'; }
    GoogleAuthProvider.prototype.addScope = function () { return this; };
    GoogleAuthProvider.prototype.setCustomParameters = function () { return this; };
    GoogleAuthProvider.PROVIDER_ID = 'google.com';

    // No reCAPTCHA: OTP sending is rate-limited on the server instead.
    function RecaptchaVerifier(container, params) {
        this.type = 'recaptcha';
        this._params = params || {};
    }
    RecaptchaVerifier.prototype.render = function () { return Promise.resolve(0); };
    RecaptchaVerifier.prototype.verify = function () {
        var cb = this._params.callback;
        if (typeof cb === 'function') setTimeout(function () { cb('local'); }, 0);
        return Promise.resolve('local');
    };
    RecaptchaVerifier.prototype.clear = function () {};

    // ------------------------------------------------------------- storage
    function StorageRef(storage, fullPath, url) {
        this.storage = storage;
        this.fullPath = fullPath || '';
        this.name = this.fullPath.split('/').pop();
        this._url = url || null;
    }
    StorageRef.prototype.child = function (p) {
        return new StorageRef(this.storage, (this.fullPath ? this.fullPath + '/' : '') + String(p).replace(/^\/+/, ''));
    };
    StorageRef.prototype.put = function (file) {
        var form = new FormData();
        form.append('path', this.fullPath);
        form.append('file', file);
        return new UploadTask(this, post('/fs/storage/upload', form, true), file);
    };
    StorageRef.prototype.putString = function (data, format) {
        return new UploadTask(this, post('/fs/storage/upload-string', {path: this.fullPath, data: data, format: format || 'raw'}), {size: String(data).length});
    };
    StorageRef.prototype.getDownloadURL = function () {
        return this._url ? Promise.resolve(this._url) : post('/fs/storage/url', {path: this.fullPath}).then(function (r) { return r.url; });
    };
    StorageRef.prototype.delete = function () {
        return post('/fs/storage/delete', this._url ? {url: this._url} : {path: this.fullPath}).then(function () {});
    };

    // Thenable with Firebase's .on('state_changed', progress, error, complete) API.
    function UploadTask(ref, promise, file) {
        var self = this;
        var total = (file && file.size) || 0;
        this.snapshot = {ref: ref, bytesTransferred: 0, totalBytes: total, state: 'running', task: this};
        this._promise = promise.then(function (r) {
            ref._url = r.url;
            ref.fullPath = r.path || ref.fullPath;
            self.snapshot = {ref: ref, bytesTransferred: total, totalBytes: total, state: 'success', task: self, metadata: {fullPath: ref.fullPath}};
            return self.snapshot;
        });
    }
    UploadTask.prototype.on = function (event, next, error, complete) {
        var self = this;
        if (next && typeof next === 'object') { error = next.error; complete = next.complete; next = next.next; }
        if (typeof next === 'function') next(this.snapshot);
        this._promise.then(function (snap) {
            if (typeof next === 'function') next(snap);
            if (typeof complete === 'function') complete();
        }, function (e) { if (typeof error === 'function') error(e); });
        return function () {};
    };
    UploadTask.prototype.then = function (a, b) { return this._promise.then(a, b); };
    UploadTask.prototype.catch = function (b) { return this._promise.catch(b); };
    UploadTask.prototype.cancel = function () { return false; };
    UploadTask.prototype.pause = function () { return false; };
    UploadTask.prototype.resume = function () { return false; };

    function Storage(app) { this.app = app; }
    Storage.prototype.ref = function (path) { return new StorageRef(this, path ? String(path).replace(/^\/+/, '') : ''); };
    Storage.prototype.refFromURL = function (url) { return new StorageRef(this, '', url); };

    // ----------------------------------------------------------- messaging
    // Web push via FCM is not available without Firebase; these are inert.
    function Messaging() {}
    Messaging.prototype.getToken = function () { return Promise.resolve(''); };
    Messaging.prototype.deleteToken = function () { return Promise.resolve(true); };
    Messaging.prototype.onMessage = function () { return function () {}; };
    Messaging.prototype.onTokenRefresh = function () { return function () {}; };
    Messaging.prototype.requestPermission = function () { return Promise.resolve(); };
    Messaging.prototype.usePublicVapidKey = function () {};
    Messaging.prototype.useServiceWorker = function () {};

    // ------------------------------------------------------------ firebase
    var singletons = {};
    function lazy(name, Ctor) { return function () { return singletons[name] || (singletons[name] = new Ctor(app)); }; }

    var app = {name: '[DEFAULT]', options: {}};
    var firebase = {
        SDK_VERSION: 'local-mysql',
        apps: [],
        initializeApp: function (options) {
            app.options = options || {};
            if (!firebase.apps.length) firebase.apps.push(app);
            return app;
        },
        app: function () { return app; },
        firestore: lazy('firestore', Firestore),
        auth: lazy('auth', Auth),
        storage: lazy('storage', Storage),
        messaging: lazy('messaging', Messaging),
        database: function () { throw makeError('unimplemented', 'Realtime Database is not available'); }
    };
    firebase.firestore.FieldValue = FieldValue;
    firebase.firestore.FieldPath = FieldPath;
    firebase.firestore.GeoPoint = GeoPoint;
    firebase.firestore.Timestamp = Timestamp;
    firebase.firestore.Firestore = Firestore;
    firebase.auth.GoogleAuthProvider = GoogleAuthProvider;
    firebase.auth.RecaptchaVerifier = RecaptchaVerifier;
    firebase.auth.Auth = {Persistence: {LOCAL: 'local', SESSION: 'session', NONE: 'none'}};
    firebase.messaging.isSupported = function () { return false; };
    app.firestore = firebase.firestore;
    app.auth = firebase.auth;
    app.storage = firebase.storage;

    // The views use the default app without waiting for initializeApp.
    firebase.initializeApp({});
    window.firebase = firebase;
})(window);
