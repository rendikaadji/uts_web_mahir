#!/bin/bash
# Dijalankan cron di server: tarik branch `deploy` dari GitHub, lalu install dan migrate jika ada commit baru.
set -e
export PATH="/usr/local/cpanel/3rdparty/lib/path-bin:/usr/bin:/bin"
cd "$(dirname "$0")"
exec 9>storage/deploy.lock
flock -n 9 || exit 0

git fetch -q origin deploy
[ "$(git rev-parse HEAD)" = "$(git rev-parse origin/deploy)" ] && exit 0

PHP=/opt/cpanel/ea-php84/root/usr/bin/php
git checkout -q -f -B deploy origin/deploy
$PHP "$HOME/bin/composer" install --no-dev --optimize-autoloader --no-interaction --no-scripts
$PHP artisan package:discover --ansi
$PHP artisan migrate --force
$PHP artisan optimize:clear
$PHP artisan optimize
echo "deployed $(git rev-parse --short HEAD) at $(date)"
