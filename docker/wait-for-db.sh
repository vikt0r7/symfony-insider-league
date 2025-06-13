#!/bin/sh
set -e

echo "📦 Waiting for database..."

host="database"
port=5432

while ! nc -z "$host" "$port"; do
  echo "Waiting for database connection on $host:$port..."
  sleep 1
done

echo "✅ Database is available"

echo "✅ RUN : php bin/console doctrine:migrations:migrate --no-interaction"

php bin/console doctrine:migrations:migrate --no-interaction

echo "✅ RUN : php bin/console doctrine:fixtures:load --group=initial --no-interaction --purge-with-truncate"

php bin/console doctrine:fixtures:load --group=initial --no-interaction --purge-with-truncate

echo "✅ RUN : php bin/console messenger:consume async --time-limit=3600 --memory-limit=256M"

php bin/console messenger:consume async --time-limit=3600 --memory-limit=256M
