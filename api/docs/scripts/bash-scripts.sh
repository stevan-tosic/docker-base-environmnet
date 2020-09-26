#!/bin/bash

PROJECT_PATH=/var/www/mars

# docker start
function d-up() { cd $PROJECT_PATH; docker-compose up -d; }
# docker stop
function d-down() { cd $PROJECT_PATH; docker-compose down; }

# create admin user
function d-create-user() { docker exec -it $(docker ps -aqf "name=php") php bin/console create:user; }

# docker ps
function d-ps() { docker ps -a; }

# npm lint
function d-lint() { docker exec $(docker ps -aqf name=client) npm run lint; docker exec $(docker ps -aqf name=client) npm run lint-scss; }
# composer code-check
function d-code-check() { cd $PROJECT_PATH/api; composer code-check; cd -; }

# npm install
function d-npm-install() { docker exec $(docker ps -aqf name=client) npm install; }
# composer develop
function d-develop() { docker exec -it $(docker ps -aqf "name=php") composer develop; }

# composer coverage
function d-coverage() { cd $PROJECT_PATH/api; composer coverage; cd -; }
# npm client build
function d-client-build() { docker exec $(docker ps -aqf name=client) npm run client:build; }


# Enter api container
function de-api() { docker exec -it $(docker ps -aqf "name=php") /bin/sh; }
# Enter client container
function de-client() { docker exec -it $(docker ps -aqf "name=client") /bin/sh; }

# View api logs
function dl-api() { docker-compose logs -f | grep 'php'; }
# View client logs
function dl-client() { docker-compose logs -f | grep 'client'; }

# Rebuild api
function dr-api() { docker-compose up -d --build --force-recreate --no-deps php-platform; }
# Rebuild client
function dr-client() { docker-compose up -d --build --force-recreate --no-deps client; }

# Docker compose logs
function dc-l() { docker-compose logs -f; }
# Docker truncate logs
function d-truncate-logs() { sudo sh -c "truncate -s 0 /var/lib/docker/containers/*/*-json.log"; }
