#!/usr/bin/env bash

echo 'Setting up sqlite database for mock'
bin/console doctrine:database:create
bin/console doctrine:schema:create
bin/console doctrine:migrations:migrate
