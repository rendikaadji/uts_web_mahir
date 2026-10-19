#!/bin/bash
# Dijalankan cron di server: tarik branch `deploy`, periksa ketat, backup DB, deploy, cek kesehatan.
# Gagal di tahap mana pun setelah checkout => kode dikembalikan ke commit sebelumnya.
#   bash deploy.sh           deploy normal
#   bash deploy.sh --check   hanya pemeriksaan (pre-flight, koneksi DB, health), tanpa mengubah apa pun
# Semua dibungkus fungsi agar bash membaca seluruh script sebelum git checkout mengganti file ini.
main() {
  set -Eeuo pipefail
  export PATH="/usr/local/cpanel/3rdparty/lib/path-bin:/usr/bin:/bin"
  cd "$(dirname "$0")"

  PHP=/opt/cpanel/ea-php84/root/usr/bin/php
  DOCROOT="$HOME/public_html"
  BACKUPS="$HOME/backups"
  FAILED="$PWD/storage/deploy.failed"
  STAGE=preflight
  BACKUP_FILE=""
  old=""
  new=""

  log() { echo "[$(date '+%F %T')] $*"; }
  dbget() { grep -E "^$1=" .env | head -1 | cut -d= -f2-; }

  preflight() {
    [ -f .env ] || { log ".env tidak ada"; return 1; }
    "$PHP" -r 'exit(PHP_VERSION_ID >= 80401 ? 0 : 1);' || { log "PHP CLI kurang dari 8.4.1"; return 1; }
    command -v mysqldump > /dev/null || { log "mysqldump tidak tersedia, backup DB tidak mungkin"; return 1; }
    [ "$(df -Pm "$HOME" | awk 'NR==2{print $4}')" -gt 500 ] || { log "disk kosong kurang dari 500 MB"; return 1; }
    local k
    for k in APP_KEY APP_URL DB_HOST DB_DATABASE DB_USERNAME DB_PASSWORD; do
      grep -qE "^$k=.+" .env || { log ".env: $k kosong"; return 1; }
    done
    grep -q '^APP_ENV=production' .env || { log ".env: APP_ENV bukan production"; return 1; }
    if grep -q '^APP_DEBUG=true' .env; then log ".env: APP_DEBUG=true"; return 1; fi
  }

  health() {
    local base path code
    base=$(dbget APP_URL | sed 's|/*$||')
    for path in /up / /login; do
      code=000
      for _ in 1 2 3; do
        code=$(curl -s -o /dev/null -w '%{http_code}' --max-time 20 "$base$path" || true)
        [ "$code" = 200 ] && break
        sleep 3
      done
      log "health $path -> $code"
      [ "$code" = 200 ] || return 1
    done
  }

  # Hosting (LiteSpeed) tidak mengikuti symlink, jadi isi public/ disalin ke docroot.
  sync_docroot() {
    rm -f public/storage
    mkdir -p "$DOCROOT/storage"
    cp -a public/. "$DOCROOT/"
    sed -i "s|__DIR__.'/../|'$PWD/|g" "$DOCROOT/index.php"
  }

  install_and_cache() {
    "$PHP" "$HOME/bin/composer" install --no-dev --optimize-autoloader --no-interaction --no-scripts
    rm -f bootstrap/cache/packages.php bootstrap/cache/services.php
    "$PHP" artisan package:discover --ansi
  }

  rollback() {
    log "rollback kode ke $old"
    git checkout -q -f -B deploy "$old" || true
    install_and_cache || true
    "$PHP" artisan optimize || true
    sync_docroot || true
    "$PHP" artisan up || true
    log "database TIDAK dikembalikan otomatis. Backup: ${BACKUP_FILE:-tidak ada}"
  }

  on_error() {
    local code=$?
    trap - ERR
    log "GAGAL di tahap '$STAGE' (exit $code)"
    if [ "$STAGE" != preflight ] && [ -n "$new" ]; then echo "$new" > "$FAILED"; fi
    case "$STAGE" in preflight | backup) ;; *) rollback ;; esac
    exit "$code"
  }
  trap on_error ERR

  exec 9> storage/deploy.lock
  flock -n 9 || return 0

  preflight
  if [ "${1:-}" = "--check" ]; then
    "$PHP" artisan migrate:status > /dev/null
    health
    log "check OK"
    return 0
  fi

  git fetch -q origin deploy
  new=$(git rev-parse origin/deploy)
  old=$(git rev-parse HEAD)
  if [ "$old" = "$new" ] && [ -f "$DOCROOT/index.php" ]; then return 0; fi
  if [ -f "$FAILED" ] && [ "$(cat "$FAILED")" = "$new" ]; then
    log "lewati $new: gagal sebelumnya (hapus storage/deploy.failed untuk mencoba lagi)"
    return 0
  fi
  log "deploy $old -> $new"

  STAGE=backup
  mkdir -p "$BACKUPS"
  BACKUP_FILE="$BACKUPS/db-$(date +%Y%m%d-%H%M%S).sql.gz"
  MYSQL_PWD="$(dbget DB_PASSWORD)" mysqldump --single-transaction --no-tablespaces \
    -h "$(dbget DB_HOST)" -u "$(dbget DB_USERNAME)" "$(dbget DB_DATABASE)" | gzip > "$BACKUP_FILE"
  [ -s "$BACKUP_FILE" ] || { log "backup kosong"; return 1; }
  ls -1t "$BACKUPS"/db-*.sql.gz | tail -n +11 | xargs -r rm -f

  STAGE=checkout
  "$PHP" artisan down --retry=60 --refresh=15
  git checkout -q -f -B deploy "$new"

  STAGE=install
  install_and_cache
  "$PHP" artisan migrate:status > /dev/null

  STAGE=migrate
  "$PHP" artisan migrate --force

  STAGE=cache
  "$PHP" artisan optimize:clear
  "$PHP" artisan optimize
  "$PHP" artisan route:list --except-vendor > /dev/null
  "$PHP" artisan about --only=environment

  STAGE=sync
  sync_docroot
  "$PHP" artisan up

  STAGE=health
  health

  rm -f "$FAILED"
  log "deployed $(git rev-parse --short HEAD), backup $BACKUP_FILE"
}
main "$@"
exit
