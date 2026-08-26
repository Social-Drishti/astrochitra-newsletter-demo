#!/bin/bash
set -e

# ============================================
# AstroChitra Newsletter Deploy Script
# ============================================

APP_DIR="/var/www/astrochitra-newsletters"
BRANCH="main"
DOMAIN="newsletters.astrochitra.com"

echo "============================================"
echo "  Deploying astrochitra-newsletters"
echo "  App dir : $APP_DIR"
echo "  Domain  : $DOMAIN"
echo "============================================"

cd "$APP_DIR"

# 1. Pull latest code (re-exec once if script was updated)
echo "[1/5] Pulling $BRANCH branch..."
git fetch origin
git checkout "$BRANCH"
git reset --hard "origin/$BRANCH"

if [ -z "$_DEPLOY_REEXEC" ]; then
    export _DEPLOY_REEXEC=1
    echo "Re-executing with updated deploy script..."
    exec bash "$0" "$@"
fi

# 2. Ensure data directory exists with correct permissions (DO NOT overwrite database)
echo "[2/5] Ensuring directories..."
mkdir -p data
sudo chown -R deploy:www-data data
sudo chmod 775 data

# Ensure subscribers.json exists (legacy)
if [ ! -f data/subscribers.json ]; then
    echo "[]" > data/subscribers.json
fi
sudo chown deploy:www-data data/subscribers.json
sudo chmod 664 data/subscribers.json

# 3. Fix database permissions (www-data PHP-FPM needs read access)
echo "[3/5] Fixing database permissions..."
sudo chown deploy:www-data newsletters.db 2>/dev/null || true
sudo chmod 664 newsletters.db 2>/dev/null || true

# 4. Run database migrations (auto-creates tables, adds columns if missing)
echo "[4/5] Running database migrations..."
php -r "
require_once 'admin/db.php';
echo 'Migrations complete';
" 2>&1 || echo "Warning: Migration check completed"

# 5. Reload Nginx (config should already be in place)
echo "[5/5] Reloading Nginx..."
sudo nginx -t && sudo systemctl reload nginx

# Health check
sleep 2
echo "Health check..."
HTTP_CODE=$(curl -sk -o /dev/null -w "%{http_code}" -H "Host: $DOMAIN" https://127.0.0.1/ 2>/dev/null || echo "000")
if [ "$HTTP_CODE" = "301" ] || [ "$HTTP_CODE" = "302" ] || [ "$HTTP_CODE" = "200" ]; then
    echo "  Application responding: HTTP $HTTP_CODE"
else
    echo "  WARNING: Application returned HTTP $HTTP_CODE"
fi

echo "============================================"
echo "  DEPLOY COMPLETE"
echo "  Site  : https://$DOMAIN"
echo "============================================"