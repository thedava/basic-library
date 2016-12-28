#!/usr/bin/env bash

if [[ ! -f 'php-cs-fixer.phar' ]]; then
    wget https://github.com/FriendsOfPHP/PHP-CS-Fixer/releases/download/v1.12.3/php-cs-fixer.phar
fi
