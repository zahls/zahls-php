#!/usr/bin/env bash
# Regression test: with opcache enabled, autoloading a response model (which extends
# its request model) before the request model must not crash later requests.
#
# Request models used to declare `getResponseModel(): Response<Model>`, a covariant
# return type naming their own subclass. Opcache's inheritance cache then segfaults
# the next request that loads the request model first (PHP 8.2-8.4, mod_php/FPM/-S).
set -euo pipefail

cd "$(dirname "$0")/../.."
PORT="${PORT:-8765}"
php -d opcache.enable_cli=1 -d opcache.enable=1 -S "127.0.0.1:${PORT}" tests/opcache/router.php >/dev/null 2>&1 &
SERVER=$!
trap 'kill ${SERVER} 2>/dev/null || true' EXIT
for _ in $(seq 1 50); do curl -s "http://127.0.0.1:${PORT}/" >/dev/null 2>&1 && break || sleep 0.1; done

failed=0
for file in lib/Zahls/Models/Request/*.php; do
  model="$(basename "${file}" .php)"
  [ -f "lib/Zahls/Models/Response/${model}.php" ] || continue
  for mode in child parent parent; do
    if [ "${mode}" = child ]; then class="Zahls\\Models\\Response\\${model}"; else class="Zahls\\Models\\Request\\${model}"; fi
    body="$(curl -s -G --data-urlencode "class=${class}" --data-urlencode "mode=${mode}" "http://127.0.0.1:${PORT}/" || echo "connection failed (server crashed)")"
    if [ "${body}" != "Zahls\\Models\\Response\\${model}" ]; then
      echo "FAIL ${model}: ${mode} request -> ${body}"
      failed=1
      kill "${SERVER}" 2>/dev/null || true
      php -d opcache.enable_cli=1 -d opcache.enable=1 -S "127.0.0.1:${PORT}" tests/opcache/router.php >/dev/null 2>&1 &
      SERVER=$!
      sleep 0.5
      break
    fi
  done
done
[ "${failed}" = 0 ] && echo "opcache inheritance regression: all models ok"
exit "${failed}"
