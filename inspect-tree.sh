#!/bin/bash

echo "📂 STRUCTURE + CONTENTS"
echo

# Игнорируемые директории
IGNORE_DIRS=(node_modules cache config media venv scripts test_* external_modules .git .idea vendor var assets/vendor translations migrations config frontend/node_modules public)

# Расширения файлов, которые хотим показать
EXTENSIONS=("js" "ts" "php" "json" "yml" "yaml" "Dockerfile" "" "sh" "md" "override.yaml" "vue" "css" "svg" "md" "html")

# Расширения, которые нужно исключить
EXCLUDE_EXTENSIONS=("env" "xml" "iml" "txt")

# Файлы, которые нужно исключить по имени
EXCLUDE_FILES=("inspect-tree.sh" ".env.staging" ".env.production" "package.json" "package-lock.json" "pnpm-lock.yaml" "yarn.lock")

# Пробегаем по проекту
find . -type f | while read -r file; do
  # Пропуск по директориям
  for dir in "${IGNORE_DIRS[@]}"; do
    if [[ "$file" == ./$dir/* ]]; then
      continue 2
    fi
  done

  # Пропуск по расширениям
  for ext in "${EXCLUDE_EXTENSIONS[@]}"; do
    if [[ "$file" == *.$ext ]]; then
      continue 2
    fi
  done

  # Пропуск по имени файла
  base="$(basename "$file")"
  for skipname in "${EXCLUDE_FILES[@]}"; do
    if [[ "$base" == "$skipname" ]]; then
      continue 2
    fi
  done

  # Проверка расширения
  matched=false
  for ext in "${EXTENSIONS[@]}"; do
    if [[ "$ext" == "" && "$file" != *.* ]]; then
      matched=true
      break
    elif [[ "$ext" != "" && "$file" == *.$ext ]]; then
      matched=true
      break
    elif [[ "$ext" == "Dockerfile" && "$base" == "Dockerfile" ]]; then
      matched=true
      break
    fi
  done

  if [[ "$matched" == true ]]; then
    echo "$file"
    echo "------"
    cat "$file"         # <--- теперь показывает ВЕСЬ файл
    echo
    echo
  fi
done
