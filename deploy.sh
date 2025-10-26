#! /bin/bash

git pull
composer install --no-dev
rm -f var/cache/*