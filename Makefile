install: \
	build \
	up \
	composer-install \
	dbupdate \
	run-migrations

build:
	cd ./docker && docker compose build --no-cache

up:
	cd ./docker && docker compose up -d

composer-install:
	cd ./docker && docker compose exec php composer install

dbupdate:
	cd ./docker && docker compose exec php php bin/console doctrine:database:create --if-not-exists

run-migrations:
	cd ./docker && docker compose exec php php bin/console doctrine:migrations:migrate --no-interaction

down:
	cd ./docker && docker compose down

import:
	cd ./docker && docker compose exec php php bin/console app:import-logs /logs/logs.log 10

test:
	cd ./docker && docker compose exec php composer test

sh:
	cd ./docker && docker compose exec -u 1000:1000  php bash -l
