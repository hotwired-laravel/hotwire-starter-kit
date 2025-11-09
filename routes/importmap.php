<?php

use Tonysm\ImportmapLaravel\Facades\Importmap;

Importmap::pinAllFrom('resources/js', to: 'js/');

Importmap::pin("@hotwired/turbo", to: "/js/vendor/@hotwired--turbo.js"); // @hotwired/turbo@8.0.20 downloaded from https://ga.jspm.io/npm:@hotwired/turbo@8.0.20/dist/turbo.es2017-esm.js
Importmap::pin("@hotwired/hotwire-native-bridge", to: "/js/vendor/@hotwired--hotwire-native-bridge.js"); // @hotwired/hotwire-native-bridge@1.2.2 downloaded from https://ga.jspm.io/npm:@hotwired/hotwire-native-bridge@1.2.2/dist/hotwire-native-bridge.js
Importmap::pin("laravel-echo", to: "/js/vendor/laravel-echo.js"); // laravel-echo@2.2.6 downloaded from https://ga.jspm.io/npm:laravel-echo@2.2.6/dist/echo.js
Importmap::pin('pusher-js', to: '/js/vendor/pusher-js.js'); // pusher-js@8.4.0 downloaded from https://ga.jspm.io/npm:pusher-js@8.4.0/dist/web/pusher.js
Importmap::pin("@hotwired/stimulus", to: "/js/vendor/@hotwired--stimulus.js"); // @hotwired/stimulus@3.2.2 downloaded from https://ga.jspm.io/npm:@hotwired/stimulus@3.2.2/dist/stimulus.js
Importmap::pin('@hotwired/stimulus-loading', to: 'vendor/stimulus-laravel/stimulus-loading.js', preload: true);
