#!/bin/bash
#
# OpenTHC API Init
#

set -o errexit
set -o errtrace
set -o nounset
set -o pipefail

echo "Starting OpenTHC API..."

/etc/init.d/php8.4-fpm start
exec caddy run --config /etc/caddy/Caddyfile --adapter caddyfile
