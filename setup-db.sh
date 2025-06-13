#!/bin/bash
set -e

echo "🚀 Starting docker containers..."
docker-compose up -d

echo "⏳ Waiting for PostgreSQL to be ready..."
until docker exec -i symfony-insider-league-database-1 pg_isready -U "${POSTGRES_USER:-app}"; do
  echo "Waiting for database..."
  sleep 2
done

echo "✅ Database is ready!"

echo "🔄 Running Doctrine migrations..."
docker exec -i symfony-insider-league-php-1 php bin/console doctrine:migrations:migrate --no-interaction

echo "🧹 Loading initial fixtures group (teams, league state, matches)..."
docker exec -i symfony-insider-league-php-1 php bin/console doctrine:fixtures:load --group=initial --no-interaction --purge-with-truncate

echo "🎯 Loading additional fixtures (players)..."
docker exec -i symfony-insider-league-php-1 php bin/console doctrine:fixtures:load --append --no-interaction --fixtures=src/Infrastructure/DataFixtures/PremierLeagueFixtures.php

echo "✅ Initialization complete!"
