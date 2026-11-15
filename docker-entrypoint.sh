#!/bin/sh
set -e

cd /var/www/html

echo "[entrypoint] Ensuring required storage and cache directories exist..."
mkdir -p \
    storage/framework/cache/data \
    storage/framework/sessions \
    storage/framework/views \
    storage/logs \
    storage/app/public \
    storage/app/private \
    bootstrap/cache \
    "${VIEW_COMPILED_PATH:-storage/framework/views}"

if [ ! -f .env ] && [ -f .env.example ]; then
    echo "[entrypoint] Copying .env.example to .env..."
    cp .env.example .env
fi

COMPOSER_LOCK_HASH=$(sha1sum composer.lock | cut -d ' ' -f1)
if [ ! -f vendor/autoload.php ] || [ "$(cat vendor/.composer-lock-hash 2>/dev/null)" != "$COMPOSER_LOCK_HASH" ]; then
    echo "[entrypoint] composer.lock changed or vendor missing, installing Composer dependencies..."
    composer install --no-interaction --prefer-dist
    echo "$COMPOSER_LOCK_HASH" > vendor/.composer-lock-hash
fi

if [ -f .env ]; then
    APP_KEY_VALUE=$(grep -E '^APP_KEY=' .env | head -n 1 | cut -d '=' -f2- | tr -d '\r' | tr -d '"' | tr -d "'" || true)
    if [ -z "$APP_KEY_VALUE" ]; then
        echo "[entrypoint] Generating application key..."
        php artisan key:generate --ansi --no-interaction
    fi
fi

if [ ! -e public/storage ]; then
    echo "[entrypoint] Creating storage symbolic link..."
    php artisan storage:link --force --no-interaction
fi

APP_DATABASE=$(grep -E '^DB_DATABASE=' .env 2>/dev/null | head -n 1 | cut -d '=' -f2- | tr -d '\r' | tr -d '"' | tr -d "'" || true)
export APP_DATABASE="${APP_DATABASE:-website_himattun}"

echo "[entrypoint] Waiting for MySQL at ${DB_HOST:-mysql}:${DB_PORT:-3306}..."
MAX_TRIES=30
TRIES=0
until php -r '
    $host = getenv("DB_HOST") ?: "mysql";
    $port = getenv("DB_PORT") ?: "3306";
    $user = getenv("DB_USERNAME") ?: "root";
    $pass = getenv("DB_PASSWORD") !== false ? getenv("DB_PASSWORD") : "root";
    try {
        $pdo = new PDO("mysql:host={$host};port={$port}", $user, $pass, [PDO::ATTR_TIMEOUT => 2]);
    } catch (Throwable $e) {
        exit(1);
    }
    foreach (array_unique([getenv("APP_DATABASE"), "testing"]) as $database) {
        try {
            $pdo->exec("CREATE DATABASE IF NOT EXISTS `".str_replace("`", "", $database)."` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        } catch (Throwable $e) {
            fwrite(STDERR, "[entrypoint] Could not create database {$database}: {$e->getMessage()}\n");
        }
    }
    exit(0);
'; do
    TRIES=$((TRIES + 1))
    if [ "$TRIES" -ge "$MAX_TRIES" ]; then
        echo "[entrypoint] MySQL is unavailable after ${MAX_TRIES} attempts. Exiting."
        exit 1
    fi
    echo "[entrypoint] MySQL not ready yet (${TRIES}/${MAX_TRIES}), retrying in 2s..."
    sleep 2
done
echo "[entrypoint] MySQL connection established."

echo "[entrypoint] Running database migrations..."
php artisan migrate --force --no-interaction

if php -r '
    require "vendor/autoload.php";
    $app = require "bootstrap/app.php";
    $app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
    $forceSeed = filter_var(getenv("FORCE_SEED") ?: "false", FILTER_VALIDATE_BOOLEAN);
    exit(($forceSeed || App\Models\User::count() === 0) ? 0 : 1);
'; then
    echo "[entrypoint] Seeding initial database records..."
    php artisan db:seed --force --no-interaction
fi

echo "[entrypoint] Clearing cached configuration and views..."
php artisan optimize:clear --no-interaction

echo "[entrypoint] Starting application server..."
exec "$@"
