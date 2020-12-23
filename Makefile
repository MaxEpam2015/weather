init: docker-down-clear manager-clear docker-pull docker-build docker-up manager-init

docker-down:
	docker-compose up -d

manager-clear:
	docker run --rm -v ${PWD}/manager:/app --workdir=/app alpine rm -f .ready

docker-pull:
	docker-compose pull

docker-build:
	docker-compose build

docker-up:
	docker-compose up -d

manager-init: manager-composer-install manager-oauth-keys manager-ready

manager-composer-install:
	docker-compose run --rm manager-php-cli composer install

manager-oauth-keys:
	docker-compose run --rm manager-php-cli mkdir -p var/oauth
	docker-compose run --rm manager-php-cli openssl genrsa -out var/oauth/private.key 2048
	docker-compose run --rm manager-php-cli openssl rsa -in var/oauth/private.key -pubout -out var/oauth/public.key
	docker-compose run --rm manager-php-cli chmod 644 var/oauth/private.key var/oauth/public.key

manager-ready:
	docker run --rm -v ${PWD}/manager:/app --workdir=/app alpine touch .ready

docker-down-clear:
	docker-compose down -v --remove-orphans

dev-build:
	docker build --file=manager/docker/development/php-cli.docker --tag manager-php-cli manager/docker/development

dev-cli:
	docker run --rm -v ${PWD}/manager:/app manager-php-cli php bin/app.php

cli:
	docker-compose run --rm manager-php-cli php bin/app.php

run:
	docker-compose run --rm manager-php-cli composer create-project symfony/website-skeleton skeleton
