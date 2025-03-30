function _assertThisInitialized(e){if(void 0===e)throw new ReferenceError("this hasn't been initialised - super() hasn't been called");return e}function _callSuper(e,n,t){return n=_getPrototypeOf(n),_possibleConstructorReturn(e,_isNativeReflectConstruct()?Reflect.construct(n,t||[],_getPrototypeOf(e).constructor):n.apply(e,t))}function _classCallCheck(e,n){if(!(e instanceof n))throw new TypeError("Cannot call a class as a function")}function _defineProperties(e,n){for(var t=0;t<n.length;t++){var r=n[t];r.enumerable=r.enumerable||!1,r.configurable=!0,"value"in r&&(r.writable=!0),Object.defineProperty(e,_toPropertyKey(r.key),r)}}function _createClass(e,n,t){return n&&_defineProperties(e.prototype,n),t&&_defineProperties(e,t),Object.defineProperty(e,"prototype",{writable:!1}),e}function _defineProperty(e,n,t){return(n=_toPropertyKey(n))in e?Object.defineProperty(e,n,{value:t,enumerable:!0,configurable:!0,writable:!0}):e[n]=t,e}function _getPrototypeOf(e){return _getPrototypeOf=Object.setPrototypeOf?Object.getPrototypeOf.bind():function(e){return e.__proto__||Object.getPrototypeOf(e)},_getPrototypeOf(e)}function _inherits(e,n){if("function"!=typeof n&&null!==n)throw new TypeError("Super expression must either be null or a function");e.prototype=Object.create(n&&n.prototype,{constructor:{value:e,writable:!0,configurable:!0}}),Object.defineProperty(e,"prototype",{writable:!1}),n&&_setPrototypeOf(e,n)}function _isNativeReflectConstruct(){try{var e=!Boolean.prototype.valueOf.call(Reflect.construct(Boolean,[],(function(){})))}catch(e){}return(_isNativeReflectConstruct=function(){return!!e})()}function ownKeys(e,n){var t=Object.keys(e);if(Object.getOwnPropertySymbols){var r=Object.getOwnPropertySymbols(e);n&&(r=r.filter((function(n){return Object.getOwnPropertyDescriptor(e,n).enumerable}))),t.push.apply(t,r)}return t}function _objectSpread2(e){for(var n=1;n<arguments.length;n++){var t=null!=arguments[n]?arguments[n]:{};n%2?ownKeys(Object(t),!0).forEach((function(n){_defineProperty(e,n,t[n])})):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(t)):ownKeys(Object(t)).forEach((function(n){Object.defineProperty(e,n,Object.getOwnPropertyDescriptor(t,n))}))}return e}function _possibleConstructorReturn(e,n){if(n&&("object"==typeof n||"function"==typeof n))return n;if(void 0!==n)throw new TypeError("Derived constructors may only return object or undefined");return _assertThisInitialized(e)}function _setPrototypeOf(e,n){return _setPrototypeOf=Object.setPrototypeOf?Object.setPrototypeOf.bind():function(e,n){return e.__proto__=n,e},_setPrototypeOf(e,n)}function _toPrimitive(e,n){if("object"!=typeof e||!e)return e;var t=e[Symbol.toPrimitive];if(void 0!==t){var r=t.call(e,n||"default");if("object"!=typeof r)return r;throw new TypeError("@@toPrimitive must return a primitive value.")}return("string"===n?String:Number)(e)}function _toPropertyKey(e){var n=_toPrimitive(e,"string");return"symbol"==typeof n?n:n+""}function _typeof(e){return _typeof="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(e){return typeof e}:function(e){return e&&"function"==typeof Symbol&&e.constructor===Symbol&&e!==Symbol.prototype?"symbol":typeof e},_typeof(e)}var e=function(){function Channel(){_classCallCheck(this,Channel)}return _createClass(Channel,[{key:"listenForWhisper",value:function listenForWhisper(e,n){return this.listen(".client-"+e,n)}},{key:"notification",value:function notification(e){return this.listen(".Illuminate\\Notifications\\Events\\BroadcastNotificationCreated",e)}},{key:"stopListeningForWhisper",value:function stopListeningForWhisper(e,n){return this.stopListening(".client-"+e,n)}}])}();var n=function(){function EventFormatter(e){_classCallCheck(this,EventFormatter);this.namespace=e}return _createClass(EventFormatter,[{key:"format",value:function format(e){if([".","\\"].includes(e.charAt(0)))return e.substring(1);this.namespace&&(e=this.namespace+"."+e);return e.replace(/\./g,"\\")}},{key:"setNamespace",value:function setNamespace(e){this.namespace=e}}])}();function isConstructor(e){try{new e}catch(e){if(e instanceof Error&&e.message.includes("is not a constructor"))return false}return true}var t=function(e){function PusherChannel(e,t,r){var i;_classCallCheck(this,PusherChannel);i=_callSuper(this,PusherChannel);i.name=t;i.pusher=e;i.options=r;i.eventFormatter=new n(i.options.namespace);i.subscribe();return i}_inherits(PusherChannel,e);return _createClass(PusherChannel,[{key:"subscribe",value:function subscribe(){this.subscription=this.pusher.subscribe(this.name)}},{key:"unsubscribe",value:function unsubscribe(){this.pusher.unsubscribe(this.name)}},{key:"listen",value:function listen(e,n){this.on(this.eventFormatter.format(e),n);return this}},{key:"listenToAll",value:function listenToAll(e){var n=this;this.subscription.bind_global((function(t,r){var i;if(!t.startsWith("pusher:")){var s=String((i=n.options.namespace)!==null&&i!==void 0?i:"").replace(/\./g,"\\");var o=t.startsWith(s)?t.substring(s.length+1):"."+t;e(o,r)}}));return this}},{key:"stopListening",value:function stopListening(e,n){n?this.subscription.unbind(this.eventFormatter.format(e),n):this.subscription.unbind(this.eventFormatter.format(e));return this}},{key:"stopListeningToAll",value:function stopListeningToAll(e){e?this.subscription.unbind_global(e):this.subscription.unbind_global();return this}},{key:"subscribed",value:function subscribed(e){this.on("pusher:subscription_succeeded",(function(){e()}));return this}},{key:"error",value:function error(e){this.on("pusher:subscription_error",(function(n){e(n)}));return this}},{key:"on",value:function on(e,n){this.subscription.bind(e,n);return this}}])}(e);var r=function(e){function PusherPrivateChannel(){_classCallCheck(this,PusherPrivateChannel);return _callSuper(this,PusherPrivateChannel,arguments)}_inherits(PusherPrivateChannel,e);return _createClass(PusherPrivateChannel,[{key:"whisper",value:function whisper(e,n){this.pusher.channels.channels[this.name].trigger("client-".concat(e),n);return this}}])}(t);var i=function(e){function PusherEncryptedPrivateChannel(){_classCallCheck(this,PusherEncryptedPrivateChannel);return _callSuper(this,PusherEncryptedPrivateChannel,arguments)}_inherits(PusherEncryptedPrivateChannel,e);return _createClass(PusherEncryptedPrivateChannel,[{key:"whisper",value:function whisper(e,n){this.pusher.channels.channels[this.name].trigger("client-".concat(e),n);return this}}])}(t);var s=function(e){function PusherPresenceChannel(){_classCallCheck(this,PusherPresenceChannel);return _callSuper(this,PusherPresenceChannel,arguments)}_inherits(PusherPresenceChannel,e);return _createClass(PusherPresenceChannel,[{key:"here",value:function here(e){this.on("pusher:subscription_succeeded",(function(n){e(Object.keys(n.members).map((function(e){return n.members[e]})))}));return this}},{key:"joining",value:function joining(e){this.on("pusher:member_added",(function(n){e(n.info)}));return this}},{key:"whisper",value:function whisper(e,n){this.pusher.channels.channels[this.name].trigger("client-".concat(e),n);return this}},{key:"leaving",value:function leaving(e){this.on("pusher:member_removed",(function(n){e(n.info)}));return this}}])}(r);var o=function(e){function SocketIoChannel(e,t,r){var i;_classCallCheck(this,SocketIoChannel);i=_callSuper(this,SocketIoChannel);i.events={};i.listeners={};i.name=t;i.socket=e;i.options=r;i.eventFormatter=new n(i.options.namespace);i.subscribe();return i}_inherits(SocketIoChannel,e);return _createClass(SocketIoChannel,[{key:"subscribe",value:function subscribe(){this.socket.emit("subscribe",{channel:this.name,auth:this.options.auth||{}})}},{key:"unsubscribe",value:function unsubscribe(){this.unbind();this.socket.emit("unsubscribe",{channel:this.name,auth:this.options.auth||{}})}},{key:"listen",value:function listen(e,n){this.on(this.eventFormatter.format(e),n);return this}},{key:"stopListening",value:function stopListening(e,n){this.unbindEvent(this.eventFormatter.format(e),n);return this}},{key:"subscribed",value:function subscribed(e){this.on("connect",(function(n){e(n)}));return this}},{key:"error",value:function error(e){return this}},{key:"on",value:function on(e,n){var t=this;this.listeners[e]=this.listeners[e]||[];if(!this.events[e]){this.events[e]=function(n,r){t.name===n&&t.listeners[e]&&t.listeners[e].forEach((function(e){return e(r)}))};this.socket.on(e,this.events[e])}this.listeners[e].push(n);return this}},{key:"unbind",value:function unbind(){var e=this;Object.keys(this.events).forEach((function(n){e.unbindEvent(n)}))}},{key:"unbindEvent",value:function unbindEvent(e,n){this.listeners[e]=this.listeners[e]||[];n&&(this.listeners[e]=this.listeners[e].filter((function(e){return e!==n})));if(!n||this.listeners[e].length===0){if(this.events[e]){this.socket.removeListener(e,this.events[e]);delete this.events[e]}delete this.listeners[e]}}}])}(e);var c=function(e){function SocketIoPrivateChannel(){_classCallCheck(this,SocketIoPrivateChannel);return _callSuper(this,SocketIoPrivateChannel,arguments)}_inherits(SocketIoPrivateChannel,e);return _createClass(SocketIoPrivateChannel,[{key:"whisper",value:function whisper(e,n){this.socket.emit("client event",{channel:this.name,event:"client-".concat(e),data:n});return this}}])}(o);var a=function(e){function SocketIoPresenceChannel(){_classCallCheck(this,SocketIoPresenceChannel);return _callSuper(this,SocketIoPresenceChannel,arguments)}_inherits(SocketIoPresenceChannel,e);return _createClass(SocketIoPresenceChannel,[{key:"here",value:function here(e){this.on("presence:subscribed",(function(n){e(n.map((function(e){return e.user_info})))}));return this}},{key:"joining",value:function joining(e){this.on("presence:joining",(function(n){return e(n.user_info)}));return this}},{key:"whisper",value:function whisper(e,n){this.socket.emit("client event",{channel:this.name,event:"client-".concat(e),data:n});return this}},{key:"leaving",value:function leaving(e){this.on("presence:leaving",(function(n){return e(n.user_info)}));return this}}])}(c);var u=function(e){function NullChannel(){_classCallCheck(this,NullChannel);return _callSuper(this,NullChannel,arguments)}_inherits(NullChannel,e);return _createClass(NullChannel,[{key:"subscribe",value:function subscribe(){}},{key:"unsubscribe",value:function unsubscribe(){}},{key:"listen",value:function listen(e,n){return this}},{key:"listenToAll",value:function listenToAll(e){return this}},{key:"stopListening",value:function stopListening(e,n){return this}},{key:"subscribed",value:function subscribed(e){return this}},{key:"error",value:function error(e){return this}},{key:"on",value:function on(e,n){return this}}])}(e);var l=function(e){function NullPrivateChannel(){_classCallCheck(this,NullPrivateChannel);return _callSuper(this,NullPrivateChannel,arguments)}_inherits(NullPrivateChannel,e);return _createClass(NullPrivateChannel,[{key:"whisper",value:function whisper(e,n){return this}}])}(u);var h=function(e){function NullEncryptedPrivateChannel(){_classCallCheck(this,NullEncryptedPrivateChannel);return _callSuper(this,NullEncryptedPrivateChannel,arguments)}_inherits(NullEncryptedPrivateChannel,e);return _createClass(NullEncryptedPrivateChannel,[{key:"whisper",value:function whisper(e,n){return this}}])}(u);var p=function(e){function NullPresenceChannel(){_classCallCheck(this,NullPresenceChannel);return _callSuper(this,NullPresenceChannel,arguments)}_inherits(NullPresenceChannel,e);return _createClass(NullPresenceChannel,[{key:"here",value:function here(e){return this}},{key:"joining",value:function joining(e){return this}},{key:"whisper",value:function whisper(e,n){return this}},{key:"leaving",value:function leaving(e){return this}}])}(l);var f=function(){function Connector(e){_classCallCheck(this,Connector);this.setOptions(e);this.connect()}return _createClass(Connector,[{key:"setOptions",value:function setOptions(e){this.options=_objectSpread2(_objectSpread2(_objectSpread2({},Connector._defaultOptions),e),{},{broadcaster:e.broadcaster});var n=this.csrfToken();if(n){this.options.auth.headers["X-CSRF-TOKEN"]=n;this.options.userAuthentication.headers["X-CSRF-TOKEN"]=n}n=this.options.bearerToken;if(n){this.options.auth.headers.Authorization="Bearer "+n;this.options.userAuthentication.headers.Authorization="Bearer "+n}}},{key:"csrfToken",value:function csrfToken(){var e;return typeof window!=="undefined"&&typeof window.Laravel!=="undefined"&&window.Laravel.csrfToken?window.Laravel.csrfToken:this.options.csrfToken?this.options.csrfToken:typeof document!=="undefined"&&typeof document.querySelector==="function"&&(e=document.querySelector('meta[name="csrf-token"]'))?e.getAttribute("content"):null}}])}();f._defaultOptions={auth:{headers:{}},authEndpoint:"/broadcasting/auth",userAuthentication:{endpoint:"/broadcasting/user-auth",headers:{}},csrfToken:null,bearerToken:null,host:null,key:null,namespace:"App.Events"};var v=function(e){function PusherConnector(){var e;_classCallCheck(this,PusherConnector);e=_callSuper(this,PusherConnector,arguments);e.channels={};return e}_inherits(PusherConnector,e);return _createClass(PusherConnector,[{key:"connect",value:function connect(){if(typeof this.options.client!=="undefined")this.pusher=this.options.client;else if(this.options.Pusher)this.pusher=new this.options.Pusher(this.options.key,this.options);else{if(typeof window==="undefined"||typeof window.Pusher==="undefined")throw new Error("Pusher client not found. Should be globally available or passed via options.client");this.pusher=new window.Pusher(this.options.key,this.options)}}},{key:"signin",value:function signin(){this.pusher.signin()}},{key:"listen",value:function listen(e,n,t){return this.channel(e).listen(n,t)}},{key:"channel",value:function channel(e){this.channels[e]||(this.channels[e]=new t(this.pusher,e,this.options));return this.channels[e]}},{key:"privateChannel",value:function privateChannel(e){this.channels["private-"+e]||(this.channels["private-"+e]=new r(this.pusher,"private-"+e,this.options));return this.channels["private-"+e]}},{key:"encryptedPrivateChannel",value:function encryptedPrivateChannel(e){this.channels["private-encrypted-"+e]||(this.channels["private-encrypted-"+e]=new i(this.pusher,"private-encrypted-"+e,this.options));return this.channels["private-encrypted-"+e]}},{key:"presenceChannel",value:function presenceChannel(e){this.channels["presence-"+e]||(this.channels["presence-"+e]=new s(this.pusher,"presence-"+e,this.options));return this.channels["presence-"+e]}},{key:"leave",value:function leave(e){var n=this;var t=[e,"private-"+e,"private-encrypted-"+e,"presence-"+e];t.forEach((function(e){n.leaveChannel(e)}))}},{key:"leaveChannel",value:function leaveChannel(e){if(this.channels[e]){this.channels[e].unsubscribe();delete this.channels[e]}}},{key:"socketId",value:function socketId(){return this.pusher.connection.socket_id}},{key:"disconnect",value:function disconnect(){this.pusher.disconnect()}}])}(f);var y=function(e){function SocketIoConnector(){var e;_classCallCheck(this,SocketIoConnector);e=_callSuper(this,SocketIoConnector,arguments);e.channels={};return e}_inherits(SocketIoConnector,e);return _createClass(SocketIoConnector,[{key:"connect",value:function connect(){var e,n=this;var t=this.getSocketIO();this.socket=t((e=this.options.host)!==null&&e!==void 0?e:void 0,this.options);this.socket.on("reconnect",(function(){Object.values(n.channels).forEach((function(e){e.subscribe()}))}))}},{key:"getSocketIO",value:function getSocketIO(){if(typeof this.options.client!=="undefined")return this.options.client;if(typeof window!=="undefined"&&typeof window.io!=="undefined")return window.io;throw new Error("Socket.io client not found. Should be globally available or passed via options.client")}},{key:"listen",value:function listen(e,n,t){return this.channel(e).listen(n,t)}},{key:"channel",value:function channel(e){this.channels[e]||(this.channels[e]=new o(this.socket,e,this.options));return this.channels[e]}},{key:"privateChannel",value:function privateChannel(e){this.channels["private-"+e]||(this.channels["private-"+e]=new c(this.socket,"private-"+e,this.options));return this.channels["private-"+e]}},{key:"presenceChannel",value:function presenceChannel(e){this.channels["presence-"+e]||(this.channels["presence-"+e]=new a(this.socket,"presence-"+e,this.options));return this.channels["presence-"+e]}},{key:"leave",value:function leave(e){var n=this;var t=[e,"private-"+e,"presence-"+e];t.forEach((function(e){n.leaveChannel(e)}))}},{key:"leaveChannel",value:function leaveChannel(e){if(this.channels[e]){this.channels[e].unsubscribe();delete this.channels[e]}}},{key:"socketId",value:function socketId(){return this.socket.id}},{key:"disconnect",value:function disconnect(){this.socket.disconnect()}}])}(f);var d=function(e){function NullConnector(){var e;_classCallCheck(this,NullConnector);e=_callSuper(this,NullConnector,arguments);e.channels={};return e}_inherits(NullConnector,e);return _createClass(NullConnector,[{key:"connect",value:function connect(){}},{key:"listen",value:function listen(e,n,t){return new u}},{key:"channel",value:function channel(e){return new u}},{key:"privateChannel",value:function privateChannel(e){return new l}},{key:"encryptedPrivateChannel",value:function encryptedPrivateChannel(e){return new h}},{key:"presenceChannel",value:function presenceChannel(e){return new p}},{key:"leave",value:function leave(e){}},{key:"leaveChannel",value:function leaveChannel(e){}},{key:"socketId",value:function socketId(){return"fake-socket-id"}},{key:"disconnect",value:function disconnect(){}}])}(f);var k=function(){function Echo(e){_classCallCheck(this,Echo);this.options=e;this.connect();this.options.withoutInterceptors||this.registerInterceptors()}return _createClass(Echo,[{key:"channel",value:function channel(e){return this.connector.channel(e)}},{key:"connect",value:function connect(){if(this.options.broadcaster==="reverb")this.connector=new v(_objectSpread2(_objectSpread2({},this.options),{},{cluster:""}));else if(this.options.broadcaster==="pusher")this.connector=new v(this.options);else if(this.options.broadcaster==="socket.io")this.connector=new y(this.options);else if(this.options.broadcaster==="null")this.connector=new d(this.options);else{if(typeof this.options.broadcaster!=="function"||!isConstructor(this.options.broadcaster))throw new Error("Broadcaster ".concat(_typeof(this.options.broadcaster)," ").concat(String(this.options.broadcaster)," is not supported."));this.connector=new this.options.broadcaster(this.options)}}},{key:"disconnect",value:function disconnect(){this.connector.disconnect()}},{key:"join",value:function join(e){return this.connector.presenceChannel(e)}},{key:"leave",value:function leave(e){this.connector.leave(e)}},{key:"leaveChannel",value:function leaveChannel(e){this.connector.leaveChannel(e)}},{key:"leaveAllChannels",value:function leaveAllChannels(){for(var e in this.connector.channels)this.leaveChannel(e)}},{key:"listen",value:function listen(e,n,t){return this.connector.listen(e,n,t)}},{key:"private",value:function _private(e){return this.connector.privateChannel(e)}},{key:"encryptedPrivate",value:function encryptedPrivate(e){if(this.connectorSupportsEncryptedPrivateChannels(this.connector))return this.connector.encryptedPrivateChannel(e);throw new Error("Broadcaster ".concat(_typeof(this.options.broadcaster)," ").concat(String(this.options.broadcaster)," does not support encrypted private channels."))}},{key:"connectorSupportsEncryptedPrivateChannels",value:function connectorSupportsEncryptedPrivateChannels(e){return e instanceof v||e instanceof d}},{key:"socketId",value:function socketId(){return this.connector.socketId()}},{key:"registerInterceptors",value:function registerInterceptors(){typeof Vue==="function"&&Vue.http&&this.registerVueRequestInterceptor();typeof axios==="function"&&this.registerAxiosRequestInterceptor();typeof jQuery==="function"&&this.registerjQueryAjaxSetup();(typeof Turbo==="undefined"?"undefined":_typeof(Turbo))==="object"&&this.registerTurboRequestInterceptor()}},{key:"registerVueRequestInterceptor",value:function registerVueRequestInterceptor(){var e=this;Vue.http.interceptors.push((function(n,t){e.socketId()&&n.headers.set("X-Socket-ID",e.socketId());t()}))}},{key:"registerAxiosRequestInterceptor",value:function registerAxiosRequestInterceptor(){var e=this;axios.interceptors.request.use((function(n){e.socketId()&&(n.headers["X-Socket-Id"]=e.socketId());return n}))}},{key:"registerjQueryAjaxSetup",value:function registerjQueryAjaxSetup(){var e=this;typeof jQuery.ajax!="undefined"&&jQuery.ajaxPrefilter((function(n,t,r){e.socketId()&&r.setRequestHeader("X-Socket-Id",e.socketId())}))}},{key:"registerTurboRequestInterceptor",value:function registerTurboRequestInterceptor(){var e=this;document.addEventListener("turbo:before-fetch-request",(function(n){n.detail.fetchOptions.headers["X-Socket-Id"]=e.socketId()}))}}])}();export{e as Channel,f as Connector,n as EventFormatter,k as default};

