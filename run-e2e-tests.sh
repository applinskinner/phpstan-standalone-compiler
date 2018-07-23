#!/usr/bin/env bash
set -ev

# compile phar
if [ ! -f tmp/phpstan.phar ]; then
    composer install
    php bin/compile
fi

# setup
cd e2e
rm -rf vendor
rm -f composer.lock
composer install --no-interaction
cp -f ../tmp/phpstan.phar vendor/phpstan/phpstan-shim/phpstan.phar
cp -f ../tmp/phpstan.phar vendor/phpstan/phpstan-shim/phpstan

# test that the phar autoloader works
php testPharAutoloader.php

# test levels
vendor/bin/phpunit PharTest.php
