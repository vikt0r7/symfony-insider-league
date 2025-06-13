#!/bin/bash

echo "🔥 Удаляем старые миграции..."
rm -rf migrations/*

echo "🧨 Удаляем схему (все таблицы)..."
php bin/console doctrine:schema:drop --force --full-database

echo "📦 Генерируем новую миграцию..."
php bin/console doctrine:migrations:diff

echo "🚀 Применяем миграции..."
php bin/console doctrine:migrations:migrate --no-interaction

echo "🌱 Загружаем фикстуры..."
php bin/console doctrine:fixtures:load --no-interaction

echo "✅ Готово!"
