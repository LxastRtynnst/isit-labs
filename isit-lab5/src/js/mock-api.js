(function () {
  let delayMin = 300;
  let delayMax = 800;
  let errorRate = 0;
  let networkDown = false;

  function rand(min, max) {
    return Math.floor(Math.random() * (max - min + 1)) + min;
  }

  function parseQuery(url) {
    const qIndex = url.indexOf('?');
    if (qIndex === -1) return {};
    const query = {};
    url.slice(qIndex + 1).split('&').forEach((pair) => {
      const [key, value] = pair.split('=');
      query[decodeURIComponent(key)] = decodeURIComponent(value || '');
    });
    return query;
  }

  function filterList(list, query) {
    return list.filter((item) => {
      for (const key of Object.keys(query)) {
        const q = query[key];
        if (q === '' || q === undefined) continue;
        const value = item[key];
        if (value === undefined) continue;
        if (String(value).toLowerCase().includes(String(q).toLowerCase())) {
          return true;
        }
      }
      return Object.keys(query).every(
        (key) => query[key] === '' || query[key] === undefined
      );
    });
  }

  const originalFetch = window.fetch;

  window.fetch = function (url, options) {
    url = String(url);
    if (!url.startsWith('/api/')) {
      return originalFetch.apply(this, arguments);
    }

    return new Promise((resolve, reject) => {
      const delay = rand(delayMin, delayMax);

      setTimeout(() => {
        // Сеть недоступна
        if (networkDown) {
          reject(new TypeError('Failed to fetch'));
          return;
        }

        // Ошибка сервера
        if (Math.random() < errorRate) {
          resolve(
            new Response(JSON.stringify({ error: 'Internal server error' }), {
              status: 500,
              headers: { 'Content-Type': 'application/json' },
            })
          );
          return;
        }

        const method = (options && options.method) || 'GET';
        const query = parseQuery(url);
        const path = url.split('?')[0];

        const key = method + ' ' + path;
        const handler = window.ROUTES && window.ROUTES[key];

        if (!handler) {
          resolve(
            new Response(
              JSON.stringify({
                error: 'Не найдена строка в таблице ROUTES: ' + key,
              }),
              { status: 404, headers: { 'Content-Type': 'application/json' } }
            )
          );
          return;
        }

        try {
          const result = handler(query);
          resolve(
            new Response(JSON.stringify(result), {
              status: 200,
              headers: { 'Content-Type': 'application/json' },
            })
          );
        } catch (e) {
          resolve(
            new Response(JSON.stringify({ error: e.message }), {
              status: 500,
              headers: { 'Content-Type': 'application/json' },
            })
          );
        }
      }, delay);
    });
  };

  window.MockApi = {
    setDelay(min, max) {
      delayMin = min;
      delayMax = max;
    },
    setErrorRate(rate) {
      errorRate = rate;
    },
    setNetworkDown(value) {
      networkDown = value;
    },
  };
})();