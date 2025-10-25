#! /bin/bash


# Количество коммитов, которые нужно проверить.
# Если не указано или 0, то проверяет только текущие изменения в рабочем дереве
commitsCount=$1

if [ -n "$commitsCount" ]; then
	ref="HEAD~$commitsCount"
else
	ref=HEAD
fi


git diff --name-only $ref
git diff --name-only $ref | xargs vendor/bin/php-cs-fixer fix --config=.php-cs-fixer.php -v --using-cache=no --path-mode=intersection -- 